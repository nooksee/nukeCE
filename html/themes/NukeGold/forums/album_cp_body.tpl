<table width="100%" cellspacing="2" cellpadding="2" border="0">
<form name="modcp" action="{S_ALBUM_ACTION}" method="post">
    <tr>
        <td class="nav"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a></span></td>
        <td align="right" nowrap="nowrap">
            <span class="gensmall">{L_SELECT_SORT_METHOD}:
                <select name="sort_method">
                <option {SORT_TIME} value='pic_time'>{L_TIME}</option>
                <option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
                <option {SORT_USERNAME} value='pic_user_id'>{L_USERNAME}</option>
                <option {SORT_VIEW} value='pic_view_count'>{L_VIEW}</option>
                {SORT_RATING_OPTION}
                {SORT_COMMENTS_OPTION}
                {SORT_NEW_COMMENT_OPTION}
                </select>
                &nbsp;{L_ORDER}:
                <select name="sort_order">
                <option {SORT_ASC} value='ASC'>{L_ASC}</option>
                <option {SORT_DESC} value='DESC'>{L_DESC}</option>
                </select>
                &nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
            </span>
        </td>
    </tr>
</table>
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
    <tr>
        <td class="catHead" height="25" align="center" colspan="6"><span class="cattitle">{L_MODCP}</span></td>
    </tr>
    <tr>
        <th class="thLeft" height="25" nowrap="nowrap">&nbsp;{L_PIC_TITLE}&nbsp;</th>
        <th width="5%" class="thTop" nowrap="nowrap">&nbsp;{L_RATING}&nbsp;</th>
        <th width="5%" class="thTop" nowrap="nowrap">&nbsp;{L_COMMENTS}&nbsp;</th>
        <th width="5%" class="thTop" nowrap="nowrap">&nbsp;{L_STATUS}&nbsp;</th>
        <th width="5%" class="thTop" nowrap="nowrap">&nbsp;{L_APPROVAL}&nbsp;</th>
        <th width="5%" class="thRight" nowrap="nowrap">&nbsp;{L_SELECT}&nbsp;</th>
    </tr>
    <!-- BEGIN no_pics -->
    <tr>
        <td class="row1" align="center" colspan="6" height="50"><span class="gen">{L_NO_PICS}</span></td>
    </tr>
    <!-- END no_pics -->
    <!-- BEGIN picrow -->
    <tr>
        <td class="row1" height="25"><span class="genmed">{L_PIC_TITLE}: {picrow.PIC_TITLE}<br />{L_POSTER}: {picrow.POSTER}<br />{L_TIME}: {picrow.TIME}</span></td>
        <td align="center" class="row2"><span class="genmed">{picrow.RATING}</span></td>
        <td align="center" class="row2"><span class="genmed">{picrow.COMMENTS}</span></td>
        <td align="center" class="row2"><span class="genmed">{picrow.LOCK}</span></td>
        <td align="center" class="row2"><span class="genmed">{picrow.APPROVAL}</span></td>
        <td align="center" class="row3"><span class="genmed"><input type="checkbox" name="pic_id[]" value="{picrow.PIC_ID}" /></span></td>
    </tr>
    <!-- END picrow -->
    <tr>
        <td class="catbottom" colspan="6" align="right" height="28">
            <input type="hidden" name="mode" value="modcp" />
            <input type="submit" class="liteoption" name="move" value="{L_MOVE}" />
            <input type="submit" class="liteoption" name="lock" value="{L_LOCK}" />
            <input type="submit" class="liteoption" name="unlock" value="{L_UNLOCK}" />
            {DELETE_BUTTON}
            {APPROVAL_BUTTON}
            {UNAPPROVAL_BUTTON}
        </td>
    </tr>
</form>
</table>
<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
    <tr>
        <td align="left" valign="bottom" colspan="1" nowrap="nowrap"><span class="nav">{PAGE_NUMBER}</span></td>
        <td valign="bottom" style ="width: 100%;"></td>
        <td align="right" valign="bottom" class="nav" nowrap="nowrap">{PAGINATION}</td>
    </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
        <td align="right">{ALBUM_JUMPBOX}</td>
    </tr>
</table>
<br clear="all" />