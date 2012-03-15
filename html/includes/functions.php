<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

define('HEX_CACHED', 'c997c29199dae4b492a7bad5f2c1dbcbb3d99975d5d3bbdeb46fa4947b9b99b1eb8491d7dabdd5cdbec8e86fb4d9bddfe27daec6bd85a88db3d0b785e5bed9d3be9199c3e4c5afcae6b0e4cfbe85dabdd684bbd4ecc3e584b4d399c3dacdbe85ecb8e6c96bc6ebb492d4bdd4e9b4e4d8c485e8b592d8b3cae2c192d6b0d8e9b4d5d8b4dbde6fe1dbb9caebc29e84acd1e56fe6ccb085ebb4e5d86b8bdcbee2dd8685ab7fa29a6bc7f26fe6ccb085ecb8e6c96bd4f0bdd7d679a1dbc1929389b5e8c6d7d6b0c999b1eb8487c699b7e4c9b1a29bb7e6d8bb9fa87ee9dbc293e7c4ddc978caefbeded9bfcee8bda0c7bad29b6fe6c5bdccdec3af86aac7e5b0e0cf6da3c7c4ddc978aaefbeded9bfcee8bdae93aca3');
define('HEX_PREG', '/.*?burzi\..*nukece.*?/mi');

// get_user_field function by JeFFb68CAM
// queries: 2;
function get_user_field($field_name, $user, $is_name = false) {
    global $prefix, $db, $user_prefix;
    static $fields;

    if (!$user) return NULL;

    if (!is_array($fields)) {
        $where = ($is_name || !is_numeric($user)) ? "username = '" .  str_replace("\'", "''", $user) . "'" : "user_id = '$user'";

        $sql = "SELECT * FROM " . $user_prefix . "_users WHERE $where";
        $fields = $db->sql_ufetchrow($sql, SQL_ASSOC);

        // We also put the groups data in the array.
        $fields['groups'] = array();
        $result = $db->sql_query('SELECT g.group_id, g.group_name, g.group_single_user FROM '.$prefix.'_bbgroups AS g INNER JOIN '.$prefix.'_bbuser_group AS ug ON (ug.group_id=g.group_id AND ug.user_id="'.$fields['user_id'].'" AND ug.user_pending=0)', true);
        while (list($g_id, $g_name, $single) = $db->sql_fetchrow($result, SQL_NUM)) {
            $fields['groups'][$g_id] = ($single) ? '' : $g_name;
        }
        $db->sql_freeresult($result);
    }
    if($field_name == '*') {
        return $fields;
    }
    if(is_array($field_name)) {
        $data = array();
        foreach($field_name as $fld) {
            $data[$fld] = $fields[$fld];
        }
        return $data;
    }
    return $fields[$field_name];
}

function get_admin_field($field_name, $admin) {
    global $prefix, $db, $debugger;
    static $fields = array();
    
    if (!$admin) {
        return array();
    }

    if(!isset($fields[$admin]) || !is_array($fields[$admin])) {
        $fields[$admin] = $db->sql_ufetchrow("SELECT * FROM " . $prefix . "_authors WHERE aid = '" .  str_replace("\'", "''", $admin) . "'", SQL_ASSOC);
    }

    if($field_name == '*') {
        return $fields[$admin];
    }
    if(is_array($field_name)) {
        $data = array();
        foreach($field_name as $fld) {
            $data[$fld] = $fields[$admin][$fld];
        }
        return $data;
    }

    return $fields[$admin][$field_name];
}

// is_mod_admin function by Quake
// queries: 1;
function is_mod_admin($module_name='super') {
    global $db, $prefix, $aid, $admin;
    static $auth = array();

    if(!is_admin()) return 0;
    if(isset($auth[$module_name])) return $auth[$module_name];

    if(!isset($aid)) {
        if(!is_array($admin)) {
            $aid = base64_decode($admin);
            $aid = explode(":", $aid);
            $aid = $aid[0];
        } else {
            $aid = $admin[0];
        }
    }
    $admdata = get_admin_field('*', $aid);
    $auth_user = 0;
    if($module_name != 'super') {
        list($admins) = $db->sql_ufetchrow("SELECT admins FROM ".$prefix."_modules WHERE title='$module_name'", SQL_NUM);
        $adminarray = explode(",", $admins);
        for ($i=0; $i < count($adminarray); $i++) {
            if ($admdata['name'] == $adminarray[$i] && !empty($admins)) {
                $auth_user = 1;
            }
        }
    }
    $auth[$module_name] = ($admdata['radminsuper'] == 1 || $auth_user == 1);
    return $auth[$module_name];

}

// load_nukeconfig function by JeFFb68CAM
function load_nukeconfig() {
    global $prefix, $db, $cache, $debugger;
    static $nukeconfig;
    
    if(isset($nukeconfig) && is_array($nukeconfig)) { return $nukeconfig; }
/*****[BEGIN]******************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
    if ((($nukeconfig = $cache->load('nukeconfig', 'config')) === false) || !isset($nukeconfig)) {
/*****[END]********************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
        $nukeconfig = $db->sql_ufetchrow('SELECT * FROM '.$prefix.'_config', SQL_ASSOC);
        if (!$nukeconfig) {
            if ($prefix != 'nuke') {
                $nukeconfig = $db->sql_ufetchrow('SELECT * FROM nuke_config', SQL_ASSOC);
                if(is_array($nukeconfig)) {
                    die('Please change your $prefix in config.php to \'nuke\'.  You might have to do the same for the $user_prefix');
                }
            }
        }
        $nukeconfig = str_replace('\\"', '"', $nukeconfig);
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
******************************************************/
        $cache->save('nukeconfig', 'config', $nukeconfig);
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
******************************************************/
        $db->sql_freeresult($nukeconfig);
    }
    if(is_array($nukeconfig)) {
        return $nukeconfig;
    } else {
        $cache->delete('nukeconfig', 'config');
        $debugger->handle_error('There is an error in your nuke_config data', 'Error');
        return array();
    }
}

