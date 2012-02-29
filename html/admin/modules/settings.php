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
   die ("Illegal File Access");
}

global $prefix, $db;

if (is_mod_admin()) {

    include_once(NUKE_ADMIN_MODULE_DIR.'settings/functions.php');

    switch($op) {
    
        case "Configure":
            $sub = intval($_REQUEST['sub']);
            show_settings($sub);
        break;
    
        case "ConfigSave":
            if(isset($_REQUEST['sub'])) {
                $sub = intval($_REQUEST['sub']);
                save_settings($sub);
            } else {
                exit('Illegal Operation');
            }
        break;
    
    }

} else {
    echo "Access Denied";
}

?>