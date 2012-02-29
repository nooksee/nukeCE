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
    if(($numwaitreviews = $cache->load('numwaitreviews', 'submissions')) === false) {
        list($numwaitreviews) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM ".$prefix."_reviews_add"), SQL_NUM);
        $cache->save('numwaitreviews', 'submissions', $numwaitreviews);
    }
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=reviews\">
                     "._WREVIEWS.":
                 </a>
                 <strong>
                     $numwaitreviews
                 </strong>
                 <br />
                ";
}

?>