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

if (!defined('ADMIN_FILE')) {
    die ('Illegal File Access');
}

switch($op) {
    case "themes":
    case "theme_uninstalled":
    case "theme_edit":
    case "theme_edit_save":
    case "theme_deactivate":
    case "theme_activate":
    case "theme_uninstall":
    case "theme_install":
    case "theme_install_save":
    case "theme_makedefault":
    case "theme_quickinstall":
    case "theme_transfer":
        include(NUKE_ADMIN_MODULE_DIR.'themes.php');
    break;

}

?>