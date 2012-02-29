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

/* Block to fit perfectly in the center of the site, remember that not all
   blocks looks good on Center, just try and see yourself what fits your needs */

if(!defined('NUKE_CE')) exit;

global $prefix, $multilingual, $currentlang, $db;

if ($multilingual == 1) {
    $querylang = "WHERE (alanguage='$currentlang' OR alanguage='')";
} else {
    $querylang = '';
}
$content = "
            <table align=\"center\" width=\"90%\" border=\"0\">
           ";
$sql = "SELECT sid, title, comments, counter FROM ".$prefix."_stories $querylang ORDER BY sid DESC LIMIT 0,5";
$result = $db->sql_query($sql);
while (list($sid, $title, $comments, $counter) = $db->sql_fetchrow($result)) {
    $title = stripslashes($title);
    $content .= "
                 <tr>
                     <td align=\"left\">
                         <strong>
                             <big>
                                 &middot;
                             </big>
                         </strong>
                         <a href=\"modules.php?name=News&amp;file=article&amp;sid=".$sid."\">
                             $title
                         </a>
                     </td>
                     <td align=\"right\">
                         [ $comtotal "._COMMENTS." - $counter "._READS." ]
                     </td>
                 </tr>
                ";
}
$db->sql_freeresult($result);
$content .= "
             </table>
            ";
$content .= "
             <br />
             <div align=\"center\">
                 [ 
                 <a href=\"modules.php?name=News\">
                     "._MORENEWS."
                 </a> 
                 ]
             </div>
            ";

?>