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

global $sitename, $slogan, $db, $prefix, $module_name, $site_logo, $Default_Theme;
if (file_exists("themes/$Default_Theme/images/logo.gif")) {
    $avantgo_logo = "themes/$Default_Theme/images/logo.gif";
} elseif (file_exists("images/$site_logo")) {
    $avantgo_logo = "images/$site_logo";
} elseif (file_exists("images/logo.gif")) {
    $avantgo_logo = "images/logo.gif";
} else {
    $avantgo_logo = "";
}
    
if (file_exists("themes/$Default_Theme/style/style.css")) {
    $style = "themes/$Default_Theme/style/style.css";
} else {
    $style = "";
}

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function truncate($string, $limit, $break=" ", $pad="...") {
    // return with no change if string is shorter than $limit
    if(strlen($string) <= $limit) return $string; $string = substr($string, 0, $limit);
    if(false !== ($breakpoint = strrpos($string, $break))) {
        $string = substr($string, 0, $breakpoint);
    }
    return $string . $pad;
}

$pagetitle = _MOBILE;
header("Content-Type: text/html");
echo "
      <html>
          <head>
              <title>$sitename &raquo; $pagetitle</title>
              <meta name=\"HandheldFriendly\" content=\"True\">
              <style>@import url($style);</style>
              <script type=\"text/javascript\" src=\"includes/js/jquery.js\"></script>
              <script type=\"text/javascript\">
                  $(function() {
                      $('tr.parent')
                      .css(\"cursor\",\"pointer\")
                      .attr(\"title\",\"Click to expand/collapse\")
                      .click(function(){
                          $(this).siblings('.child-'+this.id).toggle();
                      });
                      $('tr[@class^=child-]').hide().children('td');
                  });
              </script>
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
     ";
        $result = $db->sql_query("SELECT sid, title, time FROM ".$prefix."_stories ORDER BY sid DESC LIMIT 10");
        if (!result) {
            echo "An error occured";
        } else {
            echo "
                  <center>
                      <a href=\"index.php\"><img src=\"$avantgo_logo\" alt=\"$slogan\" title=\"$slogan\" border=\"0\"></a><br />
                      <h1>$sitename</h1>
                  </center>
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
                          <td class=\"row1\" onmouseover=\"this.className='row3';\" onmouseout=\"this.className='row1';\"  onclick=\"window.location.href='modules.php?name=$module_name&amp;file=print&amp;sid=$sid'\" width=\"72%\" align=\"left\"><a href=\"modules.php?name=$module_name&amp;file=print&amp;sid=$sid\">".truncate($title, 37)."</a></td>
                          <td class=\"row2\" width=\"28%\" align=\"center\" nowrap=\"nowrap\">$datetime</td>
                      </tr>
                     ";
            }
            echo "</table>";
        }
        echo "<div style=\"height: 20px; line-height: 20px;\">&nbsp;</div>";
        $result69 = $db->sql_query("SELECT pic_id, pic_title, pic_user_id, pic_time FROM ".$prefix."_bbalbum WHERE ( pic_approval = 1 ) ORDER BY pic_id DESC LIMIT 10");
        if (!result69) {
            echo "An error occured";
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
                          <td class=\"row1\" onmouseover=\"this.className='row3';\" onmouseout=\"this.className='row1';\"  onclick=\"window.location.href='modules.php?name=$module_name&amp;file=display&amp;pic_id=$pic_id'\" width=\"72%\" align=\"left\" nowrap=\"nowrap\"><a href=\"modules.php?name=$module_name&amp;file=display&amp;pic_id=$pic_id\">".truncate($pic_title, 37)."</a></td>
                          <td class=\"row2\" width=\"28%\" align=\"center\" nowrap=\"nowrap\">$datetime</td>
                      </tr>
                     ";
            }
            echo "
                                      </td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                  </table>
                 ";
        }
        echo "
                              </td>
                          </tr>
                      </table>
                  </body>
              </html>
             ";
include(NUKE_INCLUDE_DIR."counter.php");
exit;

?>