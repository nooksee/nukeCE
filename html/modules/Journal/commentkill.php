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
if (is_admin()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
    $username = check_html($username, "nohtml");
    $sitename = check_html($sitename, "nohtml");
    $debug = check_html($debug, "nohtml");
    if ($debug == 'true') {
        echo ("UserName:$username<br />SiteName: $sitename");
    }

    startjournal($sitename,$user);
    $onwhat = intval($onwhat);
    $sql = "DELETE FROM ".$prefix."_journal_comments WHERE cid = '$onwhat'";
    $db->sql_query($sql);
    echo "<br />";
    OpenTable();
    echo ("<div align=center>"._COMMENTDELETED."<br /><br />");
    echo ("[ <a href=\"modules.php?name=$module_name&amp;file=display&amp;jid=$ref\">"._RETURNJOURNAL."</a> ]</div>");
    CloseTable();
    journalfoot();
} else {
        if (is_user()) {
            $cookie = cookiedecode();
            $username = $cookie[1];
            if ($debug == "true") {
                echo ("UserName:$username<br />SiteName: $sitename");
            }
            startjournal($sitename, $user);
            $onwhat = intval($onwhat);
            $sql = "DELETE FROM ".$prefix."_journal_comments WHERE cid = '$onwhat' AND aid = '$username'";
            $db->sql_query($sql);
            echo "<br />";
            OpenTable();
            echo ("<div align=center>"._COMMENTDELETED."<br /><br />");
            echo ("[ <a href=\"modules.php?name=$module_name&amp;file=display&amp;jid=$ref\">"._RETURNJOURNAL."</a> ]</div>");
            CloseTable();
            journalfoot();
        }
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