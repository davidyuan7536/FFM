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

    content_css: 'editor.css?v=10',

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
        $('.EditorLoading').hide();
        $('.EditorWrap').css('visibility', 'visible');
    },

    onchange_callback : function() {
        global_formNavigate = false;
    },

    setup : function(ed) {

    }
};

$().ready(function() {
    $().FormNavigate("Leaving the page will lost in unsaved data!");

    $('#About').tinymce(mceInit);
    $('#AboutRu').tinymce(mceInit);

    $('#Save').click(save);

    function save() {
        var data = {
//            aboutHeader: $('#AboutHeader').val(),
//            aboutHeaderRu: $('#AboutHeaderRu').val(),
            about: tinyMCE.get('About').getContent(),
            aboutRu: tinyMCE.get('AboutRu').getContent()
        };

        $.post('about.php', data, function(result) {
            if (result == 'OK' || IsNumeric(result)) {
                $("#MessageText").text("Saved successfully");
                $("#Message").fadeIn('fast').delay(3000).fadeOut('slow');
            } else {
                $("#ErrorText").html(result);
                $("#Error").fadeIn('fast');
            }
        });
    }
});