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

// Group Memberships
$result = $db->sql_query("SELECT ug.group_id, g.group_name, g.group_description FROM ".$prefix."_bbuser_group ug INNER JOIN ".$prefix."_bbgroups g ON (g.group_id = ug.group_id AND g.group_single_user = 0) WHERE ug.user_pending = 0 AND ug.user_id = ".$usrinfo['user_id']);
if ($db->sql_numrows($result) > 0) {
    $usrcolor = UsernameColor($usrinfo['username']);
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"3\" height=\"28\">
                      <span class=\"cattitle\">
                          ".$usrcolor."'s "._MEMBERGROUPS."
                      </span>
                  </td>
              </tr>
              <tr>
                  <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      &nbsp;"._NAME."&nbsp;
                  </th>
                  <th align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                      &nbsp;"._DESCRIPTION."&nbsp;
                  </th>
              </tr>
              <tr>
         ";
    while(list($gid, $gname, $gdescription) = $db->sql_fetchrow($result)) {
        $grpcolor = GroupColor($gname);
        echo "
              <td class=\"row1\" width=\"40%\">
                  <span class=\"topictitle\">
                      <a href=\"modules.php?name=Groups&amp;g=$gid\">
                          $grpcolor
                      </a>
             ";
        if(is_mod_admin($module_name)) { echo "($gid)"; };
        echo "
                      </span>
                      <span class=\"gensmall\">
                      <br />
                      </span>
                  </td>
                  <td valign=\"middle\" class=\"row2\" nowrap=\"nowrap\" align=\"center\">
                      <span class=\"genmed\">
                          $gdescription
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