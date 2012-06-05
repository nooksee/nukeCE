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

if(defined('NUKE_CE')) return;

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

// Define File
define_once('NUKE_CE', '2.0.5.0.1');
define_once('CE_EDITION', '2.0.5.0.1');
define('PHPVERS', @phpversion());
define_once('CE_VERSION', NUKE_CE . ' ' . CE_EDITION);

if (!ini_get('register_globals')) @import_request_variables('GPC');

$admin = (isset($_COOKIE['admin'])) ? $_COOKIE['admin'] : false;
$user = (isset($_COOKIE['user'])) ? $_COOKIE['user'] : false;

if ((isset($_POST['name']) && !empty($_POST['name'])) && (isset($_GET['name']) && !empty($_GET['name']))) {
    $name = (isset($_GET['name']) && !stristr($_GET['name'],'..') && !stristr($_GET['name'],'://')) ? addslashes(trim($_GET['name'])) : false;
} else {
    $name = (isset($_REQUEST['name']) && !stristr($_REQUEST['name'],'..') && !stristr($_REQUEST['name'],'://')) ? addslashes(trim($_REQUEST['name'])) : false;
}
	
$start_mem = function_exists('memory_get_usage') ? memory_get_usage() : 0;
$start_time = get_microtime();

// Stupid handle to create REQUEST_URI for IIS 5 servers
if (ereg('IIS', $_SERVER['SERVER_SOFTWARE']) && isset($_SERVER['SCRIPT_NAME'])) {
    $requesturi = $_SERVER['SCRIPT_NAME'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $requesturi .= '?'.$_SERVER['QUERY_STRING'];
    }
    $_SERVER['REQUEST_URI'] = $requesturi;
}

// PHP5 with register_long_arrays off?
if (version_compare(PHPVERS, '5.0.0', '>=') && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off')) {
    $HTTP_POST_VARS =& $_POST;
    $HTTP_GET_VARS =& $_GET;
    $HTTP_SERVER_VARS =& $_SERVER;
    $HTTP_COOKIE_VARS =& $_COOKIE;
    $HTTP_ENV_VARS =& $_ENV;
    $HTTP_POST_FILES =& $_FILES;
    if (isset($_SESSION)) $HTTP_SESSION_VARS =& $_SESSION;
}

//Absolute Nuke directory
define('NUKE_BASE_DIR', dirname(__FILE__) . '/');
//Absolute Nuke directory + includes
define('NUKE_BLOCKS_DIR', NUKE_BASE_DIR . 'blocks/');
define('NUKE_IMAGES_DIR', NUKE_BASE_DIR . 'images/');
define('NUKE_INCLUDE_DIR', NUKE_BASE_DIR . 'includes/');
define('NUKE_LANGUAGE_DIR', NUKE_BASE_DIR . 'language/');
define('NUKE_MODULES_DIR', NUKE_BASE_DIR . 'modules/');
define('NUKE_THEMES_DIR', NUKE_BASE_DIR . 'themes/');
define('NUKE_ADMIN_DIR', NUKE_BASE_DIR . 'admin/');
define('NUKE_RSS_DIR', NUKE_INCLUDE_DIR . 'rss/');
define('NUKE_DB_DIR', NUKE_INCLUDE_DIR . 'db/');
define('NUKE_ADMIN_MODULE_DIR', NUKE_ADMIN_DIR . 'modules/');
define('NUKE_FORUMS_DIR', (defined("IN_ADMIN") ? './../' : 'modules/Forums/'));
define('NUKE_CACHE_DIR', NUKE_INCLUDE_DIR . 'cache/');
define('NUKE_CLASSES_DIR', NUKE_INCLUDE_DIR . 'classes/');
// define the INCLUDE PATH
define('INCLUDE_PATH', NUKE_BASE_DIR);

define('GZIPSUPPORT', extension_loaded('zlib'));
define('GDSUPPORT', extension_loaded('gd'));
define('CAN_MOD_INI', !stristr(ini_get('disable_functions'), 'ini_set'));

//Check for these functions to see if we can use the new captcha
if(function_exists('imagecreatetruecolor') && function_exists('imageftbbox')) {
    define('CAPTCHA',true);
}

if (CAN_MOD_INI) {
    ini_set('magic_quotes_sybase', 0);
    ini_set('zlib.output_compression', 0);
}

// Include config file
@require_once(NUKE_BASE_DIR.'config.php');
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

// Include the required files
@require_once(NUKE_DB_DIR.'db.php');

// Include Error Logger and Client classes
@require_once(NUKE_INCLUDE_DIR.'log.php');
@require_once(NUKE_CLASSES_DIR.'class.client.php');

// parse the requesting user agent
@require_once(NUKE_CLASSES_DIR.'class.UAParser.php');
global $result;
$result = UA::parse();

if (ini_get('output_buffering') && !isset($result->isSpider)) {
    ob_end_clean();
    header('Content-Encoding: none');
}

