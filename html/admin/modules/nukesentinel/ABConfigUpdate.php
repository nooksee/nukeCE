<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

global $cache;
$xblocker_row['list'] = str_replace($xblocker_row['listdelete'], "", $xblocker_row['list']);
$xblocker_row['list'] = str_replace("\r\n\r\n", "\r\n", $xblocker_row['list']);
$block_list = explode("\r\n", $xblocker_row['list']);
rsort($block_list);
$endlist = count($block_list)-1;
if(empty($block_list[$endlist])) { array_pop($block_list); }
sort($block_list);
$xblocker_row['list'] = implode("\r\n", $block_list);
$db->sql_query("UPDATE `".$prefix."_nsnst_blockers` SET `list`='".$xblocker_row['list']."' WHERE `block_name`='".$xblocker_row['block_name']."'");
$cache->delete('', 'sentinel');
$cache->resync();
Header("Location: ".$admin_file.".php?op=$xop");

?>