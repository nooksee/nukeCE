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

global $cookie, $userinfo;
$check = $cookie[1];
$check2 = $cookie[2];
$row = get_user_field(array('user_id', 'user_password'), $check);
$vuid = $row['user_id'];
$ccpass = $row['user_password'];
if (($user_id == $vuid) AND ($check2 == $ccpass)) {
    if (empty($theme)) $theme = $Default_Theme;
    if(ThemeAllowed($theme)) {
            ChangeTheme($theme, $user_id);
    }
}

?>