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

if (!defined('IN_PHPBB')) {
    die('Hacking attempt');
}

// Move Module one up
function move_up($module_id) {
    global $db;

    // Select current module order
    $sql = "SELECT module_order FROM " . MODULES_TABLE . " WHERE module_id = " . $module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to select module order', '', __LINE__, __FILE__, $sql);
    }

    $row = $db->sql_fetchrow($result);
    $old_module_order = intval($row['module_order']);
    
    // Select Module in order before the current one
    $sql = "SELECT module_id, module_order FROM " . MODULES_TABLE . " WHERE module_order < " . $old_module_order . " ORDER BY module_order DESC LIMIT 1";

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to select module order', '', __LINE__, __FILE__, $sql);
    }

    if ($db->sql_numrows($result) == 0) {
        return;
    }
    
    $row = $db->sql_fetchrow($result);
    $new_module_order = intval($row['module_order']);
    $replaced_module_id = intval($row['module_id']);

    // Assign current module order to the one before
    $sql = "UPDATE " . MODULES_TABLE . " SET module_order = " . $old_module_order . " WHERE module_id = " . $replaced_module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to update module order', '', __LINE__, __FILE__, $sql);
    }

    // Assign the new module order to the current module
    $sql = "UPDATE " . MODULES_TABLE . " SET module_order = " . $new_module_order . " WHERE module_id = " . $module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to update module order', '', __LINE__, __FILE__, $sql);
    }

    return;
}

// Move Module one down
function move_down($module_id) {
    global $db;

    // Select current module order
    $sql = "SELECT module_order FROM " . MODULES_TABLE . " WHERE module_id = " . $module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to select module order', '', __LINE__, __FILE__, $sql);
    }

    $row = $db->sql_fetchrow($result);
    $old_module_order = intval($row['module_order']);
    
    // Select Module in order after the current one
    $sql = "SELECT module_id, module_order FROM " . MODULES_TABLE . " WHERE module_order > " . $old_module_order . " ORDER BY module_order ASC LIMIT 1";

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to select module order', '', __LINE__, __FILE__, $sql);
    }

    if ($db->sql_numrows($result) == 0) {
        return;
    }
    
    $row = $db->sql_fetchrow($result);
    $new_module_order = intval($row['module_order']);
    $replaced_module_id = intval($row['module_id']);

    // Assign current module order to the one before
    $sql = "UPDATE " . MODULES_TABLE . " SET module_order = " . $old_module_order . " WHERE module_id = " . $replaced_module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to update module order', '', __LINE__, __FILE__, $sql);
    }

    // Assign the new module order to the current module
    $sql = "UPDATE " . MODULES_TABLE . " SET module_order = " . $new_module_order . " WHERE module_id = " . $module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to update module order', '', __LINE__, __FILE__, $sql);
    }

    return;
}

// activate module
function activate($module_id) {
    global $db;

    $sql = "UPDATE " . MODULES_TABLE . " SET active = 1 WHERE module_id = " . $module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to activate module', '', __LINE__, __FILE__, $sql);
    }

    return;
}

// deactivate module
function deactivate($module_id) {
    global $db;

    $sql = "UPDATE " . MODULES_TABLE . " SET active = 0 WHERE module_id = " . $module_id;

    if (!($result = $db->sql_query($sql))) {
        message_die(GENERAL_ERROR, 'Unable to deactivate module', '', __LINE__, __FILE__, $sql);
    }

    return;
}

// Resync Module Order
function resync_module_order() {
    global $db;

    $sql = "SELECT * FROM " . MODULES_TABLE . " ORDER BY module_order ASC";

    if( !$result = $db->sql_query($sql) ) {
        message_die(GENERAL_ERROR, "Couldn't get list of Modules", "", __LINE__, __FILE__, $sql);
    }

    $i = 10;
    $inc = 10;

    while( $row = $db->sql_fetchrow($result) ) {
        $sql = "UPDATE " . MODULES_TABLE . " SET module_order = $i WHERE module_id = " . intval($row['module_id']);
        if( !$db->sql_query($sql) ) {
            message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
        }
        $i += $inc;
    }
}

?>