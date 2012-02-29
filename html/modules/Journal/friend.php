<?php

/************************************************************************/
/* PHP-NUKE EVOLVED: Web Portal System                                  */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2008 by Kevin Atwood                                   */
/* http://www.nuke-evolved.com                                          */
/*                                                                      */
/* All PHP-Nuke code is released under the GNU General Public License.  */
/* See COPYRIGHT.txt and LICENSE.txt.                                   */
/************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
get_lang($module_name);
if (!isset($jid) OR !is_numeric($jid)) { die('No journal specified.'); }
$pagetitle = '- '._USERSJOURNAL;
include_once(NUKE_BASE_DIR.'header.php');
include(NUKE_MODULES_DIR.$module_name.'/functions.php');
if (is_user()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
    $user = check_html($user, "nohtml");
    $username = check_html($username, "nohtml");
    $sitename = check_html($sitename, "nohtml");
    $debug = check_html($debug, "nohtml");
    if ($debug == "true") {
        echo ("UserName:$username<br />SiteName: $sitename");
    }
    startjournal($sitename, $user);
    $jid = intval($jid);
    $sql = "SELECT title FROM ".$prefix."_journal WHERE jid='$jid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $jtitle = $row['title'];
    $send = intval($send);
    $sent = intval($sent);
    if ($send == 1) {
        $fname = removecrlf($fname);
        $fmail = validate_mail(removecrlf($fmail));
        $yname = removecrlf($yname);
        $ymail = validate_mail(removecrlf($ymail));
        $subject = _INTERESTING." $sitename";
        $message = _HELLO." $fname:\n\n"._YOURFRIEND." $yname "._CONSIDERED."\n\n\n$jtitle\n"._URL.": $nukeurl/modules.php?name=$module_name&amp;file=display&amp;jid=$jid\n\n\n"._AREMORE."\n\n---\n$sitename\n$nukeurl";
        nuke_mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());
        $title = urlencode($jtitle);
        $fname = urlencode($fname);
        $sent = 1;
    }
    if ($sent == 1) {
        echo "<br />";
        title(_SENDJFRIEND);
        OpenTable();
        echo "<center>"._FSENT."<br /><br />[ <a href=\"modules.php?name=$module_name&amp;file=display&amp;jid=$jid\">"._RETURNJOURNAL2."</a> ]</center>";
        CloseTable();
        journalfoot();
        exit;
    }
    echo "<br />";
    title(_SENDJFRIEND);
    OpenTable();
    echo "<table align=center border=0><tr><td>" ."<center><strong>$jtitle</strong><br />"._YOUSENDJOURNAL."</center><br /><br />" ."<form action=\"modules.php?name=$module_name&amp;file=friend\" method=\"post\">" ."<input type=\"hidden\" name=\"send\" value=\"1\" />" ."<input type=\"hidden\" name=\"jid\" value=\"$jid\" />";
    if (is_user()) {
        $sql = "SELECT name, username, user_email FROM ".$user_prefix."_users WHERE user_id = '".intval($cookie[0])."'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $yn = check_html($row['name'], "nohtml");
        $yun = check_html($row['username'], "nohtml");
        $ye = check_html($row['user_email'], "nohtml");
    }
    if (empty($yn)) {
        $yn = $yun;
    }
    echo "<strong>"._FYOURNAME." </strong> <input type=\"text\" name=\"yname\" value=\"$yn\"><br /><br />\n" ."<strong>"._FYOUREMAIL." </strong> <input type=\"text\" name=\"ymail\" value=\"$ye\"><br /><br /><br />\n" ."<strong>"._FFRIENDNAME." </strong> <input type=\"text\" name=\"fname\"><br /><br />\n" ."<strong>"._FFRIENDEMAIL." </strong> <input type=\"text\" name=\"fmail\"><br /><br />\n" ."<input type=\"hidden\" name=\"op\" value=\"SendStory\">\n" ."<input type=\"submit\" value="._SEND.">\n" ."</form></td></tr></table>\n";
    CloseTable();
    journalfoot();
} else {
    echo ("<br />");
    OpenTable();
    echo ("<div align=center>"._YOUMUSTBEMEMBER."<br /></div>");
    CloseTable();
    journalfoot();
    exit;
}

?>