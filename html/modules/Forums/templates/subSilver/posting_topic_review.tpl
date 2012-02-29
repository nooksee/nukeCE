<body style="background-color:#e5e5e5;">
<script language="JavaScript" type="text/javascript">
    message = new Array();
    <!-- BEGIN postrow -->
    message[{postrow.U_POST_ID}] = "[quote=\"{postrow.EXT_POSTER_NAME}\";p=\"{postrow.U_POST_ID}\"]\n{postrow.PLAIN_MESSAGE}\n[/quote]";
    <!-- END postrow -->

    function addquote(post_id) {
    window.parent.document.post.message.value += message[post_id];
    window.parent.document.post.message.focus();
    return;
    }
</script>
<!-- BEGIN switch_inline_mode -->
<table style="background-color: #FFFFFF; border-color: #006699; border-style: solid; border-width: 1px;" cellpadding="3" cellspacing="1" width="100%">
    <tr> 
        <td style="background-color: #D1D7DC; background-image: url(modules/Forums/templates/subSilver/images/cellpic1.gif); border-color: #FFFFFF; border-style: solid; height: 28px;" align="center"><strong><span class="cattitle">{L_TOPIC_REVIEW}</span></strong></td>
    </tr>
    <tr>
        <td style="background-color: #EFEFEF; color: inherit;">
            <iframe width="100%" height="300" src="{U_REVIEW_TOPIC}" >
<!-- END switch_inline_mode -->
                <table style="background-color: #FFFFFF; border-color: #006699; border-style: solid; border-width: 1px;" border="0" cellpadding="3" cellspacing="1" width="100%">
                    <tr>
                        <th style="background-color: #006699; background-image: url(modules/Forums/templates/subSilver/images/cellpic3.gif); color: #FFA34F; font-size: 11px; font-weight: bold; height: 25px; border-bottom-width: 0px; border-left-width: 1px; border-right-width: 0px; border-top-width: 0px;" width="22%">{L_AUTHOR}</th>
                        <th style="background-color: #006699; background-image: url(modules/Forums/templates/subSilver/images/cellpic3.gif); color: #FFA34F; font-size: 11px; font-weight: bold; height: 25px; border-bottom-width: 0px; border-left-width: 0px; border-right-width: 1px; border-top-width: 0px;">{L_MESSAGE}</th>
                    </tr>
<!-- BEGIN postrow -->
                    <tr>
                        <td style="background-color: #efefef; color: inherit;" width="22%" align="left" valign="top"><span class="name"><a name="{postrow.U_POST_ID}"></a><strong>{postrow.POSTER_NAME}</strong></span></td>
                        <td style="background-color: #DEE3E7; color: inherit;" height="28" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                        <td width="100%"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" border="0" /><span style="color: #000000; font-size: 8px;">{L_POSTED}:&nbsp;{postrow.POST_DATE}<span class="gen">&nbsp;</span>&nbsp;&nbsp;&nbsp;{L_POST_SUBJECT}:&nbsp;{postrow.POST_SUBJECT}</span></td>
                        <td valign="top" align="right" nowrap="nowrap"><span class="genmed"><input type="button" class="button" name="addquote" value="Quote" style="width: 50px" onclick="addquote({postrow.U_POST_ID});" /></span></td>
                    </tr>
                    <tr> 
                        <td colspan="2"><hr /></td>
                    </tr>
                    <tr> 
                        <td colspan="2"><span class="postbody">{postrow.MESSAGE}</span>{postrow.ATTACHMENTS}</td>
                    </tr>
                </table>
            </td>
        </tr>
<!-- END postrow -->
                </table>
<!-- BEGIN switch_inline_mode -->
            </iframe>
        </td>
    </tr>
</table>
<!-- END switch_inline_mode -->