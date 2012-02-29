<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('IN_PHPBB'))
{
    die('Hacking attempt');
}

// 
// Statistics Overview
// 
$core->start_module(true);
$core->set_content('values');
 
// configuration of module: number of columns to use for displaying the links, may be 1..n
$user_variables = $core->get_user_defines();
$use_num_columns = intval($user_variables['num_columns']);

$core->set_view('columns', 1);
$core->set_view('num_blocks', $use_num_columns);
$core->set_view('value_order', 'up_down');

$core->define_view('set_columns', array(
    'stats' => '')
);

$core->set_header($lang['module_name']);

//
// Use internal Functions to get an array of installed and activated Modules (and their Names)
// -> Link: <a href="#module_id">lang['module_name']</a>
//
$current_modules = get_modules();
$link_array = array();

for ($i = 0; $i < count($current_modules); $i++)
{
    $module_id = intval($current_modules[$i]['module_id']);
    $module_short_name = trim($current_modules[$i]['short_name']);

    if ($module_short_name != $core->current_module_name)
    {
        eval('$current_module_name = $' . $module_short_name . '[\'module_name\'];');
        if (empty($current_module_name))
        {
            $current_module_name = $module_short_name;
        }

        $link_array[] = '<a href="#' . $module_id . '">' . $current_module_name . '</a>';
    }
}

$data = $core->assign_defined_view('value_array', array($link_array));

$core->set_data($data);

$core->define_view('iterate_values', array());

$core->run_module();

?>