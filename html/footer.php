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

if(!defined('HEADER')) return;

define('NUKE_FOOTER', true);

function footmsg() {
    global $foot1, $foot2, $foot3, $copyright, $total_time, $start_time, $footmsg, $db, $queries_count, $use_cache, $usrclearcache, $debugger, $debug, $cache, $use_cache, $start_mem;
    static $has_echoed;
    if(isset($has_echoed) && $has_echoed == 1) { return; }
        $footmsg = "<span class=\"footmsg\"><br />\n";
        if (!empty($foot1)) {
            $footmsg .= $foot1."<br />\n";
        }
        if (!empty($foot2)) {
            $footmsg .= $foot2."<br />\n";
        }
        if (!empty($foot3)) {
            $footmsg .= $foot3."<br />\n";
        }
        
        // DO NOT REMOVE THE FOLLOWING COPYRIGHT LINE. YOU'RE NOT ALLOWED TO REMOVE NOR EDIT THIS.
        // IF YOU REALLY NEED TO REMOVE IT AND HAVE MY WRITTEN AUTHORIZATION CHECK:
        // http://phpnuke.org/modules.php?name=Commercial_License
        // PLAY FAIR AND SUPPORT THE DEVELOPMENT, PLEASE!
        $footmsg .= '<br />'.$copyright.'<br />';
        $footmsg = (preg_match(HEX_PREG,$footmsg)) ? $footmsg : $footmsg."";
        if($use_cache && $usrclearcache) {
            $footmsg .= "<form method='post' name='clear_cache' action='".$_SERVER['REQUEST_URI']."'>";
            $footmsg .= "<input type='hidden' name='clear_cache' value='1'><span style='font-size: 11px'>";
            $footmsg .= _SITECACHED . "</span> <a href=\"javascript:clear_cache.submit()\">" . _UPDATECACHE . "</a>";
            $footmsg .= "</form>";
        }
        $total_time = (get_microtime() - $start_time);
        $total_time = '<span class="copyright">[ '._PAGEGENERATION." ".substr($total_time,0,4)." "._SECONDS;
        if ($start_mem > 0) {
            $total_mem = memory_get_usage()-$start_mem;
            $total_time .= ' | Memory Usage: '.(($total_mem >= 1048576) ? round((round($total_mem / 1048576 * 100) / 100), 2).' MB' : (($total_mem >= 1024) ? round((round($total_mem / 1024 * 100) / 100), 2).' KB' : $total_mem.' Bytes'));
        }

        if($queries_count) {
            $total_time .= ' | DB Queries: ' . $db->num_queries;
        }

        $total_time .= ' ]';
        $total_time .= '</span><br />';

        if(is_admin()) {
            $first_time = false;
            if (($last_optimize = $cache->load('last_optimize', 'config')) === false) {
                $last_optimize = time();
                $first_time = true;
            }
            //For information on how to change the auto-optimize intervals
            //Please see www.php.net/strtotime
            //Default: -1 day
            $interval = strtotime('-1 day');
            if (($last_optimize <= $interval) || ($first_time && $cache->valid && $use_cache)) {
                if ($db->sql_optimize()) {
                    $cache->save('last_optimize', 'config', time());
                    $total_time .= "<br />Database Optimized";
                }
            }
            update_modules();
        }

        $footmsg .= $total_time."<br />\n</span>\n";

        if(is_admin() && $debugger->debug && count($debugger->errors) > 0) {
            $footmsg .= "<br /><center><strong>Debugging:</strong></center>";
            $footmsg .= "<table border='0' width='80%' align='center'><tr><td>";
            $footmsg .= $debugger->return_errors();
            $footmsg .= "</td></tr></table>";
        }
        if (is_admin()) {
            echo $db->print_debug();
        }

	$debug_sql = false;
	if (is_admin() && !is_bool($debug) && $debug == 'full') {
            $strstart = strlen(NUKE_BASE_DIR);
            $debug_sql = '<span class="genmed" style="font-weight: bold;">SQL Debug:</span><br /><br />';
            foreach ($db->querylist as $file => $queries) {
                $file = substr($file, $strstart);
                if (empty($file)) $file = 'unknown file';
                $debug_sql .= '<span style="font-weight: bold;">'.$file.'</span><ul>';
                foreach ($queries as $query) { $debug_sql .= "<li>$query</li>"; }
                $debug_sql .= '</ul>';
            }
            $debug_sql .= '<span style="color: #0000FF; font-weight: bold;">*</span> - Result freed<br /><br />';
	}
	echo $debug_sql;
	unset($debug_sql);
	global $browser;
	if ($browser == 'Bot' || $browser == 'Other') {
	   $footmsg .= "<a href=\"trap.php\" >.</a>\n";
	}
	echo $footmsg;
        $has_echoed = 1;
}

if ( defined('ADMIN_FILE') && defined('ADMIN_POS') && is_admin()) {
    global $admin;
    $admin1 = base64_decode($admin);
    $admin1 = addslashes($admin1);
    $admin1 = explode(':', $admin1);
    $aid = $admin1[0];
    unset($admin1);
    echo "<br />";
    GraphicAdmin(0);
}

global $prefix, $user_prefix, $db, $index, $user, $cookie, $storynum, $user, $cookie, $Default_Theme, $foot1, $foot2, $foot3, $foot4, $home, $name, $admin, $persistency, $do_gzip_compress, $cache;
if(defined('HOME_FILE')) {
    blocks('Down');
}
if (!defined('HOME_FILE') AND defined('MODULE_FILE') AND file_exists(NUKE_MODULES_DIR.$name.'/copyright.php')) {
    $cpname = str_replace("_", " ", $name);
    echo "<div align=\"right\"><a href=\"modules/".$name."/copyright.php\" rel='4' class='newWindow'>$cpname &copy;</a></div>";
}
if (!defined('HOME_FILE') AND defined('MODULE_FILE') AND (file_exists(NUKE_MODULES_DIR.$name.'/admin/panel.php') && is_admin())) {
    echo "<br />";
    OpenTable();
    include_once(NUKE_MODULES_DIR . $name . '/admin/panel.php');
    CloseTable();
}
themefooter();

if (!defined('IN_PHPBB')) {
    echo "<div style=\"display:none\" class=\"resize\"></div>";
}
if (file_exists(NUKE_INCLUDE_DIR . 'custom_files/custom_footer.php')) {
    include_once(NUKE_INCLUDE_DIR . 'custom_files/custom_footer.php');
}
echo "\n</body>\n</html>";
$cache->resync();
$db->sql_close();
$s = 'PHP-Nuke Copyright &copy; 2008 by Francisco Burzi.<br />All logos, trademarks and posts in this site are property of their respective owners, all the rest &copy; 2006 by the site owner.</a>';

if(GZIPSUPPORT && $do_gzip_compress) {
    $gzip_contents = ob_get_contents();
    ob_end_clean();
    $gzip_size = strlen($gzip_contents);
    $gzip_crc = crc32($gzip_contents);
    $gzip_contents = gzcompress($gzip_contents, 9);
    $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
    echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
    echo $gzip_contents;
    echo pack('V', $gzip_crc);
    echo pack('V', $gzip_size);
}
ob_end_flush();
exit;

?>