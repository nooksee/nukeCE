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

global $cookie;
$lid = intval($lid);
$cat = intval($cat);
$filesize = str_replace(',', '', $filesize);
$filesize = str_replace('.', '', $filesize);
$filesize = intval($filesize);
if(is_user()) {
  $ratinguser = $cookie[1];
} else {
  $ratinguser = $anonymous;
}
if ($dl_config['blockunregmodify'] == 1 && !is_user()) {
  include_once(NUKE_BASE_DIR.'header.php');
  menu(1);
  echo "<br />\n";
  OpenTable();
  echo "<center><span class='content'>"._DONLYREGUSERSMODIFY."</span></center>\n";
  CloseTable();
  include_once(NUKE_BASE_DIR.'footer.php');
} else {
  $title = Fix_Quotes($title);
  $url = Fix_Quotes($url);
  $description = Fix_Quotes($description);
  $sub_ip = $_SERVER['REMOTE_ADDR'];
  $db->sql_query("INSERT INTO ".$prefix."_downloads_mods VALUES (NULL, $lid, $cat, 0, '$title', '$url', '$description', '$ratinguser', '$sub_ip', 0, '$auth_name', '$email', '$filesize', '$version', '$homepage')");
  include_once(NUKE_BASE_DIR.'header.php');
  menu(1);
  echo "<br />\n";
  OpenTable();
  echo "<center><span class='content'>"._THANKSFORINFO." "._LOOKTOREQUEST."</span></center>\n";
  CloseTable();
  include_once(NUKE_BASE_DIR.'footer.php');
}

?>