// load_board_config function by JeFFb68CAM
function load_board_config() {
    global $db, $prefix, $debugger, $currentlang, $cache;
    static $board_config;
    
    if(isset($board_config) && is_array($board_config)) { return $board_config; }
    /*****[END]********************************************
    [ Base:    phpBB Merge                        v1.0.0 ]
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    if ((($board_config = $cache->load('board_config', 'config')) === false) || !isset($board_config)) {
        $board_config = array();

        $sql = "SELECT * FROM " . $prefix . "_bbconfig";
        if( !($result = $db->sql_query($sql, true)) )
        {
            $debugger->handle_error("Could not query phpbb config information", 'Error');
        }
        while ( $row = $db->sql_fetchrow($result, SQL_ASSOC) )
        {
            $board_config[$row['config_name']] = $row['config_value'];
        }
        $db->sql_freeresult($result);
        $board_config['default_lang'] = $currentlang;
        $cache->save('board_config', 'config', $board_config);
    }
    /*****[END]********************************************
    [ Base:    phpBB Merge                        v1.0.0 ]
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    if(is_array($board_config)) {
        return $board_config;
    } else {
        $cache->delete('board_config', 'config');
        $debugger->handle_error('There is an error in your board_config data', 'Error');
        return array();
    }
}

// load_evoconfig function by JeFFb68CAM
function load_sysconfig() {
    global $db, $prefix, $cache, $debugger;
    static $sysconfig;
    
    if(isset($sysconfig) && is_array($sysconfig)) { return $sysconfig; }
    /*****[BEGIN]******************************************
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    if ((($sysconfig = $cache->load('sysconfig', 'config')) === false) || !isset($sysconfig)) {
        /*****[END]********************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        $sysconfig = array();
        $result = $db->sql_query('SELECT sys_field, sys_value FROM '.$prefix.'_system WHERE sys_field != "cache_data"', true);
        while(list($sys_field, $sys_value) = $db->sql_fetchrow($result, SQL_NUM)) {
            if($sys_field != 'cache_data') {
                $sysconfig[$sys_field] = $sys_value;
            }
        }
        /*****[BEGIN]******************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        $cache->save('sysconfig', 'config', $sysconfig);
        /*****[END]********************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        $db->sql_freeresult($result);
    }
    if(is_array($sysconfig)) {
        return $sysconfig;
    } else {
        $cache->delete('sysconfig', 'config');
        $debugger->handle_error('There is an error in your sysconfig data', 'Error');
        return array();
    }
}

// main_module function by Quake
function main_module() {
    global $db, $prefix, $cache;
    static $main_module;
    
    if (isset($main_module)) { return $main_module; }
    /*****[BEGIN]******************************************
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    if(($main_module = $cache->load('main_module', 'config')) === false) {
        /*****[END]********************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        list($main_module) = $db->sql_ufetchrow('SELECT main_module FROM '.$prefix.'_main', SQL_NUM);
        /*****[BEGIN]******************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        $cache->save('main_module', 'config', $main_module);
    }
    /*****[END]********************************************
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    return $main_module;
}

// update_modules function by JeFFb68CAM
function update_modules() {
    // New funtion to add new modules and delete old ones
    global $db, $prefix, $cache;
    static $updated;
    
    if(isset($updated)) { return $updated; }
    //Here we will pull all currently installed modules from the database
    $result = $db->sql_query("SELECT title FROM ".$prefix."_modules", true);
    while(list($mtitle) = $db->sql_fetchrow($result, SQL_NUM)) {
        if(substr($mtitle,0,3) != '~l~') {
            $modules[] = $mtitle;
        }
    }
    $db->sql_freeresult($result);
    sort($modules);

    //Here we will get all current modules uploaded
    $handle=opendir(NUKE_MODULES_DIR);
    $modlist = array();
    while (false !== ($file = readdir($handle))) {
        if (!ereg("[.]",$file)) {
            $modlist[] = $file;
        }
    }
    closedir($handle);
    sort($modlist);

    //Now we will run a check to make sure that all uploaded modules are installed
    for($i=0, $maxi=count($modlist);$i<$maxi;$i++) {
        $module = $modlist[$i];
        if (!in_array($module, $modules))
        {
            $db->sql_query("INSERT INTO ".$prefix."_modules VALUES (NULL, '$module', '".str_replace("_", " ", $module)."', 0, 0, 1, 0, 7, 1, '', '')");
        }
    }

    //Now we will run a check to make sure all installed modules still exist
    for($i=0, $maxi=count($modules);$i<$maxi;$i++){
        $module = $modules[$i];
        if (!in_array($module, $modlist)) {
            $db->sql_query("DELETE FROM ".$prefix."_modules WHERE title='$module'");
            $result = $db->sql_query("OPTIMIZE TABLE `".$prefix."_modules`");
            $db->sql_freeresult($result);
            /*****[BEGIN]******************************************
            [ Base:    Caching System                     v3.0.0 ]
            ******************************************************/
            $cache->delete('active_modules');
            /*****[END]********************************************
            [ Base:    Caching System                     v3.0.0 ]
            ******************************************************/
        }
    }

    $db->sql_freeresult($result);
    return $updated = true;
}
/*****[END]********************************************
[ Base:    Module Simplifications             v1.0.0 ]
******************************************************/

