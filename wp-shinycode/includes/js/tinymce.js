jQuery(document).ready(function($) {
    tinymce.create('tinymce.plugins.sc', {
        init : function(ed, url) {
            // Add code
            $(document).on( 'click','#shinycode-insert', function( e ) {
                e.preventDefault();
                ed.execCommand(
                    'mceInsertContent',
                    false,
                    shinycode_create()
                );
                tb_remove();
            });
            ed.addButton('sc', {
                title : 'Shiny Code',
                image : url+'/../images/shinycode-button.png',
                onclick : function() {
                    tb_show('Shinycode', ajaxurl+'?action=shinycode_ajax_shortcodes&width=600&height=auto');
                    $("#TB_ajaxContent").css('overflow',"visible");
                    setTimeout(function() {
                        $('#TB_window').css({'height':'450px', 'marginTop':($(window).height()-450) / 2});
                    },800);
                }
            });
        },
    });
    tinymce.PluginManager.add('sc', tinymce.plugins.sc);
});

// Add some magic to replace special chars
function shinycode_esc_html( str ) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&#34;').replace(/'/g, '&#039;');
}

function shinycode_create() {
    var inputs = jQuery('#shinycode-ajax-form').serializeArray();
    var shortcode = '[shinycode ';
    var textarea = '';
    for ( var a in inputs ) {
        if ( inputs[a].value == "" ||  inputs[a].value == undefined)
            continue;
        if ( inputs[a].name=='shinycode-blockcode' )
            textarea = inputs[a].value;
        else {
            inputs[a].name = inputs[a].name.replace( 'shinycode-', '' );
            shortcode += ' '+inputs[a].name+'="'+inputs[a].value+'"';
        }
    }

    if ( textarea!='')
        shortcode += ']<pre><code>' + shinycode_esc_html( textarea ) + '</code></pre>[/shinycode]';
    else
        shortcode += '/]';
    return shortcode;
}
