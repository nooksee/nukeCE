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

global $cookie;
$check = $cookie[1];
$result = $db->sql_query("SELECT user_id, username, user_password FROM ".$user_prefix."_users WHERE username='$check'");
list($uid, $uname, $pass) = $db->sql_fetchrow($result);
confirm_msg(_SUREDELETE, "modules.php?name=$module_name&amp;op=deleteconfirm&amp;uid=$uid&amp;code=$pass", "modules.php?name=$module_name", 1);

?>