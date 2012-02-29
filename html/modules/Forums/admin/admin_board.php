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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
        $module['General']['Configuration'] = "$file";
    return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include(NUKE_FORUMS_DIR.'includes/functions_selects.php');
/*****[BEGIN]******************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/
if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_adv_time.' . $phpEx)) )
{
    include_once($phpbb_root_path . 'language/lang_english/lang_adv_time.' . $phpEx);
} else
{
    include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_adv_time.' . $phpEx);
}
/*****[END]********************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/
//
// Pull all config data
//
$sql = "SELECT *
    FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
    message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
/*****[BEGIN]******************************************
 [ Other:  URL Check                           v1.0.0 ]
 ******************************************************/
    if( isset($HTTP_POST_VARS['submit']) ) {
        ValidateURL($HTTP_POST_VARS["server_name"], 1, "Domain Name");
        if(empty($HTTP_POST_VARS["confirm"])) {
            $server_url = $_SERVER["HTTP_HOST"];

            $pos = strpos($_SERVER["PHP_SELF"],"modules");
            $PHP_SELF = substr($_SERVER["PHP_SELF"],0,$pos);
            $pos = strrpos($PHP_SELF,"/");
                if(!empty($pos)) {
                  $server_url .= substr($_SERVER["PHP_SELF"],0,$pos);
                }
                if($HTTP_POST_VARS["server_name"] != $server_url) {
                    echo "<form action='".append_sid("admin_board.$phpEx")."' method='post'>";
                    foreach ($HTTP_POST_VARS as $key => $value) {
                        echo "<input type='hidden' name='".$key."' value='".$value."'>";
                    }
                    echo "<input type='hidden' name='confirm' value='1'>";
                    echo "<br /><br />";
                    echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">";
                    echo "<tr>";
                    echo "<th class=\"thHead\" align=\"center\">".$lang['General_Error']."</th>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td class=\"row1\" width=\"100%\" align=\"center\"><span class=\"gen\">". sprintf($lang['URL_server_error'],$HTTP_POST_VARS["server_name"],$server_url) ."</span></td>";
                    echo "</tr><tr>";
                    echo "<td class=\"row1\" width=\"100%\" align=\"center\"><span class=\"gen\">".$lang['URL_error_confirm']."<br /><br /><input type='submit' value='Yes'></form>";
                    echo "<form action='javascript:history.back()' method='post'><input type='submit' value='No'></form></span></td>";
                    echo "</tr>";
                    echo "</table>";
                    echo "";
                    exit;
                }
        } else {
            array_pop($HTTP_POST_VARS);
        }
    }
/*****[END]********************************************
 [ Other:  URL Check                           v1.0.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Other:  Cookie Check                        v1.0.0 ]
 ******************************************************/
    if( isset($HTTP_POST_VARS['submit']) ) {
        ValidateURL($HTTP_POST_VARS["cookie_domain"], 1, "Cookie Name");
        if(empty($HTTP_POST_VARS["confirm_cookie"])) {
            $server_url = $_SERVER["HTTP_HOST"];

            $pos = strpos($_SERVER["PHP_SELF"],"modules");
            $PHP_SELF = substr($_SERVER["PHP_SELF"],0,$pos);
            $pos = strrpos($PHP_SELF,"/");
            if(!empty($pos)) {
            $server_url .= substr($_SERVER["PHP_SELF"],0,$pos);
            }
            if($HTTP_POST_VARS["cookie_domain"] != $server_url) {
                echo "<form action='".append_sid("admin_board.$phpEx")."' method='post'>";
                foreach ($HTTP_POST_VARS as $key => $value) {
                    echo "<input type='hidden' name='".$key."' value='".$value."'>";
                }
                echo "<input type='hidden' name='confirm_cookie' value='1'>";
                echo "<br /><br />";
                echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">";
                echo "<tr>";
                echo "<th class=\"thHead\" align=\"center\">".$lang['General_Error']."</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<td class=\"row1\" width=\"100%\" align=\"center\"><span class=\"gen\">". sprintf($lang['Cookie_server_error'],$HTTP_POST_VARS["cookie_domain"],$server_url) ."</span></td>";
                echo "</tr><tr>";
                echo "<td class=\"row1\" width=\"100%\" align=\"center\"><span class=\"gen\">".$lang['Cookie_error_confirm']."<br /><br /><input type='submit' value='Yes'></form>";
                echo "<form action='javascript:history.back()' method='post'><input type='submit' value='No'></form></span></td>";
                echo "</tr>";
                echo "</table>";
                echo "";
                exit;
            }
        } else {
            array_pop($HTTP_POST_VARS);
        }
    }
    if( isset($HTTP_POST_VARS['submit']) ) {
        if(empty($HTTP_POST_VARS["confirm_cookie_name"])) {
            $server_url = $_SERVER["HTTP_HOST"];

            $pos = strpos($_SERVER["PHP_SELF"],"modules");
            $PHP_SELF = substr($_SERVER["PHP_SELF"],0,$pos);
            $pos = strrpos($PHP_SELF,"/");
            if(!empty($pos)) {
                $server_url .= substr($_SERVER["PHP_SELF"],0,$pos);
            }
            $pos = strrpos($server_url,".");
            if(!empty($pos)) {
                $server_url = substr($server_url,0,$pos);
            }
            $pos = strrpos($server_url,".");
            if(!empty($pos)) {
                $server_url = substr($server_url,0,$pos);
            }

            if($HTTP_POST_VARS["cookie_name"] == "nukece" || $HTTP_POST_VARS["cookie_name"] == "phpbb2mysql") {
                echo "<form action='".append_sid("admin_board.$phpEx")."' method='post'>";
                foreach ($HTTP_POST_VARS as $key => $value) {
                    echo "<input type='hidden' name='".$key."' value='".$value."'>";
                }
                echo "<input type='hidden' name='confirm_cookie_name' value='1'>";
                echo "<br /><br />";
                echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" class=\"forumline\">";
                echo "<tr>";
                echo "<th class=\"thHead\" align=\"center\">".$lang['General_Error']."</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<td class=\"row1\" width=\"100%\" align=\"center\"><span class=\"gen\">". sprintf($lang['Cookie_name_error'],$HTTP_POST_VARS["cookie_name"],$server_url) ."</span></td>";
                echo "</tr><tr>";
                echo "<td class=\"row1\" width=\"100%\" align=\"center\"><span class=\"gen\">".$lang['Cookie_error_confirm']."<br /><br /><input type='submit' value='Yes'></form>";
                echo "<form action='javascript:history.back()' method='post'><input type='submit' value='No'></form></span></td>";
                echo "</tr>";
                echo "</table>";
                echo "";
                exit;
            }
        } else {
            array_pop($HTTP_POST_VARS);
        }
    }