$do_gzip_compress = false;
if (GZIPSUPPORT && !ini_get('zlib.output_compression') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && eregi('gzip', $_SERVER['HTTP_ACCEPT_ENCODING'])) {
    if (version_compare(PHPVERS, '4.3.0', '>=')) { # PHP 4.2.x seems to give memleak
        ob_start('ob_gzhandler');
    } else {
        $do_gzip_compress = true;
        ob_start();
        ob_implicit_flush(0);
        header('Content-Encoding: gzip');
    }
} else {
    ob_start();
    ob_implicit_flush(0);
}

@require_once(NUKE_CLASSES_DIR.'class.cache.php');
@require_once(NUKE_CLASSES_DIR.'class.debugger.php');
require_once(NUKE_INCLUDE_DIR.'functions.php');
include_once(NUKE_INCLUDE_DIR.'validation.php');

if (PHPVERS > '4.0' && (!defined('NO_SECURITY') && !defined('ADMIN_FILE'))) {
    require_once(NUKE_CLASSES_DIR.'class.inputfilter.php');
    $data = array_merge($_POST, $_GET);
    //This is the new php input class it will filter out bad HTML code and XSS
    //data from POSTs or GETs
    if(defined('MEDIUM_SECURITY')) {
        if(isset($_POST['message']) && !empty($_POST['message'])){
            if (preg_match("/(<.*?pre\s?.*?>.*<.*?pre\s?'.*?>|document.location.*?=.*document\..*)/i", $_POST['message'])) {
                InputFilter::filtered('',$_POST['message']);
            }
        }
        $filter = new InputFilter("", "", 1, 1, 0);
    } else {
        $filter = new InputFilter("", "", 1, 1, 1);
    }
    if(!empty($data)) {
        $data = $filter->process($data);
    }
    define('INPUT_FILTER',true);
}

// We globalize the $cookie and $userinfo variables,
// so that they dont have to be called each time
// And as you can see, getusrinfo() is now deprecated.
// Because you dont have to call it anymore, just call $userinfo
if(is_user()) {
    $cookie = cookiedecode();
    $userinfo = get_user_field('*', $cookie[1], true);
} else {
    $cookie = array();
    $userinfo = get_user_field('*', 'Anonymous', true);
}

if(stristr($_SERVER['REQUEST_URI'], '.php/')) {
    redirect(str_replace('.php/', '.php', $_SERVER['REQUEST_URI']));
}

include_once(NUKE_MODULES_DIR.'Your_Account/includes/mainfileend.php');

if (isset($_POST['clear_cache'])) {
    $cache->clear();
}

define('NUKE_FILE', true);
$dbi = $db->db_connect_id;
$badreasons = 4;
$sitekey = md5($_SERVER['HTTP_HOST']);
$gfx_chk = 0;
$tipath = 'images/topics/';
$reasons = array('As Is', 'Offtopic', 'Flamebait', 'Troll', 'Redundant', 'Insighful', 'Interesting', 'Informative', 'Funny', 'Overrated', 'Underrated');
$AllowableHTML = array('b'=>1, 'i'=>1, 'a'=>2, 'em'=>1, 'br'=>1, 'strong'=>1, 'blockquote'=>1, 'tt'=>1, 'li'=>1, 'ol'=>1, 'ul'=>1, 'pre'=>1);
$nukeconfig = load_nukeconfig();
foreach($nukeconfig as $var => $value) {
    $$var = $value;
}

@require_once(NUKE_INCLUDE_DIR.'language.php');

$adminmail = stripslashes($adminmail);
$foot1 = stripslashes($foot1);
$foot2 = stripslashes($foot2);
$foot3 = stripslashes($foot3);
$commentlimit = intval($commentlimit);
$minpass = intval($minpass);
$pollcomm = intval($pollcomm);
$articlecomm = intval($articlecomm);
$my_headlines = intval($my_headlines);
$top = intval($top);
$storyhome = intval($storyhome);
$user_news = intval($user_news);
$oldnum = intval($oldnum);
$ultramode = intval($ultramode);
$banners = intval($banners);
$multilingual = intval($multilingual);
$useflags = intval($useflags);
$notify = intval($notify);
$moderate = intval($moderate);
$admingraphic = intval($admingraphic);
$httpref = intval($httpref);
$httprefmax = intval($httprefmax);
$domain = str_replace('http://', '', $nukeurl);
if(isset($default_Theme)) $Default_Theme = $default_Theme;
if (CAN_MOD_INI) ini_set('sendmail_from', $adminmail);
$sysconfig = load_sysconfig();
$board_config = load_board_config();
$lock_modules = intval($sysconfig['lock_modules']);
$queries_count = intval($sysconfig['queries_count']);
$adminssl = intval($sysconfig['adminssl']);
$censor_words = $sysconfig['censor_words'];
$censor = intval($sysconfig['censor']);
$usrclearcache = intval($sysconfig['usrclearcache']);
$use_colors = intval($sysconfig['use_colors']);
$lazy_tap = intval($sysconfig['lazy_tap']);
$img_resize = intval($sysconfig['img_resize']);
$wysiwyg = $sysconfig['textarea'];
$capfile = $sysconfig['capfile'];
$collapse = intval($sysconfig['collapse']);
$nukeuserinfo_ec = intval($sysconfig['nukeuserinfo_ec']);
$more_js = '';

