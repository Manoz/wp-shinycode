<?php
/**
 * Plugin Name:     WP Shiny Code
 * Plugin URI:      https://github.com/Manoz/wp-shinycode
 * Description:     WP Shiny code is another syntax highlighting plugin for WordPress. It uses GeSHi library and of course, is server-side.
 * Version:         1.0.0
 * Author:          Kevin Legrand
 * Author URI:      http://www.k-legrand.fr/
 * Text Domain:     shinycode
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /languages
 *
 * @package   WP Shiny Code
 * @author    Kevin Legrand <manoz@outlook.com>
 * @license   GPL-2.0+
 * @link      http://www.k-legrand.fr/
 * @copyright 2014 Kevin Legrand
 *
 */
if ( !defined( 'ABSPATH' )) { exit(); }
define( 'SHINYCODE_VERSION', '1.0.0' );
$path = dirname(__FILE__);

/**
 * Load our plugin text domain
 * @since 1.0.0
 */
add_action( 'plugins_loaded', 'shinycode_languages' );
function shinycode_languages() {
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'shinycode', false, "$plugin_dir/languages" );
}

require_once( "$path/includes/a-shinycode-shortcodes.php" );  // Load shortcodes stuff
require_once( "$path/includes/b-shinycode-admin.php" );       // Load admin page stuff
require_once( "$path/c-shinycode-install.php" );              // Load install/uninstall stuff
require_once( "$path/includes/d-shinycode-tinymce.php" );     // Build shortcodes button and AJAX window
