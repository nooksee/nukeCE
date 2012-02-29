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

$sql = "SELECT pid, title FROM " . $prefix . "_pages WHERE active='1'";
$result = $db->sql_query($sql);
while (list($pid, $title) = $db->sql_fetchrow($result)) {
    $pid = intval($pid);
    $title = stripslashes($title);
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"modules.php?name=Content&amp;pa=showpage&amp;pid=$pid\">
                     $title
                 </a>
                 <br />
                ";
}
$db->sql_freeresult($result);

?>