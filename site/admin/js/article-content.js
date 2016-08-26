/*
 var mceInit = {
 mode:"specific_textareas",
 editor_selector:"theEditor",
 width:"100%",
 theme:"advanced",
 skin:"wp_theme",
 theme_advanced_buttons1:"bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,wp_more,|,spellchecker,fullscreen,wp_adv", theme_advanced_buttons2:"formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,media,charmap,|,outdent,indent,|,undo,redo,wp_help",
 theme_advanced_buttons3:"",
 theme_advanced_buttons4:"",
 language:"en",
 spellchecker_languages:"+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv",
 theme_advanced_toolbar_location:"top",
 theme_advanced_toolbar_align:"left",
 theme_advanced_statusbar_location:"bottom",
 theme_advanced_resizing:"1",
 theme_advanced_resize_horizontal:"",
 dialog_type:"modal",
 relative_urls:"",
 remove_script_host:"",
 convert_urls:"",
 apply_source_formatting:"",
 remove_linebreaks:"1",
 gecko_spellcheck:"1",
 entities:"38,amp,60,lt,62,gt",
 accessibility_focus:"1",
 tabfocus_elements:"major-publishing-actions",
 media_strict:"",
 paste_remove_styles:"1",
 paste_remove_spans:"1",
 paste_strip_class_attributes:"all",
 wpeditimage_disable_captions:"",
 plugins:"safari,inlinepopups,spellchecker,paste,wordpress,media,fullscreen,wpeditimage,wpgallery,tabfocus"
 };
 */

var M_DIALOG, P_DIALOG;

var mceInit = {
    script_url: 'tiny_mce/tiny_mce.js',

    theme: 'advanced',
    plugins: 'pagebreak,style,layer,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist',

    theme_advanced_buttons1: 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,|,preview',
    theme_advanced_buttons2: 'paste,pasteword,removeformat,|,bullist,numlist,|,outdent,indent,blockquote,|,charmap,movie,pictures,|,forecolor,|,undo,redo,|,link,unlink,anchor,code,fullscreen',
    theme_advanced_buttons3: '',
    theme_advanced_buttons4: '',
    theme_advanced_toolbar_location: 'top',
    theme_advanced_toolbar_align: 'left',
    theme_advanced_statusbar_location: 'bottom',
    theme_advanced_resizing: true,
    theme_advanced_resize_horizontal: false,

    content_css: 'editor.css?v=8',

    // Drop lists for link/image/media/template dialogs
    template_external_list_url: "lists/template_list.js",
    external_link_list_url: "lists/link_list.js",
    external_image_list_url: "lists/image_list.js",
    media_external_list_url: "lists/media_list.js",

    dialog_type: 'modal',
    relative_urls: false,
    remove_script_host: false,
    convert_urls: false,
    apply_source_formatting: false,
    remove_linebreaks: false,
    entities: '38,amp,60,lt,62,gt',
    accessibility_focus: true,
    media_strict: false,
    paste_remove_styles: true,
    paste_remove_spans: true,
    paste_strip_class_attributes: 'all',

    oninit : function() {
        $('#EditorLoading').hide();
        $('#EditorWrap').css('visibility', 'visible');
    },

    onchange_callback : function() {
        global_formNavigate = false;
    },

    setup : function(ed) {
        ed.addButton('movie', {
            title : 'Movie',
            image : '/site/i/icons/button-film.gif',
            onclick : function() {
                if (!M_DIALOG) {
                    M_DIALOG = new MovieDialog(ed);
                }
                M_DIALOG.open();
            }
        });
        ed.addButton('pictures', {
            title : 'Pictures',
            image : '/site/i/icons/button-pictures.gif',
            onclick : function() {
                PIC_SELECTION = true;
                if (!P_DIALOG) {
                    P_DIALOG = new PicturesDialog(ed);
                }
                P_DIALOG.open();
            }
        });
    }
};

$().ready(function() {
    $().FormNavigate("Leaving the page will lost in unsaved data!");

    $('#Editor').tinymce(mceInit);
});


/**
 *
 */
function save() {
    //    var ed = tinyMCE.get('Editor');
    
    var data = {
        'action': 'save',
        'Id': $('#Id').val(),
        'Lang': $('#Lang').val(),
        'Content': tinyMCE.activeEditor.getContent()
    };

    $.post('article-content.php', data, function(result) {
        if (result == 'OK') {
            $("#MessageText").text("Saved successfully");
            $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
        } else {
            $("#ErrorText").html(result);
            $("#Error").fadeIn('fast');
        }
    });
}


