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

if (!defined('ADMIN_FILE')) {
   die ("Illegal File Access");
}

global $prefix, $db;

if (is_mod_admin()) {

function removeSubComments($tid) {
    global $prefix, $db;
    $tid = intval($tid);
    $result = $db->sql_query("SELECT tid from " . $prefix . "_comments where pid='$tid'");
    $numrows = $db->sql_numrows($result);
    if($numrows>0) {
    while ($row = $db->sql_fetchrow($result)) {
    $stid = intval($row['tid']);
            removeSubComments($stid);
            $stid = intval($stid);
            $db->sql_query("delete from " . $prefix . "_comments where tid='$stid'");
        }
    }
    $db->sql_query("delete from " . $prefix . "_comments where tid='$tid'");
}

function removeComment ($tid, $sid, $ok=0) {
    global $ultramode, $prefix, $db, $admin_file;
    if($ok) {
        $tid = intval($tid);
        $result = $db->sql_query("SELECT date from " . $prefix . "_comments where pid='$tid'");
        $numresults = $db->sql_numrows($result);
        $sid = intval($sid);
        $db->sql_query("update " . $prefix . "_stories set comments=comments-1-'$numresults' where sid='$sid'");
        removeSubComments($tid);
        if ($ultramode) {
            ultramode();
        }
        redirect("modules.php?name=News&file=article&sid=$sid");
    } else {
        confirm_msg(_SURETODELCOMMENTS, "".$admin_file.".php?op=RemoveComment&amp;tid=$tid&amp;sid=$sid&amp;ok=1", "modules.php?name=News&file=article&sid=$sid");
    }
}

function removePollSubComments($tid) {
    global $prefix, $db;
    $tid = intval($tid);
    $result = $db->sql_query("SELECT tid from " . $prefix . "_pollcomments where pid='$tid'");
    $numrows = $db->sql_numrows($result);
    if($numrows>0) {
        while ($row = $db->sql_fetchrow($result)) {
            $stid = intval($row['tid']);
            removePollSubComments($stid);
            $db->sql_query("delete from " . $prefix . "_pollcomments where tid='$stid'");
        }
    }
    $db->sql_query("delete from " . $prefix . "_pollcomments where tid='$tid'");
}

function RemovePollComment ($tid, $pollID, $ok=0) {
    global $admin_file;
    if($ok) {
        removePollSubComments($tid);
        redirect("modules.php?name=Surveys&op=results&pollID=$pollID");
    } else {
        confirm_msg(_SURETODELCOMMENTS, "".$admin_file.".php?op=RemovePollComment&amp;tid=$tid&amp;pollID=$pollID&amp;ok=1", "modules.php?name=Surveys&op=results&pollID=$pollID");
    }
}

switch ($op) {

    case "RemoveComment":
    removeComment ($tid, $sid, $ok);
    break;

    case "removeSubComments":
    removeSubComments($tid);
    break;

    case "removePollSubComments":
    removePollSubComments($tid);
    break;

    case "RemovePollComment":
    RemovePollComment($tid, $pollID, $ok);
    break;

}

} else {
    echo "Access Denied";
}

?>