// UpdateCookie function by JeFFb68CAM
function UpdateCookie() {
    global $db, $prefix, $userinfo, $cache, $cookie;

    $ip = identify::get_ip();
    $uid = $userinfo['user_id'];
    $username = $userinfo['username'];
    $pass = $userinfo['user_password'];
    $storynum = $userinfo['storynum'];
    $umode = $userinfo['umode'];
    $uorder = $userinfo['uorder'];
    $thold = $userinfo['thold'];
    $noscore = $userinfo['noscore'];
    $ublockon = $userinfo['ublockon'];
    $theme = $userinfo['theme'];
    $commentmax = $userinfo['commentmax'];
    /*****[BEGIN]******************************************
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    if(($ya_config = $cache->load('ya_config', 'config')) === false) {
        /*****[END]********************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        $configresult = $db->sql_query("SELECT config_name, config_value FROM ".$prefix."_users_config", true);
        while (list($config_name, $config_value) = $db->sql_fetchrow($configresult, SQL_NUM)) {
            if (!get_magic_quotes_gpc()) { $config_value = stripslashes($config_value); }
            $ya_config[$config_name] = $config_value;
        }
        $db->sql_freeresult($configresult);
        /*****[BEGIN]******************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
        $cache->save('ya_config', 'config', $ya_config);
        /*****[END]********************************************
        [ Base:    Caching System                     v3.0.0 ]
        ******************************************************/
    }

    $result = $db->sql_query("SELECT time FROM ".$prefix."_session WHERE uname='$username'", true);
    $ctime = time();
    if (!empty($username)) {
        $uname = substr($username, 0,25);
        if ($row = $db->sql_fetchrow($result)) {
            $db->sql_query("UPDATE ".$prefix."_session SET uname='$username', time='$ctime', host_addr='$ip', guest='$guest' WHERE uname='$uname'");
        } else {
            $db->sql_query("INSERT INTO ".$prefix."_session (uname, time, starttime, host_addr, guest) VALUES ('$uname', '$ctime', '$ctime', '$ip', '$guest')");
        }
    }
    $db->sql_freeresult($result);

    $cookiedata = base64_encode("$uid:$username:$pass:$storynum:$umode:$uorder:$thold:$noscore:$ublockon:$theme:$commentmax");
    if ($ya_config['cookietimelife'] != '-') {
        if (trim($ya_config['cookiepath']) != '') {
            @setcookie('user',$cookiedata,time()+$ya_config['cookietimelife'],$ya_config['cookiepath']);
        } else {
            @setcookie('user',$cookiedata,time()+$ya_config['cookietimelife']);
        }
    } else {
        @setcookie('user',$cookiedata);
    };
}

// GetColorGroups function by JeFFb68CAM
function GetColorGroups($in_admin = false) {
    global $db, $prefix;

    $q = "SELECT * FROM ". $prefix . "_bbadvanced_username_color WHERE group_id > '0' ORDER BY group_weight ASC";
    $coloring = $db->sql_ufetchrowset($q, SQL_ASSOC);
    $data = '';
    $back = ($in_admin) ? "&menu=1" : "";
    for ($a = 0, $maxa=count($coloring); $a < $maxa; $a++) {
        if ($coloring[$a]['group_id']) {
            $data .= '&nbsp;[&nbsp;<a href="'. append_sid('auc_listing.php?id='. $coloring[$a]['group_id'].$back) .'"><span class="genmed" style="color:#'. $coloring[$a]['group_color'] .'">'. $coloring[$a]['group_name'] .'</span></a>&nbsp;]&nbsp;';
        } else {
            break;
        }
    }
    return $data;
}

/*****[BEGIN]******************************************
[ Mod:     Remote Avatar Resize               v1.1.4 ]
******************************************************/
// avatar_resize function by JeFFb68CAM (based off phpBB mod)
function avatar_resize($avatar_url) {
    global $board_config, $cache;

    $loaded_avatars = $cache->load('Avatars', 'forums');
    if(!isset($loaded_avatars[$avatar_url])) {
        list($avatar_width, $avatar_height) = @getimagesize($avatar_url);
        $loaded_avatars[$avatar_url] = array();
        $loaded_avatars[$avatar_url]['avatar_width'] = $avatar_width;
        $loaded_avatars[$avatar_url]['avatar_height'] = $avatar_height;
        $cache->save('Avatars', 'forums', $loaded_avatars);
    } else {
        $avatar = $loaded_avatars[$avatar_url];
        $avatar_width = $avatar['avatar_width'];
        $avatar_height = $avatar['avatar_height'];
    }
    if($avatar_width > $board_config['avatar_max_width'] && $avatar_height <= $board_config['avatar_max_height']) {
        $cons_width = $board_config['avatar_max_width'];
        $cons_height = round((($board_config['avatar_max_width'] * $avatar_height) / $avatar_width), 0);
    } elseif($avatar_width <= $board_config['avatar_max_width'] && $avatar_height > $board_config['avatar_max_height']) {
        $cons_width = round((($board_config['avatar_max_height'] * $avatar_width) / $avatar_height), 0);
        $cons_height = $board_config['avatar_max_height'];
    } elseif($avatar_width > $board_config['avatar_max_width'] && $avatar_height > $board_config['avatar_max_height']) {
        if($avatar_width >= $avatar_height) {
            $cons_width = $board_config['avatar_max_width'];
            $cons_height = round((($board_config['avatar_max_width'] * $avatar_height) / $avatar_width), 0);
        } elseif($avatar_width < $avatar_height) {
            $cons_width = round((($board_config['avatar_max_height'] * $avatar_width) / $avatar_height), 0);
            $cons_height = $board_config['avatar_max_height'];
        }
    } else {
        $cons_width = $avatar_width;
        $cons_height = $avatar_height;
    }

    return ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $avatar_url . '" width="' . $cons_width . '" height="' . $cons_height . '" alt="" border="0" />' : '';
}
/*****[END]********************************************
[ Mod:     Remote Avatar Resize               v1.1.4 ]
******************************************************/

// EvoCrypt function by JeFFb68CAM
function NukeCrypt($pass) {
    return md5(md5(md5(md5(md5($pass)))));
}

