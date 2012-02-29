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

include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
DLadminmain();

/*********************************************************/
/* Downloads Functions                                   */
/*********************************************************/

    echo "<br />\n";
    global $admin, $admlanguage, $language, $bgcolor, $bgcolor1, $bgcolor2, $bgcolor3, $prefix, $db, $multilingual, $admin_file;
    OpenTable();
    $perpage = $dl_config['perpage'];
    if (!isset($min)) $min=0;
    if (!isset($max)) $max=$min+$perpage;
    $totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads"));
    pagenums_admin($op, $totalselected, $perpage, $max);
    echo "<table border=\"1\" width=\"100%\" bgcolor=\"$bgcolor1\">"
    ."<tr bgcolor=\"".$bgcolor2."\">"
    ."<td align=\"center\"><strong>" . _TITLE . "</strong></td>"
    ."<td align=\"center\">&nbsp;<strong>" . _FILESIZE . "</strong>&nbsp;</td>"
    ."<td align=\"center\" nowrap>&nbsp;<strong>" . _ADDED . "</strong>&nbsp;</td>"
    ."<td align=\"center\">&nbsp;<strong>" . _FUNCTIONS . "</strong>&nbsp;</td></tr>";
$x = 0;
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads ORDER BY title LIMIT $min,$perpage");
while($lidinfo = $db->sql_fetchrow($result)) {
  echo "<tr><form method='post' action='".$admin_file.".php'>\n";
  echo "<input type='hidden' name='min' value='$min'>\n";
  echo "<input type='hidden' name='lid' value='".$lidinfo['lid']."'>\n";
  echo "<td>".$lidinfo['title']."</td>\n";
  echo "<td align='center'>".CoolSize($lidinfo['filesize'])."</td>\n";
  echo "<td align='center'>".CoolDate($lidinfo['date'])."</td>\n";
  echo "<td align='center'><select name='op'><option value='DownloadModify' selected>"._MODIFY."</option>\n";
  if ($lidinfo['active'] ==1) {
    echo "<option value='DownloadDeactivate'>"._DL_DEACTIVATE."</option>\n";
  } else {
    echo "<option value='DownloadActivate'>"._DL_ACTIVATE."</option>\n";
  }
  echo "<option value='DownloadDelete'>"._DL_DELETE."</option></select> ";
  echo "<input type='submit' value='"._DL_OK."'></td></tr>\n";
  echo "</form></tr>\n";
  $x++;
}
echo "</table>\n";
pagenums_admin($op, $totalselected, $perpage, $max);
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>