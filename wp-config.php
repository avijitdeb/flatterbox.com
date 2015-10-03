<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'flatterb_wpdev');

/** MySQL database username */
define('DB_USER', 'i1224361_wp1');

/** MySQL database password */
define('DB_PASSWORD', '28b4SS-)PO');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '1cegzj4opdaxc3gwy1loxmhqc6spzawajjjk6euyhvr6gl3jzrckdwua24xz8bxi');
define('SECURE_AUTH_KEY',  'cb3obrrjfy3ufmtnowmlmkhls4ho7nnfpsmxujbpgnidc6qw3dsirfm7id3e6mw9');
define('LOGGED_IN_KEY',    'gfmexmuks2ha8znxltnqbzxctklcnmwnetmycl9gtad5p4mcd4ukkghntyngfxzy');
define('NONCE_KEY',        'jvijfw5bzdi25nyougyyzlpgqehz9balot0ncednm4hi5gwl9nqar0vzhmdl1tws');
define('AUTH_SALT',        '70rce0v860gdwakkhr42eyetktj5lakb7jnkgvcoohtspimjhjojftvfciymyquh');
define('SECURE_AUTH_SALT', 'f2ji3o0q999kaooldqv2ahxztabtqcvn0pph6qgapoit77icwj0zimflef0g2ejh');
define('LOGGED_IN_SALT',   'lav8sfmwbzbmoflwjtgtbrk9bmgyxwl8mrozim1lwqqhalfgpsmy9exgtrmr4eki');
define('NONCE_SALT',       'jbev51bcityw7i6zi1rbo1rv6hkfjtgbtcpy4tqgoa4gzvunrgrslcsl02hxtllm');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* Added by SBDC */
define('WP_MEMORY_LIMIT', '128M');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
