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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tellme' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Admin1234.' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'T,=&1YWw6-+T|8wN[/Ta`B6DPkBrMP.=_lw(REIaP9F]jx8mCwCSqGeu]}m:rPEw' );
define( 'SECURE_AUTH_KEY',  'CCu_m}jF@T!&MQhZ]xFLHX)GVYR [>^F#t^IL6V8wBCsSY(-X;<Zg`q>z[qnn@yg' );
define( 'LOGGED_IN_KEY',    '2<Bg`3rCTK`Ux?@L=JB#]M17?g3:}}+%/&m.F&D^BN:&b~l_D6^J|cxhZ?/Wf0N<' );
define( 'NONCE_KEY',        'k*!-FjK)im9?(p1t8VmU/.7UH5*HVj3Y7Jx`m<QB@DMUK/TxBFkgbYc/}DejuFyb' );
define( 'AUTH_SALT',        '.j) J7CBVOQ-E.irw3gC(wwnh~S~?*:-KD9,i:@l!8@mKjAYtZ|4L~vmx]>),.`!' );
define( 'SECURE_AUTH_SALT', 'h5>>IoyQ[QGE]fNVoUPqmLeUYEuA(#?9Ra/~CrA$kGQp.E.k`m!nnsX/z47qrDXe' );
define( 'LOGGED_IN_SALT',   'e9u{jF6GX6mhtIUr8|pobT9!F)1ZZo;J^mWHXLL8@kmvv_m(T=]|syD.;VyLP$4D' );
define( 'NONCE_SALT',       'KOB_~G|Y{[bVD YXPmS*X#B3cytT;W1/&4-a-+mGX>k=e.0v2ZKi`<7PsPHUq+Y&' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
