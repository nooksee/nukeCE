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

require_once("mainfile.php");
require_once(NUKE_CLASSES_DIR.'class.bbcode.php');

$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$theme_Sel = get_theme();

function avtgo() {
    global $sitename, $theme_Sel, $slogan, $db, $prefix, $module_name, $site_logo, $datetime, $adminmail;
    $pagetitle = _MOBILE;
    header("Content-Type: text/html");
    echo "
          <html>
              <head>
                  <title>$sitename &raquo; $pagetitle</title>
                  <meta name=\"HandheldFriendly\" content=\"True\">
                  <style>@import url(themes/$theme_Sel/style/style.css);</style>
         ";
    include_once("includes/javascript.min.php");
    echo "
          </head>
              <body>
                  <table border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\">
                      <tr>
                          <td>
                              <table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
                                  <tr>
                                      <td>
                                          <table border=\"0\" align=\"center\" cellpadding=\"20\" cellspacing=\"1\">
                                              <tr>
                                                  <td>
                                                      <center>
                                                          <a href=\"index.php\"><img src=\"images/$site_logo\" alt=\"$slogan\" title=\"$slogan\" border=\"0\"></a><br />
                                                          <h1>$sitename</h1>
                                                      </center>
         ";
    $result = $db->sql_query("SELECT sid, title, time FROM ".$prefix."_stories ORDER BY sid DESC LIMIT 10");
    if (($db->sql_numrows($result) > 0)) {
        if (!result) {
            echo "
                  <div align=\"center\">
                      <span class=\"option\"><b><em>"._DBERROR."</em></b></span>        
                  </div>
                 ";
        } else {
            echo "
                  <table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                      <tr title=\"Click to expand/collapse\" style=\"cursor: pointer;\" class=\"parent\" id=\"row123\">
                          <td width=\"100%\" class=\"catHead\" colspan=\"2\" height=\"28\" align=\"center\">
                              <span class=\"cattitle\">"._LATEST_NEWS."</span>
                          </td>
                      </tr>
                      <tr style=\"display: none;\" class=\"child-row123\">
                          <th width=\"72%\" colspan=\"1\" align=\"left\" class=\"thCornerL\">"._ATITLE."</th>
                          <th width=\"28%\" colspan=\"1\" align=\"center\" class=\"thCornerR\">"._DATE."</th>
                      </tr>
                 ";
            for ($m=0; $m < $db->sql_numrows($result); $m++) {
                $row = $db->sql_fetchrow($result);
                $sid = intval($row['sid']);
                $title = stripslashes(check_html($row['title'], "nohtml"));
                $time = $row['time'];
                formatTimestamp($time, 'M d, Y');
                echo "
                      <tr style=\"display: none;\" class=\"child-row123\">
                          <td class=\"row1\" height=\"23\" width=\"72%\" onmouseover=\"this.className='row3';\" onmouseout=\"this.className='row1';\" onclick=\"window.location.href='modules.php?name=$module_name&amp;op=PrintPage&amp;sid=$sid'\" align=\"left\"><a href=\"modules.php?name=$module_name&amp;op=PrintPage&amp;sid=$sid\">".truncate($title, 35)."</a></td>
                          <td class=\"row2\" height=\"23\" width=\"28%\" align=\"center\" nowrap=\"nowrap\">$datetime</td>
                      </tr>
                     ";
            }
            echo "
                  </table>
                  <div style=\"height: 12px; line-height: 12px;\">&nbsp;</div>
                 ";
        }
    }
    $result69 = $db->sql_query("SELECT pic_id, pic_title, pic_user_id, pic_time FROM ".$prefix."_bbalbum WHERE ( pic_approval = 1 ) ORDER BY pic_id DESC LIMIT 10");
    if (($db->sql_numrows($result69) > 0)) {
        if (!result69) {
            echo "
                  <div align=\"center\">
                      <span class=\"option\"><b><em>"._DBERROR."</em></b></span>        
                  </div>
                 ";
        } else {
            echo "
                  <table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                      <tr title=\"Click to expand/collapse\" style=\"cursor: pointer;\" class=\"parent\" id=\"row456\">
                          <td width=\"100%\" class=\"catHead\" colspan=\"2\" height=\"28\" align=\"center\">
                              <span class=\"cattitle\">"._LATEST_PICS."</span>
                          </td>
                      </tr>
                      <tr style=\"display: none;\" class=\"child-row456\">
                          <th width=\"72%\" colspan=\"1\" align=\"left\" class=\"thCornerL\">"._PTITLE."</th>
                          <th width=\"28%\" colspan=\"1\" align=\"center\" class=\"thCornerR\">"._POSTED."</th>
                      </tr>
                 ";
            for ($m=0; $m < $db->sql_numrows($result69); $m++) {
                $row = $db->sql_fetchrow($result69);
                $pic_id = intval($row['pic_id']);
                $pic_title = stripslashes(check_html($row['pic_title'], "nohtml"));
                $pic_time = $row['pic_time'];
                formatTimestamp($pic_time, 'M d, Y');
                echo "
                      <tr style=\"display: none;\" class=\"child-row456\">
                          <td class=\"row1\" height=\"23\" width=\"72%\" onmouseover=\"this.className='row3';\" onmouseout=\"this.className='row1';\" onclick=\"window.location.href='modules.php?name=$module_name&amp;op=DisplayPic&amp;pic_id=$pic_id'\" align=\"left\" nowrap=\"nowrap\"><a href=\"modules.php?name=$module_name&amp;op=DisplayPic&amp;pic_id=$pic_id\">".truncate($pic_title, 35)."</a></td>
                          <td class=\"row2\" height=\"23\" width=\"28%\" align=\"center\" nowrap=\"nowrap\">$datetime</td>
                      </tr>
                     ";
            }
            echo "
                  </table>
                  <div style=\"height: 12px; line-height: 12px;\">&nbsp;</div>
                 ";
        }
    }
    echo "
                                                          <div style=\"height: 12px; line-height: 12px;\">&nbsp;</div>
                                                          <center>
                                                              <span class=\"content\">"._EMAIL.": <a href='mailto:".$adminmail."'>".$adminmail."</a></span>
                                                          </center>
                                                      </div>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                  </table>
              </body>
          </html>
         ";
    include(NUKE_INCLUDE_DIR."counter.php");
    die();
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
    
    $pagetitle = _MOBILE;
    header("Content-Type: text/html");
    echo "
          <html>
              <head>
                  <title>$sitename &raquo; $pagetitle &raquo; "._ARTICLES." &raquo; $title</title>
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

function DisplayPic($pic_id) {
    global $site_logo, $nukeurl, $module_name, $sitename, $datetime, $prefix, $db;
    $pic_id = intval($pic_id);
    $num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_bbalbum WHERE pic_id='$pic_id'"));
    if ($num == 0) {
        Header("Location: modules.php?name=$module_name");
        die();
    }
    $pic_id = intval(trim($pic_id));
    $row = $db->sql_fetchrow($db->sql_query("SELECT pic_id, pic_title, pic_desc, pic_time, pic_cat_id FROM ".$prefix."_bbalbum WHERE pic_id='$pic_id'"));
    $pic_id = intval($row['pic_id']);
    $pic_title = stripslashes($row['pic_title']);
    $pic_time = $row['pic_time'];
/*****[BEGIN]******************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
    $pic_desc = decode_bbcode(stripslashes($row["pic_desc"]), 1, true);
    $pic_desc = BBCode2Html(stripslashes($row["pic_desc"]));
/*****[END]********************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
    $pic_cat_id = intval($row['pic_cat_id']);
    $row2 = $db->sql_fetchrow($db->sql_query("SELECT cat_title FROM ".$prefix."_bbalbum_cat WHERE cat_id='$pic_cat_id'"));
    $cat_title = stripslashes($row2['cat_title']);
    formatTimestamp($pic_time);
    
    $pagetitle = _MOBILE;
    header("Content-Type: text/html");
    echo "
          <html>
              <head>
                  <title>$sitename &raquo; $pagetitle &raquo; "._PICS." &raquo; $pic_title</title>
                  <meta name=\"HandheldFriendly\" content=\"True\">
         ";
    include_once("includes/javascript.min.php");
    echo "
              </head>
              <body bgcolor=\"#ffffff\" text=\"#000000\">
                  <table border=\"0\" align=\"center\">
                      <tr>
                          <td>
                              <table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\">
                                  <tr>
                                      <td>
                                          <table border=\"0\" align=\"center\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#ffffff\">
                                              <tr>
                                                  <td>
                                                      <center>
                                                          <img src=\"images/$site_logo\" border=\"0\" alt=\"\"><br /><br />
                                                          <span class=\"content\"><strong>".truncate($pic_title, 30)."</strong></span><br />
                                                          <span class=tiny>
                                                              <strong>"._PPOSTED."</strong> $datetime<br />
                                                              <strong>"._PALBUM."</strong> $cat_title</span><br /><br />
                                                          <div style=\"max-width:620px;\" class=\"content\"><a href=\"modules.php?name=Forums&file=album_pic&amp;pic_id=$pic_id\"><img src=\"modules.php?name=Forums&amp;file=album_thumbnail&amp;pic_id=$pic_id\" border=\"0\" alt=\"".$pic_title."\" title=\"".$pic_title."\" vspace=\"5\" /></a></div><br />
                                                      </center>
                                                      <div style=\"max-width:520px;\" class=\"content\">
                                                          $pic_desc<br /><br />
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
                                      "._PICCOMESFROM." $sitename<br />
                                      <a href=\"$nukeurl\">$nukeurl</a><br /><br />
                                      "._THEPURL."<br />
                                      <a href=\"$nukeurl/modules.php?name=Forums&amp;file=album_page&amp;pic_id=$pic_id\">$nukeurl/modules.php?name=Forums&amp;file=album_page&amp;pic_id=$pic_id</a>
                                  </span>
                              </center>
                          </td>
                      </tr>
                  </table>
              </body>
          </html>
         ";
}

switch($op) {
    case "PrintPage":
    PrintPage($sid);
    break;

    case "DisplayPic":
    DisplayPic($pic_id);
    break;

    default:
    avtgo();
    break;
}

?>