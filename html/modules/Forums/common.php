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

if (!defined('IN_PHPBB')) {
    die('Hacking attempt');
}

// based on http://forum.mamboserver.com/showthread.php?t=26406 article
$url_denied = array('/bin', '/usr', '/etc', '/boot', '/dev', '/perl', '/initrd', '/lost+found', '/mnt', '/proc', '/root', '/sbin', '/cgi-bin', '/tmp', '/var', 'ps%20', 'wget%20', 'uname%20-a', '/chgrp', 'chgrp%20', '/chown', 'chown%20', '/chmod', 'chmod%20', 'md%20', 'mdir', 'rm%20', 'rmdir%20', 'mv%20', 'tftp%20', 'ftp%20', 'telnet%20', 'ls%20', 'gcc%20-o', 'cc%20', 'cpp%20', 'g++%20', 'python%20', 'tclsh8%20', 'nasm%20', 'perl%20', 'traceroute%20', 'nc%20', 'nmap%20', '%20-display%20', 'lsof%20', '.conf', '.htgroup', '.htpasswd', '.htaccess', '.history', '.bash_history', '/rksh', '/bash', '/zsh', '/csh', '/tcsh', '/rsh', '/ksh', '/icat', 'document.domain(', '/....', '..../', 'cat%20', '/*%0a.pl', '/server-status', 'chunked', '/mod_gzip_status', 'cmdd=', 'path=http://', 'exec', 'passthru', 'cmd', 'fopen', 'exit', 'fwrite', '<script', '/script>', '<?', '?>', 'javascript://', 'img src=', 'phpbb_root_path=', 'sql=', 'delete%20', '%20delete', 'drop%20', '%20drop', 'insert into', 'select%20', '%20select', 'union%20', '%20union', 'union(', 'chr%20', 'chr(', 'http_', '_http', 'php_', '_php', '_global', 'global_', 'global[', '_globals', 'globals_', 'globals[', '_server', 'server_', 'server[', '$_request', '$_get', '$request', '$get',);
$_server = isset($_SERVER) && !empty($_SERVER) ? '_SERVER' : 'HTTP_SERVER_VARS';
$_env = isset($_ENV) && !empty($_ENV) ? '_ENV' : 'HTTP_ENV_VARS';
if ( ($url_request = !empty(${$_server}['QUERY_STRING']) ? ${$_server}['QUERY_STRING'] : (!empty(${$_env}['QUERY_STRING']) ? ${$_env}['QUERY_STRING'] : getenv('QUERY_STRING'))) ) {
    $url_request = preg_replace('/([\s]+)/', '%20', strtolower($url_request));
    $url_checked = preg_replace('/[\n\r]/', '', str_replace($url_denied, '', $url_request));
    if ( $url_request != $url_checked ) {
        die('Hack attempt');
    }
}
unset($_server);
unset($_env);

//error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// The following code (unsetting globals)

// Protect against GLOBALS tricks
if (isset($HTTP_POST_VARS['GLOBALS']) || isset($HTTP_POST_FILES['GLOBALS']) || isset($HTTP_GET_VARS['GLOBALS']) || isset($HTTP_COOKIE_VARS['GLOBALS'])) {
    die("Hacking attempt");
}

// Protect against HTTP_SESSION_VARS tricks
if (isset($HTTP_SESSION_VARS) && !is_array($HTTP_SESSION_VARS)) {
    die("Hacking attempt");
}

if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on') {
    // PHP4+ path
    $not_unset = array('HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS', 'HTTP_POST_FILES', 'phpEx', 'phpbb_root_path', 'name', 'admin', 'nukeuser', 'user', 'no_page_header', 'cookie', 'db', 'prefix', 'cancel');
    //$not_unset = array('HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS', 'HTTP_ENV_VARS', 'HTTP_POST_FILES', 'phpEx', 'phpbb_root_path');

    // Not only will array_merge give a warning if a parameter
    // is not an array, it will actually fail. So we check if
    // HTTP_SESSION_VARS has been initialised.
    if (!isset($HTTP_SESSION_VARS) || !is_array($HTTP_SESSION_VARS)) {
        $HTTP_SESSION_VARS = array();
    }

    // Merge all into one extremely huge array; unset
    // this later
    $input = array_merge($HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_SESSION_VARS, $HTTP_ENV_VARS, $HTTP_POST_FILES);

    unset($input['input']);
    unset($input['not_unset']);

    while (list($var,) = @each($input)) {
        if (!in_array($var, $not_unset)) {
            unset($$var);
        }
    }

    unset($input);
}

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//

