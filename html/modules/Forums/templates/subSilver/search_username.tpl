<script language="javascript" type="text/javascript">
    function refresh_username(selected_username) {
        <!-- Start replacement - Custom mass PM MOD -->
        if (opener.document.forms['post'].username.value) {
            opener.document.forms['post'].username.value = opener.document.forms['post'].username.value + ';' + selected_username;
        } else {
            opener.document.forms['post'].username.value = selected_username;
        }
        <!-- End replacement - Custom mass PM MOD -->
        opener.focus();
        window.close();
    }
</script>

<body style="background-color:#e5e5e5;">
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <form method="post" name="search" action="{S_SEARCH_ACTION}">
        <tr>
            <td>
                <table style="background-color: #FFFFFF; border-color: #006699; border-style: solid; border-width: 1px;" width="100%" cellpadding="4" cellspacing="1" border="0">
                    <tr> 
                        <th style="background-color: #006699; background-image: url(modules/Forums/templates/subSilver/images/cellpic3.gif); color: #FFA34F; font-size: 11px; font-weight: bold; height: 25px;">{L_SEARCH_USERNAME}</th>
                    </tr>
                    <tr> 
                        <td style="background-color: #EFEFEF; color: inherit;" valign="top"><span class="genmed"><br /><input type="text" name="search_username" value="{USERNAME}" class="post" />&nbsp; <input type="submit" name="search" value="{L_SEARCH}" class="liteoption" /></span><br /><span style="background-image: none; COLOR: #000000; FONT-FAMILY: Verdana, Helvetica; FONT-SIZE: 11px;">{L_SEARCH_EXPLAIN}</span><br />
                            <!-- BEGIN switch_select_name -->
                            <span class="genmed">{L_UPDATE_USERNAME}<br /><select name="username_list">{S_USERNAME_OPTIONS}</select>&nbsp; <input type="submit" class="liteoption" onClick="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);return false;" name="use" value="{L_SELECT}" /></span><br />
                            <!-- END switch_select_name -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
    </table>
    <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
        <tr>
            <td align="center"><span class="genmed"><a href="javascript:window.close();" style="BACKGROUND: none; COLOR: #000000; FONT-SIZE: 11px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none;">{L_CLOSE_WINDOW}</a></span></td>
        </tr>
    </table>