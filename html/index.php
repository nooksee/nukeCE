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

define('HOME_FILE', true);
define('MODULE_FILE', true);
$_SERVER['PHP_SELF'] = 'modules.php';

require_once(dirname(__FILE__).'/mainfile.php');

global $prefix, $db, $admin_file, $httpref, $httprefmax, $result;

$result = UA::parse();

// redirect phones, to redirect tablets use isMobileDevice
if ($result->isMobile) {
    header("location:modules.php?name=AvantGo");
}

if (isset($_GET['op'])) {
    if($_GET['op'] == 'ad_click' && isset($_GET['bid'])) {
        $bid = intval($_GET['bid']);
        list($clickurl) = $db->sql_ufetchrow("SELECT `clickurl` FROM `".$prefix."_banner` WHERE `bid`='$bid'", SQL_NUM);
        if(!is_admin()) {
           $db->sql_query("UPDATE `".$prefix."_banner` SET `clicks`=clicks+1 WHERE `bid`='$bid'");
        }
        redirect($clickurl);
    } elseif($_GET['op'] == 'gfx') {
        include_once(NUKE_INCLUDE_DIR.'gfxchk.php');
    } else {
        exit('Illegal Operation');
    }
}

if (isset($_GET['url']) && is_admin()) {
    redirect($_GET['url']);
}

$module_name = main_module();

global $lock_modules;
if(($lock_modules && $module_name != 'Your_Account') && !is_admin() && !is_user()) {
    include(NUKE_MODULES_DIR.'Your_Account/index.php');
}

$mop = (!isset($mop)) ? 'modload' : trim($mop);
$mod_file = (!isset($mod_file)) ? 'index' : trim($mod_file);
$file = (isset($_REQUEST['file'])) ? trim($_REQUEST['file']) : 'index';
if (!isset($modpath)) { $modpath = ''; }

if (stristr($file,"..") || stristr($mod_file,"..") || stristr($mop,"..")) {
    log_write('error', 'Inappropriate module path was used', 'Hack Attempt');
    die("You are so cool...");
} else {
    $module = $db->sql_ufetchrow('SELECT `blocks` FROM `'.$prefix.'_modules` WHERE `title`="'.$module_name.'"');
    $modpath = NUKE_MODULES_DIR.$module_name."/$file.php";
    if (file_exists($modpath)) {
        $showblocks = $module['blocks'];
        unset($module, $error);
        require($modpath);
    } else {
        DisplayError((is_admin()) ? "<strong>"._HOMEPROBLEM."</strong><br /><br />[ <a href=\"".$admin_file.".php?op=modules\">"._ADDAHOME."</a> ]" : _HOMEPROBLEMUSER);
    }
}

?>