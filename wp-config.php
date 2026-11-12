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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'newuser2' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

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
define( 'AUTH_KEY',         'y5Y49-AQ-<D41T;>OvQF>xX@*PMPZ1jqH[QQ=3GZYc$s|G6cC%T`a-B]@#).(pii' );
define( 'SECURE_AUTH_KEY',  '>>U^4V2`#ox6v$TtTU|k^{i6H@@ACl#{E;rx{Z;heBfxp^7$0 +tKD@q!+=EgDq:' );
define( 'LOGGED_IN_KEY',    ') c}X}^a&J.nVVZ>^WG8YSsr.8NN6!81Ngq8 7-OC.9z)M>[-$EOIIX)B>:OU]}.' );
define( 'NONCE_KEY',        'e@4@.|{DF}inscwrA S{>8ok9Uz3@;IoJkl)$h+NM]-sE:rJl=6Xs##Sg:*0)rsa' );
define( 'AUTH_SALT',        '=a97I!^B]zf/!P:@P6DnZxn~Mtyvd0N!{5>qpxLabD,ex!`vuG~vd%g>UUAtmwB}' );
define( 'SECURE_AUTH_SALT', 'BY)q<O>-%q{v$NZI]t=c7_=p2NT$+gWNM0tmlUtp4C?02Jg8=&8IO{NTBd}h,Wi*' );
define( 'LOGGED_IN_SALT',   'mq73,)`ZQ~JWeVL>k&y:C5S]*P:%KjejO3]dCTQ)B7Ol)TpV02Cuu egPD9}Z}j8' );
define( 'NONCE_SALT',       'Ya{})i=l,DI-$hRIxvMP$nc=AU$4A>o(UHE8rH>!SG+Q#($3@UqPKAPFg;g+qbKf' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
