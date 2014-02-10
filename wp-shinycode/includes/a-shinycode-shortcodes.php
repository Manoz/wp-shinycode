<?php
/**
 * Shinycode Shortcodes stuff
 * @package WP Shiny Code
 * @since 1.0.0
 */
if ( !defined( 'ABSPATH' )) { exit(); }

/**
 * Replace post content with better div and delete [shinycode]
 * @since 1.0.0
 */
add_shortcode( 'shinycode', 'shinycode_shortcode' );
function shinycode_shortcode( $atts, $content = "" ) {

    $atts = shortcode_atts( array(
        'language'      => 'markup',
        'title'         => '',
        ), $atts, 'shinycode_shortcode' );

    $source = apply_filters( 'shinycode_code', $atts, $content );

    if ( ! empty( $source ) ) {
        $output = array();
        $output[] = '<div class="shinycode-blockcode">';
        $output[] = '<pre class="language-' . sanitize_html_class( $atts['language'] ) . ' shinycode-pre"><code class="language-' . sanitize_html_class( $atts['language'] ) . ' shinycode-code">' . $source . '</code></pre>';

        if( !empty( $atts['title'] ) ) {
            $output[] = '<span class="shinycode-title">' . esc_html( $atts['title'] ) . '</span>';
        }

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