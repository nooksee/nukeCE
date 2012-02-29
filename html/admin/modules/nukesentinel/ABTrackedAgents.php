<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_TRACKEDAGENTS;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_TRACKEDAGENTS);
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
if(!isset($column) or !$column or $column=="") $column = "user_agent";
if(!isset($direction) or !$direction or $direction=="") $direction = "asc";
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
$totalselected = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`user_agent`) FROM `".$prefix."_nsnst_tracked_ips` $modfilter GROUP BY 1"));
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
          <form action=\"".$admin_file.".php?op=ABTrackedAgents\" method=\"post\">
              <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
                  <tr>
                      <td align='left' valign='bottom'>
                          &nbsp;
                          [
                          <a href='".$admin_file.".php?op=ABPrintTrackedAgents' target='_blank'>
                              "._AB_PRINTPAGE."
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
              </table>
          </form>
         ";
    // END Modules
    echo "
          <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
              <tr bgcolor=\"".$bgcolor2."\">
                  <td align=\"left\">
                      <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=user_agent&direction=desc&showmodule=$showmodule'>
                  &nbsp;
                  "._AB_USERAGENT."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=user_agent&direction=asc&showmodule=$showmodule'>
                  &nbsp;
                  "._AB_USERAGENT."
              </a>
             ";
    }                      
    echo "
              </strong>
          </td>
          <td align=\"center\">
              <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=ip_long&direction=desc&showmodule=$showmodule'>
                  "._AB_IPSTRACKED."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=ip_long&direction=asc&showmodule=$showmodule'>
                  "._AB_IPSTRACKED."
              </a>
             ";
    }                      
    echo "                      
              </strong>
          </td>
          <td align=\"center\">
              <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=date&direction=desc&showmodule=$showmodule'>
                  "._AB_LASTVIEWED."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=date&direction=asc&showmodule=$showmodule'>
                  "._AB_LASTVIEWED."
              </a>
             ";
    }                      
    echo "                      
              </strong>
          </td>
          <td align=\"center\">
              <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=3&direction=desc&showmodule=$showmodule'>
                  "._AB_HITS."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedAgents&column=3&direction=asc&showmodule=$showmodule'>
                  "._AB_HITS."
              </a>
             ";
    }                      
    echo "                      
                  </strong>
              </td>
              <td align=\"center\">
                  <strong>
                      "._FUNCTIONS."
                  </strong>
              </td>
          </tr>
         ";
    $result = $db->sql_query("SELECT DISTINCT(`user_agent`), tid, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` $modfilter GROUP BY 1 ORDER BY $column $direction LIMIT $min, $perpage");
        $bgcolor = $bgcolor3;
            while(list($user_agent, $tid, $lastview, $hits) = $db->sql_fetchrow($result)){
                $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                if(strlen($user_agent) > 35) {
                    $uagent = substr($user_agent, 0, 35)."...";
                } else {
                    $uagent = $user_agent;
                }
                echo "
                      <tr".$bgcolor.">
                          <td align=\"left\">
                              &nbsp;
                              $uagent
                          </td>
                     ";
                $trackedips = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_agent`='$user_agent'"));
                echo "
                      <td align='center'>
                          <a href='".$admin_file.".php?op=ABTrackedAgentsIPs&amp;tid=$tid'>
                              $trackedips
                          </a>
                      </td>
                      <td align='center'>
                          ".date("Y-m-d \@ H:i:s",$lastview)."
                      </td>
                      <td align='center'>
                          $hits
                      </td>
                      <td align=\"center\">
                          <a href='".$admin_file.".php?op=ABPrintTrackedAgentsPages&amp;tid=$tid' target='_blank'>
                              <img src='images/sys/print.png' height='16' width='16' alt='"._AB_PRINT."' title='"._AB_PRINT."' border='0' />
                          </a>
                          <a href='".$admin_file.".php?op=ABTrackedAgentsPages&amp;tid=$tid'>
                              <img src='images/view.gif' height='17' width='17' alt='"._AB_VIEW."' title='"._AB_VIEW."' border='0' />
                          </a>
                          <a href='".$admin_file.".php?op=ABTrackedAgentsListAdd&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction'>
                              <img src='images/sys/forbidden.png' height='16' width='16' alt='"._AB_BLOCK."' title='"._AB_BLOCK."' border='0' />
                          </a>
                          <a href='".$admin_file.".php?op=ABTrackedAgentsDelete&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction'>
                              <img src='images/delete.gif' height='17' width='17' alt='"._DELETE."' title='"._DELETE."' border='0' />
                          </a>
                      </td>
                  </tr>
                 ";
            }
    abpagenums($op, $totalselected, "", "", "", $min, $perpage, $max, $column, $direction, $showmodule);
} else {
    ErrorReturn(_AB_NOIPS);
}
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>