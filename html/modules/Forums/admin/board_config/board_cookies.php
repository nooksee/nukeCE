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

if (!defined('BOARD_CONFIG')) {
    die('Access Denied');
}

$template->set_filenames(array(
    "cookies" => "admin/board_config/board_cookies.tpl")
);

$cookie_secure_yes = ( $new['cookie_secure'] ) ? "checked=\"checked\"" : "";
$cookie_secure_no = ( !$new['cookie_secure'] ) ? "checked=\"checked\"" : "";

//General Template variables
$template->assign_vars(array(
    "DHTML_ID" => "c" . $dhtml_id)
);
    
//Language Template variables
$template->assign_vars(array(
    "L_COOKIE_SETTINGS" => $lang['Cookie_settings'],
    "L_COOKIE_SETTINGS_EXPLAIN" => $lang['Cookie_settings_explain'],
    "L_COOKIE_DOMAIN" => $lang['Cookie_domain'],
    "L_COOKIE_NAME" => $lang['Cookie_name'],
    "L_COOKIE_PATH" => $lang['Cookie_path'],
    "L_COOKIE_SECURE" => $lang['Cookie_secure'],
    "L_COOKIE_SECURE_EXPLAIN" => $lang['Cookie_secure_explain'],
    "L_SESSION_LENGTH" => $lang['Session_length'],
));

//Data Template Variables
$template->assign_vars(array(
    "COOKIE_DOMAIN" => $new['cookie_domain'],
    "COOKIE_NAME" => $new['cookie_name'],
    "COOKIE_PATH" => $new['cookie_path'],
    "SESSION_LENGTH" => $new['session_length'],
    "S_COOKIE_SECURE_ENABLED" => $cookie_secure_yes,
    "S_COOKIE_SECURE_DISABLED" => $cookie_secure_no,
 ));
$template->pparse("cookies");

?>