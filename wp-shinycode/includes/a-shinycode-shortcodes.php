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
        'theme'         => 'default',
        'title'         => '',
        'linenumbers'   => '',
        'highlight'     => '',
        ), $atts, 'shinycode_shortcode' );

    $source = apply_filters( 'shinycode_code', $atts, $content );

    $geshi = new GeSHi( $source, $atts['language'] );

    /* Configure GeShi. */
    $geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
    $geshi->enable_classes();
    $geshi->enable_keyword_links(false);

    if ( $atts['linenumbers'] == 1 ) {
        $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 1);
    }

    /* Our blockcodes are stored in $geshi. Parse the content and do the magic trick */
    $result = $geshi->parse_code();

    // Temp. I should find a better way to do this
    $result = htmlspecialchars_decode( $result );

    if ( ! empty( $source ) ) {
        // Enqueue styles only if we have a blockcode.
        if ( $atts['theme'] == 'default' ) wp_enqueue_style( 'shinycode-codecss' );
        if ( $atts['theme'] == 'sc-git' ) wp_enqueue_style( 'shinycode-git' );
        if ( $atts['theme'] == 'b16-ol' ) wp_enqueue_style( 'shinycode-b16-ol' );

        $output = array();

        if( !empty( $atts['title'] ) ) {
            $output[] = '<span class="shinycode-title">' . esc_html( $atts['title'] ) . '</span>';
        }

        $output[] = '<div class="shinycode-blockcode ' . esc_html( $atts['theme'] ) . ' ">';
        $output[] = $result;
        $output[] = '</div>';
        $output = implode( "\n", $output );

        return $output;
    }
}

/* Clean our blockcodes by removing junk <br> or <p> tags */
add_filter( 'shinycode_code', 'shinycode_code', 10, 3 );
function shinycode_code( $source, $content ) {
    $source = esc_html( str_replace( array('<br>','<br />', '<br/>','</p>'."\n".'<pre><code>','</code></pre>'."\n".'<p>'), array(''), $content ) );

    return $source;
}