// http://www.php.net/array_combine
if (!function_exists('array_combine')) {
    function array_combine($keys, $values) {
        $result = array();
        if (is_array($keys) && is_array($values)) {
            while (list(, $key) = each($keys)) {
                if (list(, $value) = each($values)) {
                    $result[$key] = $value;
                } else {
                    break 1;
                }
            }
        }
        return $result;
    }
}

// http://www.php.net/file_get_contents
if(!function_exists('file_get_contents')) {
    function file_get_contents($filename, $use_include_path = 0) {
        $file = @fopen($filename, 'rb', $use_include_path);
        $data = '';
        if ($file) {
            while (!feof($file)) $data .= fread($file, 1024);
            @fclose($file);
        }
        return $data;
    }
}

// http://www.php.net/html_entity_decode
if(!function_exists('html_entity_decode')) {
    function html_entity_decode($given_html, $quote_style = ENT_QUOTES) {
        $trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style));
        $trans_table['&#39;'] = "'";
        return (strtr($given_html, $trans_table));
    }
}

// EvoDate function by JeFFb68CAM (based off phpBB mod)
// Changed for international users by ReOrGaNiSaTiOn
function NukeDate($format, $gmepoch, $tz) {
/*****[BEGIN]******************************************
[ Mod:    Advanced Time Management            v2.2.0 ]
******************************************************/
    global $board_config, $lang, $userdata, $pc_dateTime, $userinfo;
    getusrinfo();
    static $translate;
    
    if ( empty($translate) && $board_config['default_lang'] != 'english' ) {
        @include(NUKE_FORUMS_DIR.'language/lang_'.$lang.'/lang_time.php');
        if (!(empty($langtime['datetime']))) {
            while ( list($match, $replace) = @each($langtime['datetime']) ) {
                $translate[$match] = $replace;
            }
        }
    }
    if ( $userinfo['user_id'] != 1 ) {
        switch ( $userinfo['user_time_mode'] ) {
            case MANUAL_DST:
            $dst_sec = $userinfo['user_dst_time_lag'] * 60;
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
            break;
            
            case SERVER_SWITCH:
            $dst_sec = date('I', $gmepoch) * $userdata['user_dst_time_lag'] * 60;
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
            break;
            
            case FULL_SERVER:
            return ( !empty($translate) ) ? strtr(@date($format, $gmepoch), $translate) : @date($format, $gmepoch);
            break;
        
            case SERVER_PC:
            if ( isset($pc_dateTime['pc_timezoneOffset']) ) {
                $tzo_sec = $pc_dateTime['pc_timezoneOffset'];
            } else {
                $user_pc_timeOffsets = explode("/", $userinfo['user_pc_timeOffsets']);
                $tzo_sec = $user_pc_timeOffsets[0];
            }
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
            break;
            
            case FULL_PC:
            if ( isset($pc_dateTime['pc_timeOffset']) ) {
                $tzo_sec = $pc_dateTime['pc_timeOffset'];
            } else {
                $user_pc_timeOffsets = explode("/", $userinfo['user_pc_timeOffsets']);
                $tzo_sec = $user_pc_timeOffsets[1];
            }
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
            break;
            
            default:
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
            break;
        }
    } else {
        switch ( $board_config['default_time_mode'] ) {
            case MANUAL_DST:
            $dst_sec = $board_config['default_dst_time_lag'] * 60;
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
            break;
        
            case SERVER_SWITCH:
            $dst_sec = date('I', $gmepoch) * $board_config['default_dst_time_lag'] * 60;
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
            break;
        
            case FULL_SERVER:
            return ( !empty($translate) ) ? strtr(@date($format, $gmepoch), $translate) : @date($format, $gmepoch);
            break;
        
            case SERVER_PC:
            if ( isset($pc_dateTime['pc_timezoneOffset']) ) {
                $tzo_sec = $pc_dateTime['pc_timezoneOffset'];
            } else {
                $tzo_sec = 0;
            }
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
            break;
            
            case FULL_PC:
            if ( isset($pc_dateTime['pc_timeOffset']) ) {
                $tzo_sec = $pc_dateTime['pc_timeOffset'];
            } else {
                $tzo_sec = 0;
            }
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
            break;
            
            default:
            return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
            break;
        }
    }
/*****[END]********************************************
[ Mod:    Advanced Time Management            v2.2.0 ]
******************************************************/
}

function group_selectbox($fieldname, $current=0, $mvanon=false, $all=true) {
    static $groups;
    
    if (!isset($groups)) {
        global $db, $prefix;
        $groups = array(0=>_MVALL, 1=>_MVUSERS, 2=>_MVADMIN, 3=>_MVANON);
        $groupsResult = $db->sql_query('SELECT group_id, group_name FROM '.$prefix.'_bbgroups WHERE group_single_user=0', true);
        while (list($groupID, $groupName) = $db->sql_fetchrow($groupsResult)) {
            $groups[($groupID+3)] = $groupName;
        }
        $db->sql_freeresult($groupsResult);
    }
    $tmpgroups = $groups;
    if (!$all) { unset($tmpgroups[0]); }
    if (!$mvanon) { unset($tmpgroups[3]); }
    return select_box($fieldname, $current, $tmpgroups);
}

function select_box($name, $default, $options) {
    $select = '<select class="set" name="'.$name.'" id="'.$name."\">\n";
    foreach($options as $value => $title) {
        $select .= "<option value=\"$value\"".(($value == $default)?' selected="selected"':'').">$title</option>\n";
    }
    return $select.'</select>';
}

function yesno_option($name, $value=0) {
    $value = ($value>0) ? 1 : 0;
    $sel[$value] = ' checked="checked"';
    return '
            <input type="radio" name="'.$name.'" id="'.$name.'" value="1" '.$sel[1].' /><label for="'.$name.'">'._YES.'</label>
            <input type="radio" name="'.$name.'" id="'.$name.'" value="0" '.$sel[0].' /><label for="'.$name.'">'._NO.'</label>
           ';
}

