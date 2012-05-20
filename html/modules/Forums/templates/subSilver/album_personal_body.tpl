<table width="100%" cellspacing="2" cellpadding="2" border="0">
    <tr>
        <td width="100%"><a class="maintitle" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a><br />
        <span class="genmed">{L_PERSONAL_GALLERY_EXPLAIN}</span></td>
        <td align="right" valign="bottom" class="nav" nowrap="nowrap">{PAGINATION}</td>
    </tr>
</table>
<table width="100%" cellspacing="2" cellpadding="2" border="0">
    <tr>
        <!-- BEGIN your_personal_gallery -->
        <td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" border="0" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a>&nbsp;&nbsp;&nbsp;</td>
        <!-- END your_personal_gallery -->
        <td class="nav" width="100%"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a></span></td>
    </tr>
</table>
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
<form action="{U_PERSONAL_GALLERY}" method="post">
    <tr>
        <th class="thTop" height="25" colspan="{S_COLS}">{L_PERSONAL_GALLERY_OF_USER}</th>
    </tr>
    <!-- BEGIN no_pics -->
    <tr>
        <td class="row1" align="center" height="50"><span class="gen">{L_PERSONAL_GALLERY_NOT_CREATED}</span></td>
    </tr>
    <!-- END no_pics -->
    <!-- BEGIN picrow -->
    <tr>
        <!-- BEGIN piccol -->
        <td align="center" width="{S_COL_WIDTH}" class="row1" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';"><span class="genmed"><a href="{picrow.piccol.U_PIC}" rel="album" class="fullsize"><img src="{picrow.piccol.THUMBNAIL}" border="0" alt="{picrow.piccol.DESC}" title="{picrow.piccol.DESC}" vspace="10" /></a></span></td>
        <!-- END piccol -->
        <!-- BEGIN nopiccol --> 
        <td align="center" width="{S_COL_WIDTH}" class="row1">&nbsp;</span></td> 
        <!-- END nopiccol -->
    </tr>
    <tr>
        <!-- BEGIN pic_detail -->
        <td class="row2" align="left" nowrap="nowrap"><span class="gensmall">
        {picrow.pic_detail.TIME}<br />
        {L_PIC_TITLE}: {picrow.pic_detail.TITLE}<br />
        </td>
        <!-- END pic_detail -->
        <!-- BEGIN picnodetail --> 
        <td class="row2">&nbsp;</td> 
        <!-- END picnodetail -->
    </tr>
    <tr>
        <!-- BEGIN pic_detail -->
        <td class="row2" align="left" nowrap="nowrap"><span class="gensmall">
        {L_VIEW}: {picrow.pic_detail.VIEW}<br />
        {picrow.pic_detail.RATING}
        {picrow.pic_detail.COMMENTS}
        {picrow.pic_detail.IP}
        {picrow.pic_detail.EDIT}  {picrow.pic_detail.DELETE}  {picrow.pic_detail.LOCK}</span>
        </td>
        <!-- END pic_detail -->
        <!-- BEGIN picnodetail --> 
        <td class="row2">&nbsp;</td> 
        <!-- END picnodetail -->
    </tr>
    <!-- END picrow -->
    <tr>
        <td class="catBottom" colspan="{S_COLS}" align="center" height="28">
        <span class="gensmall">{L_SELECT_SORT_METHOD}:
        <select name="sort_method">
        <option {SORT_TIME} value='pic_time'>{L_TIME}</option>
        <option {SORT_PIC_TITLE} value='pic_title'>{L_PIC_TITLE}</option>
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
        &nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></span>
        </td>
    </tr>
</form>
</table>
<table width="100%" cellspacing="2" border="0" cellpadding="2">
    <tr>
        <!-- BEGIN your_personal_gallery -->
        <td><a href="{U_UPLOAD_PIC}"><img src="{UPLOAD_PIC_IMG}" border="0" alt="{L_UPLOAD_PIC}" title="{L_UPLOAD_PIC}" /></a>&nbsp;&nbsp;&nbsp;</td>
        <!-- END your_personal_gallery -->
        <td width="100%"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a> -> <a class="nav" href="{U_PERSONAL_GALLERY}">{L_PERSONAL_GALLERY_OF_USER}</a></span></td>
        <td align="right" valign="middle" nowrap="nowrap" style ="width: 50%;"><br />
        </td>
    </tr>
    <tr>
        <td align="left" valign="bottom" colspan="1"><span class="nav">{PAGE_NUMBER}</span></td>
        <td valign="bottom" style ="width: 100%;"></td>
        <td align="right" valign="bottom" class="nav" nowrap="nowrap">{PAGINATION}</td>
    </tr>
</table>
<br clear="all" />