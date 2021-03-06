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

$pagetitle = _DOWNLOADSADMIN.": "._DUSERREPBROKEN;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_mods WHERE brokendownload='1' ORDER BY rid");
$totalbroken = $db->sql_numrows($result);
title("$pagetitle ($totalbroken)");
DLadminmain();
echo "<br />\n";
OpenTable();
echo "<center>"._DIGNOREINFO."<br />\n"._DDELETEINFO."</center><br />\n";
echo "<table align='center' width='80%' cellpadding='2' cellspacing='0'>";
if ($totalbroken==0) {
  echo "<tr><td align='center'><strong>"._DNOREPORTEDBROKEN."<strong></td></tr>\n";
} else {
  $colorswitch = $bgcolor2;
  echo "<tr>\n";
  echo "<td><strong>"._DOWNLOAD."</strong></td>\n";
  echo "<td><strong>"._SUBMITTER."</strong></td>\n";
  echo "<td><strong>"._DOWNLOADOWNER."</strong></td>\n";
  echo "<td><strong>"._IGNORE."</strong></td>\n";
  echo "<td><strong>"._DL_DELETE."</strong></td>\n";
  echo "<td><strong>"._DL_EDIT."</strong></td>\n";
  echo "</tr>\n";
  while($ridinfo = $db->sql_fetchrow($result)) {
    $lidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE lid='".$ridinfo['lid']."'"));
    list($memail) = $db->sql_fetchrow($db->sql_query("SELECT user_email FROM ".$user_prefix."_users WHERE username='".$ridinfo['modifier']."'"));
    list($oemail) = $db->sql_fetchrow($db->sql_query("SELECT user_email FROM ".$user_prefix."_users WHERE username='".$lidinfo['name']."'"));
    echo "<tr>\n";
    echo "<td bgcolor='$colorswitch'><a href='".$lidinfo['url']."'>".$lidinfo['title']."</a></td>\n";
    echo "<td bgcolor='$colorswitch'>";
    if ($memail=='') { echo $ridinfo['modifier']; } else { echo "<a href='mailto:$memail'>".$ridinfo['modifier']."</a>"; }
    echo "</td>\n";
    echo "<td bgcolor='$colorswitch'>";
    if ($oemail=='') { echo $lidinfo['name']; } else { echo "<a href='mailto:$oemail'>".$lidinfo['name']."</a>"; }
    echo "</td>\n";
    echo "<td bgcolor='$colorswitch' align='center'><a href='".$admin_file.".php?op=DownloadBrokenIgnore&amp;lid=".$ridinfo['lid']."'>X</a></td>\n";
    echo "<td bgcolor='$colorswitch' align='center'><a href='".$admin_file.".php?op=DownloadBrokenDelete&amp;lid=".$ridinfo['lid']."'>X</a></td>\n";
    echo "<td bgcolor='$colorswitch' align='center'><a href='".$admin_file.".php?op=DownloadModify&amp;lid=".$ridinfo['lid']."'>X</a></td>\n";
    echo "</tr>\n";
    if ($colorswitch == $bgcolor2) { $colorswitch = $bgcolor1; } else { $colorswitch = $bgcolor2; }
  }
}
echo "</table>\n";
CloseTable();
$cache->delete('numbrokend', 'submissions');
include_once(NUKE_BASE_DIR.'footer.php');

?>