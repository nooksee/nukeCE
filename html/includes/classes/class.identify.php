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

class identify {

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

    function identify_agent()
    {
        static $data;
        if(!isset($data)) {
            $data = array();
        } elseif(is_array($data)) {
            return $data;
        }
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $pattern = array(
            # Netscape
            '#^Mozilla/[34].[0-8]{1,2}( \[[a-zA-Z\-]{2,5}\])? \(([a-zA-Z0-9]+); [UI]#e',
            # Gecko family: Netscape, Firefox, Thunderbird, Camino, Galeon, Epiphany, Linspire, MultiZilla, K-Meleon, WebWasher, Mozilla
            '#^Mozilla/5.0 \(([a-zA-Z0-9]+); U; (.*[^;])(; [a-zA-Z\-]{2,5})?; rv:[0-9\.]+.*?\) Gecko/[0-9]{8} .* (Firefox).*#e',
            '#^Mozilla/5.0 \(([a-zA-Z0-9]+); U; (.*[^;])(; [a-zA-Z\-]{2,5})?; rv:[0-9\.]+.*?\) Gecko/[0-9]{8}( \(No IDN\))? ([a-zA-Z6\-]+)/[0-9\.]+.*#e',
            '#^Mozilla/5.0 \(([a-zA-Z0-9]+); U; (.*[^;])(; [a-zA-Z\-]{2,5})?; rv:[0-9\.]+.*?\) Gecko/[0-9]{8}( \(No IDN\))?$#e',
            # Galeon
            '#^Mozilla/5.0 (Galeon)/[0-9\.]+ \(([a-zA-Z0-9]+); (.*[^;]); U\)#e',
            # Konqueror
            '#^Mozilla/5.0 \(compatible; (Konqueror)/[0-9\.\-rc]+; (i686 )?(Linux|FreeBSD).*#e',
            # Lynx
            '#^(Lynx)/2.[0-9\.]+(rel|dev)[0-9\.]+ libwww-FM/.*#e',
            # Safari family: Safari, OmniWeb, Shiira, DEVONtech
            '#^Mozilla/5.0 \(Macintosh; U; PPC Mac OS X; [a-zA-Z\-]{2,5}\) AppleWebKit/.*? \(KHTML, like Gecko.*?\) ([a-zA-Z]+)/.*#e',
            # w3m
            '#^(w3m)/[0-9\.]+#e',
            # Links
            '#^(Links) \([0-9].[a-z0-9]+; ([a-zA-Z]+) #e',
            # ELinks
            '#^(ELinks)/0.[0-9]+ \([a-z]+; ([a-zA-Z]+) #e',
            # Voyager
            '#^Mozilla/4.0 \(compatible; (Voyager); (AmigaOS).*#e',
            # Opera
            '#^(Opera)/[67].[0-9]{1,2} \((.*?); U\)[\ ]{1,2}\[[a-zA-Z\-]{2,5}\]#e', # Opera 6-7
            '#^Mozilla/[45].0 \(compatible; MSIE [56].0; (.*?)\) (Opera) [567].[0-9]{1,2} \[[a-zA-Z\-]{2,5}\]#e', # Opera 6-7 faking IE
            '#^Mozilla/5.0 \((.*?); U\) (Opera) [67].[0-9]{1,2} \[[a-zA-Z\-]{2,5}\]#e', # Opera 6-7 faking Gecko
            '#^(Opera)/[89].[0-9]{1,2} \((.*?); U; [a-zA-Z\-]{2,5}\)#e', # Opera 8-9
            '#^Mozilla/4.0 \(compatible; MSIE 6.0; (.*?); [a-zA-Z\-]{2,5}\) (Opera) [89].[0-9]{1,2}#e', # Opera 8-9 faking IE
            '#^Mozilla/5.0 \((.*?); U; [a-zA-Z\-]{2,5}\) (Opera) [89].[0-9]{1,2}#e', # Opera 8-9 faking Gecko
            # IE
            '#^Mozilla/4.0 \([a-z]+; MSIE (4.0|5.0|5.5|6.0|7.0)[b1]?(; .*[^;])?; (Windows) [A-Z0-9\ \.]+[;)](.*)?#e',
            '#^Mozilla/2.0 \(compatible; MSIE (3.0|4.0)[1]?(; .*[^;])?; (Windows) [A-Z0-9\ \.]+[;)](.*)?#e',
            '#^Mozilla/4.0 \(compatible; MSIE 5.[1-2][1-7]; Mac_PowerPC\)#e', # 5.: 13, 16, 17, 21, 22, 23
            # Dillo/0.8.5-i18n-misc
            '#^Dillo/[0-9\.]+.*#e',
            # mobile phones
            '#^KWC-[a-zA-Z0-9]+/[0-9\.]+ UP.Browser/[0-9\.]+#e',
            '#^LG-[A-Z0-9]+ (.*?)Profile/MIDP-[12]#e',
            '#^Nokia[0-9i]+/[0-9\.]+ \([0-9\.]+\) (.*?)Profile/MIDP-[12]#e',
            '#^SAMSUNG-[A-Z0-9\-]+/[A-Z0-9]+ UP.Browser/[0-9\.]+#e',
            '#^SonyEricsson[a-zA-Z0-9]+/[A-Z0-9]+ (.*?)Profile/MIDP-[12]#e',
            '#^BlackBerry[a-zA-Z0-9]+/[A-Z0-9\.]+ (.*?)Profile/MIDP-[A-Z0-9\.]+ Configuration/CLDC-[A-Z0-9\.]+#e',
            # PlayStation
            '#^Mozilla/4.0 \(PSP \(PlayStation Portable\); 2.00\)#e',
        );
         $replacement = array(
            # Netscape
            'identify::set_data(\'Netscape\', \'\\1\', \'Gecko\', \'\', $data)',
            # Gecko family
            'identify::set_data(\'\\4\', \'\\2\', \'Gecko\', \'\', $data)',
            'identify::set_data(\'\\5\', \'\\2\', \'Gecko\', \'\', $data)',
            'identify::set_data(\'Mozilla\', \'\\2\', \'Gecko\', \'\', $data)',
            # Galeon
            'identify::set_data(\'\\1\', \'\\3\', \'\', \'\', $data)',
            # Konqueror
            'identify::set_data(\'\\1\', \'\\3\', \'KHTML\', \'\', $data)',
            # Lynx
            'identify::set_data(\'\\1\', \'\', \'\', \'\', $data)',
            # Safari family
            'identify::set_data(\'\\1\', \'Mac\', \'Safari\', \'\', $data)',
            # w3m
            'identify::set_data(\'\\1\', \'\', \'\', \'\', $data)',
            # Links
            'identify::set_data(\'\\1\', \'\\2\', \'\', \'\', $data)',
            # ELinks
            'identify::set_data(\'\\1\', \'\\2\', \'\', \'\', $data)',
            # Voyager
            'identify::set_data(\'\\1\', \'\\2\', \'\', \'\', $data)',
            # Opera
            'identify::set_data(\'\\1\', \'\\2\', \'\', \'\', $data)',
            'identify::set_data(\'\\1\', \'\\2\', \'\', \'\', $data)',
            'identify::set_data(\'\\2\', \'\\1\', \'\', \'\', $data)',
            'identify::set_data(\'\\1\', \'\\2\', \'\', \'\', $data)',
            'identify::set_data(\'\\2\', \'\\1\', \'\', \'\', $data)',
            'identify::set_data(\'\\2\', \'\\1\', \'\', \'\', $data)',
            # IE
            'identify::set_data(\'MSIE\', \'\\3\', \'\', \'\\4\', $data)',
            'identify::set_data(\'MSIE\', \'\\3\', \'\', \'\\4\', $data)',
            'identify::set_data(\'MSIE\', \'Mac\', \'\', \'\\4\', $data)',
            # Dillo
            'identify::set_data(\'Dillo\', \'Linux\', \'\', \'\', $data)',
            # mobile phones
            'identify::set_data(\'WAP\', \'\', \'\', \'KWC\', $data)',
            'identify::set_data(\'WAP\', \'\', \'\', \'LG\', $data)',
            'identify::set_data(\'WAP\', \'\', \'\', \'Nokia\', $data)',
            'identify::set_data(\'WAP\', \'\', \'\', \'SAMSUNG\', $data)',
            'identify::set_data(\'WAP\', \'\', \'\', \'SonyEricsson\', $data)',
            'identify::set_data(\'WAP\', \'\', \'\', \'BlackBerry\', $data)',
            # PlayStation
            'identify::set_data(\'PlayStation\', \'\', \'\', \'Sony\', $data)',
        );
        preg_replace($pattern, $replacement, $agent);
        unset($pattern, $replacement);
        if (!isset($data['ua'])) {
            return $data = identify::detect_bot();
        } else if ($data['ua'] == 'MSIE') {
            preg_match_all('#(iRider|Crazy Browser|NetCaptor|Maxthon|Avant Browser)#s', $data['ext'], $regs);
            if (!empty($regs[0])) {
                $data['ua'] = str_replace(' Browser','',$regs[0][count($regs[0])-1]);
                $data['ext'] = '';
            }
        }
        preg_match('#(Win|Mac|Linux|FreeBSD|SunOS|IRIX|BeOS|OS/2|AIX|Amiga)#is', $data['os'], $regs);
        $data['os'] = $regs[0];
        if ($data['os'] == 'Win') $data['os'] = 'Windows';
        return $data;
    }

