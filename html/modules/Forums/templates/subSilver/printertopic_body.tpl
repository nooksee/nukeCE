<br clear="all">
<table border="0" width="640" cellpadding="3" cellspacing="1" bgcolor="#ffffff" align="center">
  <tr>
	<td align="left"><font color="#aaaaaa"><b>[ <a title="{L_PRINT_DESC}" href="javascript:self.print()">{L_PRINT}</a> ]</b></font></td>
  </tr>
</table>
        <table border="0" align="center"><tr><td>

        <table border="0" width="640" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
        <table border="0" width="640" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td>
<center><a class="maintitle" href="{U_VIEW_TOPIC}"><font color="black">{TOPIC_TITLE}</font></a></center><br />
<span class="nav">{PAGINATION}</span><br />
<span class="nav"><font color="black"><a href="{U_INDEX}" class="nav"><font color="black">{SITENAME}</font></a>
	  -> <a href="{U_VIEW_FORUM}" class="nav"><font color="black">{FORUM_NAME}</font></a></font></span>


	{POLL_DISPLAY} 

	<!-- BEGIN postrow -->
	<center><hr width="100%"></center>
<span class="name"><a name="{postrow.U_POST_ID}"></a></span><span class="postdetails">#{postrow.POST_NUMBER}:&nbsp;<font color="black"><strong>{postrow.POST_SUBJECT}</strong> {L_AUTHOR}:&nbsp;<strong>{postrow.POSTER_NAME}</strong>,&nbsp;</font></span><span class="postdetails"><font color="black">{postrow.POSTER_FROM}</font></span>
<a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" border="0" /></a><span class="postdetails"><font color="black">{L_POSTED}: {postrow.POST_DATE}</font></span><br />
	<span class="gensmall">&nbsp;&nbsp;&nbsp;&nbsp;&mdash;</span><br />

<span class="postbody">{postrow.MESSAGE}</span><span class="gensmall">{postrow.EDITED_MESSAGE}{postrow.ATTACHMENTS}</span>
	<!-- END postrow -->
	<center><hr width="100%"></center>
<span class="nav"><a href="{U_INDEX}" class="nav"><font color="black">{SITENAME}</font></a> 
	  -> <a href="{U_VIEW_FORUM}" class="nav"><font color="black">{FORUM_NAME}</font></a></span>
        </td></tr></table></td></tr></table><br clear="all">
<form action="{U_VIEW_TOPIC}" method="post">
<table border="0" align="center">
  <tr>
	<td class="gen" nowrap>{L_SELECT_MESSAGES_FROM}</td><td class="gen" nowrap title="{L_BOX1_DESC}">
	#<input class="post" type="text" maxlength="5" size="5" name="start_rel" value="{START_REL}"></td><td> {L_SELECT_THROUGH} <td class="gen" nowrap title="{L_BOX2_DESC}">
	#<Input class="post" type="text" maxlength="5" size="5" name="finish_rel" value="{FINISH_REL}"></td><td class="gen">
	<input type="hidden" name="t" value="{TOPIC_ID}">
	<input type="hidden" name="printertopic" value="1">
	<input type="submit" name="submit" value="{L_SHOW}" class="mainoption"></td>
</tr>
</table>
</form>
<br clear="all">
<center><span class="nav">{PAGE_NUMBER}</span></center>
</body>
</html>