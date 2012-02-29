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
    case "mod_authors":
    case "modifyadmin":
    case "UpdateAuthor":
    case "AddAuthor":
    case "deladmin2":
    case "deladmin":
    case "assignstories":
    case "deladminconf":
        include(NUKE_ADMIN_MODULE_DIR.'authors.php');
    break;

}

?>