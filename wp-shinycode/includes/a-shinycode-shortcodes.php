<?php
/**
 * Shinycode Shortcodes stuff
 * @package WP Shiny Code
 * @since 1.0.0
 */
if ( !defined( 'ABSPATH' )) { exit(); }

/**
 * Replace post content and "colorize" our code with GeShi
 * @todo add some options to the shortcodes
 * @since 1.0.0
 */
add_shortcode( 'shinycode', 'shinycode_shortcode' );
function shinycode_shortcode( $atts, $content = "" ) {
    $path = dirname( __FILE__ );
    if ( !class_exists( 'GeSHi' ) ) include_once( "$path/lib/geshi.php" );

    $atts = shortcode_atts( array(
        'language'      => 'HTML5',
        'title'         => '',
        'linenumbers'   => '',
        'highlight'     => '',
        ), $atts, 'shinycode_shortcode' );

    $source = apply_filters( 'shinycode_code', $atts, $content );

    $geshi = new GeSHi( $source, $atts['language'] );

    $geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
    $geshi->enable_classes();
    $geshi->enable_keyword_links(false);

    // Set some temp constants for line numbers.
    // I should find a way to do this with shortcodes settings.
    $line_normal = GESHI_NORMAL_LINE_NUMBERS;
    $line_fancy  = GESHI_FANCY_LINE_NUMBERS;
    $line_hidden = GESHI_NO_LINE_NUMBERS;

    if ( $atts['linenumbers'] == 1 ) {
        $geshi->enable_line_numbers($line_fancy, 1);
    }

    $result = $geshi->parse_code();
    $result = htmlspecialchars_decode( $result );

    if ( ! empty( $source ) ) {
        wp_enqueue_style( 'shinycode-codecss' );

        $output = array();

        if( !empty( $atts['title'] ) ) {
            $output[] = '<span class="shinycode-title">' . esc_html( $atts['title'] ) . '</span>';
        }

        $output[] = '<div class="shinycode-blockcode">';
        $output[] = $result;
        $output[] = '</div>';
        $output = implode( "\n", $output );

        return $output;
    }
}

add_filter( 'shinycode_code', 'shinycode_code', 10, 3 );
function shinycode_code( $source, $content ) {
    $source = esc_html( str_replace( array('<br>','<br />', '<br/>','</p>'."\n".'<pre><code>','</code></pre>'."\n".'<p>'), array(''), $content ) );

    return $source;
}