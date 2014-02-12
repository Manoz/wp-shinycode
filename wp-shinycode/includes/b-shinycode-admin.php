<?php
/**
 * Shinycode Admin stuff.
 * @package WP Shiny Code
 * @since 1.0.0
 */
if ( !defined( 'ABSPATH' )) { exit(); }

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
 * Register our styles/scripts
 * @since 1.0.0
 */
add_action( 'wp_enqueue_scripts', 'shinycode_enqueue_styles' );
function shinycode_enqueue_styles() {
    wp_register_style( 'shinycode-codecss', plugins_url( '/css/themes/default.css', __FILE__ ), false, /*SHINYCODE_VERSION,*/ 'all' );
    wp_register_style( 'shinycode-git', plugins_url( '/css/themes/thm-github.css', __FILE__ ), false, 'all' );
    wp_register_style( 'shinycode-b16-ol', plugins_url( '/css/themes/thm-b16-ol.css', __FILE__ ), false, 'all' );
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
        __( 'Default color Schemes', 'shinycode' ),     // Field title
        'shinycode_field_callback_theme',             // Field callback (to build a <select> menu)
        'shinycode',                            // Page id (same as menu slug)
        'shinycode_settings_section',           // Section ID
        array(
            'options' => array(
                'default'   => 'Default',
                'b16-od'    => 'Base16 Ocean Dark',
                'b16-ol'    => 'Base16 Ocean Light',
                'b16-td'    => 'Base16 Tomorrow Dark',
                'b16-tl'    => 'Base16 Tomorrow Light',
                'sc-git'    => 'Github',
                'sc-mono'   => 'Monokai',
            ),
            'name' => 'shinycode_theme'
        )
    );

    add_settings_field(
        'shinycode_languages',
        __( 'Code language', 'shinycode' ),
        'shinycode_field_callback_lang',
        'shinycode',
        'shinycode_settings_section',
        array(
            'options' => array(
                'HTML5'         => 'HTML',
                'css'           => 'CSS',
                'javascript'    => 'JavaScript',
                'jquery'        => 'jQuery',
                'php'           => 'PHP',
                'htmlphp'       => 'HTML + PHP',
            ),
            'name' => 'shinycode_languages'
        )
    );

    add_settings_field(
        'shinycode_linenumbers',
        __( 'Line numbers', 'shinycode' ),
        'shinycode_field_callback_linenumbers',
        'shinycode',
        'shinycode_settings_section'
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
    <h2>WP Shiny Code settings</h2>
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

function shinycode_field_callback_theme( $args ) {
    extract( $args );
    $old = get_option( $name );

    echo '<select name="' . $name . '" id="' . $name . '">';
    foreach( $options as $key => $option )
        echo '<option value="' . $key . '" ' . selected( $old==$key, true, false ) . '>' . esc_html( $option ) . '</option>';
    echo '</select>';
    echo '<label for="' . $name . '"> '. __('Choose your favorite syntax coloration theme.', 'shinycode') .'</label>';
}

function shinycode_field_callback_lang( $args ) {
    extract( $args );
    $old = get_option( $name );

    echo '<select name="' . $name . '" id="' . $name . '">';
    foreach( $options as $key => $option )
        echo '<option value="' . $key . '" ' . selected( $old==$key, true, false ) . '>' . esc_html( $option ) . '</option>';
    echo '</select>';
    echo '<label for="' . $name . '"> '. __('Choose your default code language.', 'shinycode') .'</label>';
}

function shinycode_field_callback_linenumbers() {
    echo '<input name="shinycode_linenumbers" id="shinycode_linenumbers" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'shinycode_linenumbers' ), false ) . ' />';
    echo '<label for="shinycode_linenumbers"> '. __('Show / hide line numbers by default?', 'shinycode') .'</label>';
}
