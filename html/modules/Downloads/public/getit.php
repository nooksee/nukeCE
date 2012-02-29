<?php

/**************************************************************************/
/* PHP-EVOLVED: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.php-evolved.com                                             */
/*                                                                        */
/* All PHP-EVOLVED code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if(!defined('IN_DOWNLOADS')) {
  exit('Access Denied');
}

$lid = intval($lid);
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE lid=$lid AND active>'0'");
$lidinfo = $db->sql_fetchrow($result);
$pagetitle = "- "._DOWNLOADPROFILE.": ".stripslashes($lidinfo['title']);
include_once(NUKE_BASE_DIR.'header.php');
$priv = $lidinfo['sid'] - 2;
if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv)) || $dl_config['show_download'] == '1') {
  if (empty($lidinfo['lid']) OR $lidinfo['active'] == 0) {
    title(_DOWNLOADPROFILE.": "._INVALIDDOWNLOAD);
    OpenTable();
    echo "<center><strong>"._INVALIDDOWNLOAD."</strong></center>\n";
  } else {
    $fetchid = base64_encode($lidinfo['url']);
    $title = stripslashes($lidinfo['title']);
    title(_DOWNLOADPROFILE.": $title");
    OpenTable();
    mt_srand ((double)microtime()*1000000);
    $maxran = 1000000;
    $random_num = mt_rand(0, $maxran);
    $lidinfo['description'] = stripslashes($lidinfo['description']);
    $lidinfo['description'] = str_replace ("\r\n", "<br />", $lidinfo['description']);
    if (is_mod_admin($module_name)) {
      $myimage = myimage("edit.png");
      echo "<a href='".$admin_file.".php?op=DownloadModify&amp;lid=$lid' target='$lid'><img align='middle' src='$myimage' border='0' alt='"._DL_EDIT."'></a>&nbsp;";
    } else {
      $myimage = myimage("show.png");
      echo "<img align='middle' src='$myimage' border='0' alt=''>&nbsp;";
    }
    echo "<span class='title'>"._DOWNLOADPROFILE.": <strong>$title</strong></span><br /><hr />";
    echo "".$lidinfo['description']."<br /><hr />";
    echo "<strong>"._VERSION.":</strong> ".$lidinfo['version']."<br />\n";
    echo "<strong>"._FILESIZE.":</strong> ".CoolSize($lidinfo['filesize'])."<br />";
    echo "<strong>"._ADDEDON.":</strong> ".CoolDate($lidinfo['date'])."<br />\n";
    echo "<strong>"._DOWNLOADS.":</strong> ".$lidinfo['hits']."<br />";
    echo "<strong>"._HOMEPAGE.":</strong> ";
    if (empty($lidinfo['homepage']) || $lidinfo['homepage'] == "http://") {
      echo _DL_NOTLIST;
    } else {
      echo "<a href='".$lidinfo['homepage']."' target='new'>".$lidinfo['homepage']."</a>";
    }
    echo "<hr />";
    if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv))) {
      echo _DL_DIRECTIONS." "._DL_DLNOTES1."$title"._DL_DLNOTES2."</span><br /><br />";
      echo "<center><table border='0'>";
      echo "<form action='modules.php?name=$module_name' method='POST'>";
      echo "<input type='hidden' name='op' value='go'>";
      echo "<input type='hidden' name='lid' value='".$lidinfo['lid']."'>";
      echo "<input type='hidden' name='fetchid' value='$fetchid'>";
      if ($dl_config['usegfxcheck'] == 1) {
        echo security_code(1,'normal', 1);
      }
      echo "<tr><td colspan='2' align='center'><input type='submit' name='"._DL_GOGET."' value='"._DL_GOGET."'></td></tr>";
      echo "</form>";
      echo "</table></center><br />";
      if(is_mod_admin($module_name)) {
          echo "<center><span class='content'>[ <a href='" . $admin_file . ".php?op=DownloadModify&amp;lid=$lid'>"._MODIFY."</a> ]</span></center>\n";
      } else {
          echo "<center><span class='content'>[ <a href='modules.php?name=$module_name&amp;op=modifydownloadrequest&amp;lid=$lid'>"._MODIFY."</a> ]</span></center>\n";
      }
    } else {
      restricted($lidinfo['sid']);
    }
  }
  CloseTable();
} else {
  OpenTable();
  restricted($lidinfo['sid']);
  CloseTable();
}
include_once(NUKE_BASE_DIR.'footer.php');

?>