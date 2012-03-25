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

if (stristr(htmlentities($_SERVER['PHP_SELF']), "counter.php")) {
    Header("Location: index.php");
    die();
}

global $prefix, $db;

// Obtain user agent which is a long string not meant for human reading, but we're gonna echo it out, just because I am bored xD
// Not needed for it to work though ^_^
$agent = $_SERVER['HTTP_USER_AGENT'];

// Get the users Browser ^_^
// Create the Associative Array for the browsers ^_^
$browserArray = array(
    'IEMobile' => 'IEMobile',
    'Android' => 'Android',
    'iPhone' => 'iPhone',
    'Firefox' => 'Firefox',
    'Chrome' => 'Chrome',
    'MSIE' => 'MSIE',
    'Opera' => 'Opera',
    'Safari' => 'Safari',
    'PlayStation' => 'PLAYSTATION 3'
);

foreach ($browserArray as $k => $v) {
    if (preg_match("/$v/", $agent)) {
        break;
    } elseif(empty($agent)) {
        $k = "Bot"; 
    } else {
        $k = "Other";
    }
}
$browser = $k;

// Get the users OS ^^
// Create the Associative Array for the Operating Systems ^_^
$osArray = array(
    'WINXP' => '(Windows XP)|(Windows NT 5.1)',
    'WINVISTA' => 'Windows NT 6.0',
    'WIN7' => '(Windows NT 6.1)|(Windows NT 7.0)',
    'Linux' => '(X11)|(Linux)',
    'Mac' => '(Mac_PowerPC)|(Macintosh)|(Mac OS)',
    'PlayStation' => 'PLAYSTATION 3'
    );

foreach ($osArray as $k => $v) {
    if (preg_match("/$v/", $agent)) {
        break;
    } else {
        $k = "Other";
    }
}
$os = $k;

/* Save on the databases the obtained values */
$db->sql_query("UPDATE ".$prefix."_counter SET count=count+1 WHERE (type='total' AND var='hits') OR (var='$browser' AND type='browser') OR (var='$os' AND type='os')");

/* Start Detailed Statistics */
$dot = date("d-m-Y-H");
$now = explode ("-",$dot);
$nowHour = $now[3];
$nowYear = $now[2];
$nowMonth = $now[1];
$nowDate = $now[0];
$sql = "SELECT year FROM ".$prefix."_stats_year WHERE year='$nowYear'";
$resultyear = $db->sql_query($sql);
$jml = $db->sql_numrows($resultyear);
if ($jml <= 0) {
    $sql = "INSERT INTO ".$prefix."_stats_year VALUES ('$nowYear','0')";
    $db->sql_query($sql);
    for ($i=1;$i<=12;$i++) {
        $db->sql_query("INSERT INTO ".$prefix."_stats_month VALUES ('$nowYear','$i','0')");
        if ($i == 1) $TotalDay = 31;
        if ($i == 2) {
            if (date("L") == true) {
                $TotalDay = 29;
            } else {
                $TotalDay = 28;
            }
        }
        if ($i == 3) $TotalDay = 31;
        if ($i == 4) $TotalDay = 30;
        if ($i == 5) $TotalDay = 31;
        if ($i == 6) $TotalDay = 30;
        if ($i == 7) $TotalDay = 31;
        if ($i == 8) $TotalDay = 31;
        if ($i == 9) $TotalDay = 30;
        if ($i == 10) $TotalDay = 31;
        if ($i == 11) $TotalDay = 30;
        if ($i == 12) $TotalDay = 31;
        for ($k=1;$k<=$TotalDay;$k++) {
            $db->sql_query("INSERT INTO ".$prefix."_stats_date VALUES ('$nowYear','$i','$k','0')");
        }
    }
}

$sql = "SELECT hour FROM ".$prefix."_stats_hour WHERE (year='$nowYear') AND (month='$nowMonth') AND (date='$nowDate')";
$result = $db->sql_query($sql);
$numrows = $db->sql_numrows($result);

if ($numrows <= 0) {
    for ($z = 0;$z<=23;$z++) {
        $db->sql_query("INSERT INTO ".$prefix."_stats_hour VALUES ('$nowYear','$nowMonth','$nowDate','$z','0')");
    }
}

$db->sql_query("UPDATE ".$prefix."_stats_year SET hits=hits+1 WHERE year='$nowYear'");
$db->sql_query("UPDATE ".$prefix."_stats_month SET hits=hits+1 WHERE (year='$nowYear') AND (month='$nowMonth')");
$db->sql_query("UPDATE ".$prefix."_stats_date SET hits=hits+1 WHERE (year='$nowYear') AND (month='$nowMonth') AND (date='$nowDate')");
$db->sql_query("UPDATE ".$prefix."_stats_hour SET hits=hits+1 WHERE (year='$nowYear') AND (month='$nowMonth') AND (date='$nowDate') AND (hour='$nowHour')");

?>