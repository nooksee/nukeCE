<h1>{L_ALBUM_AUTH_TITLE}</h1>

<p>{L_ALBUM_AUTH_EXPLAIN}</p>

<form action="{S_ALBUM_ACTION}" method="post">
<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thHead" align="center">{L_SELECT_CAT}</th>
	</tr>
	<tr>
		<td class="row1" align="center">
			<select name="cat_id">
			<!-- BEGIN catrow -->
			<option value="{catrow.CAT_ID}">{catrow.CAT_TITLE}</option>
			<!-- END catrow -->
			</select>
		&nbsp;&nbsp;<input name="submit" type="submit" value="{L_LOOK_UP_CAT}" class="liteoption" />
		</td>
		</tr>
</table>
</form>