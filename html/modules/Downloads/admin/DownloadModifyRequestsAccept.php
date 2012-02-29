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

$result = $db->sql_query("SELECT rid, lid, cid, sid, title, url, description, name, email, filesize, version, homepage FROM ".$prefix."_downloads_mods WHERE rid='$rid'");
while(list($rid, $lid, $cid, $sid, $title, $url, $description, $aname, $email, $filesize, $version, $homepage) = $db->sql_fetchrow($result)) {
  $title = stripslashes($title);
  $title = addslashes($title);
  $description = stripslashes($description);
  $description = addslashes($description);
  $db->sql_query("UPDATE ".$prefix."_downloads_downloads SET cid=$cid, sid=$sid, title='$title', url='$url', description='$description', name='$aname', email='$email', filesize='$filesize', version='$version', homepage='$homepage' WHERE lid='$lid'");
  $db->sql_query("DELETE FROM ".$prefix."_downloads_mods WHERE rid='$rid'");
  $db->sql_query("OPTIMIZE TABLE ".$prefix."_downloads_mods");
}
redirect($admin_file.".php?op=DownloadModifyRequests");

?>