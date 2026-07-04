<?php
/**
 * Plugin Name: EIAP Debug Logs
 * Description: Adds a simple admin-only debug log page in WP Admin.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'eiap_debug_logs_admin_menu' );

function eiap_debug_logs_admin_menu() {
	add_menu_page(
		'Debug Logs',
		'Debug Logs',
		'manage_options',
		'eiap-debug-logs',
		'eiap_debug_logs_render_page',
		'dashicons-media-text',
		80
	);
}

function eiap_debug_logs_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to view debug logs.', 'eiap' ) );
	}

	$log_file = trailingslashit( WP_CONTENT_DIR ) . 'debug.log';
	$lines    = eiap_debug_logs_read_recent_lines( $log_file, 500 );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Debug Logs', 'eiap' ); ?></h1>
		<p>
			<?php echo esc_html__( 'Showing the latest entries from:', 'eiap' ); ?>
			<code><?php echo esc_html( str_replace( ABSPATH, '', $log_file ) ); ?></code>
		</p>

		<?php if ( ! file_exists( $log_file ) ) : ?>
			<div class="notice notice-warning">
				<p><?php echo esc_html__( 'No debug.log file exists yet.', 'eiap' ); ?></p>
			</div>
		<?php elseif ( ! is_readable( $log_file ) ) : ?>
			<div class="notice notice-error">
				<p><?php echo esc_html__( 'The debug.log file exists but is not readable.', 'eiap' ); ?></p>
			</div>
		<?php else : ?>
			<p>
				<input
					type="search"
					id="eiap-debug-log-search"
					class="regular-text"
					placeholder="<?php echo esc_attr__( 'Search visible logs...', 'eiap' ); ?>"
				>
			</p>
			<pre id="eiap-debug-log-output" style="max-height:70vh;overflow:auto;background:#111;color:#eee;padding:16px;white-space:pre-wrap;"><?php echo esc_html( implode( "\n", $lines ) ); ?></pre>
			<script>
				(function () {
					const search = document.getElementById('eiap-debug-log-search');
					const output = document.getElementById('eiap-debug-log-output');
					const lines = output.textContent.split('\n');

					search.addEventListener('input', function () {
						const query = search.value.toLowerCase();
						output.textContent = lines.filter(function (line) {
							return !query || line.toLowerCase().includes(query);
						}).join('\n');
					});
				})();
			</script>
		<?php endif; ?>
	</div>
	<?php
}

function eiap_debug_logs_read_recent_lines( $file, $limit ) {
	if ( ! file_exists( $file ) || ! is_readable( $file ) || ! is_file( $file ) ) {
		return array();
	}

	$contents = file_get_contents( $file, false, null, max( 0, filesize( $file ) - 1024 * 1024 ) );
	if ( false === $contents || '' === $contents ) {
		return array();
	}

	$lines = preg_split( '/\r\n|\r|\n/', trim( $contents ) );
	return array_slice( $lines, -absint( $limit ) );
}
