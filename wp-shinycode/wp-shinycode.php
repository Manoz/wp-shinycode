<?php
/**
 * Plugin Name:     WP Shiny Code
 * Plugin URI:      https://github.com/Manoz/wp-shinycode
 * Description:     WP Shiny code is another syntax highlighting plugin for WordPress. It uses GeSHi library and of course, is server-side.
 * Version:         0.0.2
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
if ( !defined( 'ABSPATH' )) exit();
define( 'SHINYCODE_VERSION', '0.0.2' );
$path = dirname( __FILE__ );

/**
 * Load our plugin text domain
 * @since 0.0.1
 */
add_action( 'plugins_loaded', 'shinycode_languages' );
function shinycode_languages() {
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'shinycode', false, "$plugin_dir/languages" );
}

/**
 * Add a "settings" link in the plugins page
 * @since 0.0.1
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'shinycode_action_links', 10, 2 );
function shinycode_action_links( $links ) {
    $new_links   = array();
    $new_links[] = '<a href="admin.php?page=shinycode">' . __('Settings', 'shinycode') . '</a>';
    return array_merge($new_links, $links);
}

/**
 * Add "FAQ" and "Support" links in the plugin page
 * @todo add URL to FAQ and Support.
 * @since 0.0.1
 */
add_filter( 'plugin_row_meta', 'shinycode_row_meta', 10, 2 );
function shinycode_row_meta( $links, $file ) {
    if ( $file == basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) ) {
        $links[] = '<a title="' . __( 'Visit FAQ page', 'shinycode' ) . '" href="#faq">' . __( 'FAQ', 'shinycode' ) . '</a>';
        $links[] = '<a title="' . __( 'Visit Support page', 'shinycode' ) . '" href="#support">' . __( 'Support', 'shinycode' ) . '</a>';
    }
    return $links;
}

/** Load the plugin files */
require_once( "$path/includes/a-shinycode-shortcodes.php" ); // Load shortcodes stuff
require_once( "$path/includes/b-shinycode-admin.php" );      // Load admin page stuff
require_once( "$path/c-shinycode-install.php" );             // Load install/uninstall stuff
require_once( "$path/includes/d-shinycode-tinymce.php" );    // Build shortcodes button and AJAX window
