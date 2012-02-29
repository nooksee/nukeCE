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

$pagetitle = _CATEGORIESADMIN.": "._ADDCATEGORY;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title($pagetitle);
$folder = dirname(dirname(__FILE__));
if(preg_match('/\/(.*?)\//', $folder)) {
    $folder .= '/files/';
} else {
    $folder .= '\files\\';
}
DLadminmain();
echo "<br />\n";
OpenTable();
echo "<table align='center' cellpadding='2' cellspacing='2' border='0'>\n";
echo "<form method='post' action='".$admin_file.".php'>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._NAME.":</td><td><input type='text' name='title' size='50' maxlength='50'></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._PARENT."</td><td><select name='cid'><option value='0' selected>"._DL_NONE."</option>\n";
$result = $db->sql_query("SELECT cid, title, parentid FROM ".$prefix."_downloads_categories WHERE parentid='0' ORDER BY title");
while($cidinfo = $db->sql_fetchrow($result)) {
  $crawled = array($cidinfo['cid']);
  CrawlLevel($cidinfo['cid']);
  $x=0;
  while ($x <= (count($crawled)-1)) {
    list($title,$parentid) = $db->sql_fetchrow($db->sql_query("SELECT title, parentid FROM ".$prefix."_downloads_categories WHERE cid='$crawled[$x]'"));
    if ($x > 0) { $title = getparent($parentid,$title); }
    echo "<option value='$crawled[$x]'>$title</option>\n";
    $x++;
  }
}
echo "</select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2' valign='top'>"._DESCRIPTION.":</td><td><textarea name='cdescription' cols='50' rows='5'></textarea></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._DL_WHOADD.":</td><td><select name='whoadd'>\n";
echo "<optgroup label='"._DLGENERAL."'>\n";
echo "<option value='-1'>"._DL_NONE."</option>\n";
echo "<option value='0' selected>"._DL_ALL."</option>\n";
echo "<option value='1'>"._DL_USERS."</option>\n";
echo "<option value='2'>"._DL_ADMIN."</option>\n";
echo "</optgroup><optgroup label='"._DLGROUPS."'>\n";
$gresult = $db->sql_query("SELECT * FROM ".$prefix."_bbgroups WHERE group_single_user != '1' ORDER BY group_name");
while($gidinfo = $db->sql_fetchrow($gresult)) {
  $gidinfo['group_id'] = $gidinfo['group_id'] + 2;
  echo "<option value='".$gidinfo['group_id']."'>".$gidinfo['group_name']." "._DL_ONLY."</option>\n";
}
echo "</optgroup></select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2' valign='top'>"._UPDIRECTORY.":</td><td><input type='text' name='uploaddir' size='50' maxlength='255'><br />("._USEUPLOAD.")<br />(".$folder.")</td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'>"._DL_CANUPLOAD.":</td><td><select name='canupload'>\n";
echo "<option value='0'>"._DL_NO."</option>\n";
echo "<option value='1'>"._DL_YES."</option>\n";
echo "</select></td></tr>\n";
echo "<input type='hidden' name='op' value='CategoryAddSave'>\n";
echo "<tr><td align='center' colspan='2'><input type='submit' value='"._ADDCATEGORY."'></td></tr>\n";
echo "</form>\n</table>\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>