<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(($xip[0] < 0 OR $xip[0] > 255 OR (!is_numeric($xip[0]) AND $xip[0] != "*")) OR ($xip[1] < 0 OR $xip[1] > 255 OR (!is_numeric($xip[1]) AND $xip[1] != "*")) OR ($xip[2] < 0 OR $xip[2] > 255 OR (!is_numeric($xip[2]) AND $xip[2] != "*")) OR ($xip[3] < 0 OR $xip[3] > 255 OR (!is_numeric($xip[3]) AND $xip[3] != "*"))) {
    $pagetitle = _AB_SENTINEL.": "._AB_ERROR;
    DisplayErrorReturn(_AB_IPERROR);
    die();
}

$xIPs = implode(".", $xip);
$bantemp = str_replace("*", "0", $xIPs);
$xIPl = sprintf("%u", ip2long($bantemp));
if($xexpires>0) { $xexpires = ($xexpires * 86400) + time(); }
$xdate = strtotime($xdatetime);
$xuser_id = intval($xuser_id);
$xusername = stripslashes($xusername);
$xuser_agent = htmlentities($xuser_agent, ENT_QUOTES);
$xnotes = str_replace("<br />", "\r\n", $xnotes);
$xnotes = str_replace("<br />", "\r\n", $xnotes);
$xnotes = htmlentities($xnotes, ENT_QUOTES);

if(!get_magic_quotes_runtime()) {
    $xnotes = addslashes($xnotes);
    $xusername = addslashes($xusername);
}
$result = $db->sql_query("UPDATE `".$prefix."_nsnst_blocked_ips` SET `ip_addr`='$xIPs', `ip_long`='$xIPl', `user_id`='$xuser_id', `username`='$xusername', `user_agent`='$xuser_agent', `date`='$xdate', `notes`='$xnotes', `reason`='$xreason', `expires`='$xexpires', `c2c`='$xc2c' WHERE `ip_addr`='$old_xIPs'");
if(!$result) { die("DB Error"); }
$i = 1;
while($i <= 3) {
    $tip = substr($xIPs, -2);
    if($tip == ".*") { $xIPs = substr($xIPs, 0, -2); }
    $i++;
}
$i = 1;
while($i <= 3) {
    $tip = substr($old_xIPs, -2);
    if($tip == ".*") { $old_xIPs = substr($old_xIPs, 0, -2); }
    $i++;
}
$testip1 = "";
if($xIPs != "0" AND $xIPs != "*") { $testip1 = "deny from $xIPs\n"; }
$testip2 = "deny from $old_xIPs\n";
if($ab_config['htaccess_path'] != "") {
    $ipfile = file($ab_config['htaccess_path']);
    $ipfile = implode("", $ipfile);
    $ipfile = str_replace($testip2, "", $ipfile);
    $ipfile = $ipfile.$testip1;
    $doit = fopen($ab_config['htaccess_path'], "w");
    fwrite($doit, $ipfile);
    fclose($doit);
}
Header("Location: ".$admin_file.".php?op=$xop&min=$min&column=$column&direction=$direction&sip=$sip");

?>