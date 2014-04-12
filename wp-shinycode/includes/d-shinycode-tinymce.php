<?php
/**
 * Build shortcodes button and AJAX window
 * @package WP Shiny Code
 * @since 0.0.1
 */
if ( !defined( 'ABSPATH' )) exit();

/**
 * Add TinyMCE shortcodes button
 * @since 0.0.1
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
    $plugin_array['sc'] = plugin_dir_url( __FILE__ ) .'js/tinymce.js';
    return $plugin_array;
}

function shinycode_register_button( $buttons ) {
    array_push($buttons, "sc");
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
                                <label for="language"><?php _e('Select a language', 'shinycode'); ?></label>
                                <td>
                                    <select name="language" id="language">
                                        <optgroup label="<?php _e( 'Select a language', 'shinycode' ); ?>">
                                        <?php
                                        $langs  = array(
                                            'HTML5'         => 'HTML',
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
                            <th class="label" scope="row">
                                <label for="theme"><?php _e('Choose a theme', 'shinycode'); ?></label>
                                <td>
                                    <select name="theme" id="theme">
                                        <optgroup label="<?php _e( 'Choose a theme', 'shinycode' ); ?>">
                                        <?php
                                        $tms = array(
                                            'default'   => 'Default',
                                            'b16-od'    => 'Base16 Ocean Dark',
                                            'b16-ol'    => 'Base16 Ocean Light',
                                            'b16-td'    => 'Base16 Tomorrow Dark',
                                            'b16-tl'    => 'Base16 Tomorrow Light',
                                            'sc-git'    => 'Github',
                                            'sc-mono'   => 'Monokai',
                                        );
                                        $tms = apply_filters( 'shinycode_themes', $tms );
                                        foreach( $tms as $t => $tm )
                                            echo '<option value="' . $t . '">' . $tm . '</value>';
                                        unset( $t );
                                        ?>
                                        </optgroup>
                                    </select>
                                </td>
                            </th>
                        </tr>

                        <tr valign="top" class="field">
                            <th class="label" scope="row">
                                <label for="title"><?php _e('Add a title', 'shinycode'); ?></label>
                                <td>
                                    <input type="text" name="title" id="title" placeholder="<?php _e('Title for your blockcode', 'shinycode'); ?>"/>
                                </td>
                            </th>
                        </tr>

                        <tr valign="top" class="field">
                            <th class="label" scope="row">
                                <label for="linenumbers"><?php _e('Show line numbers?', 'shinycode'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="linenumbers" id="linenumbers" value="1" />
                            </td>
                        </tr>

                        <tr valign="top" class="field">
                            <th class="label" scope="row">
                                <label for="shinycode-blockcode"><?php _e('Add your code', 'shinycode'); ?></label>
                                <td>
                                    <textarea name="shinycode-blockcode" id="shinycode-blockcode" rows="5"></textarea>
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

<?php die();
}