if( !get_magic_quotes_gpc() ) {
    if( is_array($HTTP_GET_VARS) ) {
        foreach ($HTTP_GET_VARS as $k => $v) {
            if( is_array($HTTP_GET_VARS[$k]) ) {
                foreach ($HTTP_GET_VARS[$k] as $k2 => $v2) {
                    $HTTP_GET_VARS[$k][$k2] = addslashes($v2);
                }
                @reset($HTTP_GET_VARS[$k]);
            } else {
                $HTTP_GET_VARS[$k] = addslashes($v);
            }
        }
        @reset($HTTP_GET_VARS);
    }

    if( is_array($HTTP_POST_VARS) ) {
        foreach ($HTTP_POST_VARS as $k => $v) {
            if( is_array($HTTP_POST_VARS[$k]) ) {
                foreach ($HTTP_POST_VARS[$k] as $k2 => $v2) {
                    $HTTP_POST_VARS[$k][$k2] = addslashes($v2);
                }
                @reset($HTTP_POST_VARS[$k]);
            } else {
                $HTTP_POST_VARS[$k] = addslashes($v);
            }
        }
        @reset($HTTP_POST_VARS);
    }

    if( is_array($HTTP_COOKIE_VARS) ) {
        foreach ($HTTP_COOKIE_VARS as $k => $v) {
            if( is_array($HTTP_COOKIE_VARS[$k]) ) {
                foreach ($HTTP_COOKIE_VARS[$k] as $k2 => $v2) {
                    $HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
                }
                @reset($HTTP_COOKIE_VARS[$k]);
            } else {
                $HTTP_COOKIE_VARS[$k] = addslashes($v);
            }
        }
        @reset($HTTP_COOKIE_VARS);
    }
}

//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and otherarray values via
// URI params
//
$userdata = array();
$theme = array();
$images = array();
$lang = array();
$nav_links = array();
$dss_seeded = false;
$pc_dateTime = array();
$gen_simple_header = FALSE;

if(!$directory_mode) {
    $directory_mode = 0777;
} else {
    $directory_mode = 0755; 
}
if (!$file_mode) {
    $file_mode = 0666;
} else { 
    $file_mode = 0644; 
}

include_once(NUKE_FORUMS_DIR.'includes/constants.php');
include_once(NUKE_FORUMS_DIR.'includes/template.php');
include_once(NUKE_FORUMS_DIR.'includes/sessions.php');
include_once(NUKE_FORUMS_DIR.'includes/auth.php');
include_once(NUKE_FORUMS_DIR.'includes/functions.php');
include_once(NUKE_DB_DIR.'db.php');

// We do not need this any longer, unset for safety purposes
unset($dbpasswd);

//
// Obtain and encode users IP
//
global $client;
$client = new Client();
$client_ip = $client->getIp();
$user_ip = encode_ip($client_ip);

//
// Setup forum wide options, if this fails
// then we output a CRITICAL_ERROR since
// basic forum information is not available
//

include($phpbb_root_path . 'attach/attachment.php');

//
// Show 'Board is disabled' message if needed.
//
if( $board_config['board_disable'] && !defined("IN_ADMIN") && !defined("IN_LOGIN") && ($board_config['board_disable_adminview'] && $userinfo['user_level'] != 2) ) {
    if ( $board_config['board_disable_msg'] != "" ) {
        message_die(GENERAL_MESSAGE, $board_config['board_disable_msg'], 'Information');
    } else {
        message_die(GENERAL_MESSAGE, 'Board_disable', 'Information');
    }
}

?>