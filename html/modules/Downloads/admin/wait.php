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
  if(($numbrokend = $cache->load('numbrokend', 'submissions')) === false) {
      $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_downloads_mods WHERE brokendownload='1'");
      list($numbrokend) = $db->sql_fetchrow($result, SQL_NUM);
      $db->sql_freeresult($result);
      $cache->save('numbrokend', 'submissions', $numbrokend);
  }
  if(($numwaitd = $cache->load('numwaitd', 'submissions')) === false) {
      $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_downloads_new");
      list($numwaitd) = $db->sql_fetchrow($result, SQL_NUM);
      $db->sql_freeresult($result);
      $cache->save('numwaitd', 'submissions', $numwaitd);
  }
  if(($nummodreqd = $cache->load('nummodreqd', 'submissions')) === false) {
      $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_downloads_mods WHERE brokendownload='0'");
      list($nummodreqd) = $db->sql_fetchrow($result, SQL_NUM);
      $db->sql_freeresult($result);
      $cache->save('nummodreqd', 'submissions', $nummodreqd);
  }
  $content .= "
               &nbsp;
               <strong>
                   <big>
                       &middot;
                   </big>
               </strong>
               <a href=\"".$admin_file.".php?op=DownloadBroken\">
                   "._BROKENDOWN.":
               </a>
               <strong>
                   $numbrokend
               </strong>
               <br />
               &nbsp;
               <strong>
                   <big>
                       &middot;
                   </big>
               </strong>
               <a href=\"".$admin_file.".php?op=DownloadNew\">
                   "._UDOWNLOADS.":
               </a>
               <strong>
                   $numwaitd
               </strong>
               <br />
               &nbsp;
               <strong>
                   <big>
                       &middot;
                   </big>
               </strong>
               <a href=\"".$admin_file.".php?op=DownloadModifyRequests\">
                   "._MODREQDOWN.":
               </a>
               <strong>
                   $nummodreqd
               </strong>
               <br />
              ";
}

?>