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
    $pagetitle = ": "._USERADMIN." - "._RESENDMAIL;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    list($uname, $email) = $db->sql_fetchrow($db->sql_query("SELECT username, user_email FROM ".$user_prefix."_users_temp WHERE user_id='$chng_uid'"));
    ya_confirm("" . _SURE2RESEND . " <em><b>$uname ($email)</b>?</em>", resendMailConf, rsn_uid, $chng_uid, listpending);  
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>