/*****[END]********************************************
 [ Other:  Cookie Check                        v1.0.0 ]
 ******************************************************/
    while( $row = $db->sql_fetchrow($result) )
    {
        $config_name = $row['config_name'];
        $config_value = $row['config_value'];
        $default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

        $new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

        if ($config_name == 'cookie_name')
        {
            $cookie_name = str_replace('.', '_', $new['cookie_name']);
        }
 		// Attempt to prevent a common mistake with this value,
 		// http:// is the protocol and not part of the server name
 		if ($config_name == 'server_name')
 		{
 			$new['server_name'] = str_replace('http://', '', $new['server_name']);
  		}
		// Attempt to prevent a mistake with this value.
		if ($config_name == 'avatar_path')
		{
			$new['avatar_path'] = trim($new['avatar_path']);
			if (strstr($new['avatar_path'], "\0") || !is_dir($phpbb_root_path . $new['avatar_path']) || !is_writable($phpbb_root_path . $new['avatar_path']))
			{
				$new['avatar_path'] = $default_config['avatar_path'];
			}
		}
        if( isset($HTTP_POST_VARS['submit']) )
        {
            if ($config_name == "default_Theme") {
                $sql = "UPDATE " . $prefix . "_config SET
                     default_Theme = '" . str_replace("\'", "''", $new[$config_name]) . "'";
                 if( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
                }
            } else {
                $sql = "UPDATE " . CONFIG_TABLE . " SET
                    config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
                    WHERE config_name = '$config_name'";
                if( !$db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
                }
            }
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
                $cache->delete('nukeconfig');
                $cache->delete('board_config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        }
    }

    if( isset($HTTP_POST_VARS['submit']) )
    {
        $message = $lang['Config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_board.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);
    }
}

/*****[BEGIN]******************************************
 [ Mod:     Admin DHTML Menu                   v1.0.0 ]
 ******************************************************/
$dhtml_display = ( $new['use_dhtml'] ) ? "style=\"display: none\"" : "";
$dhtml_hand = ( $new['use_dhtml'] ) ? "style=\"cursor:pointer;cursor:hand\"" : "";
$dhtml_onclick  = ( $new['use_dhtml'] ) ? "onclick=" : "";
/*****[END]********************************************
 [ Mod:     Admin DHTML Menu                   v1.0.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Base:     Drop-A-Mod                        v1.0.0 ]
 ******************************************************/
$config_dir = dirname(__FILE__) . "/board_config";

define("BOARD_CONFIG", true);
include($config_dir . "/page_header.php");

    $load_files = 1;

    $dir = opendir($config_dir);

    $dhtml_id = 0;
    while( false !== ($file = @readdir($dir)) )
    {
        if( preg_match("/^board_.*?\." . $phpEx . "$/", $file) )
        {
            $dhtml_id++;
            include($config_dir . "/" . $file);
        }
    }

    @closedir($dir);

    unset($load_files);

include($config_dir . "/page_footer.php");
define("BOARD_CONFIG", false);
/*****[END]********************************************
 [ Base:     Drop-A-Mod                        v1.0.0 ]
 ******************************************************/

include('./page_footer_admin.'.$phpEx);

?>