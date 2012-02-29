<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_VIEWTEMPLATE;;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();
global $admin_file;
echo "
      <form action='".$admin_file.".php' method='post' target='templateview'>
          <input type='hidden' name='op' value='ABTemplateView' />
          <table align='center' border='0' cellpadding='2' cellspacing='2'>
              <tr>
                  <td>
                      "._AB_TEMPLATE.":
                  </td>
                  <td>
                      <select name='template'>
     ";
$templatelist = "";
$templatedir = dir(NUKE_INCLUDE_DIR.'abuse/');
while($func=$templatedir->read()) {
    if(substr($func, -4) == ".tpl") { $templatelist .= "$func "; }
}
closedir($templatedir->handle);
$templatelist = explode(" ", $templatelist);
sort($templatelist);
for($i=0; $i < sizeof($templatelist); $i++) {
    if($templatelist[$i]!="") {
        $bl = ereg_replace(".tpl","",$templatelist[$i]);
        $bl = ereg_replace("_"," ",$bl);
        echo "<option value='$templatelist[$i]'>".$bl."</option>\n";
    }
}
echo "
                      </select>
                  </td>
                  <td align='center' colspan='2'>
                      <input type=submit value='"._AB_VIEWTEMPLATE."' />
                  </td>
              </tr>
          </table>
      </form>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>