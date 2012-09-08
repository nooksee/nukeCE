<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>
        <td align="center" width="100%" colspan="2" valign="top"><span class="gen"><br /></span>          
            <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
                <tr>
                    <th class="thTop" height="25" colspan="{S_COLS}" nowrap="nowrap">{L_RECENT_PUBLIC_PICS}</th>
                </tr>
                <!-- BEGIN no_pics -->
                <tr>
                    <td class="row1" align="center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td>
                </tr>
                <!-- END no_pics -->
                <!-- BEGIN recent_pics -->
                <tr>
                    <!-- BEGIN recent_col -->
                    <td class="row1" width="{S_COL_WIDTH}" align="center" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';"><a href="{recent_pics.recent_col.U_PIC}" rel="album" class="fullsize"><img src="{recent_pics.recent_col.THUMBNAIL}" border="0" alt="{recent_pics.recent_col.DESC}" title="{recent_pics.recent_col.DESC}" vspace="10" /></a></td>
                    <!-- END recent_col -->
                </tr>
                <tr>
                    <!-- BEGIN recent_detail -->
                    <td class="row2" align="left" nowrap="nowrap">
                        <span class="gensmall">
                        {recent_pics.recent_detail.TIME}<br />
                        {L_POSTER}: {recent_pics.recent_detail.POSTER}<br />
                        {L_PIC_TITLE}: {recent_pics.recent_detail.TITLE}<br />
                        </span>
                    </td>
                    <!-- END recent_detail -->
                </tr>
                <tr>
                    <!-- BEGIN recent_detail -->
                    <td class="row2" align="left" nowrap="nowrap">
                        <span class="gensmall">
                        {L_VIEW}: {recent_pics.recent_detail.VIEW}<br />
                        {recent_pics.recent_detail.RATING}
                        {recent_pics.recent_detail.COMMENTS}
                        {recent_pics.recent_detail.IP}
                        </span>
                    </td>
                    <!-- END recent_detail -->
                </tr>
                <!-- END recent_pics -->
            </table>
            <span class="gen">&nbsp;</span>          
        </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
    <tr> 
        <td align="left" valign="bottom"><span class="gensmall">
        <!-- BEGIN switch_user_logged_in -->
        {LAST_VISIT_DATE}<br />
        <!-- END switch_user_logged_in -->
        {CURRENT_TIME}<br /><br /></span>
        <span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_ALBUM}" class="nav">{L_ALBUM}</a></span></td>
        <td align="right" valign="bottom">
        <a href="{U_USERS_PERSONAL_GALLERIES}" class="gensmall">{L_USERS_PERSONAL_GALLERIES}</a><br />
        <a href="{U_YOUR_PERSONAL_GALLERY}" class="gensmall">{L_YOUR_PERSONAL_GALLERY}</a></td>
    </tr>
</table>
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
    <tr>
        <th colspan="2" class="thCornerL" height="25" nowrap="nowrap">&nbsp;{L_CATEGORY}&nbsp;</th>
        <th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_PICS}&nbsp;</th>
        <th class="thCornerR" nowrap="nowrap">&nbsp;{L_LAST_PIC}&nbsp;</th>
    </tr>
    <tr>
        <td class="catLeft" colspan="2" height="28"><span class="cattitle">{L_PUBLIC_CATS}</span></td>
        <td class="rowpic" colspan="2" align="right">&nbsp;</td>
    </tr>
    <!-- BEGIN catrow -->
    <tr>
        <td class="row1" align="center" valign="middle" height="50"><a href="{catrow.U_VIEW_CAT}" class="forumlink"><img src="{catrow.FOLDER_IMG}" border="0" alt="{catrow.CAT_TITLE}" title="{catrow.CAT_TITLE}" /></a></td>
        <td class="row1" width="100%" height="50"><span class="forumlink"> <a href="{catrow.U_VIEW_CAT}" class="forumlink">{catrow.CAT_TITLE}</a><br /></span> <span class="genmed">{catrow.CAT_DESC}<br /></span> <span class="gensmall">{catrow.L_MODERATORS} {catrow.MODERATORS}</span></td>
        <td class="row2" align="center"><span class="gensmall">{catrow.PICS}</span></td>
        <td class="row2" align="center" nowrap="nowrap"><span class="gensmall">{catrow.LAST_PIC_INFO}</span></td>
    </tr>
    <!-- END catrow -->
</table>
<table width="100%" cellspacing="2" cellpadding="1" border="0">
    <tr>
        <td align="right"><span class="gensmall">{S_TIMEZONE}</span></td>
    </tr>
</table>
<br clear="all" />