/**
 *
 * @param ed
 */
var MovieDialog = function(ed) {
    var services_regexp = {
        'vimeo': /vimeo\.com\/([0-9]*)[/\?]?/,
        'youtube': /youtube\.[a-z]{0,5}\/.*[\?&]v=([^&]*)/
    };
    var V_TEMPLATE = '<object width="400" height="225"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id={#id}&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=00ADEF&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id={#id}&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=00ADEF&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="400" height="225"></embed></object>';
    var Y_TEMPLATE = '<object width="400" height="225"><param name="movie" value="http://www.youtube.com/v/{#id}&amp;hl=en_US&amp;fs=1?rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/{#id}&amp;hl=en_US&amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="400" height="225"></embed></object>';
    var setup = {
        autoOpen: false,
        height: 350,
        width: 460,
        modal: true,
        position: 'center',
        open: function(event, ui) {
            $('body').css({'overflow': 'hidden'});
        },
        close: function(event, ui) {
            $('body').css({'overflow': 'auto'});
            onClose();
        }
    };

    var dialog = $('#MovieDialog').dialog(setup);
    
    var fieldUrl = $('#Url');
    var fieldCode = $('#Code');

    $('#MovieInsert').click(doInsert);
    $('#MovieCancel').click(doCancel);

    /**
     *
     */
    function doInsert() {
        fieldUrl.removeClass('ui-state-error');
        var url = fieldUrl.val();
        var code = fieldCode.val();

        if (url.length > 0) {
            dialog.dialog('close');
            ed.focus();
            ed.selection.setContent(getPlayerCode(url));
        } else if (code.length > 0) {
            dialog.dialog('close');
            ed.focus();
            ed.selection.setContent(code);
        } else {
            fieldUrl.addClass('ui-state-error');
        }
    }

    /**
     *
     */
    function doCancel() {
        dialog.dialog('close');
    }

    /**
     *
     */
    function onClose() {
        fieldUrl.val('').removeClass('ui-state-error');
    }

    /**
     *
     * @param s
     */
    function getPlayerCode(s) {
        var result = '';
        var m;

        if (s.indexOf('youtube') > -1) {
            m = services_regexp['youtube'].exec(s);
            if (m[1] && m[1].length > 1) {
                result = Y_TEMPLATE.replace('{#id}', m[1]);
            }
        }

        if (s.indexOf('vimeo') > -1) {
            m = services_regexp['vimeo'].exec(s);
            if (m[1] && m[1].length > 1) {
                result = V_TEMPLATE.replace('{#id}', m[1]);
            }
        }

        return result;
    }

    return {
        open: function() {
            fieldUrl.val('');
            fieldCode.val('');
            dialog.dialog('open');
            fieldUrl.focus();
        }
    }
};


/**
 *
 * @param ed
 */
var PicturesDialog = function(ed) {
    var setup = {
        autoOpen: false,
        height: 500,
        width: 800,
        modal: true,
        position: 'center',
        open: function(event, ui) {
            $('body').css({'overflow': 'hidden'});
        },
        close: function(event, ui) {
            $('body').css({'overflow': 'auto'});
            onClose();
        }
    };

    var dialog = $('#PicturesDialog').dialog(setup);

    $('#PicturesInsert').click(doInsert);
    $('#PicturesCancel').click(doCancel);

    /**
     *
     */
    function doInsert() {
        var content = '';
        $('.Pic-Item.ui-selected').each(function() {
            var pic = $(this).attr('GUID');
            var width = $(this).attr('pictureWidth');
            var height = $(this).attr('pictureHeight');
            content += '<img src="' + pic + '" width="' + width + '" height="' + height + '" alt=""/>';
        });
        dialog.dialog('close');
        ed.focus();
        ed.selection.setContent(content);
    }

    /**
     *
     */
    function doCancel() {
        dialog.dialog('close');
    }

    /**
     *
     */
    function onClose() {
    }

    return {
        open: function() {
            dialog.dialog('open');
            $('#Years').empty();
            $('#List').empty().text('Loading...');
            loadArticleAndYears($('#Id').val());
        }
    }
};
