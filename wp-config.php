<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

/**
 * Database connection information is automatically provided.
 * There is no need to set or change the following database configuration
 * values:
 *   DB_HOST
 *   DB_NAME
 *   DB_USER
 *   DB_PASSWORD
 *   DB_CHARSET
 *   DB_COLLATE
 */

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define('AUTH_KEY',         'Y51}(PAgcK~V9!2x8mn%P~7j?)kzF]+xf0Ej%+;9#HgJbwlY8~sXX~3k|7LOpa%@');
define('SECURE_AUTH_KEY',  'YR+M{_HUgYXr=g*,sU+WZ_1=YxPfK9h4Oi8bh6fS;G4JJh,h0-VU$p.GLOnJ~y{J');
define('LOGGED_IN_KEY',    'Er(%Q>-Q-LsDq>G()k$,L}YZ^G^G_U,P-Zrp$u{6nq]bro}5}KWO<ivV|rx)<nyQ');
define('NONCE_KEY',        '[,_.7hbP@1xnRi-Xz;0Z#b_CEi-#Nw|g,O;<+Kb2BQR3W204-j{l4U<tpy!@c%dS');
define('AUTH_SALT',        '^$j-(UQ-x4;s_f8-_$xo[vK[}t00,+6-@%v|u!B;iLObg{{{MP,d-p@cY<4n;aLm');
define('SECURE_AUTH_SALT', '3dW{G([gH;m!u=+l;A!hWYR2b}g[g00%]9BeN!R)-GPhuGz5q<6:682?p@vyN2L2');
define('LOGGED_IN_SALT',   '~f:=?;,6{fw4W,{_*Am6Ht{)1kDY2+9gOq:R%)~]a[{a]ddS6sc>udYH3UXRugXd');
define('NONCE_SALT',       'Aol@#XRd3aJ[I@IK|=_#0?_6b+D;8z.@f=:NISXIplgel(Xqe8yZE0z[SBSz=INT');

define( 'WP_MEMORY_LIMIT','1000M');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
if ( ! defined( 'WP_DEBUG') ) {
	define('WP_DEBUG',true);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */ 
require_once(ABSPATH . 'wp-settings.php');
