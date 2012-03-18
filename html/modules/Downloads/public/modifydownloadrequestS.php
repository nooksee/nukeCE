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
$pagetitle = _REQUESTDOWNLOADMOD;
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

include_once(NUKE_BASE_DIR.'header.php');
menu(1);
if ($dl_config['blockunregmodify'] == 1 && !is_user()) {
    DisplayError(_DONLYREGUSERSMODIFY, 1);
    exit;
} else {
    $title = Fix_Quotes($title);
    $url = Fix_Quotes($url);
    $description = Fix_Quotes($description);
    $sub_ip = $_SERVER['REMOTE_ADDR'];
    $db->sql_query("INSERT INTO ".$prefix."_downloads_mods VALUES (NULL, $lid, $cat, 0, '$title', '$url', '$description', '$ratinguser', '$sub_ip', 0, '$auth_name', '$email', '$filesize', '$version', '$homepage')");
    DisplayError(_THANKSFORINFO." "._LOOKTOREQUEST, 1);
    exit;
}

?>