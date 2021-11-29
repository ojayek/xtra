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
define( 'DB_NAME', 'oj' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '1234' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '+QIor4[O7af4j$?fb?;;ej1Ga7qiw:O:.yUsW=h:^ys=|]ZW~pYYM}H^|fxl+78j' );
define( 'SECURE_AUTH_KEY',  'o@KBO7=x?Z=CSTswT7UbC8!=8C0DO~PwoO)E9Ce$[jUskM[c<N_ =rh=-YOI)*kH' );
define( 'LOGGED_IN_KEY',    '#J(+rG:A,@A2$g>#u*M]w`s+ixy%N?Ao/oD)wxz6}DjJ=23lsk/s>~DI5h<L2x|x' );
define( 'NONCE_KEY',        '&kj0<O]tQ]6nC+8At8Qhu.JOm0tgHrcYb#muJ5F6>0d`ZTqA 9;!rzos;,8~AlX!' );
define( 'AUTH_SALT',        'D0Ig|m*@B+>oNOHOSk]EBe1~QP]T< xA5.Jfk<1L$pwys{dfec^32i6fpjzvSPul' );
define( 'SECURE_AUTH_SALT', '+?)NQCA^Km([I=%cUD?19Lb`t#6};,-<XjDu,|3_+7[Ar2Fg~ls(/0nQ7#HXz=^h' );
define( 'LOGGED_IN_SALT',   'zruCv=.9wDXVgYb]T!OAp=:k/e1Uj0P(S(O1]qG1<= F),!O`FUI_mHm=qJ=()?]' );
define( 'NONCE_SALT',       '7`$oL+_Dzt1oSF1LY|m58Txm6(T}|rt(s}hO$#n~$d#sJeC5EQV?.[z#|[@enA-&' );

/**#@-*/

/**
 * WordPress database table prefix.
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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
