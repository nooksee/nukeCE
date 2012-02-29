<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_TRACKEDIPS;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_TRACKEDIPS);
abmenu();
CarryMenu();
trackedmenu();
CloseMenu();
CloseTable();
echo "
      <br />
     ";
OpenTable();
$perpage = $ab_config['track_perpage'];
if($perpage == 0) { $perpage = 25; }
if(!isset($showmodule)) $showmodule="All_Modules";
if(!isset($min)) $min=0;
if(!isset($max)) $max=$min+$perpage;
if(!isset($column) or !$column or $column=="") $column = $ab_config['track_sort_column'];
if(!isset($direction) or !$direction or $direction=="") $direction = $ab_config['track_sort_direction'];
if(ereg("All.*Modules", $showmodule) || !$showmodule ) {
    $modfilter="";
} elseif(ereg("Admin", $showmodule)) {
    $modfilter="WHERE page LIKE '%".$admin_file.".php%'";
} elseif(ereg("Index", $showmodule)) {
    $modfilter="WHERE page LIKE '%index.php%'";
} elseif(ereg("Backend", $showmodule)) {
    $modfilter="WHERE page LIKE '%backend.php%'";
} else {
    $modfilter="WHERE page LIKE '%name=$showmodule%'";
}
$totalselected = $db->sql_numrows($db->sql_query("SELECT `username`, `ip_addr`, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` $modfilter GROUP BY 1,2"));
if($totalselected > 0) {
    // START Modules
    $handle=opendir('modules');
    $moduleslist = '';
    while($file = readdir($handle)) {
        if( (!ereg("^[.]",$file)) && !ereg("html$", $file) ) {
            $moduleslist .= "$file ";
        }
    }
    closedir($handle);
    $moduleslist .= "&nbsp;All&nbsp;Modules &nbsp;Index &nbsp;Admin &nbsp;Backend";
    $moduleslist = explode(" ", $moduleslist);
    sort($moduleslist);
    echo "
          <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\" width=\"96%\">
              <form action=\"".$admin_file.".php?op=ABTracked\" method=\"post\">
                  <tr>
                      <td align='left' valign='bottom'>
                          [
                          <a href='".$admin_file.".php?op=ABPrintTracked' target='_blank'>
                              "._AB_PRINTPAGE."
                          </a>
                          |
                          <a href='".$admin_file.".php?op=ABTrackedClear'>
                              "._AB_CLEAR."
                          </a>
                          ]
                      </td>                  
                      <td align='right'>
                          <select name=\"showmodule\">
                              <option value=\"\">
                                  "._AB_MODULE."
                              </option>
         ";
    for($i=0; $i < sizeof($moduleslist); $i++) {
        if($moduleslist[$i]!="") {
            $moduleslist[$i] = str_replace("&nbsp;", " ", $moduleslist[$i]);
            echo "<option value=\"$moduleslist[$i]\" ";
            if (!isset($showmodule)) $showmodule = '';
            if($showmodule==$moduleslist[$i] OR ((!$showmodule OR $showmodule=="") AND $moduleslist[$i]==" All Modules")) { echo " selected"; }
            echo ">".$moduleslist[$i]."</option>\n";
        }
    }
    echo "
                          </select>
                          <input type='hidden' name='column' value='$column' />
                          <input type='hidden' name='direction' value='$direction' />
                          <input type='submit' value='"._AB_GO."' />
                      </td>
                  </tr>
              </form>
          </table>
         ";
    // END Modules
    echo "
          <table align=\"center\" width=\"96%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"5\" height=\"28\" align=\"center\">
                      <span class=\"cattitle\">
                          "._AB_TRACKEDIPS."
                      </span>
                  </td>
              </tr>
              <tr>
                  <th colspan=\"1\" align=\"left\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=ip_long&direction=desc&showmodule=$showmodule'>
                  &nbsp;<img src='images/pix.gif' height='12' width='13' alt='' title='' border='0'>
                  "._AB_IPADDRESS."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=ip_long&direction=asc&showmodule=$showmodule'>
                  &nbsp;<img src='images/pix.gif' height='12' width='13' alt='' title='' border='0'>
                  "._AB_IPADDRESS."
              </a>
             ";
    }                      
    echo "
              </strong>
          </th>
          <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
              <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=c2c&direction=desc&showmodule=$showmodule'>
                  "._AB_COUNTRY."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=c2c&direction=asc&showmodule=$showmodule'>
                  "._AB_COUNTRY."
              </a>
             ";
    }                      
    echo "                      
              </strong>
          </th>
          <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
              <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=date&direction=desc&showmodule=$showmodule'>
                  "._AB_LASTVIEWED."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=date&direction=asc&showmodule=$showmodule'>
                  "._AB_LASTVIEWED."
              </a>
             ";
    }                      
    echo "                      
              </strong>
          </th>
          <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
              <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=5&direction=desc&showmodule=$showmodule'>
                  "._AB_HITS."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTracked&column=5&direction=asc&showmodule=$showmodule'>
                  "._AB_HITS."
              </a>
             ";
    }                      
    echo "                      
                  </strong>
              </th>
              <th colspan=\"1\" align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                  <strong>
                      "._FUNCTIONS."
                  </strong>
              </th>
          </tr>
         ";
    $result = $db->sql_query("SELECT `user_id`, `username`, `ip_addr`, MAX(`date`), COUNT(*), MIN(`tid`), `c2c` FROM `".$prefix."_nsnst_tracked_ips` $modfilter GROUP BY 2,3 ORDER BY $column $direction LIMIT $min, $perpage");
    while(list($userid,$username,$ipaddr,$lastview,$hits,$tid,$c2c) = $db->sql_fetchrow($result)){
        $row_class = ($c++%2==1) ? 'row2' : 'row1';
        echo "
              <tr>
                  <td class=".$row_class." align=\"left\">
             ";
        if($userid != 1) {
            echo "
                  <a href='modules.php?name=Your_Account&amp;op=userinfo&amp;username=$username' target='_blank'>
                      &nbsp;<img src='modules/Forums/templates/subSilver/images/icon_mini_profile.gif' height='12' width='13' alt='$username' title='$username' border='0' />
                  </a>
                 ";
        } else {
            echo "
                  &nbsp;<img src='images/pix.gif' height='12' width='13' alt='' title='' border='0'>
                 ";
        }
        echo "
                  <a href='".$ab_config['lookup_link']."$ipaddr' target='_blank'>
                      $ipaddr
                  </a>
              </td>
             ";
        $getIPs['flag_img'] = flag_img($c2c);
        echo "
                  <td class=".$row_class." align=\"center\">
                      ".$getIPs['flag_img']."
                  </td>
                  <td class=".$row_class." align=\"center\">
                      ".date("Y-m-d \@ H:i:s",$lastview)."
                  </td>
                  <td class=".$row_class." align=\"center\">
                      $hits
                  </td>
                  <td class=".$row_class." align=\"center\">
                      <a href='".$admin_file.".php?op=ABPrintTrackedPages&amp;user_id=$userid&amp;ip_addr=$ipaddr' target='_blank'>
                          <img src='images/sys/print.png' height='16' width='16' alt='"._AB_PRINT."' title='"._AB_PRINT."' border='0' />
                      </a>
                      <a href='".$admin_file.".php?op=ABTrackedPages&amp;user_id=$userid&amp;ip_addr=$ipaddr'>
                          <img src='images/view.gif' height='17' width='17' alt='"._AB_VIEW."' title='"._AB_VIEW."' border='0' />
                      </a>
                      <a href='".$admin_file.".php?op=ABTrackedAdd&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;showmodule=$showmodule'>
                          <img src='images/sys/forbidden.png' height='16' width='16' alt='"._AB_BLOCK."' title='"._AB_BLOCK."' border='0' />
                      </a>
                      <a href='".$admin_file.".php?op=ABTrackedDelete&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;showmodule=$showmodule&amp;xop=$op'>
                          <img src='images/delete.gif' height='17' width='17' alt='"._DELETE."' title='"._DELETE."' border='0' />
                      </a>
                  </td>
              </tr>
             ";
    }
    abpagenums($op, $totalselected, "", "", "", $min, $perpage, $max, $column, $direction, $showmodule);
    echo "
          <span class=\"gen\">
          <br />
          </span>
         ";
} else {
    ErrorReturn(_AB_NOIPS);
}
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>