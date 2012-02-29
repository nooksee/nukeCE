<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

global $admin_file;
if(!isset($admin_file)) { $admin_file = 'admin'; }
if(!defined('ADMIN_FILE')) { die('Illegal Access Detected!!'); }
switch($op) {
    case 'ABAuthEdit':
    case 'ABAuthEditSave':
    case 'ABAuthList':
    case 'ABAuthListScan':
    case 'ABAuthResend':
    case 'ABBlockedIP':
    case 'ABBlockedIPAdd':
    case 'ABBlockedIPAddSave':
    case 'ABBlockedIPClear':
    case 'ABBlockedIPClearExpired':
    case 'ABBlockedIPClearSave':
    case 'ABBlockedIPDelete':
    case 'ABBlockedIPDeleteSave':
    case 'ABBlockedIPEdit':
    case 'ABBlockedIPEditSave':
    case 'ABBlockedIPMenu':
    case 'ABBlockedIPView':
    case 'ABCGIAuth':
    case 'ABCGIBuild':
    case 'ABConfig':
    case 'ABConfigAdmin':
    case 'ABConfigAuthor':
    case 'ABConfigClike':
    case 'ABConfigFilter':
    case 'ABConfigFlood':
    case 'ABConfigHarvester':
    case 'ABConfigReferer':
    case 'ABConfigRequest':
    case 'ABConfigSave':
    case 'ABConfigScript':
    case 'ABConfigString':
    case 'ABConfigUnion':
    case 'ABConfigUpdate':
    case 'ABCountryList':
    case 'ABLoadError':
    case 'ABMain':
    case 'ABMainSave':
    case 'ABPrintBlockedIP':
    case 'ABPrintBlockedIPView':
    case 'ABPrintTracked':
    case 'ABPrintTrackedAgents':
    case 'ABPrintTrackedAgentsPages':
    case 'ABPrintTrackedPages':
    case 'ABPrintTrackedRefers':
    case 'ABPrintTrackedRefersPages':
    case 'ABPrintTrackedUsers':
    case 'ABPrintTrackedUsersPages':
    case 'ABSearch':
    case 'ABTemplate':
    case 'ABTemplateView':
    case 'ABTracked':
    case 'ABTrackedAdd':
    case 'ABTrackedAddSave':
    case 'ABTrackedAgents':
    case 'ABTrackedAgentsDelete':
    case 'ABTrackedAgentsIPs':
    case 'ABTrackedAgentsListAdd':
    case 'ABTrackedAgentsPages':
    case 'ABTrackedClear':
    case 'ABTrackedClearSave':
    case 'ABTrackedDelete':
    case 'ABTrackedDeleteSave':
    case 'ABTrackedDeleteUser':
    case 'ABTrackedDeleteUserIP':
    case 'ABTrackedMenu':
    case 'ABTrackedPages':
    case 'ABTrackedRefers':
    case 'ABTrackedRefersDelete':
    case 'ABTrackedRefersIPs':
    case 'ABTrackedRefersListAdd':
    case 'ABTrackedRefersPages':
    case 'ABTrackedUsers':
    case 'ABTrackedUsersIPs':
    case 'ABTrackedUsersPages':
        include('admin/modules/nukesentinel.php');
    break;

}
?>