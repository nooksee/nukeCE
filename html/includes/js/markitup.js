$(document).ready(function() {
    // Add markItUp! to your textarea in one line
    // $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
    $('.markitup_editor').markItUp(myBbcodeSettings);
    $('#emoticons a').click(function() {
        emoticon = $(this).attr("title");
        $.markItUp( { replaceWith:emoticon } );
        return false;
    }); 
});