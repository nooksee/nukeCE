<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if(!isset($sid)) {
    exit();
}

function PrintPage($sid) {
    global $site_logo, $nukeurl, $module_name, $sitename, $datetime, $prefix, $db;
    $sid = intval($sid);
    $num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_stories WHERE sid='$sid'"));
    if ($num == 0) {
        Header("Location: modules.php?name=$module_name");
        die();
    }
    $sid = intval(trim($sid));
    $row = $db->sql_fetchrow($db->sql_query("SELECT title, time, hometext, bodytext, topic, notes FROM ".$prefix."_stories WHERE sid='$sid'"));
    $title = stripslashes($row['title']);
    $time = $row['time'];
/*****[BEGIN]******************************************
[ Mod:     News BBCodes                       v1.1.0 ]
******************************************************/
    $hometext = BBCode2Html(stripslashes($row["hometext"]));
    $bodytext = BBCode2Html(stripslashes($row["bodytext"]));
    $hometext = nuke_img_tag_to_resize($hometext);
    $bodytext = nuke_img_tag_to_resize($bodytext);
/*****[END]********************************************
[ Mod:     News BBCodes                       v1.1.0 ]
******************************************************/
    $topic = intval($row['topic']);
    $notes = stripslashes($row['notes']);
    $row2 = $db->sql_fetchrow($db->sql_query("SELECT topictext FROM ".$prefix."_topics WHERE topicid='$topic'"));
    $topictext = stripslashes($row2['topictext']);
    formatTimestamp($time);
    
    $pagetitle = $title;
    header("Content-Type: text/html");
    echo "
          <html>
              <head>
                  <title>$sitename &raquo; $title</title>
                  <meta name=\"HandheldFriendly\" content=\"True\">
         ";
    include_once("includes/javascript.min.php");
    echo "
              </head>
              <body bgcolor=\"#ffffff\" text=\"#000000\">
                  <table border=\"0\" align=\"center\">
                      <tr>
                          <td>
                              <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\">
                                  <tr>
                                      <td>
                                          <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#ffffff\">
                                              <tr>
                                                  <td>
                                                      <center>
                                                          <img src=\"images/$site_logo\" border=\"0\" alt=\"\"><br /><br />
                                                          <span class=\"content\"><strong>$title</strong></span><br />
                                                          <span class=tiny>
                                                            <strong>"._PDATE."</strong> $datetime<br />
                                                            <strong>"._PTOPIC."</strong> $topictext<br /><br />
                                                          </span>
                                                      </center>
                                                      <div style=\"max-width:640px;\" class=\"content\">
                                                          $hometext
                                                          $bodytext
                                                          $notes
                                                      </div>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                              </table>
                              <div style=\"height: 12px; line-height: 12px;\">&nbsp;</div>
                              <center>
                                  <span class=\"content\">
                                      "._NEWSCOMESFROM." $sitename<br />
                                      <a href=\"$nukeurl\">$nukeurl</a><br /><br />
                                      "._THEAURL."<br />
                                      <a href=\"$nukeurl/modules.php?name=News&amp;file=article&amp;sid=$sid\">$nukeurl/modules.php?name=News&amp;file=article&amp;sid=$sid</a>
                                  </span>
                              </center>
                          </td>
                      </tr>
                  </table>
              </body>
          </html>
         ";
}

PrintPage($sid);

?>