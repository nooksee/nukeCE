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
    list($uname, $realname, $email, $upass, $ureg) = $db->sql_fetchrow($db->sql_query("SELECT username, realname, user_email, user_password, user_regdate FROM ".$user_prefix."_users_temp WHERE user_id='$act_uid'"));
    if ($ya_config['servermail'] == 0) {
        $message = _SORRYTO." $sitename "._HASAPPROVE;
        $subject = _SORRYTO." $sitename "._HASAPPROVE;
        $from  = "From: $adminmail<br />";
        $from .= "Reply-To: $adminmail<br />";
        $from .= "Return-Path: $adminmail<br />";
        nuke_mail($email, $subject, $message, $from);
    }
    $db->sql_query("DELETE FROM ".$user_prefix."_users_temp WHERE user_id='$act_uid'");
    $db->sql_query("OPTIMIZE TABLE ".$user_prefix."_users_temp");
    list($newest_uid) = $db->sql_fetchrow($db->sql_query("SELECT max(user_id) AS newest_uid FROM ".$user_prefix."_users"));
    if ($newest_uid == "-1") { $new_uid = 1; } else { $new_uid = $newest_uid+1; }
    $db->sql_query("INSERT INTO ".$user_prefix."_users (user_id, name, username, user_email, user_regdate, user_password, user_level, user_active, user_avatar, user_avatar_type, user_from) VALUES ('$new_uid', '$realname', '$uname', '$email', '$ureg', '$upass', 1, 1, 'gallery/blank.png', 3, '')");
    $sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator) VALUES ('', 'Personal User', '1', '0')";
    if ( !($result = $db->sql_query($sql)) ) {
        $pagetitle = ": "._USERADMIN." - "._YA_ACTIVATEUSER;
        DisplayError("Could not insert data into groups table!", 1);
    }
    $group_id = $db->sql_nextid();
    $sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending) VALUES ('$new_uid', '$group_id', '0')";
    if( !($result = $db->sql_query($sql)) ) {
        $pagetitle = ": "._USERADMIN." - "._YA_ACTIVATEUSER;
        DisplayError("Could not insert data into groups table!", 1);
    }
    send_pm($new_uid, $uname);
    init_group($new_uid);
    redirect($admin_file.".php?op=listpending");
}

?>