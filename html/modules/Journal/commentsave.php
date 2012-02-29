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
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

$pagetitle = '- '._USERSJOURNAL;

include_once(NUKE_BASE_DIR.'header.php');
include(NUKE_MODULES_DIR.$module_name.'/functions.php');
if (is_user()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
    $htime = date("h");
    $mtime = date("i");
    $ntime = date("a");
    $mtime = "$htime:$mtime $ntime";
    $mdate = date("m");
    $ddate = date("d");
    $ydate = date("Y");
    $ndate = "$mdate-$ddate-$ydate";
    $username = check_html($username, "nohtml");
    $sitename = check_html($sitename, "nohtml");
    $ndate = check_html($ndate, "nohtml");
    $rid = check_html($rid, "nohtml");
    $comment = addslashes(check_html($comment, "nohtml"));
    startjournal($sitename,$user);
    $rid = intval($rid);
    $sql="INSERT INTO ".$prefix."_journal_comments VALUES ('','$rid','$username','$comment','$ndate','$mtime')";
    $db->sql_query($sql);
    echo ("<br />");

    OpenTable();
    echo ("<div align=center>"._COMMENTPOSTED."<br /><br />");
    echo ("<a href=\"modules.php?name=$module_name&amp;file=display&amp;jid=$rid\">"._RETURNJOURNAL2."</a><br /><br /><div class=title>"._THANKS."</div></div>");
    CloseTable();
    journalfoot();
}
if (!is_user() && !is_admin()) {
        $pagetitle = '- '._YOUMUSTBEMEMBER;
        $pagetitle = check_html($pagetitle, "nohtml");
        OpenTable();
        echo "<center><strong>"._YOUMUSTBEMEMBER."</strong></center>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
        exit;
    }

?>