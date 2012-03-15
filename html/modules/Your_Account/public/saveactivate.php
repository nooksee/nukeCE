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

$pagetitle = _ACTIVATIONYES;
$ya_username = trim(check_html($ya_username, 'nohtml'));
$check_num = trim(check_html($check_num, 'nohtml'));
$ya_time = intval($ya_time);
$result = $db->sql_query("SELECT * FROM ".$user_prefix."_users_temp WHERE username='$ya_username' AND check_num='$check_num' AND time='$ya_time'");
if ($db->sql_numrows($result) == 1) {
    $row = $db->sql_fetchrow($result);
    $username = $row['username'];
    $user_email = $row['user_email'];
    $user_regdate = $row['user_regdate'];
    $user_password = $row['user_password'];
    $realname = ya_fixtext($realname);
    if(empty($realname)) { $realname = $row['username']; }
    $user_sig = str_replace("<br />", "\r\n", $user_sig);
    $user_sig = ya_fixtext($user_sig);
    $user_email = ya_fixtext($user_email);
    $femail = ya_fixtext($femail);
    $user_website = ya_fixtext($user_website);
    if (!eregi("http://", $user_website) AND !empty($user_website)) { $user_website = "http://$user_website"; }
    $bio = str_replace("<br />", "\r\n", $bio);
    $bio = ya_fixtext($bio);
    $user_icq = ya_fixtext($user_icq);
    $user_aim = ya_fixtext($user_aim);
    $user_yim = ya_fixtext($user_yim);
    $user_msnm = ya_fixtext($user_msnm);
    $user_occ = ya_fixtext($user_occ);
    $user_from = ya_fixtext($user_from);
    $user_interests = ya_fixtext($user_interests);
    $user_dateformat = ya_fixtext($user_dateformat);
    $newsletter = intval($newsletter);
    $user_viewemail = intval($user_viewemail);
    $user_allow_viewonline = intval($user_allow_viewonline);
    $user_timezone = intval($user_timezone);
    list($latest_uid) = $db->sql_fetchrow($db->sql_query("SELECT max(user_id) AS latest_uid FROM ".$user_prefix."_users"));
    if ($latest_uid == "-1") { $new_uid = 1; } else { $new_uid = $latest_uid+1; }
    $lv = time();
    $db->sql_query("LOCK TABLES ".$user_prefix."_users WRITE");
    $db->sql_query("INSERT INTO ".$user_prefix."_users (user_id, user_avatar, user_avatar_type, user_lang, user_lastvisit, umode) VALUES ($new_uid, 'gallery/blank.png', '3', '$language', '$lv', 'nested')");
    $db->sql_query("UPDATE ".$user_prefix."_users SET username='$username', name='$realname', user_email='$user_email', femail='$femail', user_website='$user_website', user_icq='$user_icq', user_aim='$user_aim', user_yim='$user_yim', user_msnm='$user_msnm', user_from='$user_from', user_occ='$user_occ', user_interests='$user_interests', newsletter='$newsletter', user_viewemail='$user_viewemail', user_allow_viewonline='$user_allow_viewonline', user_timezone='$user_timezone', user_dateformat='$user_dateformat', user_sig='$user_sig', bio='$bio', user_password='$user_password', user_regdate='$user_regdate' WHERE user_id='$new_uid'");
    $db->sql_query("UNLOCK TABLES");
    $db->sql_query("DELETE FROM ".$user_prefix."_users_temp WHERE username='$username'");
    $db->sql_query("OPTIMIZE TABLE ".$user_prefix."_users_temp");

    include_once(NUKE_BASE_DIR.'header.php');
    include('functions_welcome_pm.php');
    title(_ACTIVATIONYES);
    OpenTable();
    $result = $db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE username='$username' AND user_password='$user_password'");
    if ($db->sql_numrows($result) == 1) {
        send_pm($new_uid,$ya_username);
        $userinfo = $db->sql_fetchrow($result);
        yacookie($userinfo[user_id],$userinfo[username],$userinfo[user_password],$userinfo[storynum],$userinfo[umode],$userinfo[uorder],$userinfo[thold],$userinfo[noscore],$userinfo[ublockon],$userinfo[theme],$userinfo[commentmax]);
        include('custom_functions.php');
        init_group($new_uid);
        echo "
              <meta http-equiv=\"refresh\" content=\"modules.php?name=Your_Account\">
              <div align=\"center\">
                  <b>
                      <em>
                          $row[username], "._ACTMSG."
                      </em>
                  </b>
              </div>
              <br />
             ";
        $complete = 1;
    } else {
        echo "
              <div align=\"center\">
                  <b>
                      <em>
                          "._SOMETHINGWRONG."
                      </em>
                  </b>
              </div>
              <br />
             ";
    }
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
    
    if($complete) {
        header("Refresh: 3; URL=index.php");
        exit();
    }
} else {
    DisplayErrorReturn(_ACTERROR, 1);
    exit;
}

?>