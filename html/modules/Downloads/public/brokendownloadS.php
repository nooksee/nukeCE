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

/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

if(!defined('IN_DOWNLOADS')) {
  exit('Access Denied');
}

global $cookie;
$pagetitle = _REPORTBROKEN;
$lid = intval($lid);
if(is_user()) {
  $ratinguser = $cookie[1];
} else {
  $ratinguser = $anonymous;
}
$sub_ip = $_SERVER['REMOTE_ADDR'];
$db->sql_query("INSERT INTO ".$prefix."_downloads_mods VALUES (NULL, $lid, 0, 0, '', '', '', '$ratinguser', '$sub_ip', 1, '', '', '', '', '')");
include_once(NUKE_BASE_DIR.'header.php');
menu(1);
DisplayError(_THANKSFORINFO." "._LOOKTOREQUEST, 1);

?>