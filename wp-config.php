<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'evisavietnam' );

/** Database username */
define( 'DB_USER', 'evisavietnam' );

/** Database password */
define( 'DB_PASSWORD', 'Tf7tcwwnTnmMG4db' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '!;w3:#wMPDxZ0E?5wy56ieMwtvWtJgX`SHSYUzroP0|67@>3|3*SqVR)(l_Y|_ir' );
define( 'SECURE_AUTH_KEY',  'rR,PO#;0iGkLT][7T4yMv(&oI@T)=8Z-Z^PgJN=i2_L0*z_ooK;b<C7To|5T/b,@' );
define( 'LOGGED_IN_KEY',    'f?C Ra*`KAZ7(,*F0]X}AunBv%rb=w,P+fuFb$eP$5+_#a6i+5xFa&do%)=,vF?!' );
define( 'NONCE_KEY',        'MoB 1GR6w@Z3f&`GKsf(f&m_.[G.nvC_y{@P-h(rH4Y |2`]wpS:_G$wU8X,TST?' );
define( 'AUTH_SALT',        ':>.gfJ)gk#<m^wx:)YQT@VML_Cwr=/5a|0>f~~;.TVW;ML=.E,a|EZSDlr[w1Th/' );
define( 'SECURE_AUTH_SALT', 'Oa3VpiSP:!PGlL.cfQ23Gr_8ryVRkZ]OXWmuFL{cO*F{KfT|iM?I1eYz>KSHO^gW' );
define( 'LOGGED_IN_SALT',   'Mr5m{td15_dHK8-^9]?2~xEf4kptD?A}w-<UhZ26bR:,ZWm`^Mk)puau6(/O8sU6' );
define( 'NONCE_SALT',       'I1s,e)nKmEa69>&O(X4*+$</Pb$?Md@`(^RL^M0RWykEcGpmS7F.xKKfp4v4Z8:a' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'img_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );

define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

ini_set( 'display_errors', 0 );
ini_set( 'display_startup_errors', 0 );
error_reporting( 0 );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
