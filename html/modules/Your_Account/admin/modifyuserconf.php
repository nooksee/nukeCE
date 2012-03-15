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

if(is_mod_admin($module_name)) {
    $pagetitle = _USERADMIN;
    $stop = "";
    if ($chng_uname != $old_uname) { ya_userCheck($chng_uname); }
    if ($chng_email != $old_email) { ya_mailCheck($chng_email); }
    if (!empty($chng_pass) OR !empty($chng_pass2)) { ya_passCheck($chng_pass, $chng_pass2); }
    
    $chng_uname = ya_fixtext($chng_uname);
    $chng_name = ya_fixtext($chng_name);
    $chng_email = ya_fixtext($chng_email);
    $chng_femail = ya_fixtext($chng_femail);
    $chng_url = ya_fixtext($chng_url);
    $chng_user_icq = ya_fixtext($chng_user_icq);
    $chng_user_aim = ya_fixtext($chng_user_aim);
    $chng_user_yim = ya_fixtext($chng_user_yim);
    $chng_user_msnm = ya_fixtext($chng_user_msnm);
    $chng_user_occ = ya_fixtext($chng_user_occ);
    $chng_user_from = ya_fixtext($chng_user_from);
    $chng_user_intrest = ya_fixtext($chng_user_intrest);
    $chng_avatar = ya_fixtext($chng_avatar);
    $chng_user_viewemail = intval($chng_user_viewemail);
    $chng_user_sig = ya_fixtext($chng_user_sig);
    $chng_newsletter = intval($chng_newsletter);
    $chng_points = 0;

    if (empty($stop)) {
        if (!empty($chng_pass)) {
            $cpass = md5($chng_pass);
            $db->sql_query("UPDATE ".$user_prefix."_users SET username='$chng_uname', name='$chng_name', user_email='$chng_email', femail='$chng_femail', user_website='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_interests='$chng_user_interests', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', user_password='$cpass', newsletter='$chng_newsletter' WHERE user_id='$chng_uid'");
            if ($Version_Num > 6.9) { $db->sql_query("UPDATE ".$user_prefix."_users SET points='$chng_points' WHERE user_id='$chng_uid'"); }
        } else {
            $db->sql_query("UPDATE ".$user_prefix."_users SET username='$chng_uname', name='$chng_name', user_email='$chng_email', femail='$chng_femail', user_website='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_interests='$chng_user_interests', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', newsletter='$chng_newsletter' WHERE user_id='$chng_uid'");
            if ($Version_Num > 6.9) { $db->sql_query("UPDATE ".$user_prefix."_users SET points='$chng_points' WHERE user_id='$chng_uid'"); }
        }
        redirect($admin_file.".php?op=modifyUser&chng_uid=$chng_uid");
    } else {
        DisplayErrorReturn($stop);
        return;
    }

}

?>