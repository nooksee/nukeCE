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

// Last 3 Pics
global $prefix, $db;
get_lang('blocks');
$result32 = $db->sql_query("SELECT pic_id, pic_title, pic_user_id, pic_time FROM ".$prefix."_bbalbum WHERE ( pic_approval = 1 ) order by pic_time DESC LIMIT 0,3");
if (($db->sql_numrows($result32) > 0)) {
    $usrname = $usrinfo['username'];
    $content .= "
                 <table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">
                     <tr>
                ";
    while(list($pic_id, $pic_title) = $db->sql_fetchrow($result32)) {
        $content .= "
                         <td>
                             <table align=\"center\">
                                 <tr>
                                     <td width=\"100%\">
                                         <span class=\"gen\">
                                             <a href=\"modules.php?name=Forums&amp;file=album_page&amp;pic_id=$pic_id\">
                                                 <img src=\"modules.php?name=Forums&amp;file=album_thumbnail&amp;pic_id=$pic_id\" border=\"0\" alt=\"".$pic_title."\" title=\"".$pic_title."\" vspace=\"5\" />
                                             </a>
                                         </span>
                                         <span class=\"gensmall\">
                                         <br />
                                         </span>
                                     </td>
                                 </tr>
                             </td>
                         </table>
                     </td>
                    ";
    }
    $content .= "
                     </tr>
                 </table>
                 <table width=\"100%\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\">
                     <tr>
                         <td align=\"center\" valign=\"bottom\">
                             <span class=\"gensmall\">
                                 <a href=\"rss.php?feed=pics\" target=\"_blank\">
                                     <img src=\"images/powered/rss.png\" alt=\""._RSS."\" title=\""._RSS."\" border=\"0\" />
                                 </a>
                             </span>
                         </td>
                     </tr>
                 </table>
                ";
}

?>