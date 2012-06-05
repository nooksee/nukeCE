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

function log_write($file, $output, $title = 'General Error') {
    global $cookie, $client;
    if(isset($cookie) && is_array($cookie)) {
        $username = $cookie[1];
    } else {
        if(isset($_COOKIE['user']) && !empty($_COOKIE['user'])) {
            $ucookie = explode(':', base64_decode($_COOKIE['user']));
        }
        if(isset($ucookie) && is_array($ucookie) && !empty($ucookie[1])) {
            $username = $ucookie[1];
        } else {
            $username = _ANONYMOUS;
        }
    }
    $client = new Client();    
    $ip = GetHostByName($client->getIp());
    $date = date("d M Y - H:i:s");
    if($file == 'admin') {
        $string = '';
    } elseif ($file == 'error') {
        $string = 'URL: <a href="http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '">' . $_SERVER['REQUEST_URI'] . "</a>\n";
    }
    $header  = "<strong>[" . $title . "] [" . $date . "]</strong>\n";
    $wdata = $header;
    $wdata .= "\n";
    $wdata .= "User: ".UsernameColor($username)."\n";
    $wdata .= 'IP: '.$ip."\n";
    $wdata .= $string;
    $wdata .= "\n";
    if(is_array($output)) {
        foreach($output as $line) {
             $wdata .= htmlspecialchars($line) . "\n";
        }
    } else {
        $wdata .= htmlspecialchars($output) . "\n";
    }
    $wdata .= "\n\n";
    if($handle = @fopen(NUKE_INCLUDE_DIR.'log/' . $file . '.log','a')) {
        fwrite($handle, $wdata);
        fclose($handle);
    }
    return;
}

function log_size($file) {
    global $db, $prefix;

    $filename = NUKE_INCLUDE_DIR.'log/' . $file . '.log';
    if(!is_file($filename)) {
        return -1;
    }
    if(!is_writable($filename)) {
        return -2;
    }
    if(filesize($filename) == 0) {
        return 0;
    }
    $handle = @fopen($filename,'r');
    if($handle) {
        $content = fread($handle, filesize($filename));
        @fclose($handle);
    } else {
        return -1;
    }
    $file_num = substr_count($content, "\n");
    $row_log = $db->sql_ufetchrow('SELECT ' . $file . '_log_lines FROM '.$prefix.'_config');
    if($row_log[0] != $file_num) {
        return 1;
    }
    return 0;
}

?>