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

if(!defined('NUKE_CE')) {
    exit;
}

global $admin_file, $db, $prefix, $user_prefix;
$module_name = basename(dirname(dirname(__FILE__)));

if(is_active($module_name)) {
    $pen = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users_temp"));
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=listpending\">
                     "._WAITINGUSERS.":
                 </a>
                 <strong>
                     $pen
                 </strong>
                 <br />
                ";
}

?>