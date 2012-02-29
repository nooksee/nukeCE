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

if (!isset($jid) OR !is_numeric($jid)) { die('No journal specified.'); }
$pagetitle = '- '._USERSJOURNAL;
include_once(NUKE_BASE_DIR.'header.php');
include(NUKE_MODULES_DIR.$module_name.'/functions.php');
if (is_user()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
}
startjournal($sitename,$user);

if (is_user()) {
    $jid = intval($jid);
    $sql = "SELECT * FROM ".$prefix."_journal WHERE jid = '$jid'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        $owner = $row['aid'];
        if ($owner != $username) {
            OpenTable();
            echo ("<div align=center>"._YOUWRONG."</div>");
            CloseTable();
            echo ("<br />");
            journalfoot();
            exit;
        }
        $sql = "DELETE FROM ".$prefix."_journal WHERE jid = '$jid'";
        $db->sql_query($sql);
        $sql = "DELETE FROM ".$prefix."_journal_comments WHERE rid = '$jid'";
        $db->sql_query($sql);
        echo ("<br />");
        OpenTable();
        echo ("<div align=center>"._ENTRYREMOVED."<br /><br />");
        echo ("<a href=\"modules.php?name=$module_name&amp;file=edit\">"._RETURNJOURNAL."</a></div>");
        CloseTable();
    }
    $db->sql_freeresult($result);
    journalfoot();
} else {
        if (is_admin()) {
            $sql = "DELETE FROM ".$prefix."_journal WHERE jid = '$jid'";
            $db->sql_query($sql);
            $sql = "DELETE FROM ".$prefix."_journal_comments WHERE rid = '$jid'";
            $db->sql_query($sql);
            echo ("<br />");
            OpenTable();
            echo ("<div align=center>"._ENTRYREMOVED."<br /><br />");
            echo ("<a href=\"modules.php?name=$module_name&amp;file=edit\">"._RETURNJOURNAL."</a></div>");
            CloseTable();
        }
        journalfoot();
}
$pagetitle = '- '._YOUMUSTBEMEMBER;
$pagetitle = check_html($pagetitle, "nohtml");
OpenTable();
echo "<center><strong>"._YOUMUSTBEMEMBER."</strong></center>";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');
exit;

?>