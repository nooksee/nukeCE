<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$tid = intval($tid);
$deleterow = $db->sql_fetchrow($db->sql_query("SELECT `refered_from` FROM `".$prefix."_nsnst_tracked_ips` WHERE `tid`='$tid'"));
$xblocker_row = abget_blocker("referer");
$xblocker_row['list'] = str_replace($xblocker_row['listdelete'], "", $xblocker_row['list']);
$xblocker_row['list'] = str_replace("\r\n\r\n", "\r\n", $xblocker_row['list']);
$newrow = parse_url($deleterow['refered_from']);
$xblocker_row['list'] .= "\r\n".$newrow['host'];
$block_list = explode("\r\n", $xblocker_row['list']);
rsort($block_list);
$endlist = count($block_list)-1;
if(empty($block_list[$endlist])) { array_pop($block_list); }
sort($block_list);
$xblocker_row['list'] = implode("\r\n", $block_list);
$db->sql_query("UPDATE `".$prefix."_nsnst_blockers` SET `list`='".$xblocker_row['list']."' WHERE `block_name`='".$xblocker_row['block_name']."'");
Header("Location: ".$admin_file.".php?op=ABTrackedRefers&min=$min&column=$column&direction=$direction");

?>