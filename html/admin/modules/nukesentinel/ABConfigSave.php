<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(!empty($xblocker_row['list'])) {
    if(!empty($xblocker_row['listadd'])) {
        $xblocker_row['list'] = $xblocker_row['list']."\r\n".$xblocker_row['listadd'];
    } else {
        $xblocker_row['list'] = $xblocker_row['list'];
    }
} else {
    $xblocker_row['list'] = $xblocker_row['listadd'];
}
$xblocker_row['list'] = str_replace("<br>", "\r\n", $xblocker_row['list']);
$xblocker_row['list'] = str_replace("<br />", "\r\n", $xblocker_row['list']);
$block_list = explode("\r\n", $xblocker_row['list']);
rsort($block_list);
$endlist = count($block_list)-1;
if(empty($block_list[$endlist])) { array_pop($block_list); }
sort($block_list);
$xblocker_row['list'] = implode("\r\n", $block_list);
$xblocker_row['list'] = str_replace("\r\n\r\n", "\r\n", $xblocker_row['list']);
$xblocker_row['duration'] = $xblocker_row['duration'] * 86400;
$db->sql_query("UPDATE `".$prefix."_nsnst_blockers` SET `activate`='".$xblocker_row['activate']."', `block_type`='".$xblocker_row['block_type']."', `email_lookup`='".$xblocker_row['email_lookup']."', `forward`='".$xblocker_row['forward']."', `reason`='".$xblocker_row['reason']."', `template`='".$xblocker_row['template']."', `duration`='".$xblocker_row['duration']."', `htaccess`='".$xblocker_row['htaccess']."', `list`='".$xblocker_row['list']."' WHERE `block_name`='".$xblocker_row['block_name']."'");
global $cache;
$cache->delete('blockers', 'sentinel');
$cache->resync();
Header("Location: ".$admin_file.".php?op=$xop");

?>