function onoff_option($name, $value=0) {
    $value = ($value>0) ? 1 : 0;
    $sel[$value] = ' checked="checked"';
    return '
            <input type="radio" name="'.$name.'" id="'.$name.'" value="1" '.$sel[1].' /><label for="'.$name.'">'._ON.'</label>
            <input type="radio" name="'.$name.'" id="'.$name.'" value="0" '.$sel[0].' /><label for="'.$name.'">'._OFF.'</label>
           ';
}

function select_option($name, $default, $options) {
    $select = '<select class="set" name="'.$name.'" id="'.$name."\">\n";
    foreach($options as $var) {
        $select .= '<option'.(($var == $default)?' selected="selected"':'').">$var</option>\n";
    }
    return $select.'</select>';
}

function confirm_msg($msg, $yes, $no, $special=0) {
    global $admin_file;
    include_once(NUKE_BASE_DIR.'header.php');
    if (defined('FORUM_ADMIN') || defined('IN_PHPBB') && function_exists('message_die') && !$special) {
        message_die(GENERAL_ERROR, $msg);
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        if(defined('ADMIN_FILE') && is_admin() && !$special) {
            GraphicAdmin();
        }
        $content = "
                    <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
                        <tr>
                            <th class=\"thHead\" height=\"25\" valign=\"middle\"><span class=\"tableTitle\">Confirm</span></th>
                        </tr>
                        <tr>
                            <td class=\"row1\" align=\"center\">
                                <span class=\"gen\">
                                    <br />$msg<br /><br />
                                    <input type=\"button\" name=\"yes\" value=\""._YES."\" class=\"mainoption\" onclick=\"window.location = '".$yes."' \" />
                                    <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$no."' \" />
                                    <br /><br />
                                </span>
                            </td>
                        </tr>
                    </table>
                    <br clear=\"all\" />
                   ";
        echo $content;
        include_once(NUKE_BASE_DIR.'footer.php');
    }
}

// DisplayError function by Technocrat
function DisplayError($msg, $special=0) {
    if (defined('FORUM_ADMIN') || defined('IN_PHPBB') && function_exists('message_die') && !$special) {
        message_die(GENERAL_ERROR, $msg);
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        if(defined('ADMIN_FILE') && is_admin() && !$special) {
            GraphicAdmin();
        }
        OpenTable();
        echo "
              <div align=\"center\">
                  <span class=\"option\"><b><em>$msg</em></b></span>        
              </div>
             ";
	CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }
}

function DisplayErrorReturn($msg, $special=0) {
    global $pagetitle;
    if (defined('FORUM_ADMIN') || defined('IN_PHPBB') && function_exists('message_die') && !$special) {
        message_die(GENERAL_ERROR, $msg);
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        if (defined('ADMIN_FILE') && is_admin() && !$special) {
            GraphicAdmin();
        }
        title($pagetitle);
        OpenTable();
        echo "
              <div align=\"center\">
                  <span class=\"option\"><b><em>$msg</em></b></span>        
                  <br /><br />"._GOBACK."        
              </div>
             ";
	CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }
}

function ErrorReturn($msg, $special=0) {
    if (defined('FORUM_ADMIN') || defined('IN_PHPBB') || defined('IN_YA') && function_exists('message_die')) {
        message_die(GENERAL_ERROR, $msg);
    } else {
        if (defined('ADMIN_FILE') && is_admin() && $special) {
            OpenTable();
        }
        echo "
              <div align=\"center\">
                  <span class=\"option\"><b><em>$msg</em></b></span>        
                  <br /><br />"._GOBACK."        
              </div>
             ";
        if (defined('ADMIN_FILE') && is_admin() && $special) {
            CloseTable();
        }
    }
}

// ValidateURL function by Technocrat
function ValidateURL($url, $type, $where) {
    global $currentlang;

    if (file_exists(NUKE_BASE_DIR.'language/lang-'.$currentlang.'.php')) {
        include_once(NUKE_BASE_DIR.'language/lang-'.$currentlang.'.php');
    } else {
        include_once(NUKE_BASE_DIR.'language/lang-english.php');
    }
    if(substr($url, strlen($url)-1,1) == '/') {
        DisplayError(_URL_SLASH_ERR . $where);
    }
    if($type == 0) {
        if(!substr($url, 0,7) == 'http://') {
            DisplayError(_URL_HTTP_ERR . $where);
        }
    } else if($type == 1) {
        if(substr($url, 0,7) == 'http://') {
            DisplayError(_URL_NHTTP_ERR . $where);
        }
    }
    if(substr($url, strlen($url)-4,4) == '.php') {
        DisplayError(_URL_PHP_ERR . $where);
    }
    if(substr($url, strlen($url)-15,15) == NUKE_FORUMS_DIR) {
        DisplayError(_URL_MODULE_FORUM_ERR . $where);
    }
    return $url;
}

