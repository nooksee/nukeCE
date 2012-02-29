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
    list($email) = $db->sql_fetchrow($db->sql_query("SELECT user_email FROM ".$user_prefix."_users_temp WHERE user_id='$dny_uid'"));
    if ($ya_config['servermail'] == 0) {
        $message = _SORRYTO." $sitename "._HASDENY;
        if ($denyreason > "") {
            $denyreason = stripslashes($denyreason);
            $message .= "<br /><br />"._DENYREASON."<br />$denyreason";
        }
        $subject = _ACCTDENY;
        $from  = "From: $adminmail\n";
        $from .= "Reply-To: $adminmail\n";
        $from .= "Return-Path: $adminmail\n";
        nuke_mail($email, $subject, $message, $from);
    }
    $db->sql_query("DELETE FROM ".$user_prefix."_users_temp WHERE user_id='$dny_uid'");
    $db->sql_query("OPTIMIZE TABLE ".$user_prefix."_users_temp");
    redirect($admin_file.".php?op=listpending");

}

?>