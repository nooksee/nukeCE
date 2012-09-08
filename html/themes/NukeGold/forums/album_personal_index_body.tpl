<table width="100%" cellspacing="2" cellpadding="2" border="0">
    <tr>
        <td><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ALBUM}" class="nav">{L_ALBUM}</a></span></td>
        <td align="right" nowrap="nowrap">&nbsp;</td>
    </tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<form method="post" action="{S_MODE_ACTION}">
    <tr>
        <th width="60%" height="25" nowrap="nowrap" class="thCornerL">&nbsp;{L_USERS_PERSONAL_GALLERIES}&nbsp;</th>
        <th width="10%" class="thTop" nowrap="nowrap">&nbsp;{L_JOINED}&nbsp;</th>
        <th width="60" class="thTop" nowrap="nowrap">&nbsp;{L_PICS}&nbsp;</th>
        <th class="thCornerR" nowrap="nowrap">&nbsp;{L_LAST_PIC_DATE}&nbsp;</th>
    </tr>
    <!-- BEGIN memberrow -->
    <tr>
        <td height="28" class="{memberrow.ROW_CLASS}">&nbsp;<span class="gen"><a href="{memberrow.U_VIEWGALLERY}" class="gen">{memberrow.USERNAME}</a></span></td>
        <td class="{memberrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">&nbsp;{memberrow.JOINED}&nbsp;</span></td>
        <td class="{memberrow.ROW_CLASS}" align="center"><span class="gensmall">{memberrow.PICS}</span></td>
        <td class="{memberrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gensmall">&nbsp;{memberrow.LAST_PIC}&nbsp;</span></td>	  
    </tr>
    <!-- END memberrow -->
    <tr>
        <td class="catBottom" colspan="4" align="center" nowrap="nowrap" height="28"><span class="gensmall">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
        <input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
        </span>
        </td>
    </tr>
</form>
</table>
<table width="100%" cellspacing="2" border="0" cellpadding="2">
    <tr>
        <td align="left" valign="bottom" colspan="2" nowrap="nowrap"><span class="nav">{PAGE_NUMBER}</span></td>
        <td valign="bottom" style ="width: 100%;"></td>
        <td align="right" valign="bottom" class="nav" nowrap="nowrap">{PAGINATION}</td>
    </tr>
</table>
<br clear="all" />