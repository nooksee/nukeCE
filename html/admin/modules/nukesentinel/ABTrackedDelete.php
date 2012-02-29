<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$tid = intval($tid);
if(ereg("All.*Modules", $showmodule) || !$showmodule ) {
    $modfilter="";
} elseif(ereg("Admin", $showmodule)) {
    $modfilter="AND page LIKE '%".$admin_file.".php%'";
} elseif(ereg("Index", $showmodule)) {
    $modfilter="AND page LIKE '%index.php%'";
} elseif(ereg("Backend", $showmodule)) {
    $modfilter="AND page LIKE '%backend.php%'";
} else {
    $modfilter="AND page LIKE '%name=$showmodule%'";
}
$deleterow = $db->sql_fetchrow($db->sql_query("SELECT `user_id`, `ip_addr` FROM `".$prefix."_nsnst_tracked_ips` WHERE `tid`='$tid'"));
$db->sql_query("DELETE FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_id`='".$deleterow['user_id']."' AND `ip_addr`='".$deleterow['ip_addr']."' $modfilter");
$db->sql_query("OPTIMIZE TABLE `".$prefix."_nsnst_tracked_ips`");
Header("Location: ".$admin_file.".php?op=$xop&min=$min&column=$column&direction=$direction&showmodule=$showmodule&sip=$sip");

?>