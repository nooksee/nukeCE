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
$sid = intval($sid);
$query = $db->sql_query("SELECT associated FROM ".$prefix."_stories WHERE sid='$sid'");
list($associated) = $db->sql_fetchrow($query);
$db->sql_freeresult($query);

if (!empty($associated)) {
    OpenTable();
    echo "<center><strong>"._ASSOTOPIC."</strong><br /><br />";
     $asso_t = explode("-",$associated);
    for ($i=0; $i<count($asso_t); $i++) {
    if (!empty($asso_t[$i])) {
        $query = $db->sql_query("SELECT topicimage, topictext from ".$prefix."_topics WHERE topicid='".$asso_t[$i]."'");
        list($topicimage, $topictext) = $db->sql_fetchrow($query);
        $db->sql_freeresult($query);
        echo "<a href=\"modules.php?name=$module_name&new_topic=$asso_t[$i]\"><img src=\"".$tipath.$topicimage."\" border=\"0\" hspace=\"10\" alt=\"".$topictext."\" title=\"".$topictext."\"></a>";
    }
    }
    echo "</center>";
    CloseTable();
    echo "<br />";
}

?>