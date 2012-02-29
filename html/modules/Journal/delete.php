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
$pagetitle = "- "._USERSJOURNAL."";
include_once(NUKE_BASE_DIR.'header.php');
include(NUKE_MODULES_DIR.$module_name.'/functions.php');

if (is_user() || is_admin()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
    $username = check_html($username, "nohtml");
    $sitename = check_html($sitename, "nohtml");
    $debug = check_html($debug, "nohtml");
    if ($debug == 'true') {
        echo ("UserName:$username<br />SiteName: $sitename");
    }
    $jid = intval($jid);
    startjournal($sitename,$user);
    echo ("<br />");
    OpenTable();
    echo ("<div align=center class=title><strong>"._ABOUTTODELETE."</strong><br /><br /><img src=\"modules/$module_name/images/trash.gif\" alt=\"\" />&nbsp;&nbsp;&nbsp;<img src=\"modules/$module_name/images/trash.gif\" alt=\"\" />&nbsp;&nbsp;&nbsp;<img src=\"modules/$module_name/images/trash.gif\" alt=\"\" /></div>");
    echo ("<br /><div align=center>"._SUREDELJOURNAL."<br /><br />[ <a href=\"modules.php?name=$module_name&amp;file=deleteyes&amp;jid=$jid\">"._YES."</a> | <a href=\"modules.php?name=$module_name&amp;file=edit\">"._NO."</a> ]</div><br /><br />");
    echo ("<div align=center>"._YOUCANTSAVE."</div>");
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