    function set_data($ua, $os, $engine, $extra, &$data) {
        if (!empty($ua)) {
            $data = array(
                'ua' => $ua,
                'os' => $os,
                'engine' => empty($engine) ? $ua : $engine,
                'ext' => $extra
            );
        }
    }

    function detect_bot($where=false) {
        global $db, $prefix;
        $bot = false;
        $where = ($where ? "WHERE agent_name LIKE '$where%'" : '');
        $result = $db->sql_query('SELECT agent_name, agent_fullname FROM '.$prefix.'_security_agents'.$where.' ORDER BY agent_name', true);
        $find = array('\\', '(', ')', '{', '}', '.', '$', '*');
        $replace = array('\\\\', '\(', '\)', '\{', '\}', '\.', '\$', '\*');
        $agent = str_replace($find, $replace, $_SERVER['HTTP_USER_AGENT']);
        while ($row = $db->sql_fetchrow($result)) {
            $row[1] = str_replace($find, $replace, $row[1]);
            if(!empty($row[1]) && $row[1] != 'NULL') {
                if (stristr($_SERVER['HTTP_USER_AGENT'], $row[1])) {
                    $bot = $row[0];
                } else if ($bot && empty($where)) {
                    break;
                }
              }
        }
        $db->sql_freeresult($result);
        return ($bot === false) ? false : array('ua' => 'bot', 'bot' => $bot, 'engine' => 'bot');
    }

}

?>