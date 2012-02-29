<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(!defined('ADMIN_FILE')) { die("Illegal Access Detected!!"); }
if(defined("NUKESENTINEL_IS_LOADED")) $ab_config = abget_configs();
else $op = 'ABLoadError';

$nsnab_ver = "";

include("admin/modules/nukesentinel/functions.php");

$checktime = strtotime(date("Y-m-d", TIME()));
$textrowcol = "rows='6' cols='45'";

global $admin_file, $prefix, $db, $admdata;

if (is_mod_admin()) {
    
    switch ($op) {

        case 'ABAuthEdit':
            include('admin/modules/nukesentinel/ABAuthEdit.php');
        break;

        case 'ABAuthEditSave':
            include('admin/modules/nukesentinel/ABAuthEditSave.php');
        break;

        case 'ABAuthList':
            include('admin/modules/nukesentinel/ABAuthList.php');
        break;

        case 'ABAuthListScan':
            include('admin/modules/nukesentinel/ABAuthListScan.php');
        break;

        case 'ABAuthResend':
            include('admin/modules/nukesentinel/ABAuthResend.php');
        break;

        case 'ABBlockedIP':
            include('admin/modules/nukesentinel/ABBlockedIP.php');
        break;

        case 'ABBlockedIPAdd':
            include('admin/modules/nukesentinel/ABBlockedIPAdd.php');
        break;

        case 'ABBlockedIPAddSave':
            include('admin/modules/nukesentinel/ABBlockedIPAddSave.php');
        break;

        case 'ABBlockedIPClear':
            include('admin/modules/nukesentinel/ABBlockedIPClear.php');
        break;

        case 'ABBlockedIPClearExpired':
            include('admin/modules/nukesentinel/ABBlockedIPClearExpired.php');
        break;

        case 'ABBlockedIPClearSave':
            include('admin/modules/nukesentinel/ABBlockedIPClearSave.php');
        break;

        case 'ABBlockedIPDelete':
            include('admin/modules/nukesentinel/ABBlockedIPDelete.php');
        break;

        case 'ABBlockedIPDeleteSave':
            include('admin/modules/nukesentinel/ABBlockedIPDeleteSave.php');
        break;

        case 'ABBlockedIPEdit':
            include('admin/modules/nukesentinel/ABBlockedIPEdit.php');
        break;

        case 'ABBlockedIPEditSave':
            include('admin/modules/nukesentinel/ABBlockedIPEditSave.php');
        break;

        case 'ABBlockedIPMenu':
            include('admin/modules/nukesentinel/ABBlockedIPMenu.php');
        break;

        case 'ABBlockedIPView':
            include('admin/modules/nukesentinel/ABBlockedIPView.php');
        break;

        case 'ABCGIAuth':
            include('admin/modules/nukesentinel/ABCGIAuth.php');
        break;

        case 'ABCGIBuild':
            include('admin/modules/nukesentinel/ABCGIBuild.php');
        break;

        case 'ABConfig':
            include('admin/modules/nukesentinel/ABConfig.php');
        break;

        case 'ABConfigAdmin':
            include('admin/modules/nukesentinel/ABConfigAdmin.php');
        break;

        case 'ABConfigAuthor':
            include('admin/modules/nukesentinel/ABConfigAuthor.php');
        break;

        case 'ABConfigClike':
            include('admin/modules/nukesentinel/ABConfigClike.php');
        break;

        case 'ABConfigFilter':
            include('admin/modules/nukesentinel/ABConfigFilter.php');
        break;

        case 'ABConfigFlood':
            include('admin/modules/nukesentinel/ABConfigFlood.php');
        break;

        case 'ABConfigHarvester':
            include('admin/modules/nukesentinel/ABConfigHarvester.php');
        break;

        case 'ABConfigReferer':
            include('admin/modules/nukesentinel/ABConfigReferer.php');
        break;

        case 'ABConfigRequest':
            include('admin/modules/nukesentinel/ABConfigRequest.php');
        break;

        case 'ABConfigSave':
            include('admin/modules/nukesentinel/ABConfigSave.php');
        break;

        case 'ABConfigScript':
            include('admin/modules/nukesentinel/ABConfigScript.php');
        break;

        case 'ABConfigString':
            include('admin/modules/nukesentinel/ABConfigString.php');
        break;

        case 'ABConfigUnion':
            include('admin/modules/nukesentinel/ABConfigUnion.php');
        break;

        case 'ABConfigUpdate':
            include('admin/modules/nukesentinel/ABConfigUpdate.php');
        break;

        case 'ABCountryList':
            include('admin/modules/nukesentinel/ABCountryList.php');
        break;

        case 'ABLoadError':
            include('admin/modules/nukesentinel/ABLoadError.php');
        break;

        case 'ABMain':
            include('admin/modules/nukesentinel/ABMain.php');
        break;

        case 'ABMainSave':
            include('admin/modules/nukesentinel/ABMainSave.php');
        break;

        case 'ABPrintBlockedIP':
            include('admin/modules/nukesentinel/ABPrintBlockedIP.php');
        break;

        case 'ABPrintBlockedIPView':
            include('admin/modules/nukesentinel/ABPrintBlockedIPView.php');
        break;

        case 'ABPrintTracked':
            include('admin/modules/nukesentinel/ABPrintTracked.php');
        break;

        case 'ABPrintTrackedAgents':
            include('admin/modules/nukesentinel/ABPrintTrackedAgents.php');
        break;

        case 'ABPrintTrackedAgentsPages':
            include('admin/modules/nukesentinel/ABPrintTrackedAgentsPages.php');
        break;

        case 'ABPrintTrackedPages':
            include('admin/modules/nukesentinel/ABPrintTrackedPages.php');
        break;

        case 'ABPrintTrackedRefers':
            include('admin/modules/nukesentinel/ABPrintTrackedRefers.php');
        break;

        case 'ABPrintTrackedRefersPages':
            include('admin/modules/nukesentinel/ABPrintTrackedRefersPages.php');
        break;

        case 'ABPrintTrackedUsers':
            include('admin/modules/nukesentinel/ABPrintTrackedUsers.php');
        break;

        case 'ABPrintTrackedUsersPages':
            include('admin/modules/nukesentinel/ABPrintTrackedUsersPages.php');
        break;

        case 'ABSearch':
            include('admin/modules/nukesentinel/ABSearch.php');
        break;

        case 'ABTemplate':
            include('admin/modules/nukesentinel/ABTemplate.php');
        break;

        case 'ABTemplateView':
            include('admin/modules/nukesentinel/ABTemplateView.php');
        break;

        case 'ABTracked':
            include('admin/modules/nukesentinel/ABTracked.php');
        break;

        case 'ABTrackedAdd':
            include('admin/modules/nukesentinel/ABTrackedAdd.php');
        break;

        case 'ABTrackedAddSave':
            include('admin/modules/nukesentinel/ABTrackedAddSave.php');
        break;

        case 'ABTrackedAgents':
            include('admin/modules/nukesentinel/ABTrackedAgents.php');
        break;

        case 'ABTrackedAgentsDelete':
            include('admin/modules/nukesentinel/ABTrackedAgentsDelete.php');
        break;

        case 'ABTrackedAgentsIPs':
            include('admin/modules/nukesentinel/ABTrackedAgentsIPs.php');
        break;

        case 'ABTrackedAgentsListAdd':
            include('admin/modules/nukesentinel/ABTrackedAgentsListAdd.php');
        break;

        case 'ABTrackedAgentsPages':
            include('admin/modules/nukesentinel/ABTrackedAgentsPages.php');
        break;

        case 'ABTrackedClear':
            include('admin/modules/nukesentinel/ABTrackedClear.php');
        break;

        case 'ABTrackedClearSave':
            include('admin/modules/nukesentinel/ABTrackedClearSave.php');
        break;

        case 'ABTrackedDelete':
            include('admin/modules/nukesentinel/ABTrackedDelete.php');
        break;

        case 'ABTrackedDeleteSave':
            include('admin/modules/nukesentinel/ABTrackedDeleteSave.php');
        break;

        case 'ABTrackedDeleteUser':
            include('admin/modules/nukesentinel/ABTrackedDeleteUser.php');
        break;

        case 'ABTrackedDeleteUserIP':
            include('admin/modules/nukesentinel/ABTrackedDeleteUserIP.php');
        break;

        case 'ABTrackedMenu':
            include('admin/modules/nukesentinel/ABTrackedMenu.php');
        break;

        case 'ABTrackedPages':
            include('admin/modules/nukesentinel/ABTrackedPages.php');
        break;

        case 'ABTrackedRefers':
            include('admin/modules/nukesentinel/ABTrackedRefers.php');
        break;

        case 'ABTrackedRefersDelete':
            include('admin/modules/nukesentinel/ABTrackedRefersDelete.php');
        break;

        case 'ABTrackedRefersIPs':
            include('admin/modules/nukesentinel/ABTrackedRefersIPs.php');
        break;

        case 'ABTrackedRefersListAdd':
            include('admin/modules/nukesentinel/ABTrackedRefersListAdd.php');
        break;

        case 'ABTrackedRefersPages':
            include('admin/modules/nukesentinel/ABTrackedRefersPages.php');
        break;

        case 'ABTrackedUsers':
            include('admin/modules/nukesentinel/ABTrackedUsers.php');
        break;

        case 'ABTrackedUsersIPs':
            include('admin/modules/nukesentinel/ABTrackedUsersIPs.php');
        break;

        case 'ABTrackedUsersPages':
            include('admin/modules/nukesentinel/ABTrackedUsersPages.php');
        break;
    }
} else {
  echo "Access Denied";
}

?>