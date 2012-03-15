<?php

/********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ================================================================             */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion */
/********************************************************************************/

if(!defined('IN_YA')) {
    exit('Access Denied');
}

include(NUKE_MODULES_DIR.$module_name.'/public/functions_welcome_pm.php');
include(NUKE_MODULES_DIR.$module_name.'/public/custom_functions.php');
include(NUKE_FORUMS_DIR.'includes/constants.php');

if(is_mod_admin($module_name)) {
    $pagetitle = _USERADMIN;
    if ($add_email != $add_email2) {
        DisplayErrorReturn(_EMAILDIFFERENT, 1);
        exit;
    }
    
    $add_email = strtolower($add_email);
    ya_userCheck($add_uname);
    ya_mailCheck($add_email);
    ya_passCheck($add_pass, $add_pass2);
    $add_name = ya_fixtext($add_name);
    if(empty($add_name)) { $add_name = $add_uname; }
    $add_femail = ya_fixtext($add_femail);
    $add_url = check_html($add_url);
    if (!eregi("http://", $add_url) AND !empty($add_url)) { $add_url = "http://$add_url"; }
    $add_user_sig = str_replace("<br />", "\r\n", $add_user_sig);
    $add_user_sig = ya_fixtext($add_user_sig);
    $add_user_icq = ya_fixtext($add_user_icq);
    $add_user_aim = ya_fixtext($add_user_aim);
    $add_user_yim = ya_fixtext($add_user_yim);
    $add_user_msnm = ya_fixtext($add_user_msnm);
    $add_user_from = ya_fixtext($add_user_from);
    $add_user_occ = ya_fixtext($add_user_occ);
    $add_user_interest = ya_fixtext($add_user_interest);
    $add_user_viewemail = intval($add_user_viewemail);
    $add_newsletter = intval($add_newsletter);
    $user_points = intval($user_points);
    
    if (empty($stop)) {
        $user_password = $add_pass;
        $add_pass = md5($add_pass);
        $user_regdate = date("M d, Y");
        list($newest_uid) = $db->sql_fetchrow($db->sql_query("SELECT max(user_id) AS newest_uid FROM ".$user_prefix."_users"));
        if ($newest_uid == "-1") { $new_uid = 1; } else { $new_uid = $newest_uid+1; }
        $sql = "INSERT INTO ".$user_prefix."_users (user_id, name, username, user_email, femail, user_website, user_regdate, user_icq, user_aim, user_yim, user_msnm, user_from, user_occ, user_interests, user_viewemail, user_avatar, user_avatar_type, user_sig, user_password, newsletter, broadcast, popmeson) VALUES ('$new_uid', '$add_name', '$add_uname', '$add_email', '$add_femail', '$add_url', '$user_regdate', '$add_user_icq', '$add_user_aim', '$add_user_yim', '$add_user_msnm', '$add_user_from', '$add_user_occ', '$add_user_intrest', '$add_user_viewemail', 'gallery/blank.png', '3', '$add_user_sig', '$add_pass', '$add_newsletter', '1', '0')";
        $result = $db->sql_query($sql);
        if (!$result) {
            DisplayError(_ERRORSQL, 1);
            return;
        } else {
            send_pm($new_uid, $add_uname);
            init_group($new_uid);
            if ($ya_config['servermail'] == 0) {
                $message  = _WELCOMETO." $sitename!<br /><br />";
                $message .= _YOUUSEDEMAIL." ($add_email) "._TOREGISTER." $sitename.<br /><br />";
                $message .= _FOLLOWINGMEM."<br />"._UNICKNAME." $add_uname<br />"._UPASSWORD." $user_password";
                $subject  = _ACCOUNTCREATED;
                $from  = "From: $adminmail\n";
                $from .= "Reply-To: $adminmail\n";
                $from .= "Return-Path: $adminmail\n";
                nuke_mail($user_email, $subject, $message, $from);
            }
            if (isset($min)) { $xmin = "&min=$min"; }
            if (isset($xop)) { $xxop = "&op=$xop"; }
            redirect($admin_file.".php?op=addUser"."$xxop"."$xmin");
        }
    } else {
        DisplayErrorReturn($stop, 1);
        return;
    }

}

?>