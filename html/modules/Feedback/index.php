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

$subject = $sitename." "._FEEDBACK;

include_once(NUKE_BASE_DIR.'header.php');

if (!isset($opi) || ($opi != 'ds')) {
    $intcookie = intval($cookie[0]);
    if (!empty($cookie[1])) {
        $sql = "SELECT name, username, user_email FROM ".$user_prefix."_users WHERE user_id='".$intcookie."'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        if (!empty($row['name'])) {
            $sender_name = $row['name'];
        } else {
            $sender_name = $row['username'];
        }
        $sender_email = $row['user_email'];
    } else {
        $sender_email = "";
        $sender_name = "";
    }
}

if (!isset($message)) { $message = ''; }
if (!isset($opi)) { $opi = ''; }
if (!isset($send)) { $send = ''; }

title(_FEEDBACKTITLE);
info_box("note", _FEEDBACKNOTE);
$form_block = "
	       <table border=\"0\" width=\"100%\">
                   <tr>
                       <td nowrap>
                           <form onsubmit=\"this.submit.disabled='true'\" method=\"post\" action=\"modules.php?name=$module_name\">
		       </td>
                   </tr>
                   <tr>
                       <td>
                           <strong>
                               "._YOURNAME.":
                           </strong>
                       </td>
                       <td>
                           <INPUT type=\"text\" NAME=\"sender_name\" VALUE=\"$sender_name\" SIZE=30>
                       </td>
                   </tr>
                   <tr>
                       <td nowrap>
                           <strong>
                               "._YOUREMAIL.":
                           </strong>
                       </td>
                       <td>
                           <INPUT type=\"text\" NAME=\"sender_email\" VALUE=\"$sender_email\" SIZE=30>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           <strong>
                               "._MESSAGE.":
                           </strong>
                       </td>
                       <td>
                           <TEXTAREA NAME=\"message\" COLS=60 ROWS=10 WRAP=virtual>$message</TEXTAREA>
                           <br>
                           <b>
                               ".security_code(array(7), 'normal', 1)."
                           </b>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           &nbsp;
                       </td>
                       <td>
                           <INPUT type=\"hidden\" name=\"opi\" value=\"ds\">
                           <INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\""._SEND."\">
                           </FORM>
                       </td>
                   </tr>
               </table>
              ";

OpenTable();

if ($opi != 'ds') {
    echo $form_block;
} else {
    $send = true;
    if (empty($sender_name)) {
        $name_err = "
                     <div align=\"center\">
                         <span class=\"option\">
                             <strong>
                                 <em>
                                     "._FBENTERNAME."
                                 </em>
                             </strong>
                         </span>
                     </div>
                     <br />
                    ";
        $send = false;
    }
    if (!Validate($sender_email, 'email', $module_name, 1)) {
        $email_err = "<div align=\"center\"><span class=\"option\"><strong><em>"._FBENTEREMAIL."</em></strong></span></div><br />";
        $send = false;
    }
    if (empty($message)) {
        $message_err = "<div align=\"center\"><span class=\"option\"><strong><em>"._FBENTERMESSAGE."</em></span></font></div><br />";
        $send = false;
    }
    if (!security_code_check($_POST['gfx_check'], 'force')) {
        $texterrorcenter = "<div align=\"center\"><span class=\"option\"><strong><em>"._FBSECCODEFAIL."</em></span></font></div><br />";
        $send = false;
    }

    if ($send) {
        global $nsnst_const;
        $sender_name = Remove_Slashes(removecrlf($sender_name));
        $sender_email = Remove_Slashes(removecrlf($sender_email));
        $message = Remove_Slashes($message);
        $msg = $sitename."\n\n";
        $msg .= _SENDERNAME.": $sender_name\n";
        $msg .= _SENDEREMAIL.": $sender_email\n";
        $msg .= _MESSAGE.": $message\n\n";
        $msg .= "IP: ".$nsnst_const['remote_ip']."\n\n";
        $to = $adminmail;
        $mailheaders = "From: $sender_name <$sender_email>\r\n";
        $mailheaders .= "Reply-To: $sender_email\r\nX-Mailer: PHP/" . phpversion();
        mail($to, $subject, $msg, $mailheaders);
        echo "<div align=\"center\"><p>"._FBMAILSENT."</p></div>";
        echo "<div align=\"center\"><p>"._FBTHANKSFORCONTACT."</p></div>";
    } elseif (!$send) {
        OpenTable2();
        if (!empty($name_err)) { echo $name_err; }
        if (!empty($email_err)) {echo $email_err; }
        if (!empty($message_err)) {echo $message_err; }
        if (!security_code_check($_POST['gfx_check'], 'force')) {echo "$texterrorcenter"; }
	CloseTable2();
        echo "<br /><br />";
        echo $form_block;
    }
}

CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>