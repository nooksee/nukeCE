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

$sql = "SELECT id, title FROM ".$prefix."_reviews ORDER BY id DESC LIMIT 0,10";
$result = $db->sql_query($sql);
while (list($id, $title) = $db->sql_fetchrow($result)) {
    $id = intval($id);
    $title = stripslashes($title);
    if (strlen($title) > 18) {
        $title = substr($title, 0, 15);
        $title .= '...';
    }
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"modules.php?name=Reviews&amp;rop=showcontent&amp;id=$id\">
                     $title
                 </a>
                 <br />
                ";
}
$db->sql_freeresult($result);

?>