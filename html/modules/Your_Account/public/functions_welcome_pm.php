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

define('IN_PHPBB', true);

include_once(NUKE_FORUMS_DIR.'includes/bbcode.php');
include_once(NUKE_FORUMS_DIR.'includes/functions_post.php');

//PM Sign Up
function change_post_msg($message,$ya_username) {
    $message = str_replace("%NAME%", $ya_username, $message);
    return $message;
}

//PM Sign Up
function send_pm($new_uid,$ya_username) {
    global $db, $prefix, $user_prefix, $board_config;

    if($board_config['welcome_pm'] != '1') { return; }
    $privmsgs_date = time();
    $sql = "SELECT * FROM ".$prefix."_welcome_pm";
    if ( !($result = $db->sql_query($sql)) ) {
        echo "Could not obtain private message";
    }
    $row = $db->sql_fetchrow($result);
    $message = $row['msg'];
    $subject = $row['subject'];
    if(empty($message) || empty($subject)) {
        return;
    }
    $message = change_post_msg($message,$ya_username);
    $subject = change_post_msg($subject,$ya_username);
    $bbcode_uid = make_bbcode_uid();
    $privmsg_message = prepare_message($message, 1, 1, 1, $bbcode_uid);
    $sql = "INSERT INTO " . $prefix . "_bbprivmsgs (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date ) VALUES ('1', '".$subject."', '2', '".$new_uid."', ".$privmsgs_date.")";
    if ( !$db->sql_query($sql) ) {
        echo "Could not insert private message sent info";
    }
    $privmsg_sent_id = $db->sql_nextid();
    $privmsg_message = addslashes($privmsg_message);
    $sql = "INSERT INTO " . $prefix . "_bbprivmsgs_text (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text) VALUES ('".$privmsg_sent_id."', '".$bbcode_uid."', '".$privmsg_message."')";
    if ( !$db->sql_query($sql) ) {
        echo "Could not insert private message sent text";
    }
    $sql = "UPDATE " . $user_prefix . "_users SET user_new_privmsg = user_new_privmsg + 1,  user_last_privmsg = '" . time() . "' WHERE user_id = $new_uid";
    if ( !($result = $db->sql_query($sql)) ) {
        echo "Could not update users table";
    }

}

?>