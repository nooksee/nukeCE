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

// Last 10 Forum Topics
$result8 = $db->sql_query("SELECT t.topic_id, t.topic_title, f.forum_name, t.forum_id FROM ".$prefix."_bbtopics t, ".$prefix."_bbforums f WHERE t.forum_id=f.forum_id AND t.topic_poster='$usrinfo[user_id]' AND auth_view<'2' AND auth_read<'2' AND auth_post<'2' order by t.topic_time DESC LIMIT 0,10");
if (($db->sql_numrows($result8) > 0)) {
    $usrcolor = UsernameColor($usrinfo['username']);
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"3\" height=\"28\">
                      <span class=\"cattitle\">
                          ".$usrcolor."'s "._LAST10BBTOPIC."
                      </span>
                  </td>
              </tr>
              <tr>
                  <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      &nbsp;"._FORUM."&nbsp;
                  </th>
                  <th align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                      &nbsp;"._TOPIC."&nbsp;
                  </th>
              </tr>
              <tr>
         ";
    while(list($topic_id, $topic_title, $forum_name, $forum_id) = $db->sql_fetchrow($result8)) {
        echo "
                  <td class=\"row1\" width=\"50%\">
                      <span class=\"topictitle\">
                          <a href=\"modules.php?name=Forums&amp;file=viewforum&amp;f=$forum_id\">
                              $forum_name
                          </a>
                      </span>
                      <span class=\"gensmall\">
                      <br />
                      </span>
                  </td>
                  <td valign=\"middle\" class=\"row2\" nowrap=\"nowrap\" align=\"left\">
                      <span class=\"genmed\">
                          <a href=\"modules.php?name=Forums&amp;file=viewtopic&amp;t=$topic_id\">
                              $topic_title
                          </a>
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

// Last 10 Forum Posts
$result12 = $db->sql_query("SELECT p.post_id, r.post_subject, f.forum_name, p.forum_id FROM ".$prefix."_bbposts p, ".$prefix."_bbposts_text r, ".$prefix."_bbforums f WHERE p.forum_id=f.forum_id AND r.post_id=p.post_id AND p.poster_id='$usrinfo[user_id]' AND auth_view<'2' AND auth_read<'2' AND auth_post<'2' order by p.post_time DESC LIMIT 0,10");
if (($db->sql_numrows($result12) > 0)) {
    $usrcolor = UsernameColor($usrinfo['username']);
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"3\" height=\"28\">
                      <span class=\"cattitle\">
                          ".$usrcolor."'s "._LAST10BBPOST."
                      </span>
                  </td>
              </tr>
              <tr>
                  <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      &nbsp;"._FORUM."&nbsp;
                  </th>
                  <th align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                      &nbsp;"._SUBJECT."&nbsp;
                  </th>
              </tr>
              <tr>
         ";
    while(list($post_id, $post_subject, $forum_name, $forum_id) = $db->sql_fetchrow($result12)) {
        if(empty($post_subject)) { $post_subject = _NOPOSTSUBJECT; }
        echo "
                  <td class=\"row1\" width=\"50%\">
                      <span class=\"topictitle\">
                          <a href=\"modules.php?name=Forums&amp;file=viewforum&amp;f=$forum_id\">
                              $forum_name
                          </a>
                      </span>
                      <span class=\"gensmall\">
                      <br />
                      </span>
                  </td>
                  <td valign=\"middle\" class=\"row2\" nowrap=\"nowrap\" align=\"left\">
                      <span class=\"genmed\">
                          <a href=\"modules.php?name=Forums&amp;file=viewtopic&amp;p=$post_id#$post_id\">
                              $post_subject
                          </a>
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