require_once(NUKE_INCLUDE_DIR.'functions_browser.php');
require_once(NUKE_INCLUDE_DIR.'themes.php');
include_once(NUKE_INCLUDE_DIR.'functions_tap.php');

if (!defined('NO_SENTINEL')) {
    require_once(NUKE_INCLUDE_DIR.'nukesentinel.php');
}

include_once(NUKE_CLASSES_DIR.'class.wysiwyg.php');
@require_once(NUKE_CLASSES_DIR.'class.variables.php');

if (file_exists(NUKE_INCLUDE_DIR.'custom_files/custom_mainfile.php')) {
    require_once(NUKE_INCLUDE_DIR.'custom_files/custom_mainfile.php');
}

if(!defined('FORUM_ADMIN') && !isset($ThemeSel) && !defined('RSS_FEED')) {
    $ThemeSel = get_theme();
    include_once(NUKE_THEMES_DIR . $ThemeSel . '/theme.php');
}

if (!defined('FORUM_ADMIN')) {
    global $admin_file;
    if(!isset($admin_file) || empty($admin_file)) {
        die('You must set a value for $admin_file in config.php');
    } elseif (!empty($admin_file) && !file_exists(NUKE_BASE_DIR.$admin_file.'.php')) {
        die('The $admin_file you defined in config.php does not exist');
    }
}

function define_once($constant, $value) {
    if(!defined($constant)) {
        define($constant, $value);
    }
}

function is_admin($trash=0) {
    static $adminstatus;
    if(isset($adminstatus)) return $adminstatus;
    $admincookie = isset($_COOKIE['admin']) ? $_COOKIE['admin'] : false;
    if (!$admincookie) { return $adminstatus = 0; }
    $admincookie = (!is_array($admincookie)) ? explode(':', base64_decode($admincookie)) : $admincookie;
    $aid = $admincookie[0];
    $pwd = $admincookie[1];
    $aid = substr(addslashes($aid), 0, 25);
    if (!empty($aid) && !empty($pwd)) {
        if (!function_exists('get_admin_field')) {
            global $db, $prefix;
            $pass = $db->sql_ufetchrow("SELECT `pwd` FROM `" . $prefix . "_authors` WHERE `aid` = '" .  str_replace("\'", "''", $aid) . "'", SQL_ASSOC);
            $pass = (isset($pass['pwd'])) ? $pass['pwd'] : '';
        } else {
            $pass = get_admin_field('pwd', $aid);
        }
        if ($pass == $pwd && !empty($pass)) {
            return $adminstatus = 1;
        }
    }
    return $adminstatus = 0;
}

function is_user($trash=0) {
    static $userstatus;
    if(isset($userstatus)) return $userstatus;
    $usercookie = isset($_COOKIE['user']) ? $_COOKIE['user'] : false;
    if (!$usercookie) { return $userstatus = 0; }
    $usercookie = (!is_array($usercookie)) ? explode(':', base64_decode($usercookie)) : $usercookie;
    $uid = $usercookie[0];
    $pwd = $usercookie[2];
    $uid = intval($uid);
    if (!empty($uid) AND !empty($pwd)) {
        $user_password = get_user_field('user_password', $uid);
        if ($user_password == $pwd && !empty($user_password)) {
            return $userstatus = 1;
        }
    }
    return $userstatus = 0;
}

function cookiedecode($trash=0) {
    global $cookie;
    static $rcookie;
    if(isset($rcookie)) { return $rcookie; }
    $usercookie = $_COOKIE['user'];
    $rcookie = (!is_array($usercookie)) ? explode(':', base64_decode($usercookie)) : $usercookie;
    $pass = get_user_field('user_password', $rcookie[1], true);
    if ($rcookie[2] == $pass && !empty($pass)) {
        return $cookie = $rcookie;
    }
    return false;
}

function title($text) {
    OpenTable();
    echo '
          <div class="title" style="text-align: center">
              <strong>'.$text.'</strong>
          </div>
         ';
    CloseTable();
    echo '<br />';
}

function is_active($module) {
    global $prefix, $db, $cache;
    static $active_modules;
    if (is_array($active_modules)) {
        return(isset($active_modules[$module]) ? 1 : 0);
    }
    if ((($active_modules = $cache->load('active_modules', 'config')) === false) || empty($active_modules)) {
        $active_modules = array();
        $result = $db->sql_query('SELECT `title` FROM `'.$prefix.'_modules` WHERE `active`="1"');
        while(list($title) = $db->sql_fetchrow($result, SQL_NUM)) {
            $active_modules[$title] = 1;
        }
        $db->sql_freeresult($result);
        $cache->save('active_modules', 'config', $active_modules);
    }
    return (isset($active_modules[$module]) ? 1 : 0);
}

