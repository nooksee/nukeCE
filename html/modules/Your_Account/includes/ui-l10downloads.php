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

// Last 10 Download Links Approved

// menelaos: changed these routines to fist check if the database table exist
$result9 = $db->sql_query("SELECT lid, title, date FROM ".$prefix."_downloads_downloads where submitter='$usrinfo[username]' order by date DESC limit 0,10");
if (($db->sql_numrows($result9) > 0)) {
    $usrcolor = UsernameColor($usrinfo['username']);
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"3\" height=\"28\">
                      <span class=\"cattitle\">
                          ".$usrcolor."'s "._LAST10DOWNLOAD."
                      </span>
                  </td>
              </tr>
              <tr>
                  <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      &nbsp;"._TITLE."&nbsp;
                  </th>
                  <th align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                      &nbsp;"._DATE."&nbsp;
                  </th>
              </tr>
              <tr>
         ";
    while(list($lid, $title, $date) = $db->sql_fetchrow($result9)) {
        echo "
              <td class=\"row1\" width=\"60%\">
                  <span class=\"topictitle\">
                      <a href=\"modules.php?name=Downloads&amp;op=getit&amp;lid=$lid\">
                          $title
                      </a>
             ";
        if(is_mod_admin($module_name)) { echo "($lid)"; };
        echo "
                      </span>
                      <span class=\"gensmall\">
                      <br />
                      </span>
                  </td>
                  <td valign=\"middle\" class=\"row2\" nowrap=\"nowrap\" align=\"center\">
                      <span class=\"genmed\">
                          $date
                      </span>
                  </td>
              </tr>
             ";
      }
    echo "
          </table>
          <span class=\"gen\">
          <br />
          </span>
         ";
}

?>