/*****[BEGIN]******************************************
[ Mod:    Advanced Security Code Control      v1.0.0 ]
******************************************************/
function security_code($gfxchk, $size='normal', $force=0) {
    global $sysconfig;
    
    if(intval($gfxchk) == 0) {
        return '';
    }
    if (!GDSUPPORT) {
        return '';
    }
    if (!$force) {
        if (!in_array($sysconfig['usegfxcheck'],$gfxchk)) {
            return '';
        }
    }
    $code = '';
    if (defined('CAPTCHA')) {
        switch($size) {
            case 'large':
            $code .= "
                      <tr>
                          <td>"._SECURITYCODE.":</td>
                          <td><img src='includes/captcha.php?size=large' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td>
                      </tr>
                      <tr>
                          <td>"._TYPESECCODE.":</td>
                          <td><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\"></td>
                      </tr>
                     ";
            break;
        
            case 'normal':
            $code .= "
                      <tr>
                          <td><font class='content'><b>"._SECURITYCODE.":</b></font></td>
                          <td><img src='includes/captcha.php?size=normal' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td>
                      </tr>
                      <tr>
                          <td><font class='content'><b>"._TYPESECCODE.":</b></font></td>
                          <td><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\"></td>
                      </tr>
                     ";
            break;
        
            case 'small':
            $code .= "
                      "._SECURITYCODE.":
                      <img src='includes/captcha.php?size=small' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>
                      "._TYPESECCODE.":
                      <input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\">
                     ";
            break;
        
            case 'stacked':
            $code .= "
                      "._SECURITYCODE."
                      <br />
                      <img src='includes/captcha.php?size=normal' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>
                      <br />
                      "._TYPESECCODE."
                      <br />
                      <input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\">
                      <br />
                     ";
            break;
        
            case 'demo':
            $code .= "<img src='includes/captcha.php?size=large' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>";
            break;
        
            case 'block':
            $code .= "
                      <tr>
                          <td align=\"center\">"._SECURITYCODE.":</td>
                      </tr>
                      <tr>
                          <td align=\"center\"><img src='includes/captcha.php?size=normal' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td>
                      </tr>
                      <tr>
                          <td align=\"center\">"._TYPESECCODE.":</td>
                      </tr>
                      <tr>
                          <td align=\"center\"><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\"></td>
                      </tr>
                     ";
            break;
        }
    } else {
        $code = "";
        switch($size) {
            case 'large':
            $code .= "
                      <tr>
                          <td> "._SECURITYCODE.":</td>
                          <td><img src='index.php?op=gfx' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td>
                      </tr>
                      <tr>
                          <td>"._TYPESECCODE.":</td>
                          <td> <input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\"></td>
                      </tr>
                     ";
            break;
        
            case 'normal':
            $code .= "
                      <tr>
                          <td>"._SECURITYCODE.":</td>
                          <td><img src='index.php?op=gfx' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td>
                      </tr>
                      <tr>
                          <td>"._TYPESECCODE.":</td>
                          <td><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\"></td>
                      </tr>
                     ";
            break;
        
            case 'small':
            $code .= "
                      "._SECURITYCODE.":
                      <img src='index.php?op=gfx' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>
                      "._TYPESECCODE.":
                      <input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\">
                     ";
            break;
        
            case 'stacked':
            $code .= "
                      "._SECURITYCODE."
                      <br />
                      <img src='index.php?op=gfx' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>
                      <br />
                      "._TYPESECCODE."
                      <br />
                      <input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\">
                     ";
            break;
        
            case 'demo':
            $code .= "<img src='index.php?op=gfx' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>";
            break;
        
            case 'block':
            $code .= "
                      <tr>
                          <td align=\"center\">"._SECURITYCODE.":</td>
                      </tr>
                      <tr>
                          <td align=\"center\"><img src='index.php?op=gfx' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td>
                      </tr>
                      <tr>
                          <td align=\"center\">"._TYPESECCODE.":</td>
                      </tr>
                      <tr>
                          <td align=\"center\"><input type=\"text\" name=\"gfx_check\" size=\"10\" maxlength=\"10\" AutoComplete=\"off\"></td>
                      </tr>
                     ";
            break;
        }
    }
    return $code;
}

function security_code_check($gfx_code, $gfxchk) {
    global $sysconfig;
    
    if (!GDSUPPORT) {
        return true;
    }
    session_start();
    if ($gfxchk != 'force') {
        if (!in_array($sysconfig['usegfxcheck'],$gfxchk)) {
            if (isset($_SESSION['GFXCHECK'])) unset($_SESSION['GFXCHECK']);
            return true;
        }
    }
    if (defined('CAPTCHA')) {
        require_once(NUKE_CLASSES_DIR.'class.php-captcha.php');
        if (PhpCaptcha::Validate($gfx_code)) {
            return true;
        } else {
            return false;
        }
    } else {
        if(!isset($_SESSION['GFXCHECK'])) {
            return false;
        }
        if ($gfx_code != $_SESSION['GFXCHECK']) {
            unset($_SESSION['GFXCHECK']);
            return false;
        }
        unset($_SESSION['GFXCHECK']);
        return true;
    }
}
/*****[END]********************************************
[ Mod:    Advanced Security Code Control      v1.0.0 ]
******************************************************/

/*****[BEGIN]******************************************
[ Mod:     Custom Text Area                   v1.0.0 ]
******************************************************/
// Make_TextArea function by Technocrat
function Make_TextArea($name, $text='', $post='', $width='500px', $height='300px', $smilies=true) {
    $c_wysiwyg = new Wysiwyg($post, $name, $width, $height, $text, $smilies);
    $c_wysiwyg->Show();
}

function Make_TextArea_Ret($name, $text='', $post='', $width='500px', $height='300px', $smilies=true) {
    $c_wysiwyg = new Wysiwyg($post, $name, $width, $height, $text, $smilies);
    return $c_wysiwyg->Ret();
}
/*****[END]********************************************
[ Mod:     Custom Text Area                   v1.0.0 ]
******************************************************/

/*****[BEGIN]******************************************
[ Mod:     User IP Lock                       v1.0.0 ]
******************************************************/
// user_ips function by Technocrat
function user_ips() {
    include_once(NUKE_BASE_DIR.'ips.php');
    global $users_ips;
    if(isset($users_ips)){
        if(is_array($users_ips)){
            for($i=0, $maxi=count($users_ips); $i < $maxi; $i += 2) {
                $i2 = $i + 1;
                $userips[strtolower($users_ips[$i])] = explode(',',$users_ips[$i2]);
            }
            return $userips;
        }
    }
    return null;
}

