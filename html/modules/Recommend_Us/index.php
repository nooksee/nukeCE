<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

$subject = $sitename." "._RECOMMEND;

include_once(NUKE_BASE_DIR.'header.php');

if (!isset($opi) || ($opi != 'ds')) {
  $intcookie = intval($cookie[0]);
  if (!empty($cookie[1])) {
    $sql = "SELECT name, username, user_email FROM ".$user_prefix."_users WHERE user_id='".$intcookie."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    if (!empty($row['name'])) {
      $yname = $row['name'];
    } else {
      $yname = $row['username'];
    }
    $ymail = $row['user_email'];
  } else {
    $ymail = "";
    $yname = "";
  }
}

if (!isset($message)) { $message = ''; }
if (!isset($mess)) { $mess = ''; }
if (!isset($opi)) { $opi = ''; }
if (!isset($send)) { $send = ''; }

title(_RECOMMEND);
info_box("important", _RECOMMENDNOTE);
$form_block = "
	<table border=\"0\" width=\"100%\">
	<tr><td nowrap><form onsubmit=\"this.submit.disabled='true'\" method=\"post\" action=\"modules.php?name=$module_name\">
		</td></tr><tr><td><strong>"._FYOURNAME."</strong></td><td><INPUT type=\"text\" NAME=\"yname\" VALUE=\"$yname\" SIZE=30></td></tr>
    <tr><td nowrap><strong>"._FYOUREMAIL."</strong></td><td><INPUT type=\"text\" NAME=\"ymail\" VALUE=\"$ymail\" SIZE=30></td></tr>
    <tr><td><strong>"._FFRIENDNAME."</strong></td><td><INPUT type=\"text\" NAME=\"fname\" VALUE=\"$fname\" SIZE=30></td></tr>
    <tr><td><strong>"._FFRIENDEMAIL."</strong></td><td><INPUT type=\"text\" NAME=\"fmail\" VALUE=\"$fmail\" SIZE=30></td></tr>
    <tr><td><strong>"._MESSAGE.":</strong><br>"._OPTION."</td><td><TEXTAREA NAME=\"message\" COLS=60 ROWS=10 WRAP=virtual>$mess</TEXTAREA><br>
    ".security_code(array(7), 'normal', 1)."</b></td></tr>
    <tr><td>&nbsp;</td><td><INPUT type=\"hidden\" name=\"opi\" value=\"ds\">
    <INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\""._SEND."\">
    </FORM></td></tr></table>
";

OpenTable();

if ($opi != 'ds') {
    echo $form_block;
} else {
    $send = true;
    if (empty($yname)) {
        $yname_err = "<div align=\"center\"><span class=\"option\"><strong><em>"._RUENTERNAME."</em></strong></span></div><br />";
        $send = false;
    }
    if (!Validate($ymail, 'ymail', $module_name, 1)) {
        $ymail_err = "<div align=\"center\"><span class=\"option\"><strong><em>"._RUENTEREMAIL."</em></strong></span></div><br />";
        $send = false;
    }
    if (!Validate($fmail, 'fmail', $module_name, 1)) {
        $fmail_err = "<div align=\"center\"><span class=\"option\"><strong><em>"._RUENTERFEMAIL."</em></strong></span></div><br />";
        $send = false;
    }
    if (!security_code_check($_POST['gfx_check'], 'force')) {
        $texterrorcenter = "<div align=\"center\"><span class=\"option\"><strong><em>"._RUSECCODEFAIL."</em></span></font></div><br />";
        $send = false;
    }

    if ($send) {
        global $sitename, $slogan, $nukeurl, $module_name;
    $fname = stripslashes(Fix_Quotes(check_html(removecrlf($fname))));
    $fmail = validate_mail(stripslashes(check_html(removecrlf($fmail))));
    $yname = stripslashes(Fix_Quotes(check_html(removecrlf($yname))));
    $ymail = validate_mail(check_html(removecrlf($ymail)));
    $mess = Remove_Slashes($message);
    $subject = ""._INTSITE." $sitename";
    $message = ""._HELLO." $fname:\n\n"._YOURFRIEND." $yname "._OURSITE." $sitename "._INTSENT."\n\n\n$mess\n\n\n"._FSITENAME." $sitename\n$slogan\n"._FSITEURL." $nukeurl\n";
    mail($fmail, $subject, $message, "FROM: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());
    echo "<div align=\"center\"><p>"._FREFERENCE."&nbsp;$fmail</p></div>";
        echo "<div align=\"center\"><p>"._THANKSREC."</p></div>";
    } elseif (!$send) {
        OpenTable2();
        if (!empty($yname_err)) {echo $yname_err; }
        if (!empty($ymail_err)) {echo $ymail_err; }
        if (!empty($fmail_err)) {echo $fmail_err; }
        if (!security_code_check($_POST['gfx_check'], 'force')) {echo "$texterrorcenter"; }
		CloseTable2();
        echo "<br /><br />";
        echo $form_block;
    }
}

CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>