function render_blocks($side, $block) {
    global $currentlang;
    define_once('BLOCK_FILE', true);
    //Include the block lang files
    if (file_exists(NUKE_LANGUAGE_DIR.'blocks/lang-'.$currentlang.'.php')) {
        include_once(NUKE_LANGUAGE_DIR.'blocks/lang-'.$currentlang.'.php');
    } else {
        include_once(NUKE_LANGUAGE_DIR.'blocks/lang-english.php');
    }

    if (empty($block['url'])) {
        if (empty($block['blockfile'])) {
            if ($side == 'c' || $side == 'd') {
                themecenterbox($block['title'], BBCode2Html($block['content']));
            } else {
                themesidebox($block['title'], BBCode2Html($block['content']), $block['bid']);
            }
        } else {
            blockfileinc($block['title'], $block['blockfile'], $side, $block['bid']);
        }
    } else {
        headlines($block['bid'], $side, $block);
    }
}

function blocks_visible($side) {
    global $showblocks;
    $showblocks = ($showblocks == null) ? 3 : $showblocks;
    $side = strtolower($side[0]);
    //If there are no blocks for this module && not admin file
    if (!$showblocks && !defined('ADMIN_FILE')) return false;
    //If in the admin show l blocks
    if (defined('ADMIN_FILE')) {
        return true;
    }
    //If set to 3 its all blocks
    if ($showblocks == 3) return true;
    //Count the blocks on the side
    $blocks = blocks($side, true);
    //If there are no blocks
    if (!$blocks) {
        return false;
    }
    //Check for blocks to show
    if (($showblocks == 1 && $side == 'l') || ($showblocks == 2 && $side == 'r')) {
        return true;
    }
    return false;
}

function blocks($side, $count=false) {
    global $prefix, $multilingual, $currentlang, $db, $userinfo, $cache;
    static $blocks;

    $querylang = ($multilingual) ? 'AND (`blanguage`="'.$currentlang.'" OR `blanguage`="")' : '';
    $side = strtolower($side[0]);
    if((($blocks = $cache->load('blocks', 'config')) === false) || !isset($blocks)) {
        $sql = 'SELECT * FROM `'.$prefix.'_blocks` WHERE `active`="1" '.$querylang.' ORDER BY `weight` ASC';
        $result = $db->sql_query($sql);
        while($row = $db->sql_fetchrow($result, SQL_ASSOC)) {
            $blocks[$row['bposition']][] = $row;
        }
        $db->sql_freeresult($result);
        $cache->save('blocks', 'config', $blocks);
    }
    if ($count) {
        return (isset($blocks[$side]) ? count($blocks[$side]) : 0);
    }
    $blockrow = (isset($blocks[$side])) ? $blocks[$side] : array();
    for($i=0,$j = count($blockrow); $i < $j; $i++) {
        $bid = intval($blockrow[$i]['bid']);
        $view = $blockrow[$i]['view'];
        if(isset($blockrow[$i]['expire'])) {
            $expire = intval($blockrow[$i]['expire']);
        } else {
            $expire = '';
        }
        if(isset($blockrow[$i]['action'])) {
            $action = $blockrow[$i]['action'];
            $action = substr($action, 0,1);
        } else {
            $action = '';
        }
        $now = time();
        if ($expire != 0 AND $expire <= $now) {
            if ($action == 'd') {
                $db->sql_query('UPDATE `'.$prefix.'_blocks` SET `active`="0", `expire`="0" WHERE `bid`="'.$bid.'"');
                $cache->delete('blocks', 'config');
                return;
            } elseif ($action == 'r') {
                $db->sql_query('DELETE FROM `'.$prefix.'_blocks` WHERE `bid`="'.$bid.'"');
                $cache->delete('blocks', 'config');
                return;
            }
        }
        if (empty($blockrow[$i]['bkey'])) {
            if ( ($view == '0' || $view == '1') || ( ($view == '3' AND is_user()) ) || ( $view == '4' AND is_admin()) || ( ($view == '2' AND !is_user())) ) {
                render_blocks($side, $blockrow[$i]);
            } else {
                if (substr($view, strlen($view)-1) == '-') {
                    $ingroups = explode('-', $view);
                    if (is_array($ingroups)) {
                        foreach ($ingroups as $group) {
                            if (isset($userinfo['groups'][($group)])) {
                                render_blocks($side, $blockrow[$i]);
                            }
                        }
                    }
                }
            }
        }
    }
    return;
}

function blockfileinc($blockfiletitle, $blockfile, $side=1, $bid) {
    if (!file_exists(NUKE_BLOCKS_DIR.$blockfile)) {
        $content = _BLOCKPROBLEM;
    } else {
        include(NUKE_BLOCKS_DIR.$blockfile);
    }
    if (empty($content)) {
        $content = _BLOCKPROBLEM2;
    }
    if ($side == 'r' || $side == 'l') {
        themesidebox($blockfiletitle, $content, $bid);
    } else {
        themecenterbox($blockfiletitle, $content);
    }
}

