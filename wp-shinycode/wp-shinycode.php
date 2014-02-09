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
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'shinycode', false, "$plugin_dir/languages" );
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
 * @todo add URL to FAQ and Support.
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
        __( 'Shiny Code settings', 'shinycode' ),    // Section Title
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
    echo '<label for="' . $name . '"> '. __('Choose your favorite syntax coloration theme.', 'shinycode') .'</label>';
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

/**
 * Add TinyMCE shortcodes button
 * @since 1.0.0
 */
add_action( 'admin_init', 'shinycode_tinymce_button' );
function shinycode_tinymce_button() {
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return false;
    if ( get_user_option('rich_editing') == 'true') {
        add_filter( 'mce_external_plugins', 'add_shinycode_mce' );
        add_filter( 'mce_buttons', 'shinycode_register_button' );
    }
}

function add_shinycode_mce( $plugin_array ) {
    $plugin_array['sc'] = plugin_dir_url( __FILE__ ) .'/includes/js/tinymce.js';
    return $plugin_array;
}

function shinycode_register_button( $buttons ) {
    array_push($buttons, "|", "sc");
    return $buttons;
}

add_action( 'wp_ajax_shinycode_ajax_shortcodes', 'wp_ajax_shinycode_box' );
function wp_ajax_shinycode_box() {
    global $wp_styles;
    if ( !empty($wp_styles->concat) ) {
        $dir = $wp_styles->text_direction;
        $ver = md5("$wp_styles->concat_version{$dir}");

        $href = $wp_styles->base_url . "/wp-admin/load-styles.php?c={$zip}&dir={$dir}&load=media&ver=$ver";
        echo "<link rel='stylesheet' href='" . esc_attr( $href ) . "' type='text/css' media='all' />\n";
    } ?>

    <h3 class="media-title"><?php _e('Shiny Code', 'shinycode'); ?></h3>
    <form name="shinycode-ajax-form" id="shinycode-ajax-form">
        <div id="media-items">
            <div class="media-item media-blank">
                <table class="describe" style="width:100%;margin-top:1em;">
                    <tbody>
                        <tr valign="top" class="field">
                            <th class="label" scope="row">
                                <label for="shinycode-language"><?php _e('Select a language', 'shinycode'); ?></label>
                                <td>
                                    <select name="shinycode-language" id="shinycode-language">
                                        <optgroup label="<?php _e( 'Select a language', 'shinycode' ); ?>">
                                        <?php
                                        $langs  = array(
                                            'markup'        => 'HTML',
                                            'css'           => 'CSS',
                                            'javascript'    => 'JavaScript',
                                            'jquery'        => 'jQuery',
                                            'php'           => 'PHP',
                                            'htmlphp'       => 'HTML + PHP',
                                        );
                                        $langs = apply_filters( 'shinycode_langs', $langs );
                                        foreach( $langs as $l => $lang )
                                            echo '<option value="' . $l . '">' . $lang . '</value>';
                                        unset( $l );
                                        ?>
                                        </optgroup>
                                    </select>
                                </td>
                            </th>
                        </tr>
                        <tr valign="top" class="field">
                            <td>
                                <p class="current-page"><input name="shinycode-insert" type="submit" class="button-primary" id="shinycode-insert" tabindex="5" accesskey="p" value="<?php _e('Insert this Shiny Code', 'shinycode') ?>"></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

<?php }