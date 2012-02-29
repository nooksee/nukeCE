<?php

/********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ================================================================             */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion */
/********************************************************************************/

if(!defined('IN_YA')) {
    exit('Access Denied');
}

// Last 4 Pics
$result32 = $db->sql_query("SELECT pic_id, pic_title, pic_user_id, pic_time FROM ".$prefix."_bbalbum WHERE pic_user_id='$usrinfo[user_id]' AND ( pic_approval = 1 ) order by pic_time DESC LIMIT 0,4");
if (($db->sql_numrows($result32) > 0)) {
    $usrcolor = UsernameColor($usrinfo['username']);
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <th colspan=\"4\" align=\"center\" height=\"25\" class=\"thHead\" nowrap=\"nowrap\">
                      ".$usrcolor."'s "._LAST4BBPIC."
                  </th>
              </tr>
              <tr>
         ";
    while(list($pic_id, $pic_title) = $db->sql_fetchrow($result32)) {
        echo "
                  <td class=\"row1\">
                      <table align=\"center\">
                          <tr>
                              <td width=\"100%\">
                                  <span class=\"gen\">
                                      <a href=\"modules.php?name=Forums&amp;file=album_page&amp;pic_id=$pic_id\">
                                          <img src=\"modules.php?name=Forums&amp;file=album_thumbnail&amp;pic_id=$pic_id\" border=\"0\" alt=\"".$pic_title."\" title=\"".$pic_title."\" vspace=\"10\" />
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
    echo "
              </tr>
          </table>
          <span class=\"gen\">
          <br />
          </span>
         ";
}

?>