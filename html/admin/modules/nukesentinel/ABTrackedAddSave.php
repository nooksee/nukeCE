<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(($xip[0] < 0 OR $xip[0] > 255 OR (!is_numeric($xip[0]) AND $xip[0] != "*")) OR ($xip[1] < 0 OR $xip[1] > 255 OR (!is_numeric($xip[1]) AND $xip[1] != "*")) OR ($xip[2] < 0 OR $xip[2] > 255 OR (!is_numeric($xip[2]) AND $xip[2] != "*")) OR ($xip[3] < 0 OR $xip[3] > 255 OR (!is_numeric($xip[3]) AND $xip[3] != "*"))) {
    DisplayErrorReturn(_AB_IPERROR);
    die();
}
$tidinfo['ip_addr'] = implode(".", $xip);
$bantemp = str_replace("*", "0", $tidinfo['ip_addr']);
$tidinfo['ip_long'] = sprintf("%u", ip2long($bantemp));
if($tidinfo['expires']>0) { $tidinfo['expires'] = ($tidinfo['expires'] * 86400) + time(); }
$tidinfo['user_id'] = intval($tidinfo['user_id']);
$tidinfo['username'] = stripslashes($tidinfo['username']);
$tidinfo['user_agent'] = htmlentities($tidinfo['user_agent'], ENT_QUOTES);
$tidinfo['notes'] = str_replace("<br />", "\r\n", $tidinfo['notes']);
$tidinfo['notes'] = str_replace("<br />", "\r\n", $tidinfo['notes']);
$tidinfo['notes'] = htmlentities($tidinfo['notes'], ENT_QUOTES);
if(!get_magic_quotes_runtime()) {
    $tidinfo['notes'] = addslashes($tidinfo['notes']);
    $tidinfo['username'] = addslashes($tidinfo['username']);
}
$tidinfo['query_string'] = str_replace("http://", "", $nukeurl).$tidinfo['query_string'];
$tidinfo['query_string'] = base64_encode($tidinfo['query_string']);
$ip = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` WHERE `ip_addr`='".$tidinfo['ip_addr']."'"));
if($ip < 1) {
    $db->sql_query("INSERT INTO `".$prefix."_nsnst_blocked_ips` VALUES ('".$tidinfo['ip_addr']."', '".$tidinfo['ip_long']."', '".$tidinfo['user_id']."', '".$tidinfo['username']."', '".$tidinfo['user_agent']."', '".$tidinfo['date']."', '".$tidinfo['notes']."', '".$tidinfo['reason']."', '".$tidinfo['query_string']."', '".$tidinfo['query_string']."', '".$tidinfo['query_string']."', '".$tidinfo['x_forward_for']."', '".$tidinfo['client_ip']."', '".$tidinfo['remote_addr']."', '".$tidinfo['remote_port']."', '".$tidinfo['request_method']."', '".$tidinfo['expires']."', '".$tidinfo['c2c']."')");
    if($ab_config['htaccess_path'] != "") {
        $i = 1;
        while($i <= 3) {
            $tip = substr($tidinfo['ip_addr'], -2);
            if($tip == ".*") { $tidinfo['ip_addr'] = substr($tidinfo['ip_addr'], 0, -2); }
            $i++;
        }
        $tempip = "";
        if($tidinfo['ip_addr'] != "*") { $tempip = "deny from ".$tidinfo['ip_addr']."\n"; }
        $doit = fopen($ab_config['htaccess_path'], "a");
        fwrite($doit, $tempip);
        fclose($doit);
    }
}
Header("Location: ".$admin_file.".php?op=ABBlockedIP");

?>