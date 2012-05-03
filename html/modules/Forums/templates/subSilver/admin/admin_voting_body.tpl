<html>
 <head>
<title>{TOPIC}</title> 
</head>
<h1>{L_ADMIN_VOTE_TITLE}</h1>
<p>{L_ADMIN_VOTE_EXPLAIN}</p>
<form method="post" name="vote_list" action="{S_MODE_ACTION}">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
    <tr> 
      <td align="right" nowrap="nowrap"><span class="genmed">{L_SELECT_SORT_FIELD}:&nbsp;{S_FIELD_SELECT}&nbsp;&nbsp;{L_SORT_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp; 
        <input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
        </span>
      </td>
    </tr>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline"> 
    <tr> 
        <th class="thCornerL" height="20" valign="middle" nowrap="nowrap">{L_VOTE_ID}</th> 
        <th class="thTop" height="20" valign="middle" nowrap="nowrap">{L_POLL_TOPIC}</th> 
        <th class="thTop" height="20" valign="middle" nowrap="nowrap">{L_VOTE_USERNAME}</th> 
        <th class="thCornerR" height="20" valign="middle" nowrap="nowrap">{L_VOTE_END_DATE}</th> 
    </tr> 
<!-- BEGIN votes --> 
    <tr>
        <td class="{votes.COLOR}" border="1" align="center"><span class="gensmall">{votes.VOTE_ID}</span></td> 
        <td class="{votes.COLOR}" border="1"><span class="genmed">
            <script language="JavaScript" type="text/javascript"> 
            <!-- 
                onoff('vote{votes.VOTE_ID}_switch',false); 
            //--> 
            </script>
            <a href="{votes.LINK}">{votes.DESCRIPTION}</a></span><br />
        </td> 
        <td class="{votes.COLOR}" border="1"><span class="gensmall">{votes.USER}</span></td> 
        <td class="{votes.COLOR}" border="1" align="center" width="120"><span class="gensmall">{votes.VOTE_DURATION}</span></td> 
    </tr> 
    <tr id="vote{votes.VOTE_ID}_switch" style="display:none;"> 
        <td class="row2" border="1" colspan="4"> 
<table cellpadding="5" cellspacing="1" border="0"> 
<!-- BEGIN detail --> 
    <tr> 
        <td class="row1">{votes.detail.OPTION} ({votes.detail.RESULT})</td> 
        <td class="row3"><span class="gensmall">{votes.detail.USER}</span></td> 
    </tr> 
<!-- END detail --> 
</table> 
    </td> 
    </tr> 
<!-- END votes --> 
    <tr>
        <td class="catBottom" height="18" align="center" valign="middle" colspan="4"></td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr> 
    <td><span class="nav">{PAGE_NUMBER}</span></td>
    <td align="right"><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>
<br />
</body> 
</html>