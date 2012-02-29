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

if(!defined('ADMIN_FILE')) {
    die('Access Denied');
}

$module_name = basename(dirname(dirname(__FILE__)));
get_lang($module_name);

switch ($op) {
    case "YAMain":
    case "addUser":
    case "addUserConf":
    case "approveUser":
    case "approveUserConf":
    case "activateUser":
    case "activateUserConf":
    case "autoSuspend":
    case "CookieConfig":
    case "CookieConfigSave":
    case "deleteUser":
    case "deleteUserConf":
    case "denyUser":
    case "denyUserConf":
    case "detailsTemp":
    case "detailsUser":
    case "listnormal":
    case "listpending":
    case "listresults":
    case "modifyTemp":
    case "modifyTempConf":
    case "modifyUser":
    case "modifyUserConf":
    case "promoteUser":
    case "promoteUserConf":
    case "removeUser":
    case "removeUserConf":
    case "resendMail":
    case "resendMailConf":
    case "restoreUser":
    case "restoreUserConf":
    case "searchUser":
    case "suspendUser":
    case "suspendUserConf":
    case "UsersConfig":
    case "UsersConfigSave":
    case "editTOS":
        include_once(NUKE_MODULES_DIR.$module_name."/admin/index.php");
    break;

}

?>