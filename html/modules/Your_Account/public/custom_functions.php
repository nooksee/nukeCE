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

include_once(NUKE_FORUMS_DIR.'includes/constants.php');
include_once(NUKE_FORUMS_DIR.'includes/functions.php');

function init_group($uid) {
    global $prefix, $db, $board_config, $cache;
    if($board_config['initial_group_id'] != "0" && $board_config['initial_group_id'] != NULL) {
        $initialusergroup = intval($board_config['initial_group_id']);
        if($initialusergroup == 0) {
            return;
        }
        $db->sql_query("INSERT INTO ".$prefix."_bbuser_group (group_id, user_id, user_pending) VALUES ('$initialusergroup', $uid, '0')");
        add_group_attributes($uid, $initialusergroup);
    }
}

?>