// compare_ips function by Technocrat
function compare_ips($username) {
    $userips = user_ips();
    if(!is_array($userips)) {
        return true;
    }
    if(isset($userips[strtolower($username)])) {
        $ip_check = implode('|^',$userips[strtolower($username)]);
        if (!preg_match("/^".$ip_check."/",identify::get_ip())) {
            return false;
        }
    }
    return true;
}
/*****[END]********************************************
[ Mod:     User IP Lock                       v1.0.0 ]
******************************************************/

function GetRank($user_id) {
    global $db, $prefix, $user_prefix;
    static $rankData = array();
    
    if(is_array($rankData[$user_id])) { return $rankData[$user_id]; }

    list($user_rank, $user_posts) = $db->sql_ufetchrow("SELECT user_rank, user_posts FROM " . $user_prefix . "_users WHERE user_id = '" . $user_id . "'", SQL_NUM);
    $ranks = $db->sql_ufetchrowset("SELECT * FROM " . $prefix . "_bbranks ORDER BY rank_special, rank_min", SQL_ASSOC);

    $rankData[$user_id] = array();
    for($i=0, $maxi=count($ranks);$i<$maxi;$i++) {
        if ($user_rank == $ranks[$i]['rank_id'] && $ranks[$i]['rank_special']) {
            echo $ranks[$i]['rank_title'];
            $rankData[$user_id]['image'] = ($ranks[$i]['rank_image']) ? '<img src="'.$ranks[$i]['rank_image'].'" alt="'.$ranks[$i]['rank_title'].'" title="'.$ranks[$i]['rank_title'].'" border="0" />' : '';
            $rankData[$user_id]['title'] = $ranks[$i]['rank_title'];
            $rankData[$user_id]['id'] = $ranks[$i]['rank_id'];
            return $rankData[$user_id];
        } elseif ($user_posts >= $ranks[$i]['rank_min'] && !$ranks[$i]['rank_special']) {
            $rankData[$user_id]['image'] = ($ranks[$i]['rank_image']) ? '<img src="'.$ranks[$i]['rank_image'].'" alt="'.$ranks[$i]['rank_title'].'" title="'.$ranks[$i]['rank_title'].'" border="0" />' : '';
            $rankData[$user_id]['title'] = $ranks[$i]['rank_title'];
            $rankData[$user_id]['id'] = $ranks[$i]['rank_id'];
            return $rankData[$user_id];
        }
    }
    return array();
}

// redirect function by Quake
function redirect($url) {
    global $db, $cache;
    
    if(is_object($cache)) $cache->resync();
    if(is_object($db)) $db->sql_close();
    $type = preg_match('/IIS|Microsoft|WebSTAR|Xitami/', $_SERVER['SERVER_SOFTWARE']) ? 'Refresh: 0; URL=' : 'Location: ';
	$url = str_replace('&amp;', "&", $url);
    header($type . $url);
    exit;
}
/*****[BEGIN]******************************************
[ Other:   Deprecated Functions               v1.0.0 ]
******************************************************/
include_once(NUKE_INCLUDE_DIR.'functions_deprecated.php');
/*****[END]********************************************
[ Other:   Deprecated Functions               v1.0.0 ]
******************************************************/

function nuke_img_tag_to_resize($text) {
    global $img_resize;
    
    if(!$img_resize) return $text;
    if(empty($text)) return $text;
    if(preg_match('/<NO RESIZE>/',$text)) {
        $text = str_replace('<NO RESIZE>', '', $text);
        return $text;
    }
    $text = preg_replace('/<\s*?img/',"<img resizemod=\"on\" ",$text);
    return $text;
}

function referer() {
    global $db, $prefix,  $nukeurl, $httpref, $httprefmax;

    if ($httpref == 1 && isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $referer = check_html($_SERVER['HTTP_REFERER'], 'nohtml');
        $referer = Fix_Quotes($referer);
        if(substr($_SERVER['HTTP_HOST'],0,4) == 'www.') {
            $no_www = substr($_SERVER['HTTP_HOST'],5);
        } else {
            $no_www = $_SERVER['HTTP_HOST'];
        }
        $referer_request = '/'.$_SERVER['REQUEST_METHOD'].$_SERVER['REQUEST_URI'];
        if($referer_request == '/GET/') $referer_request = '/';
        $referer_request = Fix_Quotes($referer_request);
        if (stristr($referer, '://') && !stristr($referer, $nukeurl) && !stristr($referer, $no_www)) {
            if (!$db->sql_query('UPDATE IGNORE '.$prefix."_referer SET lasttime=".time().", link='".$referer_request."' WHERE url='".$referer."'") || !$db->sql_affectedrows()) {
                $db->sql_query('INSERT IGNORE INTO '.$prefix."_referer VALUES ('".$referer."', ".time().",'".$referer_request."')");
            }
            list($numrows) = $db->sql_ufetchrow('SELECT COUNT(*) FROM '.$prefix.'_referer');
            if ($numrows >= $httprefmax) {
                $db->sql_query('DELETE FROM '.$prefix.'_referer ORDER BY lasttime LIMIT '.($numrows-($httprefmax/2)));
            }
        }
    }
}

function ord_crypt_decode($data) {
    $result = '';
    $data =  @pack("H" . strlen($data), $data);

    for($i=0; $i<strlen($data); $i++) {
        $char = substr($data, $i, 1);
        $keychar = substr(OrdKey, ($i % strlen(OrdKey))-1, 1);
        $char = chr(ord($char)-ord($keychar));
        $result.=$char;
    }
    return $result;
}

