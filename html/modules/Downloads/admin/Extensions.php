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

$pagetitle = _EXTENSIONSADMIN;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title($pagetitle);
DLadminmain();
echo "<br />";
OpenTable();
$perpage = $dl_config['perpage'];
if (!isset($min)) $min=0;
if (!isset($max)) $max=$min+$perpage;
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_extensions"));
pagenums_admin($op, $totalselected, $perpage, $max);
echo "<table align='center' cellpadding='2' cellspacing='2' bgcolor='$textcolor1' border='0'>\n";
echo "<tr bgcolor='$bgcolor2'>\n<td><strong>"._EXTENSION."</strong></td>\n";
echo "<td><strong>"._FILETYPE."</strong></td>\n";
echo "<td><strong>"._IMAGETYPE."</strong></td>\n";
echo "<td align='center'><strong>"._FUNCTIONS."</strong></td>\n</tr>\n";
$x = 0;
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_extensions ORDER BY ext,eid LIMIT $min,$perpage");
while($eidinfo = $db->sql_fetchrow($result)) {
  echo "<tr bgcolor='$bgcolor1'><form method='post' action='".$admin_file.".php'>\n";
  echo "<input type='hidden' name='min' value='$min'>\n";
  echo "<input type='hidden' name='eid' value='".$eidinfo['eid']."'>\n";
  echo "<td>".$eidinfo['ext']."</td>\n";
  if ($eidinfo['file'] == 1) { $ftype = "<strong>"._YES."</strong>"; } else { $ftype = _NO; }
  echo "<td align='center'>$ftype</td>\n";
  if ($eidinfo['image'] == 1) { $itype = "<strong>"._YES."</strong>"; } else { $itype = _NO; }
  echo "<td align='center'>$itype</td>\n";
  echo "<td align='center'><select name='op'><option value='ExtensionModify' selected>"._MODIFY."</option>\n";
  echo "<option value='ExtensionDelete'>"._DL_DELETE."</option></select> ";
  echo "<input type='submit' value='"._DL_OK."'></td></tr>\n";
  echo "</form></tr>\n";
  $x++;
}
echo "</table>\n";
pagenums_admin($op, $totalselected, $perpage, $max);
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>