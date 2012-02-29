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

# Number of reviews to display
$number_of_reviews = 5;
$image_height = 100;
$image_width = 100;

$sql = "SELECT id, title, text, cover, date FROM ".$prefix."_reviews ORDER BY id DESC LIMIT 0,$number_of_reviews ";

$result = $db->sql_query($sql);

while (list($id, $title, $text, $cover, $date) = $db->sql_fetchrow($result)) {
    $id = intval($id);
    $title = stripslashes($title);
    if (strlen($title) > 18) {
        $title = substr($title, 0, 15);
        $title .= '...';
    }
    $cover = wordwrap($cover);
    $content .= "
                 <table width=\"100%\" border=\"0\">
                     <tr>
                         <td nowrap=\"nowrap\">
                             <strong>
                                 <big>
                                     &middot;
                                 </big>
                             </strong>
                             <a href=\"modules.php?name=Reviews&amp;rop=showcontent&amp;id=$id\">
                                 $title
                             </a>
                             <br />
                         </td>
                     </tr>
                 </table>
                 <table width=\"100%\" border=\"0\">
                             <div align=\"center\">
                                 <a href=\"modules.php?name=Reviews&amp;rop=showcontent&amp;id=$id\">
                                     <img src=\"images/reviews/$cover\" width=\"$image_width\" height=\"$image_height\" />
                                 </a>
                             </div>
                         </td>
                     </tr>
                 </table>
                ";
}
$db->sql_freeresult($result);
?>