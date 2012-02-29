<?php

/************************************************************************/
/* PHP-NUKE EVOLVED: Web Portal System                                  */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2008 by Kevin Atwood                                   */
/* http://www.nuke-evolved.com                                          */
/*                                                                      */
/* All PHP-Nuke code is released under the GNU General Public License.  */
/* See COPYRIGHT.txt and LICENSE.txt.                                   */
/************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

define('HOME_FILE', true);

/*********************************************************/
/* Header Functions                                      */
/*********************************************************/

	function head() {
    global $sitename, $pagetitle;
    $ThemeSel = get_theme();
    include_once(NUKE_THEMES_DIR.$ThemeSel.'/theme.php');
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<title>$sitename $pagetitle</title>\n";
    include(NUKE_INCLUDE_DIR.'meta.php');
    include(NUKE_INCLUDE_DIR.'javascript.php');
    if (file_exists(NUKE_THEMES_DIR.$ThemeSel.'/images/favicon.ico')) {
        echo "<link rel=\"shortcut icon\" href=\"themes/$ThemeSel/images/favicon.ico\" type=\"image/x-icon\">\n";
    }
    if (file_exists("themes/$ThemeSel/style/style.css")) {
        echo "<link rel=\"StyleSheet\" href=\"themes/$ThemeSel/style/style.css\" type=\"text/css\">\n";
    }
    if (file_exists(NUKE_INCLUDE_DIR.'custom_files/custom_head.php')) {
        @include_once(NUKE_INCLUDE_DIR.'custom_files/custom_head.php');
    }
    if (file_exists(NUKE_INCLUDE_DIR.'custom_files/custom_header.php')) {
        @include_once(NUKE_INCLUDE_DIR.'custom_files/custom_header.php');
    }
    echo "</head>\n";
    themeheader();
}

online();
head();

include_once(NUKE_INCLUDE_DIR.'counter.php');
if(defined('HOME_FILE')) {
    include_once(NUKE_INCLUDE_DIR.'messagebox.php');
    blocks('Center');
}

?>