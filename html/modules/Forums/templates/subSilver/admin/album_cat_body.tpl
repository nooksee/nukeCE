<h1>{L_ALBUM_CAT_TITLE}</h1>

<p>{L_ALBUM_CAT_EXPLAIN}</p>

<form action="{S_ALBUM_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thHead" height="25" colspan="4">{L_ALBUM_CAT_TITLE}</th>
	</tr>
	<!-- BEGIN catrow -->
	<tr>
		<td class="{catrow.COLOR}" width="60%" height="25"><span class="gen">{catrow.TITLE}<br /></span><span class="gensmall">{catrow.DESC}</span></td>
		<td class="{catrow.COLOR}" align="center"><span
		class="genmed"><a href="{catrow.S_MOVE_UP}">{L_MOVE_UP}</a><br /><a href="{catrow.S_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
		<td class="{catrow.COLOR}" align="center"><span
		class="genmed"><a href="{catrow.S_EDIT_ACTION}">{L_EDIT}</a></span></td>
		<td class="{catrow.COLOR}" align="center"><span
		class="genmed"><a href="{catrow.S_DELETE_ACTION}">{L_DELETE}</a></span></td>
	</tr>
	<!-- END catrow -->
	<tr>
		<td class="catBottom" align="center" height="28" colspan="4"><input type="hidden" value="new" name="mode" /><input name="submit" type="submit" value="{L_CREATE_CATEGORY}" class="liteoption"></td>
	</tr>
</table>
</form>

<br />