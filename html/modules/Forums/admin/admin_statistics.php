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

global $directory_mode, $file_mode;

define('IN_PHPBB', true);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
if (!empty($board_config))
{
    @include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
}

if( !empty($setmodules) )
{
    $filename = basename(__FILE__);
    $module['Statistics']['Manage_modules'] = $filename . '?mode=mod_manage';
    return;
}
$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : FALSE;

if ($cancel)
{
    $no_page_header = TRUE;
}

require('pagestart.' . $phpEx);

$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : FALSE;

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
    $mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
    $mode = '';
}
@include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
include($phpbb_root_path . 'stats/includes/constants.'.$phpEx);

$sql = "SELECT * FROM " . STATS_CONFIG_TABLE;
     
if ( !($result = $db->sql_query($sql)) )
{
    message_die(GENERAL_ERROR, 'Could not query statistics config table', '', __LINE__, __FILE__, $sql);
}

$stats_config = array();

while ($row = $db->sql_fetchrow($result))
{
    $stats_config[$row['config_name']] = trim($row['config_value']);
}

include($phpbb_root_path . 'stats/includes/lang_functions.'.$phpEx);
include($phpbb_root_path . 'stats/includes/stat_functions.'.$phpEx);
include($phpbb_root_path . 'stats/includes/admin_functions.'.$phpEx);

if ($cancel)
{
    $url = 'admin/' . append_sid("admin_statistics.$phpEx?mode=mod_manage", true);
    
    $server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
    $server_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['server_name']));
    $server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';
    $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
    $url = preg_replace('/^\/?(.*?)\/?$/', '/\1', trim($url));

    // Redirect via an HTML form for PITA webservers
    if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
    {
        header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
        exit;
    }

    // Behave as per HTTP/1.1 spec for others
    redirect($server_protocol . $server_name . $server_port . $script_name . $url);
    exit;
}

// BEGIN Manage Modules
if ($mode == 'mod_manage')
{
    if (isset($HTTP_GET_VARS['move_up']))
    {
        $module_id = intval($HTTP_GET_VARS['move_up']);
        move_up($module_id);
    }
    else if (isset($HTTP_GET_VARS['move_down']))
    {
        $module_id = intval($HTTP_GET_VARS['move_down']);
        move_down($module_id);
    }
    else if (isset($HTTP_GET_VARS['activate']))
    {
        $module_id = intval($HTTP_GET_VARS['activate']);
        activate($module_id);
    }
    else if (isset($HTTP_GET_VARS['deactivate']))
    {
        $module_id = intval($HTTP_GET_VARS['deactivate']);
        deactivate($module_id);
    }
    
    $template->set_filenames(array(
        'body' => 'admin/stat_manage_body.tpl')
    );

    $sql = "SELECT m.*, i.* FROM " . MODULES_TABLE . " m, " . MODULE_INFO_TABLE . " i WHERE i.module_id = m.module_id ORDER BY module_order ASC";

    if (!($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Unable to get Module Informations', '', __LINE__, __FILE__, $sql);
    }

    if ($db->sql_numrows($result) == 0)
    {
        message_die(GENERAL_MESSAGE, 'No installed Modules found.');
    }

    $template->assign_vars(array(
        'L_EDIT' => $lang['Edit'],
        'L_MOVE_UP' => $lang['Move_up'],
        'L_MOVE_DOWN' => $lang['Move_down'],
        'L_MANAGE_MODULES' => $lang['Manage_modules'],
        'L_MANAGE_MODULES_EXPLAIN' => $lang['Manage_modules_explain'])
    );

    while ($row = $db->sql_fetchrow($result))
    {
        $module_id = intval($row['module_id']);
        $module_active = (intval($row['active'])) ? TRUE : FALSE;

        $template->assign_block_vars('modulerow', array(
            'MODULE_NAME' => trim($row['long_name']),
            'MODULE_DESC' => trim(nl2br($row['extra_info'])),

            'U_VIEW_MODULE' => '../../../modules.php?name=Forums&amp;file=statistics&amp;preview='.$module_id,
            'U_MODULE_EDIT' => append_sid($phpbb_root_path . 'admin/admin_edit_module.'.$phpEx.'?mode=mod_edit&amp;module='.$module_id),
            'U_MODULE_MOVE_UP' => append_sid($phpbb_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;move_up='.$module_id),
            'U_MODULE_MOVE_DOWN' => append_sid($phpbb_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;move_down='.$module_id),
            'U_MODULE_ACTIVATE' => ($module_active) ? append_sid($phpbb_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;deactivate='.$module_id) : append_sid($phpbb_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;activate='.$module_id),
            'ACTIVATE' => ($module_active) ? $lang['Deactivate'] : $lang['Activate'])
        );
    }
}
// END Manage Modules

$template->pparse('body');

//
// Page Footer
//
include('./page_footer_admin.'.$phpEx);

?>