<h1>{L_ALBUM_PERSONAL_TITLE}</h1>

<p>{L_ALBUM_PERSONAL_EXPLAIN}</p>

<form action="{S_ALBUM_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thHead" height="25" colspan="2">{L_GROUP_CONTROL}</th>
	</tr>
	<!-- BEGIN grouprow -->
	<tr>
		<td class="row1" align="center" height="28"><span class="gen">{grouprow.GROUP_NAME}</span></td>
		<td class="row2" align="center"><input name="private[]" type="checkbox" {grouprow.PRIVATE_CHECKED} value="{grouprow.GROUP_ID}" /></td>
	</tr>
	<!-- END grouprow -->
	<tr>
		<td class="catHead" height="25" align="center" nowrap="nowrap" colspan="2"><input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" value="{L_SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>

<br />