function rss_content($url) {
    if (!nuke_site_up($url)) return false;
    require_once(NUKE_CLASSES_DIR.'class.rss.php');
    if ($rss = RSS::read($url)) {
        $items =& $rss['items'];
        $site_link =& $rss['link'];
        $content = '';
        for ($i=0,$j = count($items);$i  <$j;$i++) {
            $link = $items[$i]['link'];
            $title2 = $items[$i]['title'];
            $content .= "&nbsp;<strong><big>&middot;</big></strong><a href=\"$link\" target=\"new\">$title2</a><br />";
        }
        if (!empty($site_link)) {
            $content .= "
                         <br />
                         <div align=\"center\">
                             [&nbsp;<a href=\"$site_link\" target=\"_blank\">"._HREADMORE."</a>&nbsp;]
                         </div>
                        ";
        }
        return $content;
    }
    return false;
}

function headlines($bid, $side=0, $row='') {
    global $prefix, $db, $my_headlines, $cache;
    if(!$my_headlines) return;
    $bid = intval($bid);
    if (!is_array($row)) {
        $row = $db->sql_ufetchrow('SELECT `title`, `content`, `url`, `refresh`, `time` FROM `'.$prefix.'_blocks` WHERE `bid`='.$bid, SQL_ASSOC);
    }
    $content =& trim($row['content']);
    if ($row['time'] < (time()-$row['refresh']) || empty($content)) {
        $content = rss_content($row['url']);
        $btime = time();
        $db->sql_query("UPDATE `".$prefix."_blocks` SET `content`='".Fix_Quotes($content)."', `time`='$btime' WHERE `bid`='$bid'");
        $cache->delete('blocks', 'config');
    }
    if (empty($content)) {
        $content = _RSSPROBLEM.' ('.$row['title'].')';
    }
    $content = '<span class="content">'.$content.'</span>';
    if ($side == 'c' || $side == 'd') {
        themecenterbox($row['title'], $content);
    } else {
        themesidebox($row['title'], $content, $bid);
    }
}

function ultramode() {
    global $db, $prefix, $multilingual, $currentlang;
    $querylang = ($multilingual == 1) ? "AND (s.alanguage='".$currentlang."' OR s.alanguage='')" : "";
    $sql = "SELECT s.sid, s.catid, s.aid, s.title, s.time, s.hometext, s.comments, s.topic, s.ticon, t.topictext, t.topicimage FROM `".$prefix."_stories` s LEFT JOIN `".$prefix."_topics` t ON t.topicid = s.topic WHERE s.ihome = '0' ".$querylang." ORDER BY s.time DESC LIMIT 0,10";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result, SQL_ASSOC)) {
        $rsid = $row['sid'];
        $raid = $row['aid'];
        $rtitle = htmlspecialchars(stripslashes($row['title']));
        $rtime = $row['time'];
        $rcomments = $row['comments'];
        $topictext = $row['topictext'];
        $topicimage = ($row['ticon']) ? stripslashes($row['topicimage']) : '';
        $rtime = formatTimestamp($rtime, 'l, F d');
        $content .= "%%\n".$rtitle."\n/modules.php?name=News&file=article&sid=".$rsid."\n".$rtime."\n".$raid."\n".$topictext."\n".$rcomments."\n".$topicimage."\n";
    }
    $db->sql_freeresult($result);
    if (file_exists(NUKE_BASE_DIR."ultramode.txt") && is_writable(NUKE_BASE_DIR."ultramode.txt")) {
        $file = fopen(NUKE_BASE_DIR."ultramode.txt", "w");
        fwrite($file, "General purpose self-explanatory file with news headlines\n".$content);
        fclose($file);
    } else {
        global $debugger;
        $debugger->handle_error('Unable to write ultramode content to file', 'Error');
    }
}

// Adds slashes to string and strips PHP+HTML for SQL insertion and hack prevention
// $str: the string to modify
// $nohtml: strip PHP+HTML tags, false=no, true=yes, default=false
function Fix_Quotes($str, $nohtml=false) {
    global $db;
    //If there is not supposed to be HTML
    if ($nohtml) $str = strip_tags($str);
    // Quote if not integer
    if (!is_numeric($str)) {
        $str = str_replace('%27', "'", $str);
        $str = $db->sql_addq($str);
    }
    return $str;
}

function Remove_Slashes($str) {
    static $magic_quotes;
    if (!isset($magic_quotes)) $magic_quotes = get_magic_quotes_gpc();
    if ($magic_quotes) $str = stripslashes($str);
    return $str;
}

