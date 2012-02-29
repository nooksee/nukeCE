<?php

/**************************************************************************/
/* PHP-EVOLVED: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.php-evolved.com                                             */
/*                                                                        */
/* All PHP-EVOLVED code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
get_lang($module_name);
 	global $sitename, $slogan, $db, $prefix, $module_name, $site_logo, $Default_Theme;
 	if (file_exists("themes/$Default_Theme/images/logo.gif")) {
 	$avantgo_logo = "themes/$Default_Theme/images/logo.gif";
 	} elseif (file_exists("images/$site_logo")) {
 	$avantgo_logo = "images/$site_logo";
 	} elseif (file_exists("images/logo.gif")) {
 	$avantgo_logo = "images/logo.gif";
 	} else {
 	$avantgo_logo = "";
 	}
header("Content-Type: text/html");
echo "<html>\n"
    ."<head>\n"
    ."<title>$sitename - AvantGo</title>\n"
    ."<meta name=\"HandheldFriendly\" content=\"True\">\n"
    ."</head>\n"
    ."<body>\n\n\n"
    ."<div align=\"center\">\n";
$result = $db->sql_query("SELECT sid, title, time FROM ".$prefix."_stories ORDER BY sid DESC LIMIT 10");
if (!result) {
    echo "An error occured";
} else {
    echo "\t<a href=\"index.php\"><img src=\"$avantgo_logo\" alt=\"$slogan\" title=\"$slogan\" border=\"0\"></a><br />\r\n"
        ."\t<h1>$sitename</h1>\r\n"
        ."\t<table border=\"0\" align=\"center\">\r\n"
        ."\t\t<tr>\r\n"
        ."\t\t\t<td bgcolor=\"#efefef\">"._TITLE."</td>\r\n"
        ."\t\t\t<td bgcolor=\"#efefef\">"._DATE."</td>\r\n"
        ."\t\t</tr>\r\n";
    for ($m=0; $m < $db->sql_numrows($result); $m++) {
        $row = $db->sql_fetchrow($result);
    $sid = intval($row['sid']);
    $title = stripslashes(check_html($row['title'], "nohtml"));
    $time = $row['time'];
        echo "\t\t<tr>\r\n"
        ."\t\t\t<td><a href=\"modules.php?name=$module_name&amp;file=print&amp;sid=$sid\">$title</a></td>\r\n"
            ."\t\t\t<td>$time</td>\r\n"
            ."\t\t</tr>\r\n";
    }
    echo"\t</table>\r\n";
}
echo "</body>\n"
    ."</html>";
include(NUKE_INCLUDE_DIR."counter.php");
exit;

?>