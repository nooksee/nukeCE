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

$eid = intval($eid);
$pagetitle = _EXTENSIONSADMIN;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title(_EXTENSIONSADMIN);
DLadminmain();
echo "<br />\n";
OpenTable();
$eidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_extensions WHERE eid='$eid'"));
echo "<table align='center' cellpadding='2' cellspacing='2' border='0'>\n";
echo "<form action='".$admin_file.".php' method='post'>\n";
echo "<tr><td align='center' colspan='2'><strong>"._MODEXTENSION."</strong></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._EXT.":</td><td><input type='text' name='xext' value='".$eidinfo['ext']."' size='10' maxlength='6'></td></tr>\n";
$sel0 = $sel1 = "";
if ($eidinfo['file'] == 0) { $sel0 = " selected"; } elseif ($eidinfo['file'] == 1) { $sel1 = " selected"; }
echo "<tr><td bgcolor='$bgcolor2'>"._FILETYPE.":</td><td><select name='xfile'>\n";
echo "<option value='0'$sel0>"._DL_NO."</option>\n";
echo "<option value='1'$sel1>"._DL_YES."</option>\n";
echo "</select></td></tr>\n";
$sel0 = $sel1 = "";
if ($eidinfo['image'] == 0) { $sel0 = " selected"; } elseif ($eidinfo['image'] == 1) { $sel1 = " selected"; }
echo "<tr><td bgcolor='$bgcolor2'>"._IMAGETYPE.":</td><td><select name='ximage'>\n";
echo "<option value='0'$sel0>"._DL_NO."</option>\n";
echo "<option value='1'$sel1>"._DL_YES."</option>\n";
echo "</select></td></tr>\n";
echo "<input type='hidden' name='eid' value='$eid'>\n";
echo "<input type='hidden' name='min' value='$min'>\n";
echo "<input type='hidden' name='op' value='ExtensionModifySave'>\n";
echo "<tr><td align='center' colspan='2'><input type='submit' value='"._SAVECHANGES."'></td></tr></form>\n";
echo "<form action='admins.php' method='post'>\n";
echo "<input type='hidden' name='eid' value='$eid'>\n";
echo "<input type='hidden' name='min' value='$min'>\n";
echo "<input type='hidden' name='op' value='ExtensionDelete'>\n";
echo "<tr><td align='center' colspan='2'><input type='submit' value='"._DL_DELETE."'></td></tr></form>\n";
echo "</table>\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>