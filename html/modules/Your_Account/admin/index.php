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

global $admin_file, $client;
if(!defined('ADMIN_FILE')) {
    redirect("../../".$admin_file.".php");
    exit;
}

include(NUKE_BASE_DIR.'ips.php');

if(isset($ips) && is_array($ips)) {
    $client = new Client();  
    $ip_check = implode('|^',$ips);
    if (!preg_match("/^".$ip_check."/",$client->getIp())) {
        unset($aid);
        unset($admin);
        
        global $cookie;
        $name = (isset($cookie[1]) && !empty($cookie[1])) ? $cookie[1] : _ANONYMOUS;
        log_write('admin', $name.' used invalid IP address attempted to access the YA admin area', 'Security Breach');
        die('Invalid IP<br />Access denied');
    }
    define('ADMIN_IP_LOCK',true);
}

$module_name = basename(dirname(dirname(__FILE__)));
require_once(NUKE_MODULES_DIR.$module_name.'/includes/constants.php');
get_lang($module_name);
$aid = substr($aid, 0,25);

if(is_mod_admin($module_name)) {
    include_once(NUKE_MODULES_DIR.$module_name.'/includes/functions.php');
    $ya_config = ya_get_configs();
    if(!isset($op)) { $op="YAMain"; }
    switch ($op) {
        case "YAMain":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/Main.php");
        break;
    
        case "addUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/adduser.php");
        break;
    
        case "addUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/adduserconf.php");
        break;
    
        case "approveUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/approveuser.php");
        break;
    
        case "approveUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/approveuserconf.php");
        break;
    
        case "activateUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/activateuser.php");
        break;
    
        case "activateUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/activateuserconf.php");
        break;
    
        case "autoSuspend":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/autosuspend.php");
        break;
    
        case "CookieConfig":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/menucookies.php");
        break;
    
        case "CookieConfigSave":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/menucookiessave.php");
        break;
    
        case "deleteUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/deleteuser.php");
        break;
    
        case "deleteUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/deleteuserconf.php");
        break;
    
        case "denyUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/denyuser.php");
        break;
    
        case "denyUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/denyuserconf.php");
        break;
    
        case "detailsTemp":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/detailstemp.php");
        break;
    
        case "detailsUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/detailsuser.php");
        break;
    
        case "listnormal":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/listnormal.php");
        break;
    
        case "listpending":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/listpending.php");
        break;
    
        case "listresults":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/listresults.php");
        break;
    
        case "modifyTemp":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/modifytemp.php");
        break;
    
        case "modifyTempConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/modifytempconf.php");
        break;
    
        case "modifyUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/modifyuser.php");
        break;
    
        case "modifyUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/modifyuserconf.php");
        break;
    
        case "promoteUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/promoteuser.php");
        break;
    
        case "promoteUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/promoteuserconf.php");
        break;
    
        case "removeUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/removeuser.php");
        break;
    
        case "removeUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/removeuserconf.php");
        break;
    
        case "resendMail":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/resendmail.php");
        break;
    
        case "resendMailConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/resendmailconf.php");
        break;
    
        case "restoreUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/restoreuser.php");
        break;
    
        case "restoreUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/restoreuserconf.php");
        break;
    
        case "searchUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/searchuser.php");
        break;
    
        case "suspendUser":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/suspenduser.php");
        break;
    
        case "suspendUserConf":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/suspenduserconf.php");
        break;
    
        case "UsersConfig":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/userconfig.php");
        break;
    
        case "UsersConfigSave":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/userconfigsave.php");
        break;
    
        case "editTOS":
            include_once(NUKE_MODULES_DIR.$module_name."/admin/tos.php");
        break;
    }
} else {
    DisplayError(""._ERROR.": You do not have administration permission for module \"$module_name\"");

}

?>