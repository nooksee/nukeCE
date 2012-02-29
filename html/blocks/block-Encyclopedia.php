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

if(!defined('NUKE_CE')) exit;

global $prefix, $db;

$sql = "SELECT eid, title FROM " . $prefix . "_encyclopedia WHERE active='1'";
$result = $db->sql_query($sql);
while (list($eid, $title) = $db->sql_fetchrow($result)) {
    $eid = intval($eid);
    $title = stripslashes($title);
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                      </big>
                 </strong>
                 <a href=\"modules.php?name=Encyclopedia&amp;op=list_content&amp;eid=$eid\">
                     $title
                 </a>
                 <br />
                ";    
}

?>