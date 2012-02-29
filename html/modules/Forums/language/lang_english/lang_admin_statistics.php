<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/***************************************************************************
 *                            lang_admin_statistics.php [English]
 *                              -------------------
 *     begin                : Fri Jan 24 2003
 *     copyright            : (C) 2003 Meik Sievertsen
 *     email                : acyd.burn@gmx.de
 *
 *     $Id: lang_admin_statistics.php,v 1.21 2003/03/16 18:38:29 acydburn Exp $
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

$lang['Manage_modules'] = 'Manage Modules';
$lang['Stats_configuration'] = 'Configuration';
$lang['Edit_module'] = 'Edit Module';

$lang['Module_name'] = 'Module Name';
$lang['Module_description'] = 'Module Description';
$lang['Module_version'] = 'Module Version';
$lang['Required_stats_version'] = 'Minimum required Statistics Version';
$lang['Installed_stats_version'] = 'Statistics Version';
$lang['Module_author'] = 'Module Author';
$lang['Author_email'] = 'Author E-Mail Address';
$lang['Module_url'] = 'Module/Author Homepage';
$lang['Provided_language'] = 'Provided Language';
$lang['Install_language'] = 'Language';

// Manage Modules
$lang['Manage_modules_explain'] = 'Here you are able to manage your modules. You may edit, change the order, activate and deactivate modules. If you click on a module name, you will see a preview of this module.';
$lang['Deactivate'] = 'Deactivate';
$lang['Activate'] = 'Activate';

// Configuration
$lang['Msg_config_updated'] = '- Statistics Configuration successfully updated.';
$lang['Msg_reset_view_count'] = '- View Count successfully resetted.';
$lang['Msg_reset_cache'] = '- Successfully cleared all caches!';
$lang['Config_title'] = 'Statistics Configuration';
$lang['Config_explain'] = 'Here you are able to configure Statistics.';
$lang['Messages'] = 'Messages';
$lang['Return_limit'] = 'Return Limit';
$lang['Return_limit_explain'] = 'The number of items to include in each ranking.';
$lang['Reset_settings_title'] = 'Reset Settings';
$lang['Reset_view_count'] = 'Reset view count';
$lang['Reset_view_count_explain'] = 'Reset the view count at the bottom of the statistics page to zero.';
$lang['Reset_cache'] = 'Clear Cache';
$lang['Reset_cache_explain'] = 'Clear all the current cached data for all modules and content templates.';

// Edit Module
$lang['Msg_changed_update_time'] = '- Successfully changed update time!';
$lang['Msg_cleared_module_cache'] = '- Successfully cleared Module cache!';
$lang['Msg_module_fields_updated'] = '- Updated Module definable fields successfully!';

$lang['Module_select_title'] = 'Select Module';
$lang['Module_select_explain'] = 'Here you may select the module you wish to edit.';
$lang['Edit_module_explain'] = 'Here you are able to configure the module. At the top you will see Module Information, then the message window where all update messages are displayed. At the bottom, you will find the Configuration Area. The Configuration Area may differ from module to module, because some modules have special configuration options the author thought you may find helpful.';
$lang['Module_information'] = 'Module Information';
$lang['Module_languages'] = 'Languages linked to this Module';
$lang['Preview_module'] = 'Preview Module';
$lang['Module_configuration'] = 'Module Configuration';
$lang['Update_time'] = 'Update Time in Minutes';
$lang['Update_time_explain'] = 'Time Intervall (in Minutes) of refreshing the cached data with new data. Every x minutes, the module will refresh. Since Statistics is using a priority system, this will be greater than x minutes, but not more than one day.';
$lang['Module_status'] = 'Module Status';
$lang['Active'] = 'Active';
$lang['Not_active'] = 'Not active';
$lang['Clear_module_cache'] = 'Clear module cache';
$lang['Clear_module_cache_explain'] = 'Clear the module cache and reset the modules priority. The next time the Statistics Page is called, this module will refresh.';

// Permissions
$lang['Msg_permissions_updated'] = '- Permissions updated';
$lang['Permissions'] = 'Permissions';
$lang['Set_permissions_title'] = 'Here you are able to set the permissions to view a module. Only the Users (Anonymous, Registered, Moderators and Administrators) and Groups allowed/listed here are able to view the module within the Statistics Page.';
$lang['Perm_all'] = 'Anonymous Users';
$lang['Perm_reg'] = 'Registered Users';
$lang['Perm_mod'] = 'Moderators';
$lang['Perm_admin'] = 'Administrators';
$lang['Perm_group'] = 'Groups';
$lang['Added_groups'] = 'Added Groups';
$lang['Perm_add_group'] = 'Add Group';
$lang['Perm_remove_group'] = 'Remove Group';
$lang['Perm_groups_title'] = 'Groups able to see the Module';
$lang['No_groups_selected'] = 'No groups selected';
$lang['No_groups_to_add'] = 'There are no more groups to add';

$lang['Language'] = 'Language';
$lang['Modules'] = 'Modules';

?>