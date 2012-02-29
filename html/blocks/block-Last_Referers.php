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

if(!defined('NUKE_CE')) exit;

global $db, $prefix;

# how many referers should the block display?
$ref = 10;
$a = 1;
$content = '';

$result = $db->sql_query("SELECT url FROM ".$prefix."_referer ORDER BY lasttime DESC LIMIT 0,$ref");
$total = $db->sql_numrows($result);
if ($total < 1) {
    return $content = "
                       <div align=\"center\">
                           "._NOREFERERS."
                       <div>
                      ";
}

while (list($url) = $db->sql_fetchrow($result)) {
    $url2 = str_replace('_', ' ', $url);
    
    if (strlen($url2) > 18) {
        $url2 = substr($url, 0, 65);
        $url2 .= '...';
    }
    
    $content .= "
                 $a:
                 <a href=\"$url\" target=\"_blank\">
                     $url2
                 </a>
                 <br />
                ";
    $a++;
}

if (is_admin()) {
    global $admin_file;
    $content .= "
                 <br />
                 <div align=\"center\">
                     $total "._HTTPREFERERS."
                     <br />
                     <br />
                     [ 
                     <a href=\"".$admin_file.".php?op=hreferer&amp;del=all\">
                         "._DELETE."</a> 
                     ]
                 </div>
                ";
}
$db->sql_freeresult($result);

?>