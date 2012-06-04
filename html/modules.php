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

define('MODULE_FILE', true);

if (isset($_GET['file']) && $_GET['file'] == 'posting') {
    define('MEDIUM_SECURITY', true);
}

if (isset($_POST['tos_text']) && isset($_POST['op']) && $_POST['op'] == 'editTOS') {
    define('MEDIUM_SECURITY', true);
}

require_once(dirname(__FILE__) . '/mainfile.php');

global $name;

    if ($name) {
        global $db, $prefix, $user, $lock_modules;
        if(($lock_modules && $name != 'Your_Account') && !is_admin() && !is_user() && ($name != 'Profile' && $mode == 'register' && (isset($check_num) || isset($HTTP_POST_VARS['submit'])))) {
            include(NUKE_MODULES_DIR.'Your_Account/index.php');
        }
        $module = $db->sql_ufetchrow('SELECT `title`, `active`, `view`, `blocks`, `custom_title`, `groups` FROM `'.$prefix.'_modules` WHERE `title`="'.Fix_Quotes($name).'"');
        $module_name = $module['title'];
        if ($module_name == 'Your_Account' || $module_name == main_module()) {
            $module['active'] = true;
            $view = 0;
        } else {
            $view = $module['view'];
        }
        if ($module['active'] || is_mod_admin($module_name)) {
            if (!isset($file) OR $file != $_REQUEST['file']) $file='index';
            if (isset($open)) {
                if ($open != $_REQUEST['open']) $open = '';
            }
            if ((isset($file) && stristr($file,"..")) || (isset($mop) && stristr($mop,"..")) || (isset($open) && stristr($open,".."))) die('You are so cool...');
            $showblocks = $module['blocks'];
            $module_title = ($module['custom_title'] != '') ? $module['custom_title'] : str_replace('_', ' ', $module_name);
            $modpath = isset($module['title']) ? NUKE_MODULES_DIR.$module['title']."/$file.php" : NUKE_MODULES_DIR.$name."/$file.php";
            $groups = (!empty($module['groups'])) ? $groups = explode('-', $module['groups']) : '';
            if(!empty($open)) {
                $modpath = isset($module['title']) ? NUKE_MODULES_DIR.$module['title']."/$open.php" : NUKE_MODULES_DIR.$name."/$open.php";
            }
            unset($module, $error);
            if ($view >= 1 && !is_admin()) {
                //Must Not be a user
                if ($view == 2 AND is_user()) {
                    $error = _MVIEWANON;
                //Must Be a user
                } else if ($view == 3 && !is_user()) {
                    $error = _MODULEUSERS;
                //Must Be a admin
                } elseif ($view == 4 && !is_mod_admin($module['title'])) {
                    $error = _MODULESADMINS;
                //Groups
                } elseif ($view == 6 && !empty($groups) && is_array($groups)) {
                    $ingroup = false;
                    global $userinfo;
                    foreach ($groups as $group) {
                        if (isset($userinfo['groups'][$group])) {
                            $ingroup = true;
                        }
                    }
                    if (!$ingroup) $error = _MODULESGROUP;
                }
            }
            if(isset($error)) {
                DisplayError($error);
            } elseif(file_exists($modpath)) {
                include($modpath);
            } else {
                DisplayError(_MODULEDOESNOTEXIST);
            }
        } else {
            DisplayError(_MODULENOTACTIVE);
    }
} else {
    redirect('index.php');
}

?>