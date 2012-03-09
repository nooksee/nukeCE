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

$pagetitle = _CATEGORIESADMIN;
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title($pagetitle);
DLadminmain();
echo "<br />";
OpenTable();
$perpage = $dl_config['perpage'];
if (!isset($min)) $min=0;
if (!isset($max)) $max=$min+$perpage;
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories"));
pagenums_admin($op, $totalselected, $perpage, $max);
echo "
      <table align='center' cellpadding='2' cellspacing='2' bgcolor='$textcolor1' border='0'>
          <tr bgcolor='$bgcolor2'>
              <td><strong>"._TITLE."</strong></td>
              <td align='center'><strong>"._FUNCTIONS."</strong></td>
          </tr>
     ";
$x = 0;
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories ORDER BY parentid,title LIMIT $min,$perpage");
while($cidinfo = $db->sql_fetchrow($result)) {
    echo "
          <tr bgcolor='$bgcolor1'>
          <form method='post' action='".$admin_file.".php'>
              <input type='hidden' name='op' value='Categories'>
              <input type='hidden' name='min' value='$min'>
              <input type='hidden' name='cid' value='".$cidinfo['cid']."'>
         ";
    $cidinfo['title'] = getparent($cidinfo['parentid'],$cidinfo['title']);
    echo "
          <td>".$cidinfo['title']."</td>
          <td align='center'>
              <select name='op'>
                  <option value='CategoryModify' selected>"._MODIFY."</option>
         ";
    if ($cidinfo['active'] ==1) {
        echo "<option value='CategoryDeactivate'>"._DL_DEACTIVATE."</a>\n";
    } else {
        echo "<option value='CategoryActivate'>"._DL_ACTIVATE."</a>\n";
    }
    echo "
                    <option value='CategoryDelete'>"._DL_DELETE."</option>
                </select>
                <input type='submit' value='"._DL_OK."'>
            </td>
          </tr>
          </form>
          </tr>
         ";
    $x++;
}
echo "</table>";
pagenums_admin($op, $totalselected, $perpage, $max);
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>