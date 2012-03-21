<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if(!defined('ADMIN_FILE')) {
    die('Access Denied');
}

$module_name = "Downloads";
get_lang($module_name);

switch ($op) {

    case "DLMain":
    case "DLConfig":
    case "DLConfigSave":
    case "Categories":
    case "CategoryActivate":
    case "CategoryAdd":
    case "CategoryAddSave":
    case "CategoryDeactivate":
    case "CategoryDelete":
    case "CategoryDeleteSave":
    case "CategoryModify":
    case "CategoryModifySave":
    case "CategoryTransfer":
    case "DownloadActivate":
    case "DownloadAdd":
    case "DownloadAddSave":
    case "DownloadRequestSave":
    case "DownloadBroken":
    case "DownloadBrokenDelete":
    case "DownloadBrokenIgnore":
    case "DownloadDeactivate":
    case "DownloadDelete":
    case "DownloadModify":
    case "DownloadModifyRequests":
    case "DownloadModifyRequestsAccept":
    case "DownloadModifyRequestsIgnore":
    case "DownloadModifySave":
    case "DownloadNew":
    case "DownloadNewDelete":
    case "Downloads":
    case "DownloadTransfer":
    case "ExtensionAdd":
    case "ExtensionAddSave":
    case "ExtensionDelete":
    case "ExtensionModify":
    case "ExtensionModifySave":
    case "Extensions":
    case "FilesizeCheck":
    case "FilesizeValidate":
    include_once(NUKE_MODULES_DIR.$module_name."/admin/index.php");
    break;

}

?>