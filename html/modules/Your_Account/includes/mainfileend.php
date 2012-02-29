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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

global $userinfo, $cookie, $cache;
$uinfo = $userinfo;
$ulevel = (isset($uinfo['user_level'])) ? $uinfo['user_level'] : 0;
$uactive = (isset($uinfo['user_active'])) ? $uinfo['user_active'] : 0;
if ( ($ulevel < 1) OR ($uactive < 1) ) {
    unset($user);
    unset($cookie);
}

if(isset($_GET['name']) && isset($_GET['file']) || isset($_GET['mode'])) {
    if ( ($_GET['name']=='Forums') && ($_GET['file']=='profile') && ($_GET['mode']=='register') ) redirect("modules.php?name=Your_Account&op=new_user");
}

// WARNING THIS SECTION OF CODE PREVENTS NEW POSTS REGISTERING AS UNREAD
if (is_user()) {
    $lv = time();
    $result = $db->sql_query("SELECT time FROM ".$prefix."_session WHERE uname='$uinfo[username]'");
    list($sessiontime) = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    if(($ya_config = $cache->load('ya_config', 'config')) === false) {
        $configresult = $db->sql_query("SELECT config_name, config_value FROM ".$prefix."_users_config");
        while (list($config_name, $config_value) = $db->sql_fetchrow($configresult)) {
            if (!get_magic_quotes_gpc()) { $config_value = stripslashes($config_value); }
            $ya_config[$config_name] = $config_value;
        }
        $db->sql_freeresult($configresult);
        $cache->save('ya_config', 'config', $ya_config);
    }
    if(isset($config)) $config = $ya_config;
    $cookieinactivity = $ya_config['cookieinactivity'];
    $cookiepath = $ya_config['cookiepath'];
    $autosuspend = $ya_config['autosuspend'];
    $autosuspendmain = $ya_config['autosuspendmain'];
    if (($cookieinactivity != '-') AND ( ($sessiontime + $cookieinactivity < $lv ) ) ) {
        $r_uid = $uinfo['user_id'];
        $r_username = $uinfo['username'];
        @setcookie("user");
        if (trim($cookiepath) != '') setcookie("user","","","$ya_config[cookiepath]");
        $db->sql_query("DELETE FROM ".$prefix."_session WHERE uname='$r_username'");
        $db->sql_query("OPTIMIZE TABLE ".$prefix."_session");
        $db->sql_query("DELETE FROM ".$prefix."_bbsessions WHERE session_user_id='$r_uid'");
        $db->sql_query("OPTIMIZE TABLE ".$prefix."_bbsessions");
        unset($user);
        unset($cookie);
        redirect("modules.php?name=Your_Account");
        exit;
    };

    // WARNING THIS SECTION OF CODE CAN SLOW SITE LOAD TIME DOWN!!!!!!!!!!!!!
    // IF YOU DO NOT WANT TO USE THIS CODE YOU DO NOT HAVE TO.
    // THIS FUNCTION IS IN USER ADMIN AND CAN BE TRIGGERED ONLY
    // WHEN THE ADMIN WANTS IT RUN.
    if (($autosuspend > 0) AND ($autosuspendmain==1)) {
        $st = time() - $autosuspend;
        $susresult = $db->sql_query("SELECT user_id FROM ".$user_prefix."_users WHERE user_lastvisit <= $st AND user_level > 0");
        while(list($sus_uid) = $db->sql_fetchrow($susresult)) {
            $db->sql_query("UPDATE ".$user_prefix."_users SET user_level='0', user_active='0' WHERE user_id='$sus_uid'");
        }
    }
} else {
    @setcookie("YA_CE1","value1");
    @setcookie("YA_CE2","value2",time()+3600);
    @setcookie("YA_CE3","value3",time()+3600,"/");
    @setcookie("YA_CE4","value4",time()+3600,"$ya_config[cookiepath]");
}

?>