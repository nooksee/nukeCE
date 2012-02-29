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
    "private_messages" => "admin/board_config/board_private_messages.tpl")
);

$privmsg_on = ( !$new['privmsg_disable'] ) ? "checked=\"checked\"" : "";
$privmsg_off = ( $new['privmsg_disable'] ) ? "checked=\"checked\"" : "";

/*****[BEGIN]******************************************
[ Mod:     Welcome PM                         v2.0.0 ]
******************************************************/
$welcome_pm_yes = ( $new['welcome_pm'] ) ? 'checked="checked"' : '';
$welcome_pm_no = ( !$new['welcome_pm'] ) ? 'checked="checked"' : '';
/*****[END]********************************************
[ Mod:     Welcome PM                         v2.0.0 ]
******************************************************/
//General Template variables
$template->assign_vars(array(
    "DHTML_ID" => "c" . $dhtml_id)
);
    
//Language Template variables
$template->assign_vars(array(
    "L_PRIVATE_MESSAGING" => $lang['Private_Messaging'],
    "L_DISABLE_PRIVATE_MESSAGING" => $lang['Disable_privmsg'],
/*****[BEGIN]******************************************
[ Mod:     Welcome PM                         v2.0.0 ]
******************************************************/
    "L_WELCOME_PM" => $lang['Welcome_PM_Admin'],
/*****[END]********************************************
[ Mod:     Welcome PM                         v2.0.0 ]
******************************************************/
/*****[BEGIN]******************************************
 [ Mod:     PM threshold                       v1.0.0 ]
 ******************************************************/
    "L_PM_ALLOW_THRESHOLD" => $lang['pm_allow_threshold'],
    "L_PM_ALLOW_TRHESHOLD_EXPLAIN" => $lang['pm_allow_threshold_explain'],
/*****[END]********************************************
 [ Mod:     PM threshold                       v1.0.0 ]
 ******************************************************/
    "L_INBOX_LIMIT" => $lang['Inbox_limits'],
    "L_SENTBOX_LIMIT" => $lang['Sentbox_limits'],
    "L_SAVEBOX_LIMIT" => $lang['Savebox_limits'],
));

//Data Template Variables
$template->assign_vars(array(
    "S_PRIVMSG_ENABLED" => $privmsg_on,
    "S_PRIVMSG_DISABLED" => $privmsg_off,
/*****[BEGIN]******************************************
[ Mod:     Welcome PM                         v2.0.0 ]
******************************************************/
    "S_WELCOME_PM_ENABLED" => $welcome_pm_yes,
    "S_WELCOME_PM_DISABLED" => $welcome_pm_no,
/*****[END]********************************************
[ Mod:     Welcome PM                         v2.0.0 ]
******************************************************/
/*****[BEGIN]******************************************
 [ Mod:     PM threshold                       v1.0.0 ]
 ******************************************************/
    "PM_ALLOW_THRESHOLD" => $new['pm_allow_threshold'],
/*****[END]********************************************
 [ Mod:     PM threshold                       v1.0.0 ]
 ******************************************************/
    "INBOX_LIMIT" => $new['max_inbox_privmsgs'],
    "SENTBOX_LIMIT" => $new['max_sentbox_privmsgs'],
    "SAVEBOX_LIMIT" => $new['max_savebox_privmsgs'],
 ));
$template->pparse("private_messages");

?>