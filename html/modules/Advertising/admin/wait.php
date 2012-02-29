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

if(!defined('NUKE_CE')) {
    exit;
}

global $admin_file, $db, $prefix, $banners, $cache;

if($banners && is_mod_admin('Advertising')) {
    if (!$active = $cache->load('numbanact', 'submissions')) {
        list($active) = $db->sql_ufetchrow("SELECT COUNT(*) FROM " . $prefix . "_banner WHERE active='1'", SQL_NUM);
        $cache->save('numbanact', 'submissions', $active);
    }
    if (!$inactive = $cache->load('numbandea', 'submissions')) {
        list($inactive) = $db->sql_ufetchrow("SELECT COUNT(*) FROM " . $prefix . "_banner WHERE active='0'", SQL_NUM);
        $cache->save('numbandea', 'submissions', $inactive);
    }
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=BannersAdmin\">
                     "._ABANNERS.":
                 </a>
                 <strong>
                     $active
                 </strong>
                 <br />
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=BannersAdmin\">
                     "._DBANNERS.":
                 </a>
                 <strong>
                     $inactive
                 </strong>
                 <br />
                ";
}

?>