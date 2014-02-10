<?php
/**
 * Activation and deactivation hooks
 * @package WP Shiny Code
 * @since 1.0.0
 */
if ( !defined( 'ABSPATH' )) { exit(); }

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