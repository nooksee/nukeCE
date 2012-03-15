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

$pagetitle = _USERREGLOGIN;
    
if (!empty($username) AND empty($user_email)) {
    $sql = "SELECT username, user_email, user_password, user_level FROM ".$user_prefix."_users WHERE username='$username'";
} elseif (empty($username) AND !empty($user_email)) {
    $sql = "SELECT username, user_email, user_password, user_level FROM ".$user_prefix."_users WHERE user_email='$user_email'";
} else {
    DisplayErrorReturn(_YA_MUSTSUPPLY, 1);
    exit;
}
$result = $db->sql_query($sql);
if($db->sql_numrows($result) == 0) {
    DisplayErrorReturn(_SORRYNOUSERINFO, 1);
} else {
    if ($ya_config['servermail'] == 0) {
        $host_name = $nsnst_const['remote_ip'];
        $row = $db->sql_fetchrow($result);
        $user_name = $row[username];
        $user_email = $row[user_email];
        $user_password = $row[user_password];
        $user_level = $row[user_level];
        if ($user_level > 0) {
            $areyou = substr($user_password, 0, 10);
            if ($areyou==$code) {
                $newpass = YA_MakePass();
                $message .= _USERACCOUNT." '$user_name' "._AT." $sitename "._HASTHISEMAIL."  "._AWEBUSERFROM." $host_name "._HASREQUESTED."<br /><br />";
                $message .= _YOURNEWPASSWORD." $newpass<br /><br /> ";
                $message .= _YOUCANCHANGE."<br />$nukeurl/modules.php?name=$module_name<br /><br />"._IFYOUDIDNOTASK;
                $subject = _USERPASSWORD4;
                if (!empty($username)) {
                    $subject .= " '$user_name'";
                } else if (!empty($user_email)) {
                    $subject .= " '$user_email'";
                }
                $from  = "From: $adminmail\n";
                $from .= "Reply-To: $adminmail\n";
                $from .= "Return-Path: $adminmail\n";
                nuke_mail($user_email, $subject, $message, $from);
                $cryptpass = md5($newpass);
                if (!empty($username)) {
                    $query = "UPDATE ".$user_prefix."_users SET user_password='$cryptpass' WHERE username='$username'";
                } else if (!empty($user_email)) {
                    $query = "UPDATE ".$user_prefix."_users SET user_password='$cryptpass' WHERE user_email='$user_email'";
                }
                include_once(NUKE_BASE_DIR.'header.php');
                title(_USERREGLOGIN);
                OpenTable();
                if (!$db->sql_query($query)) { echo "<center>"._UPDATEFAILED."</center><br />"; }
                echo "<div align=\"center\"><b><em>"._PASSWORD4." ";
                if (!empty($username)) { echo "'$user_name'"; } else if (!empty($user_email)) { echo "'$user_email'"; }
                echo " "._MAILED."</em></b><br /><br />"._GOBACK."</div>";
                CloseTable();
                include_once(NUKE_BASE_DIR.'footer.php');
            } else {
                $host_name = $_SERVER['REMOTE_ADDR'];
                $row = $db->sql_fetchrow($result);
                $areyou = substr($user_password, 0, 10);
                $message = _USERACCOUNT." '$user_name' "._AT." $sitename "._HASTHISEMAIL." "._AWEBUSERFROM." $host_name "._CODEREQUESTED."<br /><br />";
                $message .= _YOURCODEIS." $areyou<br /><br />";
                $message .= _WITHTHISCODE."<br />$nukeurl/modules.php?name=$module_name&op=pass_lost<br /><br />";
                $message .= _IFYOUDIDNOTASK2;
                $subject = _CODEFOR;
                if (!empty($username)) {
                    $subject .= " '$user_name'";
                } else if (!empty($user_email)) {
                    $subject .= " '$user_email'";
                }
                $from  = "From: $adminmail\n";
                $from .= "Reply-To: $adminmail\n";
                $from .= "Return-Path: $adminmail\n";
                nuke_mail($user_email, $subject, $message, $from);
                include_once(NUKE_BASE_DIR.'header.php');
                title(_USERREGLOGIN);
                OpenTable();
                echo "<div align=\"center\"><b><em>"._CODEFOR." ";
                if (!empty($username)) { echo "'$user_name'"; } else if (!empty($user_email)) { echo "'$user_email'"; }
                echo " "._MAILED."</em></b><br /><br />"._GOBACK."</div>";
                CloseTable();
                include_once(NUKE_BASE_DIR.'footer.php');
            }
        } elseif ($user_level == 0) {
            DisplayError(_ACCSUSPENDED, 1);
        } elseif ($user_level == -1) {
            DisplayError(_ACCDELETED, 1);
        }
    } else {
        DisplayError(_SERVERNOMAIL, 1);
    }

}

?>