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

include_once(NUKE_MODULES_DIR.$module_name.'/public/functions_welcome_pm.php');
include_once(NUKE_MODULES_DIR.$module_name.'/public/custom_functions.php');
include_once(NUKE_FORUMS_DIR.'includes/constants.php');

if(is_mod_admin($module_name)) {
    list($username, $email, $check_num) = $db->sql_fetchrow($db->sql_query("SELECT username, user_email, check_num FROM ".$user_prefix."_users_temp WHERE user_id='$apr_uid'"));
    if ($ya_config['servermail'] == 0) {
        $time = time();
        $finishlink = "$nukeurl/modules.php?name=$module_name&op=activate&username=$username&check_num=$check_num";
        $message  = _WELCOMETO." $sitename!<br /><br />";
        $message .= _YOUUSEDEMAIL." ($email) "._TOREGISTER." $sitename.<br /><br />";
        $message .= _TOFINISHUSER."<br /><br /><a href=\"$finishlink\">$finishlink</a>";
        $subject  = _ACTIVATIONSUB;
        $from  = "From: $adminmail\n";
        $from .= "Reply-To: $adminmail\n";
        $from .= "Return-Path: $adminmail\n";
        nuke_mail($email, $subject, $message, $from);
    }
    $db->sql_query("UPDATE ".$user_prefix."_users_temp SET time='$time' WHERE user_id='$apr_uid'");
    send_pm($apr_uid, $username);
    init_group($apr_uid);
    redirect($admin_file.".php?op=listpending");
}

?>