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

class Security {

    function get_ip()
    {
        static $visitor_ip;
        if (!empty($visitor_ip)) { return $visitor_ip; }
        $visitor_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : $_ENV['REMOTE_ADDR'];
        $ips = array();
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != 'unknown') {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != 'unknown') {
            $ips[] = $_SERVER['HTTP_CLIENT_IP'];
        }
        for ($i = 0; $i < count($ips); $i++) {
            $ips[$i] = trim($ips[$i]);
            # IPv4
            if (strstr($ips[$i], '.')) {
                # check for a hybrid IPv4-compatible address
                $pos = strrpos($ips[$i], ':');
                if ($pos !== FALSE) { $ips[$i] = substr($ips[$i], $pos+1); }
                # Don't assign local network ip's
                if (preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $ips[$i]) &&
                    !preg_match('#^(10|127.0.0|172.(1[6-9]|2[0-9]|3[0-1])|192\.168)\.#', $ips[$i]))
                {
                    $visitor_ip = $ips[$i];
                    break;
                }
            }
            # IPv6
            else if (strpos($ips[$i], ':') !== FALSE) {
                # fix shortened ip's
                $c = substr_count($ips[$i], ':');
                if ($c < 7) { $ips[$i] = str_replace('::', str_pad('::', 9-$c, ':'), $ips[$i]); }
                if (preg_match('#^([0-9A-F]{0,4}:){7}[0-9A-F]{0,4}$#i', $ips[$i])) {
                    $visitor_ip = $ips[$i];
                    break;
                }
            }
        }

        return $visitor_ip;
    }
}

?>