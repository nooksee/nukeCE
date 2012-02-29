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

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

if ($popup != "1"){
    $module_name = basename(dirname(__FILE__));
    require(NUKE_FORUMS_DIR.'nukebb.php');
}
else
{
    $phpbb_root_path = NUKE_FORUMS_DIR;
}

define('IN_PHPBB', true);
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
        $sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
        $sid = '';
}

//
// Set default email variables
//
//$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
//$script_name = ( $script_name != '' ) ? $script_name . '/profile.'.$phpEx : 'profile.'.$phpEx;
$script_name = 'modules.php?name=Profile';
$server_name = trim($board_config['server_name']);
$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

$server_url = $server_protocol . $server_name . $server_port . $script_name;

// -----------------------
// Page specific functions
//
function gen_rand_string($hash)
{
 	$rand_str = dss_rand();
  
 	return ( $hash ) ? md5($rand_str) : substr($rand_str, 0, 8);
}
//
// End page specific functions
// ---------------------------

//
// Start of program proper
//

        $mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
        $mode = htmlspecialchars($mode);

        $check_num = ( isset($HTTP_GET_VARS['check_num']) ) ? $HTTP_GET_VARS['check_num'] : $HTTP_POST_VARS['check_num'];

        if (!$mode) {
                if ( !is_user() )
                {
                    $mode = "register";
                    redirect('modules.php?name=Your_Account&op=new_user');
                    exit;
                } else {
                    $mode = "editprofile";
                    include(NUKE_FORUMS_DIR.'includes/usercp_register.php');
                    exit;
                }
        }
        if ( $mode == 'viewprofile' )
        {
                include(NUKE_FORUMS_DIR.'includes/usercp_viewprofile.php');
                exit;
        } else if ( $mode == 'register' && ($check_num || isset($HTTP_POST_VARS['submit']))) {
                include(NUKE_FORUMS_DIR.'includes/usercp_register.php');
                exit;
        } else if ( $mode == 'register' && !$check_num) {
                redirect('modules.php?name=Your_Account&op=new_user');
        } else if ( $mode == 'editprofile')
        {
                if ( !is_user() && $mode == 'editprofile' )
                {
                        $header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", $_SERVER["SERVER_SOFTWARE"]) ) ? "Refresh: 0; URL=" : "Location: ";
                        redirect(append_sid("login.$phpEx?redirect=profile.$phpEx&mode=editprofile", true));
                        exit;
                }

                include(NUKE_FORUMS_DIR.'includes/usercp_register.php');
                exit;
        }
/*****[BEGIN]******************************************
 [ Mod:     Signature Editor/Preview Deluxe    v1.0.0 ]
 ******************************************************/
        else if ( $mode == 'signature' )
        {
            if ( !is_user() && $mode == 'signature' )
            {
                $header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", $_SERVER("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
                redirect(append_sid("login.$phpEx?redirect=profile.$phpEx&mode=signature", true));
                exit;
            }

            include(NUKE_FORUMS_DIR.'includes/usercp_signature.'.$phpEx);
            exit;
        }
/*****[END]********************************************
 [ Mod:     Signature Editor/Preview Deluxe    v1.0.0 ]
 ******************************************************/
        else if ( $mode == 'confirm' )
        {
            // Visual Confirmation
            if ( is_user() )
            {
                exit;
            }

            exit;
        }
        else if ( $mode == 'sendpassword' )
        {
            include(NUKE_FORUMS_DIR.'includes/usercp_sendpasswd.'.$phpEx);
            exit;
        }
        else if ( $mode == 'activate' )
        {
            include(NUKE_FORUMS_DIR.'includes/usercp_activate.'.$phpEx);
            exit;
        }
        else if ( $mode == 'email' )
        {
            include(NUKE_FORUMS_DIR.'includes/usercp_email.'.$phpEx);
            exit;
        }
        include(NUKE_FORUMS_DIR.'includes/usercp_register.'.$phpEx);

?>