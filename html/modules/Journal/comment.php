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

$pagetitle = "- "._USERSJOURNAL."";
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
    $debug = check_html($debug, "nohtml");
    if ($debug == 'true') {
        echo ("UserName:$username<br />SiteName: $sitename");
    }

    $onwhat = intval($onwhat);
    startjournal($sitename,$user);

    function dropcomment($username,$onwhat,$mtime,$ndate) {
        global $module_name;
        $onwhat = intval($onwhat);
        echo "<br />";
        OpenTable();
        echo ("<div align=center class=title>"._LEAVECOMMENT."</div><br /><br />");
        echo ("<form action='modules.php?name=$module_name&amp;file=commentsave' method='post'><input type='hidden' name='rid' value='$onwhat'>");
        echo ("<div align=center>"._COMMENTBOX.":<br /><textarea name=\"comment\" wrap=virtual cols=55 rows=10></textarea><br /><br /><input type='submit' name='submit' value='"._POSTCOMMENT."'></div>");
        echo ("</form><br />");
        echo ("<center>"._COMMENTSNOTE."</center>");
        CloseTable();
    }
}
if (!is_user()) {
    echo ("<br />");
    OpenTable();
    echo ("<div align=center>"._YOUMUSTBEMEMBER."<br /></div>");
    CloseTable();
    journalfoot();
    exit;
} else {
    dropcomment($username,$onwhat,$mtime,$ndate);
}

journalfoot();

?>