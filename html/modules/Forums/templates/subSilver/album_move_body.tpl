<table width="100%" cellspacing="2" cellpadding="2" border="0">
    <tr>
        <td class="nav"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a class="nav" href="{U_ALBUM}">{L_ALBUM}</a></span></td>
    </tr>
</table>
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
<form action="{S_ALBUM_ACTION}" method="post">
    <tr>
        <th height="25" class="thHead">{L_MOVE}</th>
    </tr>
    <tr>
        <td class="row1" align="center"><br /><span class="gen">{L_MOVE_TO_CATEGORY}:</span> &nbsp; {S_CATEGORY_SELECT} &nbsp; <input class="mainoption" type="submit" name="move" value="{L_MOVE}" /><br />&nbsp;</td>
    </tr>
<!-- BEGIN pic_id_array -->
<input type="hidden" name="pic_id[]" value="{pic_id_array.VALUE}" />
<!-- END pic_id_array -->
</form>
</table>
<br clear="all" />