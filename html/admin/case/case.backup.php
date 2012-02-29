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
    case "database":
    case "backup":
    case "optimize":
    case "BackupDB":
    case "OptimizeDB":
    case "CheckDB":
    case "AnalyzeDB":
    case "RepairDB":
    case "RestoreDB":
        include(NUKE_ADMIN_MODULE_DIR.'backup.php');
    break;

}

?>