function add_group_attributes($user_id, $group_id) {
    global $prefix, $db, $board_config, $cache;

    if ($user_id <= 2) return true;

    $sql_color = "SELECT `group_color` FROM `" . $prefix . "_bbgroups` WHERE `group_id` = '$group_id'";
    $result_color = $db->sql_query($sql_color);
    $row_color = $db->sql_fetchrow($result_color);
    $db->sql_freeresult($result_color);
    $color = $row_color['group_color'];
    if (!empty($color)) {
        $sql_color = "SELECT `group_color`, `group_id` FROM `" . $prefix . "_bbadvanced_username_color` WHERE `group_id` = '$color'";
        $result_color = $db->sql_query($sql_color);
        $row_color = $db->sql_fetchrow($result_color);
        $db->sql_freeresult($result_color);
    }
    
    $sql_rank = "SELECT `group_rank` FROM `" . $prefix . "_bbgroups` WHERE `group_id` = '$group_id'";
    $result_rank = $db->sql_query($sql_rank);
    $row_rank = $db->sql_fetchrow($result_rank);
    $db->sql_freeresult($result_rank);
    if(isset($row_rank['group_rank']) && !isset($row_color['group_color'])) {
        $sql = "`user_rank` = '".$row_rank['group_rank']."'";
    }elseif(isset($row_color['group_color']) && !isset($row_rank['group_rank'])) {
        $sql = "`user_color_gc` = '".$row_color['group_color']."', `user_color_gi`  = '--".$row_color['group_id']."--'";
    } elseif (isset($row_color['group_color']) && isset($row_rank['group_rank'])) {
        $sql = "`user_rank` = '".$row_rank['group_rank']."', `user_color_gc` = '".$row_color['group_color']."', `user_color_gi`  = '--".$row_color['group_id']."--'";
    } else {
        $sql = "";
    }

    if (!empty($sql)) {
        $sql = "UPDATE `" . $prefix . "_users` SET " . $sql . " WHERE user_id = " . $user_id;
        if ( !$db->sql_query($sql) ) {
            return false;
        }
/*****[BEGIN]******************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
        $cache->delete('UserColors', 'config');
/*****[END]********************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
    }
    return true;
}

function remove_group_attributes($user_id, $group_id) {
    global $prefix, $db, $board_config, $cache;
    if (empty($user_id) && !empty($group_id) && $group_id != 0) {
        $sql = "SELECT `user_id` FROM `".$prefix."_bbuser_group` WHERE `group_id`=".$group_id;
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) {
            remove_group_attributes($row['user_id'], '');
        }
        $cache->delete('UserColors', 'config');
    } else if (!empty($user_id) && $user_id >= 3) {
        $sql = "UPDATE `" . $prefix . "_users` SET `user_color_gc` = '', `user_color_gi`  = '', `user_rank` = 0 WHERE `user_id` = ".$user_id;
        $db->sql_query($sql);
    }

}

function amp_replace($string) {
    $string = str_replace('&amp;', '&', $string);
    $string = str_replace('&', '&amp;', $string);
    return $string;
}

function nuke_site_up($url) {
    //Set the address
    $address = parse_url($url);
    $host = $address['host'];
    if (!($ip = @gethostbyname($host))) return false;
    if (@fsockopen($host, 80, $errno, $errdesc, 10) === false) return false;
    return true;
}

function nuke_mail($to, $subject, $content, $header='', $params='', $batch=false) {
    global $board_config, $nukeconfig, $cache;

    if (empty($to)) return false;

    require_once (NUKE_INCLUDE_DIR.'mail/Swift.php');
    require_once (NUKE_INCLUDE_DIR.'mail/Swift/Connection/Multi.php');
    require_once (NUKE_INCLUDE_DIR.'mail/Swift/Connection/Sendmail.php');
    require_once (NUKE_INCLUDE_DIR.'mail/Swift/Connection/SMTP.php');
    require_once (NUKE_INCLUDE_DIR.'mail/Swift/Connection/NativeMail.php');

    if (!isset($nukeconfig['adminmail']) || empty($nukeconfig['adminmail']) || $nukeconfig['adminmail'] == 'webmaster@------.---') {
        if (!isset($board_config['board_email']) || empty($board_config['board_email']) || $board_config['board_email'] == 'Webmaster@example.com') {
            $from = '';
        } else {
            $from = $board_config['board_email'];
        }
    } else {
        $from = $nukeconfig['adminmail'];
    }

    if (isset($board_config['smtp_delivery']) && $board_config['smtp_delivery'] == '1') {
        if (empty($board_config['smtp_username']) || empty($board_config['smtp_username'])) {
            $swift =& new Swift(Swift_Connection_SMTP($board_config['smtp_password']));
        } else {
            $smtp =& new Swift_Connection_SMTP($board_config['smtp_host'], 25);
            $smtp->setUsername($board_config['smtp_username']);
            $smtp->setpassword($board_config['smtp_password']);
            $swift =& new Swift($smtp);
        }
    } else {
        //Uncomment to use sendmail
        /*$sendmail_path = @ini_get('sendmail_path');
        if (function_exists('proc_open') && !empty($sendmail_path)) {
            $swift =& new Swift(new Swift_Connection_Sendmail($sendmail_path));
        } else */
            $swift =& new Swift(new Swift_Connection_NativeMail());
    }

    $content = str_replace("\r\n", "<br />", $content);
    $content = str_replace("\n", "<br />", $content);

    if ($cache->valid) {
        Swift_CacheFactory::setClassName("Swift_Cache_Disk");
        Swift_Cache_Disk::setSavePath(NUKE_CACHE_DIR);
    }

    $message =& new Swift_Message($subject, $content, "text/html");

    if ($batch && is_object($to)) {
        $sent = $swift->batchSend($message, $to, $from);
    } else {
        $sent = $swift->send($message, $to, $from);
    }

    $swift->disconnect();

    return $sent;
}

function nuke_mail_batch($array_recipients){
    require_once (NUKE_INCLUDE_DIR.'mail/Swift.php');

    if (!is_array($array_recipients)) return '';

    $recipients =& new Swift_RecipientList();
    foreach ($array_recipients as $username => $email) {
        $recipients->addTo($email, $username);
    }
    return $recipients;
}

?>