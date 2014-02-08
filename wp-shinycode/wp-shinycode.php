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

/**
 * Load our plugin text domain
 * @since 1.0.0
 */
add_action( 'plugins_loaded', 'shinycode_languages' );
function shinycode_languages() {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain( 'shinycode', false, "$plugin_dir/languages");
}

/**
 * Add a "settings" link in plugins page
 * @since 1.0.0
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'shinycode_action_links', 10, 2 );
function shinycode_action_links( $links ) {
    $new_links = array();

    $new_links[] = '<a href="options-general.php?page=shinycode.php">' . __('Settings', 'shinycode') . '</a>';
    return array_merge($new_links, $links);
}

/**
 * Add some useful links in plugins page (row meta)
 * @since 1.0.0
 */
add_filter( 'plugin_row_meta', 'shinycode_row_meta', 10, 2 );
function shinycode_row_meta( $links, $file ) {
    if ( $file == basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) ) {
        $links[] = '<a title="' . __( 'Visit FAQ page', 'shinycode' ) . '" href="#faq">' . __( 'FAQ', 'shinycode' ) . '</a>';
        $links[] = '<a title="' . __( 'Visit Support page', 'shinycode' ) . '" href="#support">' . __( 'Support', 'shinycode' ) . '</a>';
    }
    return $links;
}

/**
 * Add our menu page
 * @since 1.0.0
 */
add_action( 'admin_menu', 'shinycode_admin_menu' );
function shinycode_admin_menu() {
    add_options_page(
        'WP Shinycode',             // Page title
        'WP Shinycode',             // Menu title
        'manage_options',           // Capability
        'shinycode',                // Menu slug
        'shinycode_settings_page'   // Function to do
    );

    // Register our settings during admin menu
    register_setting( 'shinycode', 'shinycode_theme' );
    add_settings_section(
        'shinycode_settings_section',               // Section ID (used to add fields)
        __( 'Shinycode Settings', 'shinycode' ),    // Section Title
        'shinycode_section_callback',               // Callback function (description)
        'shinycode'                                 // Page ID (same as menu slug)
    );

    add_settings_field(
        'shinycode_theme',                      // Field ID
        __( 'Color Schemes', 'shinycode' ),     // Field title
        'shinycode_field_callback',             // Field callback (to build a <select> menu)
        'shinycode',                            // Page id (same as menu slug)
        'shinycode_settings_section',           // Section ID
        array(
            'options' => array(
                'default'           => 'Default',
                'ocean-dark'        => 'Base16 Ocean Dark',
                'ocean-light'       => 'Base16 Ocean Light',
                'tomorrow-dark'     => 'Base16 Tomorrow Dark',
                'tomorrow-light'    => 'Base16 Tomorrow Light',
                'monokai'           => 'Monokai',
            ),
            'name' => 'shinycode_theme'
        )
    );
}

/**
 * Build our settings page
 * @since 1.0.0
 */
function shinycode_settings_page() {
?>

<div class="shinycode wrap">
    <?php screen_icon(); ?>
    <h2>Coucou</h2>
    <form action="options.php" method="POST">
    <?php settings_fields( 'shinycode' ); ?>
    <?php do_settings_sections( 'shinycode' ); ?>
    <?php submit_button(); ?>
    </form>

</div>

<?php } // End shinycode_settings_page()


function shinycode_section_callback() {
    echo 'Lorem ipsum your mother...';
}

function shinycode_field_callback( $args ) {
    extract( $args );
    $old = get_option( $name );

    echo '<select name="' . $name . '" id="' . $name . '">';
    foreach( $options as $key => $option )
        echo '<option value="' . $key . '" ' . selected( $old==$key, true, false ) . '>' . esc_html( $option ) . '</option>';
    echo '</select>';
}

/**
 * Register our activation/deactivation hooks
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'shinycode_activation' );
function shinycode_activation() {
    add_option( 'shinycode_theme', 'default' );
}

register_uninstall_hook( __FILE__, 'shinycode_uninstall' );
function shinycode_uninstall() {
    delete_option( 'shinycode_theme' );
}
