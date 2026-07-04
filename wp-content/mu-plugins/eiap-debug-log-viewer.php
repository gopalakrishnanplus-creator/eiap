<?php
/**
 * Plugin Name: EIAP Debug Log Viewer
 * Description: Admin-only debug log viewer available with ?logdebug=1.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', 'eiap_logdebug_maybe_render', 0 );
add_action( 'template_redirect', 'eiap_logdebug_maybe_render', 0 );

function eiap_logdebug_maybe_render() {
	if ( '1' !== sanitize_text_field( eiap_logdebug_query_value( 'logdebug' ) ) ) {
		return;
	}

	if ( ! is_user_logged_in() ) {
		auth_redirect();
		exit;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die(
			esc_html__( 'You do not have permission to view debug logs.', 'eiap' ),
			esc_html__( 'Forbidden', 'eiap' ),
			array( 'response' => 403 )
		);
	}

	eiap_logdebug_render_page();
	exit;
}

function eiap_logdebug_render_page() {
	$sources      = eiap_logdebug_get_sources();
	$source_key   = sanitize_key( eiap_logdebug_query_value( 'logdebug_source' ) );
	$source_key   = isset( $sources[ $source_key ] ) ? $source_key : key( $sources );
	$search       = sanitize_text_field( eiap_logdebug_query_value( 'logdebug_search' ) );
	$line_limit   = absint( eiap_logdebug_query_value( 'logdebug_limit', 300 ) );
	$line_limit   = min( 2000, max( 50, $line_limit ) );
	$case_match   = '' !== eiap_logdebug_query_value( 'logdebug_case' );
	$selected_log = $sources[ $source_key ];
	$log_result   = eiap_logdebug_read_log( $selected_log['path'], $search, $case_match, $line_limit );

	status_header( 200 );
	nocache_headers();
	header( 'Content-Type: text/html; charset=' . get_bloginfo( 'charset' ) );
	header( 'X-Robots-Tag: noindex, nofollow', true );
	?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo esc_html__( 'Debug Logs', 'eiap' ); ?></title>
		<style>
			:root {
				color-scheme: light;
				--bg: #f6f7f7;
				--panel: #fff;
				--border: #dcdcde;
				--text: #1d2327;
				--muted: #646970;
				--accent: #2271b1;
				--accent-dark: #135e96;
				--code-bg: #101517;
				--code-text: #d7f7de;
			}
			* { box-sizing: border-box; }
			body {
				margin: 0;
				background: var(--bg);
				color: var(--text);
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
				font-size: 14px;
				line-height: 1.5;
			}
			.eiap-logdebug {
				max-width: 1180px;
				margin: 0 auto;
				padding: 24px;
			}
			.eiap-logdebug__header {
				display: flex;
				align-items: flex-start;
				justify-content: space-between;
				gap: 16px;
				margin-bottom: 18px;
			}
			h1 {
				margin: 0 0 4px;
				font-size: 28px;
				line-height: 1.2;
			}
			.eiap-logdebug__meta {
				margin: 0;
				color: var(--muted);
			}
			.eiap-logdebug__actions {
				display: flex;
				gap: 8px;
				flex-wrap: wrap;
				justify-content: flex-end;
			}
			.eiap-logdebug__button,
			.eiap-logdebug button {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				min-height: 36px;
				border: 1px solid var(--accent);
				border-radius: 4px;
				background: var(--accent);
				color: #fff;
				padding: 6px 12px;
				text-decoration: none;
				font-weight: 600;
				cursor: pointer;
			}
			.eiap-logdebug__button:hover,
			.eiap-logdebug button:hover {
				background: var(--accent-dark);
				border-color: var(--accent-dark);
				color: #fff;
			}
			.eiap-logdebug__button--secondary {
				background: #fff;
				color: var(--accent);
			}
			.eiap-logdebug__card {
				background: var(--panel);
				border: 1px solid var(--border);
				border-radius: 6px;
				padding: 16px;
				margin-bottom: 16px;
			}
			.eiap-logdebug__form {
				display: grid;
				grid-template-columns: minmax(220px, 1.3fr) minmax(220px, 1fr) minmax(110px, 0.4fr) auto;
				gap: 12px;
				align-items: end;
			}
			.eiap-logdebug label {
				display: block;
				margin-bottom: 4px;
				font-weight: 600;
			}
			.eiap-logdebug input[type="search"],
			.eiap-logdebug input[type="number"],
			.eiap-logdebug select {
				width: 100%;
				min-height: 36px;
				border: 1px solid #8c8f94;
				border-radius: 4px;
				background: #fff;
				color: var(--text);
				padding: 6px 8px;
			}
			.eiap-logdebug__checkbox {
				display: flex;
				align-items: center;
				gap: 8px;
				margin-top: 8px;
				color: var(--muted);
			}
			.eiap-logdebug__status {
				display: grid;
				grid-template-columns: repeat(4, minmax(0, 1fr));
				gap: 12px;
			}
			.eiap-logdebug__stat {
				border: 1px solid var(--border);
				border-radius: 4px;
				padding: 10px;
				background: #fbfbfc;
			}
			.eiap-logdebug__stat strong {
				display: block;
				font-size: 13px;
				color: var(--muted);
			}
			.eiap-logdebug__stat span {
				display: block;
				margin-top: 3px;
				font-size: 16px;
				font-weight: 700;
			}
			.eiap-logdebug__notice {
				border-left: 4px solid #d63638;
				background: #fcf0f1;
				padding: 10px 12px;
				margin: 0;
			}
			.eiap-logdebug__log {
				margin: 0;
				overflow: auto;
				max-height: 70vh;
				border-radius: 6px;
				background: var(--code-bg);
				color: var(--code-text);
				padding: 16px;
				tab-size: 4;
				white-space: pre-wrap;
				word-break: break-word;
				font: 13px/1.55 ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
			}
			.eiap-logdebug mark {
				background: #fdd663;
				color: #111;
				padding: 0 2px;
			}
			@media (max-width: 900px) {
				.eiap-logdebug__header,
				.eiap-logdebug__actions {
					display: block;
				}
				.eiap-logdebug__form,
				.eiap-logdebug__status {
					grid-template-columns: 1fr;
				}
				.eiap-logdebug__actions {
					margin-top: 12px;
				}
			}
		</style>
	</head>
	<body>
		<main class="eiap-logdebug">
			<header class="eiap-logdebug__header">
				<div>
					<h1><?php echo esc_html__( 'Debug Logs', 'eiap' ); ?></h1>
					<p class="eiap-logdebug__meta">
						<?php echo esc_html__( 'Admin-only viewer for recent WordPress/PHP log output.', 'eiap' ); ?>
					</p>
				</div>
				<div class="eiap-logdebug__actions">
					<a class="eiap-logdebug__button eiap-logdebug__button--secondary" href="<?php echo esc_url( remove_query_arg( array( 'logdebug_search', 'logdebug_source', 'logdebug_limit', 'logdebug_case' ) ) ); ?>">
						<?php echo esc_html__( 'Refresh', 'eiap' ); ?>
					</a>
					<a class="eiap-logdebug__button eiap-logdebug__button--secondary" href="<?php echo esc_url( admin_url() ); ?>">
						<?php echo esc_html__( 'WP Admin', 'eiap' ); ?>
					</a>
				</div>
			</header>

			<section class="eiap-logdebug__card">
				<form class="eiap-logdebug__form" method="get">
					<input type="hidden" name="logdebug" value="1">
					<div>
						<label for="logdebug_search"><?php echo esc_html__( 'Search logs', 'eiap' ); ?></label>
						<input id="logdebug_search" name="logdebug_search" type="search" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php echo esc_attr__( 'Error text, file name, timestamp...', 'eiap' ); ?>">
						<label class="eiap-logdebug__checkbox">
							<input type="checkbox" name="logdebug_case" value="1" <?php checked( $case_match ); ?>>
							<?php echo esc_html__( 'Case-sensitive search', 'eiap' ); ?>
						</label>
					</div>
					<div>
						<label for="logdebug_source"><?php echo esc_html__( 'Log source', 'eiap' ); ?></label>
						<select id="logdebug_source" name="logdebug_source">
							<?php foreach ( $sources as $key => $source ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $source_key, $key ); ?>>
									<?php echo esc_html( $source['label'] ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div>
						<label for="logdebug_limit"><?php echo esc_html__( 'Line limit', 'eiap' ); ?></label>
						<input id="logdebug_limit" name="logdebug_limit" type="number" min="50" max="2000" step="50" value="<?php echo esc_attr( $line_limit ); ?>">
					</div>
					<div>
						<button type="submit"><?php echo esc_html__( 'Search', 'eiap' ); ?></button>
					</div>
				</form>
			</section>

			<section class="eiap-logdebug__card eiap-logdebug__status" aria-label="<?php echo esc_attr__( 'Log status', 'eiap' ); ?>">
				<div class="eiap-logdebug__stat">
					<strong><?php echo esc_html__( 'Selected file', 'eiap' ); ?></strong>
					<span><?php echo esc_html( $selected_log['label'] ); ?></span>
				</div>
				<div class="eiap-logdebug__stat">
					<strong><?php echo esc_html__( 'File size', 'eiap' ); ?></strong>
					<span><?php echo esc_html( $log_result['size_label'] ); ?></span>
				</div>
				<div class="eiap-logdebug__stat">
					<strong><?php echo esc_html__( 'Matches shown', 'eiap' ); ?></strong>
					<span><?php echo esc_html( number_format_i18n( count( $log_result['lines'] ) ) ); ?></span>
				</div>
				<div class="eiap-logdebug__stat">
					<strong><?php echo esc_html__( 'Source path', 'eiap' ); ?></strong>
					<span title="<?php echo esc_attr( $selected_log['path'] ); ?>"><?php echo esc_html( eiap_logdebug_shorten_path( $selected_log['path'] ) ); ?></span>
				</div>
			</section>

			<?php if ( $log_result['message'] ) : ?>
				<section class="eiap-logdebug__card">
					<p class="eiap-logdebug__notice"><?php echo esc_html( $log_result['message'] ); ?></p>
				</section>
			<?php endif; ?>

			<section class="eiap-logdebug__card">
				<pre class="eiap-logdebug__log"><?php echo eiap_logdebug_format_lines( $log_result['lines'], $search, $case_match ); ?></pre>
			</section>
		</main>
	</body>
	</html>
	<?php
}

function eiap_logdebug_get_sources() {
	$sources = array();

	if ( defined( 'WP_CONTENT_DIR' ) ) {
		$sources['wp-content-debug'] = array(
			'label' => 'wp-content/debug.log',
			'path'  => trailingslashit( WP_CONTENT_DIR ) . 'debug.log',
		);
	}

	if ( defined( 'WP_DEBUG_LOG' ) && is_string( WP_DEBUG_LOG ) && '' !== WP_DEBUG_LOG && '1' !== WP_DEBUG_LOG ) {
		$wp_debug_log_path = WP_DEBUG_LOG;
		if ( ! eiap_logdebug_is_absolute_path( $wp_debug_log_path ) && defined( 'ABSPATH' ) ) {
			$wp_debug_log_path = trailingslashit( ABSPATH ) . ltrim( $wp_debug_log_path, '/\\' );
		}

		$sources['wp-debug-log-constant'] = array(
			'label' => 'WP_DEBUG_LOG',
			'path'  => $wp_debug_log_path,
		);
	}

	if ( defined( 'ABSPATH' ) ) {
		$sources['root-debug'] = array(
			'label' => 'site root/debug.log',
			'path'  => trailingslashit( ABSPATH ) . 'debug.log',
		);
	}

	$php_error_log = ini_get( 'error_log' );
	if ( is_string( $php_error_log ) && '' !== $php_error_log && 'syslog' !== strtolower( $php_error_log ) ) {
		$sources['php-error-log'] = array(
			'label' => 'PHP error_log',
			'path'  => $php_error_log,
		);
	}

	return eiap_logdebug_remove_duplicate_sources( $sources );
}

function eiap_logdebug_remove_duplicate_sources( $sources ) {
	$unique = array();
	$seen   = array();

	foreach ( $sources as $key => $source ) {
		$normalized = function_exists( 'wp_normalize_path' ) ? wp_normalize_path( $source['path'] ) : str_replace( '\\', '/', $source['path'] );
		if ( isset( $seen[ $normalized ] ) ) {
			continue;
		}

		$seen[ $normalized ] = true;
		$unique[ $key ]      = $source;
	}

	if ( empty( $unique ) ) {
		$unique['debug-log'] = array(
			'label' => 'debug.log',
			'path'  => defined( 'WP_CONTENT_DIR' ) ? trailingslashit( WP_CONTENT_DIR ) . 'debug.log' : 'debug.log',
		);
	}

	return $unique;
}

function eiap_logdebug_read_log( $path, $search, $case_match, $line_limit ) {
	$result = array(
		'lines'      => array(),
		'message'    => '',
		'size_label' => '-',
	);

	if ( ! file_exists( $path ) ) {
		$result['message'] = sprintf( 'Log file does not exist yet: %s', eiap_logdebug_shorten_path( $path ) );
		return $result;
	}

	if ( ! is_readable( $path ) || ! is_file( $path ) ) {
		$result['message'] = sprintf( 'Log file is not readable: %s', eiap_logdebug_shorten_path( $path ) );
		return $result;
	}

	$file_size            = (int) filesize( $path );
	$result['size_label'] = function_exists( 'size_format' ) ? size_format( $file_size, 2 ) : number_format_i18n( $file_size ) . ' bytes';
	$max_bytes            = 2 * 1024 * 1024;
	$was_truncated        = $file_size > $max_bytes;
	$handle               = fopen( $path, 'rb' );

	if ( false === $handle ) {
		$result['message'] = sprintf( 'Could not open log file: %s', eiap_logdebug_shorten_path( $path ) );
		return $result;
	}

	if ( $was_truncated ) {
		fseek( $handle, -$max_bytes, SEEK_END );
		fgets( $handle );
	}

	$contents = stream_get_contents( $handle );
	fclose( $handle );

	if ( false === $contents || '' === $contents ) {
		$result['message'] = 'Log file is empty.';
		return $result;
	}

	$lines = preg_split( '/\r\n|\r|\n/', $contents );
	if ( '' === end( $lines ) ) {
		array_pop( $lines );
	}

	if ( '' !== $search ) {
		$lines = array_values(
			array_filter(
				$lines,
				function ( $line ) use ( $search, $case_match ) {
					return $case_match ? false !== strpos( $line, $search ) : false !== stripos( $line, $search );
				}
			)
		);
	}

	$total_matches = count( $lines );
	if ( $total_matches > $line_limit ) {
		$lines = array_slice( $lines, -$line_limit );
	}

	$result['lines'] = $lines;
	$notes           = array();

	if ( $was_truncated ) {
		$notes[] = 'Showing the latest 2 MB of the selected log file.';
	}

	if ( $total_matches > $line_limit ) {
		$notes[] = sprintf( 'Showing the latest %d of %d matching lines.', $line_limit, $total_matches );
	}

	if ( '' !== $search && 0 === $total_matches ) {
		$notes[] = 'No matching log lines were found in the loaded log window.';
	}

	$result['message'] = implode( ' ', $notes );
	return $result;
}

function eiap_logdebug_format_lines( $lines, $search, $case_match ) {
	if ( empty( $lines ) ) {
		return esc_html__( 'No log lines to display.', 'eiap' );
	}

	$escaped_lines = array_map( 'esc_html', $lines );

	if ( '' === $search ) {
		return implode( "\n", $escaped_lines );
	}

	$escaped_search = esc_html( $search );
	$pattern        = '/' . preg_quote( $escaped_search, '/' ) . '/' . ( $case_match ? '' : 'i' );

	return implode(
		"\n",
		array_map(
			function ( $line ) use ( $pattern ) {
				return preg_replace( $pattern, '<mark>$0</mark>', $line );
			},
			$escaped_lines
		)
	);
}

function eiap_logdebug_shorten_path( $path ) {
	$normalized_path = function_exists( 'wp_normalize_path' ) ? wp_normalize_path( $path ) : str_replace( '\\', '/', $path );

	if ( defined( 'ABSPATH' ) ) {
		$normalized_root = function_exists( 'wp_normalize_path' ) ? wp_normalize_path( ABSPATH ) : str_replace( '\\', '/', ABSPATH );
		if ( 0 === strpos( $normalized_path, $normalized_root ) ) {
			return ltrim( substr( $normalized_path, strlen( $normalized_root ) ), '/' );
		}
	}

	return $normalized_path;
}

function eiap_logdebug_is_absolute_path( $path ) {
	return (bool) preg_match( '#^(?:[a-zA-Z]:[\\\\/]|/|\\\\\\\\)#', $path );
}

function eiap_logdebug_query_value( $key, $default = '' ) {
	if ( ! isset( $_GET[ $key ] ) || is_array( $_GET[ $key ] ) ) {
		return $default;
	}

	return wp_unslash( $_GET[ $key ] );
}