function check_words($message) {
    global $censor_words, $censor;
    if(empty($message)) {
        return '';
    }
    if($censor == 0 || empty($censor_words)) {
        return $message;
    }
    $censor_words = trim($censor_words);
    $words = explode(' ', $censor_words);
    for($i=0, $j = count($words);$i < $j;$i++){
        $word = trim($words[$i]);
        $replace = str_repeat('*', strlen($word));
        if($censor == 1) {
            $message = preg_replace('/\b'.$word.'\b/i', $replace, $message);
        } else if($censor == 2) {
            $message = preg_replace('/'.$word.'/i', $replace, $message);
        }
    }
    return $message;
}

function check_html($str, $strip='') {
    if(defined('INPUT_FILTER')) {
        if ($strip == 'nohtml') {
            global $AllowableHTML;
        }
        if (!is_array($AllowableHTML)) {
            $html = '';
        } else {
            $html = '';
            foreach($AllowableHTML as $type => $key) {
                 if($key == 1) {
                   $html[] = $type;
                 }
            }
        }
        $html_filter = new InputFilter($html, "", 0, 0, 1);
        $str = $html_filter->process($str);
    } else {
        $str = Fix_Quotes($str, !empty($strip));
    }
    return $str;
}

function filter_text($Message, $strip='') {
    $Message = check_words($Message);
    $Message = check_html($Message, $strip);
    return $Message;
}

function formatTimestamp($time, $format='') {
    global $datetime, $locale, $userinfo, $board_config;
    if (empty($format)) {
        if (isset($userinfo['user_dateformat']) && !empty($userinfo['user_dateformat'])) {
            $format = $userinfo['user_dateformat'];
        } else if (isset($board_config['default_dateformat']) && !empty($board_config['default_dateformat'])) {
            $format = $board_config['default_dateformat'];
        } else {
            $format = 'D M d, Y g:i a';
        }
    }
    if (isset($userinfo['user_timezone']) && !empty($userinfo['user_timezone'])) {
        $tz = $userinfo['user_timezone'];
    } else if (isset($board_config['board_timezone']) && !empty($board_config['board_timezone'])) {
        $tz = $board_config['board_timezone'];
    } else {
        $tz = '10';
    }
    setlocale(LC_TIME, $locale);
    if (!is_numeric($time)) {
        preg_match('/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/', $time, $datetime);
        $time = gmmktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
    }
    $datetime = NukeDate($format, $time, $tz);
    return $datetime;
}

function get_microtime() {
    list($usec, $sec) = explode(' ', microtime());
    return ($usec + $sec);
}

function get_author($aid) {
    global $user_prefix, $db;
    static $users;
    if (is_array($users[$aid])) {
        $row = $users[$aid];
    } else {
        $row = get_admin_field('*', $aid);
        $users[$aid] = $row;
    }
    $result = $db->sql_query('SELECT `user_id` from `'.$user_prefix.'_users` WHERE `username`="'.$aid.'"');
    $userid = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    if (isset($userid[0])) {
        $aid = "<a href=\"modules.php?name=Profile&amp;mode=viewprofile&amp;u=".$userid[0]."\">".UsernameColor($aid)."</a>";
    } else if (isset($row['url']) && $row['url'] != 'http://') {
        $aid = "<a href=\"".$row['url']."\">".UsernameColor($aid)."</a>";
    } else {
        $aid = UsernameColor($aid);
    }
    return $aid;
}

if(!function_exists('themepreview')) {
    function themepreview($title, $hometext, $bodytext='', $notes='') {
        echo '<strong>'.$title.'</strong><br /><br />'.$hometext.'';
        if (!empty($bodytext)) {
            echo '<br /><br />'.$bodytext.'';
        }
        if (!empty($notes)) {
            echo '<br /><div><b>'._EDITORNOTE.'</b><i>'.$notes.'</i></div>';
        }
    }
}

if(!function_exists('themecenterbox')) {
    function themecenterbox($title, $content) {
        OpenTable();
        echo '
              <div align="center">
                  <span class="option"><strong>'.$title.'</strong></span>
              </div>
              <br />
              '.$content.'
             ';
        CloseTable();
        echo '<br />';
    }
}

function getTopics($s_sid) {
    global $prefix, $topicname, $topicimage, $topictext, $db;
    $sid = intval($s_sid);
    $result = $db->sql_query('SELECT t.topicname, t.topicimage, t.topictext FROM `'.$prefix.'_stories` s LEFT JOIN `'.$prefix.'_topics` t ON t.topicid = s.topic WHERE s.sid = "'.$sid.'"', SQL_ASSOC);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    $topicname = $row['topicname'];
    $topicimage = $row['topicimage'];
    $topictext = stripslashes($row['topictext']);
}

