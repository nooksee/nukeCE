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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

function ne_get_configs(){
    global $prefix, $db, $cache;
    static $config;
    if(isset($config)) return $config;
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
    if(($config = $cache->load('news', 'config')) === false) {
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        $configresult = $db->sql_query("SELECT config_name, config_value FROM ".$prefix."_nsnne_config");
        while (list($config_name, $config_value) = $db->sql_fetchrow($configresult)) {
            $config[$config_name] = $config_value;
        }
        $db->sql_freeresult($configresult);
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        $cache->save('news', 'config', $config);
    }
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
    return $config;
}

function automated_news() {
    global $prefix, $multilingual, $currentlang, $db;
    $result = $db->sql_query('SELECT * FROM '.$prefix.'_autonews WHERE time<="'.date('Y-m-d G:i:s', time()).'"');
    while ($row2 = $db->sql_fetchrow($result)) {
        $title = addslashes($row2['title']);
        $hometext = addslashes($row2['hometext']);
        $bodytext = addslashes($row2['bodytext']);
        $notes = addslashes($row2['notes']);
        $db->sql_query("INSERT INTO ".$prefix."_stories VALUES (NULL, '$row2[catid]', '$row2[aid]', '$title', '$row2[time]', '$hometext', '$bodytext', '0', '0', '$row2[topic]', '$row2[informant]', '$notes', '$row2[ihome]', '$row2[alanguage]', '$row2[acomm]', '0', '0', '0', '0', '$row2[associated]', '0', '1')");
    }
    if ($db->sql_numrows($result)) {
        $db->sql_query('DELETE FROM '.$prefix.'_autonews WHERE time<="'.date('Y-m-d G:i:s', time()).'"');
    }
    $db->sql_freeresult($result);
}

/*****[BEGIN]******************************************
 [ Base:    Friendly URL                     
 ******************************************************/
function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

$current_url = str_replace("www.", "", curPageURL());
$current_url = str_replace(':','%3A',$current_url);
$current_url = str_replace('/','%2F',$current_url);
$current_url = str_replace('&','%26',$current_url);
/*****[END]********************************************
 [ Base:    Friendly URL
 ******************************************************/

?>