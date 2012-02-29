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

$a = 1;
$result = $db->sql_query("SELECT lid, title FROM ".$prefix."_downloads_downloads ORDER BY hits DESC LIMIT 0,10");
while (list($lid, $title) = $db->sql_fetchrow($result)) {
    $lid = intval($lid);
    $title = stripslashes($title);
    $title2 = ereg_replace("_", " ", $title);
    if (strlen($title2) > 18) {
        $title2 = substr($title, 0, 12);
        $title2 .= '...';
    }
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 $a: 
                 <a href=\"modules.php?name=Downloads&amp;d_op=viewdownloaddetails&amp;lid=$lid&amp;title=$title\">
                     $title2
                 </a>
                 <br>
                ";
    $a++;
}
$db->sql_freeresult($result);

?>