<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

global $module_name;

if(!isset($module_name) || empty($module_name)){
    $module_name = basename(dirname(__FILE__));
}

get_lang($module_name);
define('INDEX_FILE', true);
define('IN_DOWNLOADS', true);
include_once(NUKE_MODULES_DIR.$module_name.'/includes/functions.php');
$result1 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_config");
$dl_config = downloads_get_configs();

if (!$dl_config OR empty($dl_config)) {
    DisplayError(_DL_DBCONFIG, 1);
    exit;
}

// A bit compliancy for old Downloads module
if(isset($d_op)) { $op = $d_op; unset($d_op); }
if(!isset($op)) { $op = "index"; }
if($op == "viewdownload") { $op = "getit"; }
if($op == "viewdownloaddetails") { $op = "getit"; }

switch($op) {
    // Downloads
    case "index":include_once(NUKE_MODULES_DIR.$module_name."/public/index.php");break;
    case "NewDownloads":include_once(NUKE_MODULES_DIR.$module_name."/public/NewDownloads.php");break;
    case "NewDownloadsDate":include_once(NUKE_MODULES_DIR.$module_name."/public/NewDownloadsDate.php");break;
    case "MostPopular":include_once(NUKE_MODULES_DIR.$module_name."/public/MostPopular.php");break;
    case "brokendownload":include_once(NUKE_MODULES_DIR.$module_name."/public/brokendownload.php");break;
    case "brokendownloadS":include_once(NUKE_MODULES_DIR.$module_name."/public/brokendownloadS.php");break;
    case "modifydownloadrequest":include_once(NUKE_MODULES_DIR.$module_name."/public/modifydownloadrequest.php");break;
    case "modifydownloadrequestS":include_once(NUKE_MODULES_DIR.$module_name."/public/modifydownloadrequestS.php");break;
    case "getit":include_once(NUKE_MODULES_DIR.$module_name."/public/getit.php");break;
    case "go":include_once(NUKE_MODULES_DIR.$module_name."/public/go.php");break;
    case "search":include_once(NUKE_MODULES_DIR.$module_name."/public/search.php");break;
    // Submit Downloads
    case "Input":
    case "Add":
    case "TermsUseUp":
    case "TermsUse":
    case "SubmitDownloads":
    include_once(NUKE_MODULES_DIR.$module_name."/public/SubmitDownloads.php");
    break;
}

?>