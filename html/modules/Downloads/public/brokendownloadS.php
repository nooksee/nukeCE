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
if(is_user()) {
  $ratinguser = $cookie[1];
} else {
  $ratinguser = $anonymous;
}
$sub_ip = $_SERVER['REMOTE_ADDR'];
$db->sql_query("INSERT INTO ".$prefix."_downloads_mods VALUES (NULL, $lid, 0, 0, '', '', '', '$ratinguser', '$sub_ip', 1, '', '', '', '', '')");
$pagetitle = "- "._REPORTBROKEN;
include_once(NUKE_BASE_DIR.'header.php');
menu(1);
echo "<br />\n";
OpenTable();
echo "<br /><center>"._THANKSFORINFO."<br /><br />"._LOOKTOREQUEST."</center><br />\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>