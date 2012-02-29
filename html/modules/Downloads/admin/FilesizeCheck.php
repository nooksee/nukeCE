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

$pagetitle = _DOWNLOADSADMIN.": "._FILESIZEVALIDATION;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title($pagetitle);
DLadminmain();
echo "<br />\n";
OpenTable();
echo "<table align='center' width='100%' cellpadding='2' cellspacing='2' border='0'>\n";
echo "<tr><td align='center'><a href='".$admin_file.".php?op=FilesizeValidate&amp;cid=0'>"._CHECKALLDOWNLOADS."</a><br /><br /></td></tr>\n";
echo "<tr><td align='center'><strong>"._CHECKCATEGORIES."</strong><br />"._INCLUDESUBCATEGORIES."<br /><br /></td></tr>\n";
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories ORDER BY parentid,title");
while($cidinfo = $db->sql_fetchrow($result)) {
  if ($cidinfo['parentid'] != 0) { $cidinfo['title'] = getparent($cidinfo['parentid'],$cidinfo['title']); }
  $transfertitle = str_replace (" ", "_", $cidinfo['title']);
  echo "<tr><td align='center'><a href='".$admin_file.".php?op=FilesizeValidate&amp;cid=".$cidinfo['cid']."&amp;ttitle=$transfertitle'>".$cidinfo['title']."</a></td></tr>\n";
}
echo "</table>\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>