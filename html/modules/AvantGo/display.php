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

if(!isset($pic_id)) {
    exit();
}

function DisplayPic($pic_id) {
    global $site_logo, $nukeurl, $sitename, $datetime, $prefix, $db, $Default_Theme;
    if (file_exists("themes/$Default_Theme/images/logo.gif")) {
        $avantgo_logo = "themes/$Default_Theme/images/logo.gif";
    } elseif (file_exists("images/$site_logo")) {
        $avantgo_logo = "images/$site_logo";
    } elseif (file_exists("images/logo.gif")) {
        $avantgo_logo = "images/logo.gif";
    } else {
        $avantgo_logo = "";
    }
    
    $row = $db->sql_fetchrow($db->sql_query("SELECT pic_id, pic_title, pic_time, pic_cat_id FROM ".$prefix."_bbalbum WHERE pic_id='$pic_id'"));
    $pic_id = intval($row['pic_id']);
    $pic_title = stripslashes($row['pic_title']);
    $pic_time = $row['pic_time'];
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
                                                          <img src=\"$avantgo_logo\" border=\"0\" alt=\"\"><br /><br />
                                                          <span class=\"content\"><strong>$pic_title</strong></span><br />
                                                          <span class=tiny>
                                                              <strong>"._PPOSTED."</strong> $datetime<br />
                                                              <strong>"._PALBUM."</strong> $cat_title</span><br /><br />
                                                          <span class=\"content\"><a href=\"modules.php?name=Forums&file=album_pic&amp;pic_id=$pic_id\"><img src=\"modules.php?name=Forums&amp;file=album_thumbnail&amp;pic_id=$pic_id\" border=\"0\" alt=\"".$pic_title."\" title=\"".$pic_title."\" vspace=\"10\" /></a></span>
                                                      </center>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                              </table>
                              <div style=\"height: 20px; line-height: 20px;\">&nbsp;</div>
                              <center>
                                  <span class=\"content\">
                                      "._PICCOMESFROM." $sitename<br />
                                      <a href=\"$nukeurl\">$nukeurl</a><br /><br />
                                      "._THEPURL."<br />
                                      <a href=\"$nukeurl/modules.php?name=Forums&amp;file=album_page&amp;pic_id=$pic_id\">".substr("$nukeurl/modules.php?name=Forums&amp;file=album_page&amp;pic_id=$pic_id", 0, 40)."..."."</a>
                                  </span>
                              </center>
                          </td>
                      </tr>
                  </table>
              </body>
          </html>
         ";
}

DisplayPic($pic_id);

?>