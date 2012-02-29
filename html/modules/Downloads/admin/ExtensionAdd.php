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

$pagetitle = _EXTENSIONSADMIN.": "._ADDEXTENSION;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title(_EXTENSIONSADMIN);
DLadminmain();
echo "<br />\n";
OpenTable();
echo "<table align='center' cellpadding='2' cellspacing='2' border='0'>\n";
echo "<form action='".$admin_file.".php' method='post'>\n";
echo "<tr><td align='center' colspan='2'><strong>"._ADDEXTENSION."</strong></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._EXTENSION.":</td><td><input type='text' name='xext' size='10' maxlength='6'></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._FILETYPE.":</td><td><select name='xfile'>\n";
echo "<option value='0' selected>"._DL_NO."</option>\n";
echo "<option value='1'>"._DL_YES."</option>\n";
echo "</select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._IMAGETYPE.":</td><td><select name='ximage'>\n";
echo "<option value='0' selected>"._DL_NO."</option>\n";
echo "<option value='1'>"._DL_YES."</option>\n";
echo "</select></td></tr>\n";
echo "<input type='hidden' name='op' value='ExtensionAddSave'>\n";
echo "<tr><td align='center' colspan='2'><input type='submit' value='"._ADDEXTENSION."'></td></tr></form>\n";
echo "</table>\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>