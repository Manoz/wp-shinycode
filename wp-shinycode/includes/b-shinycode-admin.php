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