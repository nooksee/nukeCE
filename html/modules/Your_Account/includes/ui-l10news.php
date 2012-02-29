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

// Last 10 Comments
if ($articlecomm == 1) {
    $result6 = $db->sql_query("SELECT tid, sid, date, subject FROM ".$prefix."_comments WHERE name='$usrinfo[username]' ORDER BY tid DESC LIMIT 0,10");
    if (($db->sql_numrows($result6) > 0)) {
        $usrcolor = UsernameColor($usrinfo['username']);
        echo "
              <span class=\"gen\">
              <br />
              </span>
              <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                  <tr>
                      <td class=\"catHead\" colspan=\"3\" height=\"28\">
                          <span class=\"cattitle\">
                              ".$usrcolor."'s "._LAST10COMMENT."
                          </span>
                      </td>
                  </tr>
                  <tr>
                      <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                          &nbsp;"._SUBJECT."&nbsp;
                      </th>
                      <th align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                          &nbsp;"._DATE."&nbsp;
                      </th>
                  </tr>
                  <tr>
             ";
        while($row6 = $db->sql_fetchrow($result6)) {
            $tid = $row6['tid'];
            $sid = $row6['sid'];
            $subject = $row6['subject'];
            $date = $row6['date'];
            echo "
                      <td class=\"row1\" width=\"60%\">
                          <span class=\"topictitle\">
                              <a href=\"modules.php?name=News&amp;file=article&amp;thold=-1&amp;mode=flat&amp;order=0&amp;sid=$sid#$tid\">
                                  $subject
                              </a>
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
}
// Last 10 Submissions
$result7 = $db->sql_query("SELECT sid, title, time FROM ".$prefix."_stories WHERE informant='$usrinfo[username]' ORDER BY sid DESC LIMIT 0,10");
if (($db->sql_numrows($result7) > 0)) {
    $usrcolor = UsernameColor($usrinfo['username']);
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"3\" height=\"28\">
                      <span class=\"cattitle\">
                          ".$usrcolor."'s "._LAST10SUBMISSION."
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
    while($row7 = $db->sql_fetchrow($result7)) {
        $sid = $row7['sid'];
        $title = $row7['title'];
        $time = $row7['time'];
        echo "
                  <td class=\"row1\" width=\"60%\">
                      <span class=\"topictitle\">
                          <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid\">
                              $title
                          </a>
                      </span>
                      <span class=\"gensmall\">
                      <br />
                      </span>
                  </td>
                  <td valign=\"middle\" class=\"row2\" nowrap=\"nowrap\" align=\"center\">
                      <span class=\"genmed\">
                          $time
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