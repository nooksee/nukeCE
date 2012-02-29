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

global $admin_file, $db, $prefix, $cache;

$module_name = basename(dirname(dirname(__FILE__)));

if(is_active($module_name)) {
    if(($numbrokenl = $cache->load('numbrokenl', 'submissions')) === false) {
        $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_links_modrequest WHERE brokenlink='1'");
        list($numbrokenl) = $db->sql_fetchrow($result, SQL_NUM);
        $db->sql_freeresult($result);
        $cache->save('numbrokenl', 'submissions', $numbrokenl);
    }
    if(($nummodreql = $cache->load('nummodreql', 'submissions')) === false) {
        $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_links_modrequest WHERE brokenlink='0'");
        list($nummodreql) = $db->sql_fetchrow($result, SQL_NUM);
        $db->sql_freeresult($result);
        $cache->save('nummodreql', 'submissions', $nummodreql);
    }
    if(($numwaitl = $cache->load('numwaitl', 'submissions')) === false) {
        $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_links_newlink");
        list($numwaitl) = $db->sql_fetchrow($result, SQL_NUM);
        $db->sql_freeresult($result);
        $cache->save('numwaitl', 'submissions', $numwaitl);
    }
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=LinksListBrokenLinks\">
                     "._BROKENLINKS.":
                 </a>
                 <strong>
                     $numbrokenl
                 </strong>
                 <br />
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=LinksListModRequests\">
                     "._MODREQLINKS.":</a>
                 <strong>
                     $nummodreql
                 </strong>
                 <br />
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=Links\">
                     "._WLINKS.":
                 </a>
                 <strong>
                     $numwaitl
                 </strong>
                 <br />
                ";
}

?>