<script language="javascript" type="text/javascript">
    function emoticon(text) {
        text = ' ' + text + ' ';
        if (opener.document.forms['post'].message.createTextRange && opener.document.forms['post'].message.caretPos) {
            var caretPos = opener.document.forms['post'].message.caretPos;
            caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
            opener.document.forms['post'].message.focus();
        } else {
            opener.document.forms['post'].message.value  += text;
            opener.document.forms['post'].message.focus();
        }
    }
</script>
<body style="background-color:#e5e5e5;">
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
            <td>
                <table style="background-color: #FFFFFF; border-color: #006699; border-style: solid; border-width: 1px;" width="100%" border="0" cellspacing="1" cellpadding="4">
                    <tr>
                        <th style="background-color: #006699; background-image: url(modules/Forums/templates/subSilver/images/cellpic3.gif); color: #FFA34F; font-size: 11px; font-weight: bold; height: 25px;">{L_EMOTICONS}</th>
                    </tr>
                    <tr>
                        <td style="background-color: #EFEFEF; color: inherit;">
                            <table width="100" border="0" cellspacing="0" cellpadding="5">
                                <!-- BEGIN smilies_row -->
                                <tr align="center" valign="middle"> 
                                    <!-- BEGIN smilies_col -->
                                    <td><a href="javascript:emoticon('{smilies_row.smilies_col.SMILEY_CODE}')"><img src="{smilies_row.smilies_col.SMILEY_IMG}" border="0" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" /></a></td>
                                    <!-- END smilies_col -->
                                </tr>
                                <!-- END smilies_row -->
                                <!-- BEGIN switch_smilies_extra -->
                                <tr align="center"> 
                                    <td colspan="{S_SMILIES_COLSPAN}"><span  class="nav"><a href="{U_MORE_SMILIES}" onclick="open_window('{U_MORE_SMILIES}', 700, 300);return false" target="_smilies" class="nav">{L_MORE_SMILIES}</a></td>
                                </tr>
                                <!-- END switch_smilies_extra -->
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
        <tr>
            <td align="center"><span class="genmed"><a href="javascript:window.close();" style="BACKGROUND: none; COLOR: #000000; FONT-SIZE: 11px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none;">{L_CLOSE_WINDOW}</a></span></td>
        </tr>
    </table>