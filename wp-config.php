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
define( 'DB_NAME', 'sealevelsensors' );

/** MySQL database username */
define( 'DB_USER', 'seong' );

/** MySQL database password */
define( 'DB_PASSWORD', 'deet.4shadows' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'huWyZBJ1=ZPThO2%/@:]5Mj5lJ2i92D{;^`EShdDqbtkc]<yl+9Pp_igeJ+Re^(;' );
define( 'SECURE_AUTH_KEY',  'oq{o7;p0|]D|vlxkLl=zp<630:5<qbdc/5@&=Q83wd_d^8 7pFobkJ_?D9Trc_+!' );
define( 'LOGGED_IN_KEY',    'tR;p_hluE:G_*34^{At/WO0H/^,@F`ZK)Zj6=+7SuXX;nB plc(^!H.:uvWV<rU:' );
define( 'NONCE_KEY',        'ES{*s1SAB{j3&aIc[E-4,!%6*2SpP]xs$YuF;eoGw~4w&F0W})#2$v(BhNf:9~[8' );
define( 'AUTH_SALT',        'edVHCX|B>rYU(H1t.vSOv%SjRxtARa`B23NKYKVO,7&&ze+1ZA;*?Fn_qm}M>kq#' );
define( 'SECURE_AUTH_SALT', 'OxiW-m|w.}Vu2N;>NqLG^A@xn(;~HO@&p|drkx)d[eFT< AQ;x7akkK>5WYAc :k' );
define( 'LOGGED_IN_SALT',   'h]rZva|7@qb:*teYOJO&3JvmZ!WMO6bO]?^%yUI]/*.=QDD&+wk2zN>Qp`,oL-n[' );
define( 'NONCE_SALT',       'gubX&0W<L?JA.mW_+y577Y-^DpF47>L?AF5&F0@sl/,##[)5$db@I&+yXoI%?7lM' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
