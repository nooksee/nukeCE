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
    case "cache_delete":
    case "cache_view":
    case "cache_clear":
    case "usrclearcache":
    case "howto_enable_cache":
    case "cache":
        include(NUKE_ADMIN_MODULE_DIR.'cache.php');
    break;

}

?>