$(document).ready(function () {
    $('textarea.tinymce_editor').tinymce({
        // Location of TinyMCE script
        script_url : 'includes/wysiwyg/TinyMCE/jscripts/tiny_mce/tiny_mce_gzip.php',

        // General options
        theme : "advanced",
        plugins : "save,advhr,advimage,advlink,inlinepopups,preview,media,print,contextmenu,paste,fullscreen,noneditable,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "template,|,save,|,pastetext,pasteword,|,undo,redo,|,link,unlink,|,preview,|,print,|,image,media,|,forecolor,|,removeformat,|,charmap,advhr,|,code,|,fullscreen",
        theme_advanced_buttons2 : "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Example content CSS (should be your site CSS)
        content_css : "includes/css/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "includes/wysiwyg/TinyMCE/jscripts/tiny_mce/lists/template_list.js",
        external_link_list_url : "lists/link_list.js",
        external_image_list_url : "lists/image_list.js",
        media_external_list_url : "includes/wysiwyg/TinyMCE/jscripts/tiny_mce/lists/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
            username : "Some User",
            staffid : "991234"
        }
    });
});