function ads($position) {
    global $prefix, $db, $sitename, $adminmail, $nukeurl, $banners;
    if(!$banners) { return ''; }
    $position = intval($position);
    $result = $db->sql_query("SELECT * FROM `".$prefix."_banner` WHERE `position`='$position' AND `active`='1' ORDER BY RAND() LIMIT 0,1");
    $numrows = $db->sql_numrows($result);
    if ($numrows < 1) return '';
    $row = $db->sql_fetchrow($result, SQL_ASSOC);
    $db->sql_freeresult($result);
    foreach($row as $var => $value) {
        if (isset($$var)) unset($$var);
        $$var = $value;
    }
    $bid = intval($bid);
    if(!is_admin()) {
        $db->sql_query("UPDATE `".$prefix."_banner` SET `impmade`=" . $impmade . "+1 WHERE `bid`='$bid'");
    }
    $sql2 = "SELECT `cid`, `imptotal`, `impmade`, `clicks`, `date`, `ad_class`, `ad_code`, `ad_width`, `ad_height` FROM `".$prefix."_banner` WHERE `bid`='$bid'";
    $result2 = $db->sql_query($sql2);
    list($cid, $imptotal, $impmade, $clicks, $date, $ad_class, $ad_code, $ad_width, $ad_height) = $db->sql_fetchrow($result2, SQL_NUM);
    $db->sql_freeresult($result2);
    $cid = intval($cid);
    $imptotal = intval($imptotal);
    $impmade = intval($impmade);
    $clicks = intval($clicks);
    /* Check if this impression is the last one and print the banner */
    if (($imptotal <= $impmade) && ($imptotal != 0)) {
        $db->sql_query("UPDATE `".$prefix."_banner` SET `active`='0' WHERE `bid`='$bid'");
        $sql3 = "SELECT `name`, `contact`, `email` FROM `".$prefix."_banner_clients` WHERE `cid`='$cid'";
        $result3 = $db->sql_query($sql3);
        list($c_name, $c_contact, $c_email) = $db->sql_fetchrow($result3, SQL_NUM);
        $db->sql_freeresult($result3);
        if (!empty($c_email)) {
            $from = $sitename.' <'.$adminmail.'>';
            $to = $c_contact.' <'.$c_email.'>';
            $message = _HELLO." $c_contact:\n\n";
            $message .= _THISISAUTOMATED."\n\n";
            $message .= _THERESULTS."\n\n";
            $message .= _TOTALIMPRESSIONS." $imptotal\n";
            $message .= _CLICKSRECEIVED." $clicks\n";
            $message .= _IMAGEURL." $imageurl\n";
            $message .= _CLICKURL." $clickurl\n";
            $message .= _ALTERNATETEXT." $alttext\n\n";
            $message .= _HOPEYOULIKED."\n\n";
            $message .= _THANKSUPPORT."\n\n";
            $message .= "- $sitename "._TEAM."\n";
            $message .= $nukeurl;
            $subject = $sitename.': '._BANNERSFINNISHED;
            $mailcommand = nuke_mail($to, $subject, $message, "From: $from\nX-Mailer: PHP/" . PHPVERS);
            $mailcommand = removecrlf($mailcommand);
        }
    }
    if ($ad_class == "code") {
        $ad_code = stripslashes($ad_code);
        $ads = "<center>$ad_code</center>";
    } elseif ($ad_class == "flash") {
        $ads = "<center>"
              ."<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\" width=\"".$ad_width."\" height=\"".$ad_height."\" id=\"$bid\">"
              ."<param name=\"movie\" value=\"".$imageurl."\" />"
              ."<param name=\"quality\" value=\"high\" />"
              ."<embed src=\"".$imageurl."\" quality=\"high\" width=\"".$ad_width."\" height=\"".$ad_height."\" name=\"".$bid."\" align=\"\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed></object>"
              ."</center>";
    } else {
        $ads = "<center><a href=\"index.php?op=ad_click&amp;bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"$alttext\" title=\"$alttext\"></a></center>";
    }
    return $ads;
}

function makePass() {
    $cons = 'bcdfghjklmnpqrstvwxyz';
    $vocs = 'aeiou';
    for ($x=0; $x < 6; $x++) {
        mt_srand ((double) microtime() * 1000000);
        $con[$x] = substr($cons, mt_rand(0, strlen($cons)-1), 1);
        $voc[$x] = substr($vocs, mt_rand(0, strlen($vocs)-1), 1);
    }
    mt_srand((double)microtime()*1000000);
    $num1 = mt_rand(0, 9);
    $num2 = mt_rand(0, 9);
    $makepass = $con[0] . $voc[0] .$con[2] . $num1 . $num2 . $con[3] . $voc[3] . $con[4];
    return $makepass;
}

