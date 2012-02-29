<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$tid = intval($tid);
$deleterow = $db->sql_fetchrow($db->sql_query("SELECT `user_agent` FROM `".$prefix."_nsnst_tracked_ips` WHERE `tid`='$tid'"));
$db->sql_query("DELETE FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_agent`='".$deleterow['user_agent']."'");
$db->sql_query("OPTIMIZE TABLE `".$prefix."_nsnst_tracked_ips`");
Header("Location: ".$admin_file.".php?op=ABTrackedAgents&min=$min&column=$column&direction=$direction");

?>