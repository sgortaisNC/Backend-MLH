<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'preprod_bdd_montlucon' );

/** Database username */
define( 'DB_USER', 'user_ppd_mlh' );

/** Database password */
define( 'DB_PASSWORD', '2#2zXxmjsa0G4miLz' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'KPsu,f^3;J1iYd~Zh<`bb8{$)[>.R2*D,8E]CDKm=NV)K)~$PZA?C%5.KPR}$XKn' );
define( 'SECURE_AUTH_KEY',   '(:l3!sTLk5SP}pb-to2j_*P>%~P7V{O_M}8_pWKrPYB+>NCt>GH[*6zwZ5Ax:;Ml' );
define( 'LOGGED_IN_KEY',     '%lP+r^i$<q^x<9p -y:7fQQ>@&CZ`nG?,DcU<h#X,YmGux`|.HKxCRd?g[4: 4#l' );
define( 'NONCE_KEY',         '{yMk mhD~&/6m1s}xIp@wjaxe$~&kLt.<(gmc3QAgNoV@d2%YEg)G>bklph&W]ZT' );
define( 'AUTH_SALT',         '`i#TE=C9$[{n.Ei8<0R4eB{[w-MZyNJxMzk]D^K{nFbx=Vmqu4;)9riaZez+`Fh|' );
define( 'SECURE_AUTH_SALT',  'j~WDzH<LzoC-=z]flV:x4}[6};:jX9pW8l+>%xKI/O0+P_.=1~>~3TQzVXBk0+.a' );
define( 'LOGGED_IN_SALT',    'Rm*C$?4b1)hC6qwJy-xd++m>[[qqFw7-2e ZFg2yvod9X[>0c11/lBI5YI..JKJG' );
define( 'NONCE_SALT',        '%8cM<QjA+@?bweXo=PW3/EdFeNI%~G!XI`:!fy4C|U6jzfWo%XApmlBXtt+JwiyC' );
define( 'WP_CACHE_KEY_SALT', 'gq+Iq_1R]d)Z-$yPaJ!X}9)L`tXPi:%lTUFs pZ>0Sh3$-J3Vzqyzav2mO1{iuX1' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp15468_';


/* Add any custom values between this line and the "stop editing" line. */

if ( !defined('WP_DEBUG') ) define('WP_DEBUG', false);
if ( !defined('DISALLOW_FILE_EDIT') ) define('DISALLOW_FILE_EDIT', true);
if ( !defined('AUTOMATIC_UPDATER_DISABLED') ) define('AUTOMATIC_UPDATER_DISABLED', true);
if ( !defined('WP_AUTO_UPDATE_CORE') ) define('WP_AUTO_UPDATE_CORE', false);
if ( !defined('WP_POST_REVISIONS') ) define('WP_POST_REVISIONS', 10);


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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
