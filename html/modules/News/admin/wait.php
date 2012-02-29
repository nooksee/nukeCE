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
if(is_active('Submit_News')) {
    if(($numwaits = $cache->load('numwaits', 'submissions')) === false) {
        list($numwaits) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM ".$prefix."_queue"), SQL_NUM);
        $cache->save('numwaits', 'submissions', $numwaits);
    }
    if (is_array($numwaits)) {
        $numwaits = $numwaits['numrows'];
    }
    $content .= "
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"".$admin_file.".php?op=submissions\">
                     "._SUBMISSIONS.":
                 </a>
                 <strong>
                     ".$numwaits."
                 </strong>
                 <br />
                ";
}

?>