function get_theme() {
    static $ThemeSel;
    if (isset($ThemeSel)) return $ThemeSel;
    global $Default_Theme, $cookie;
    #Quick Theme Change - Theme Management (JeFFb68CAM)
    if(isset($_REQUEST['chngtheme']) && is_user()) {
        ChangeTheme($_REQUEST['theme'], $cookie[0]);
    }
    #Theme Preview Mod - Theme Management (JeFFb68CAM)
    if(isset($_REQUEST['tpreview']) && ThemeAllowed($_REQUEST['tpreview'])) {
        $ThemeSel = $_REQUEST['tpreview'];
        if(!is_user()) {
            setcookie('guest_theme', $ThemeSel, time()+84600);
        }
        return $ThemeSel;
    }
    #Theme Preview for guests Mod - Theme Management (JeFFb68CAM)
    if (isset($_COOKIE['guest_theme']) && !is_user()) {
        return (ThemeAllowed($_COOKIE['guest_theme']) ? $_COOKIE['guest_theme'] : $Default_Theme);
    }
    #New feature to grab a backup theme if the one we are trying to use does not exist, no more missing theme errors :)
    $ThemeSel = (ThemeAllowed($nTheme = (isset($cookie[9]) ? $cookie[9] : $Default_Theme))) ? $nTheme : ThemeBackup($nTheme);
    return $ThemeSel;
}

// Function to translate Datestrings
function translate($phrase) {
    switch($phrase) {
        case'xdatestring': $tmp='%A, %B %d @ %T %Z'; break;
        case'linksdatestring': $tmp='%d-%b-%Y'; break;
        case'xdatestring2': $tmp='%A, %B %d'; break;
        default: $tmp=$phrase; break;
    }
    return $tmp;
}

function removecrlf($str) {
    return strtr($str, '\015\012', ' ');
}

function validate_mail($email) {
    if(strlen($email) < 7 || !preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email)) {
        DisplayError(_ERRORINVEMAIL);
        return false;
    } else {
        return $email;
    }
}

function encode_mail($email) {
    $finished = '';
    for($i=0, $j = strlen($email); $i<$j; ++$i) {
        $n = mt_rand(0, 1);
        $finished .= ($n) ? '&#x'.sprintf('%X',ord($email{$i})).';' : '&#'.ord($email{$i}).';';
    }
    return $finished;
}

function UsernameColor($username, $old_name=false) {
    global $db, $user_prefix, $use_colors, $cache;
    static $cached_names;
    if($old_name) { $username = $old_name; }
    if(!$use_colors) return $username;
    $plain_username = strtolower($username);
    if(isset($cached_names[$plain_username])) {
        return $cached_names[$plain_username];
    }
    if(!is_array($cached_names)) {
        $cached_names = $cache->load('UserColors', 'config');
    }
    if (!isset($cached_names[$plain_username])) {
        list($user_color, $uname) = $db->sql_ufetchrow("SELECT `user_color_gc`, `username` FROM `" . $user_prefix . "_users` WHERE `username` = '" . str_replace("'", "\'", $username) . "'", SQL_NUM);
        $uname = (!empty($uname)) ? $uname : $username;
        $username = (strlen($user_color) == 6) ? '<span style="color: #'. $user_color .'"><strong>'. $uname .'</strong></span>' : $uname;
        $cached_names[$plain_username] = $username;
        $cache->save('UserColors', 'config', $cached_names);
    }
    return $cached_names[$plain_username];
}

function GroupColor($group_name) {
    global $db, $prefix, $use_colors, $cache;
    static $new_groups = array(), $GroupColors;
    $plain_group_name = $group_name;
    if(!$use_colors) return $group_name;
    if(isset($new_groups[$plain_group_name])) {
        return $new_groups[$plain_group_name];
    }
    if ((($GroupColors = $cache->load('GroupColors', 'config')) === false) || !isset($cache)) {
        $GroupColors = array();
        $sql = "SELECT `group_color`, `group_name` FROM `" . $prefix . "_bbadvanced_username_color` ORDER BY `group_name` ASC";
        $result = $db->sql_query($sql, true);
        while ($row = $db->sql_fetchrow($result, SQL_ASSOC)) {
            $GroupColors[$row['group_name']] = $row['group_color'];
        }
        $db->sql_freeresult($result);
        $cache->save('GroupColors', 'config', $GroupColors);
    }
    $group_name = isset($GroupColors[$group_name]) ? (strlen($GroupColors[$group_name]) == 6) ? '<span style="color: #'. $GroupColors[$group_name] .'"><strong>'. $plain_group_name .'</strong></span>' : $plain_group_name : $plain_group_name;
    return $new_groups[$plain_group_name] = $group_name;
}

function info_box($graphic, $message) {
    // Function to generate a message box with a graphic inside
    // $graphic value can be whichever: warning, caution, tip, note.
    // Then the graphic value with the extension .gif should be present inside /images/sys/ folder
    if (file_exists("images/sys/".$graphic.".gif") AND !empty($message)) {
        Opentable();
        $graphic = $graphic;
        $message = $message;
        echo "
              <table align=\"center\" border=\"0\" width=\"80%\" cellpadding=\"10\">
                  <tr>
                      <td valign=\"top\"><img src=\"images/sys/".$graphic.".gif\" border=\"0\" alt=\"\" title=\"\" width=\"34\" height=\"34\"></td>
                      <td valign=\"center\">$message</td>
                  </tr>
              </table>
             ";
        CloseTable();
        echo "<br />";
    } else {
        return;
    }
}

referer();

?>