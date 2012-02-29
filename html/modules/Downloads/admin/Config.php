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
$dl_config = downloads_get_configs();
DLadminmain();
echo "<br />\n";
OpenTable();
echo "<fieldset><legend><span class='option'><strong>" . _DOWNCONFIG . "</strong></span></legend>";
echo "<table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">";
echo "<form action='".$admin_file.".php' method='post'>\n";
echo "<tr><td>"._ADMBLOCKUNREGMODIFY."</td><td><select name='xblockunregmodify'>\n";
echo "<option value='0'";
if ($dl_config['blockunregmodify'] == 0) { echo " selected"; }
echo "> "._YES." </option>\n<option value='1'";
if ($dl_config['blockunregmodify'] == 1) { echo " selected"; }
echo "> "._NO." </option>\n";
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMMOSTPOPULAR."</td><td><select name='xmostpopular'>\n";
echo "<option value='".$dl_config['mostpopular']."' selected> ".$dl_config['mostpopular']." </option>\n";
for ($i=1; $i <= 5; $i++) { $j = $i * 5; echo "<option value='$j'> $j </option>\n"; }
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMMOSTPOPULARTRIG."</td><td><select name='xmostpopulartrig'>\n";
echo "<option value='0'";
if ($dl_config['mostpopulartrig'] == 0) { echo " selected"; }
echo "> "._NUMBER." </option>\n<option value='1'";
if ($dl_config['mostpopulartrig'] == 1) { echo " selected"; }
echo "> "._PERCENT." </option>\n";
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMPERPAGE."</td><td><select name='xperpage'>\n";
echo "<option value='".$dl_config['perpage']."' selected> ".$dl_config['perpage']." </option>\n";
for ($i=1; $i <= 5; $i++) { $j = $i * 10; echo "<option value='$j'> $j </option>\n"; }
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMADMPERPAGE."</td><td><select name='xadmperpage'>\n";
echo "<option value='".$dl_config['admperpage']."' selected> ".$dl_config['admperpage']." </option>\n";
for ($i=1; $i <= 8; $i++) { $j = $i * 25; echo "<option value='$j'> $j </option>\n"; }
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMRESULTS."</td><td><select name='xresults'>\n";
echo "<option value='".$dl_config['results']."' selected> ".$dl_config['results']." </option>\n";
for ($i=1; $i <= 5; $i++) { $j = $i * 10; echo "<option value='$j'> $j </option>\n"; }
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMPOPULAR."</td><td><select name='xpopular'>\n";
echo "<option value='".$dl_config['popular']."' selected> ".$dl_config['popular']." </option>\n";
for ($i=1; $i <= 10; $i++) { $j = $i * 100; echo "<option value='$j'> $j </option>\n"; }
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMSHOWDOWNLOAD."</td><td><select name='xshow_download'>\n";
echo "<option value='0'";
if ($dl_config['show_download'] == 0) { echo " selected"; }
echo "> "._NO." </option>\n<option value='1'";
if ($dl_config['show_download'] == 1) { echo " selected"; }
echo "> "._YES." </option>\n";
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMSHOWNUM."</td><td><select name='xshow_links_num'>\n";
echo "<option value='0'";
if ($dl_config['show_links_num'] == 0) { echo " selected"; }
echo "> "._NO." </option>\n<option value='1'";
if ($dl_config['show_links_num'] == 1) { echo " selected"; }
echo "> "._YES." </option>\n";
echo "</select></td></tr>\n";
echo "<tr><td>"._ADMUSEGFX."</td><td><select name='xusegfxcheck'>\n";
echo "<option value='0'";
if ($dl_config['usegfxcheck'] == 0) { echo " selected"; }
echo "> "._NO." </option>\n<option value='1'";
if ($dl_config['usegfxcheck'] == 1) { echo " selected"; }
echo "> "._YES." </option>\n";
echo "</select></td></tr>\n";
echo "<input type='hidden' name='xdateformat' value='".$dl_config['dateformat']."'>\n";
echo "<input type='hidden' name='op' value='DLConfigSave'>\n";
echo "<tr><td colspan='2'><input type='submit' value='"._SAVECHANGES."'></td></tr>\n";
echo "</form>\n";
echo "</table>\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>