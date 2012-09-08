<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
    <tr> 
        <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
    </tr>
</table>

<!-- BEGIN switch_current_sig -->

<form method="post" action="{SIG_LINK}" name="preview">

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline" align="center">
    <tr> 
        <th class="thHead" colspan="2" height="25" valign="middle">{SIG_CURRENT}</th>
    </tr>
    <tr> 
        <td class="row1" width="24%" height="140"><span class="gen">{L_SIGNATURE}:</span><br /><span class="gensmall">{L_SIGNATURE_EXPLAIN}</td>
        <td class="row2" width="76%" valign="bottom"><span class="gen">{CURRENT_PREVIEW}</span></td>
    </tr>
    <tr> 
        <td class="row1" width="24%" height="20"><span class="gen"></span></td>
        <td class="row2" width="76%" valign="middle" nowrap="nowrap">{PROFIL_IMG} {EMAIL_IMG} {PM_IMG} {ALBUM_IMG} {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG} {ICQ_IMG}</td>
    </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="1" border="0">
  <tr>
	<td align="right"><span class="gensmall">{S_TIMEZONE}</span></td>
  </tr>
</table>
<br clear="all" />

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline" align="center">
    <tr> 
        <th class="thHead" colspan="2" height="25" valign="middle">{SIG_EDIT}</th>
    </tr>
    <tr>
        <td class="row1" width="24%" height="20">&nbsp;</td>
        <td class="row2" width="76%" align="left">{BB_BOX}</td>
    </tr>
    <tr> 
        <td class="row1" valign="top"><span class="gen"><strong>{L_OPTIONS}</strong></span><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}</span></td>
        <td class="row2" width="76%" align="left"><textarea name="signature" style="width: 450px" rows="8" cols="70" class="post">{SIGNATURE}</textarea></td>
    </tr>
    <tr> 
        <td class="catBottom" colspan="2" align="center" height="28">
        <INPUT TYPE="button" VALUE="{L_PROFILE}" onClick="location='{U_PROFILE}'"> 
        <INPUT TYPE="button" VALUE="{SIG_CURRENT}" onClick="location='{SIG_LINK}'">
        <INPUT TYPE="submit" VALUE="{SIG_PREVIEW}" name="preview">
        <INPUT TYPE="submit" VALUE="{SIG_SAVE}" name="save">
        <INPUT TYPE="submit" VALUE="{SIG_CANCEL}" name="cancel">
        </td>
    </tr>
</table>

</form>
<br clear="all" />

<!-- END switch_current_sig -->

<!-- BEGIN switch_preview_sig -->

<form method="post" action="{SIG_LINK}" name="preview">

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline" align="center">
    <tr> 
        <th class="thHead" colspan="2" height="25" valign="middle">{SIG_PREVIEW}</th>
    </tr>
    <tr> 
        <td class="row1" width="24%" height="140"><span class="gen">{L_SIGNATURE}:</span><br /><span class="gensmall">{L_SIGNATURE_EXPLAIN}</td>
        <td class="row2" width="76%" valign="bottom"><span class="gen">{REAL_PREVIEW}</span></td>
    </tr>
    <tr> 
        <td class="row1" width="24%" height="20"><span class="gen"></span></td>
        <td class="row2" width="76%" valign="middle" nowrap="nowrap">{PROFIL_IMG} {EMAIL_IMG} {PM_IMG} {ALBUM_IMG} {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG} {ICQ_IMG}</td>
    </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="1" border="0">
  <tr>
	<td align="right"><span class="gensmall">{S_TIMEZONE}</span></td>
  </tr>
</table>
<br clear="all" />

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline" align="center">
    <tr> 
        <th class="thHead" colspan="2" height="25" valign="middle">{SIG_EDIT}</th>
    </tr>
    <tr>
        <td class="row1" width="24%" height="20">&nbsp;</td>
        <td class="row2" width="76%" align="left">{BB_BOX}</td>
    </tr>
    <tr> 
        <td class="row1" valign="top"><span class="gen"><strong>{L_OPTIONS}</strong></span><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}</span></td>
        <td class="row2" width="76%" align="left"><textarea name="signature" style="width: 450px" rows="8" cols="70" class="post">{PREVIEW}</textarea></td>
    </tr>
    <tr> 
        <td class="catBottom" colspan="2" align="center" height="28">
        <INPUT TYPE="button" VALUE="{L_PROFILE}" onClick="location='{U_PROFILE}'"> 
        <INPUT TYPE="button" VALUE="{SIG_CURRENT}" onClick="location='{SIG_LINK}'">
        <INPUT TYPE="submit" VALUE="{SIG_PREVIEW}" name="preview">
        <INPUT TYPE="submit" VALUE="{SIG_SAVE}" name="save">
        <INPUT TYPE="submit" VALUE="{SIG_CANCEL}" name="cancel">
        </td>
    </tr>
</table>

</form>
<br clear="all" />

<!-- END switch_preview_sig -->

<!-- BEGIN switch_save_sig -->

<form method="post" action="{SIG_LINK}" name="preview">

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline" align="center">
    <tr> 
        <th class="thHead" height="25" valign="middle">{SIG_SAVE}</th>
    </tr>
    <tr> 
        <td class="row1" valign="middle" align="middle" height="50"><br /><span class="gen">{SAVE_MESSAGE}</span><br /><br /></td>
    </tr>
    <tr> 
        <td class="catBottom" colspan="2" align="center" height="28">
        <INPUT TYPE="button" VALUE="{L_PROFILE}" onClick="location='{U_PROFILE}'"> 
        <INPUT TYPE="button" VALUE="{SIG_CURRENT}" onClick="location='{SIG_LINK}'">
        <INPUT TYPE="submit" VALUE="{SIG_CANCEL}" name="cancel">
        </td>
    </tr>
</table>

</form>
<br clear="all" />
<!-- END switch_save_sig -->