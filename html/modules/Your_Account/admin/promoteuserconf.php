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

if (is_mod_admin('super')) {
    $pagetitle = _USERADMIN;
    $num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_authors WHERE aid='$add_aid'"));
    if ($num > 0) {
        DisplayErrorReturn(_NAMEERROR);
    } else {
       $add_pwd = md5($add_pwd);
       for ($i=0,$maxi=count($auth_modules); $i < $maxi; $i++) {
            $row = $db->sql_fetchrow($db->sql_query("SELECT admins FROM ".$prefix."_modules WHERE mid='".intval($auth_modules[$i])."'"));
            $adm = $row['admins'] . $add_name;
            $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm,' WHERE mid='".intval($auth_modules[$i])."'");
        }
        
         $add_password = check_html($add_password, 'nohtml');
         $add_aid = check_html($add_aid, 'nohtml');
         $add_name = check_html($add_name, 'nohtml');
         $add_url = check_html($add_url, 'nohtml');
         $add_email = check_html($add_email, 'nohtml');
         $add_password = check_html($add_password, 'nohtml');
         $add_radminsuper = intval($add_radminsuper);
         $add_admlanguage = check_html($add_admlanguage, 'nohtml');
         $result = $db->sql_query("INSERT INTO " . $prefix . "_authors VALUES ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_password', '0', '$add_radminsuper', '$add_admlanguage')");

        if (!$result) {
            DisplayErrorReturn(_ADDERROR);
        } else {
            if ($ya_config['servermail'] == 0) {
                $message = _SORRYTO." $sitename "._HASPROMOTE;
                $subject = _ACCTPROMOTE;
                $from  = "From: $adminmail\n";
                $from .= "Reply-To: $adminmail\n";
                $from .= "Return-Path: $adminmail\n";
                nuke_mail($add_email, $subject, $message, $from);
            }
        }
        if ($add_radminforum == "1") { $db->sql_query("UPDATE ".$user_prefix."_users SET user_level='2' WHERE user_id='$chng_uid'"); }
    }
    redirect($admin_file.".php?op=mod_authors");
} else {
    redirect("../../../index.php");
    die ();
}

?>