<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_TRACKEDREFERS;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_TRACKEDREFERS);
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
if(!isset($min)) $min=0;
if(!isset($max)) $max=$min+$perpage;
if(!isset($column) or !$column or $column=="") $column = "refered_from";
if(!isset($direction) or !$direction or $direction=="") $direction = "asc";
$totalselected = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`refered_from`) FROM `".$prefix."_nsnst_tracked_ips` GROUP BY 1"));
if($totalselected > 0) {
    echo "
          <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
              <tr>
                  <td align='left' valign='bottom'>
                      &nbsp;
                      [
                      <a href='".$admin_file.".php?op=ABPrintTrackedRefers' target='_blank'>
                          "._AB_PRINTPAGE."
                      </a>
                      ]
                  </td>                  
              </tr>
              <tr>
                  <td align='left'>
                      &nbsp;
                  </td>                  
              </tr>
          </table>
          <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
              <tr bgcolor=\"".$bgcolor2."\">
                  <td align=\"left\">
                      <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=refered_from&direction=desc'>
                  &nbsp;
                  "._AB_USERREFER."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=refered_from&direction=asc'>
                  &nbsp;
                  "._AB_USERREFER."
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
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=ip_long&direction=desc'>
                  "._AB_IPSTRACKED."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=ip_long&direction=asc'>
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
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=date&direction=desc'>
                  "._AB_LASTVIEWED."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=date&direction=asc'>
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
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=4&direction=desc'>
                  "._AB_HITS."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABTrackedRefers&column=4&direction=asc'>
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
    $result = $db->sql_query("SELECT DISTINCT(`refered_from`), tid, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` GROUP BY 1 ORDER BY $column $direction LIMIT $min, $perpage");
        $bgcolor = $bgcolor3;
            while(list($refered_from, $tid, $lastview, $hits) = $db->sql_fetchrow($result)){
                $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                echo "
                      <tr".$bgcolor.">
                     ";
                if(strlen($refered_from) > 35) {
                    $rfrom = substr($refered_from, 0, 35)."...";
                } else {
                    $rfrom = $refered_from;
                }
                if($rfrom != "on site" AND $rfrom != "none" AND $rfrom !="local") {
                    $rfrom = "<a href=\"includes/nsbypass.php?tid=".$tid."\" target=\"_blank\" title=\"$refered_from\">".html_entity_decode($rfrom, ENT_QUOTES)."</a>";
                } else {
                    $rfrom = $rfrom;
                }
                echo "
                      <td align=\"left\">
                          &nbsp;
                          $rfrom
                      </td>
                     ";
                $trackedips = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$refered_from'"));
                echo "
                      <td align='center'>
                          <a href='".$admin_file.".php?op=ABTrackedRefersIPs&amp;tid=$tid'>
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
                          <a href='".$admin_file.".php?op=ABPrintTrackedRefersPages&amp;tid=$tid' target='_blank'>
                              <img src='images/sys/print.png' height='16' width='16' alt='"._AB_PRINT."' title='"._AB_PRINT."' border='0' />
                          </a>
                          <a href='".$admin_file.".php?op=ABTrackedRefersPages&amp;tid=$tid'>
                              <img src='images/view.gif' height='17' width='17' alt='"._AB_VIEW."' title='"._AB_VIEW."' border='0' />
                          </a>
                          <a href='".$admin_file.".php?op=ABTrackedRefersListAdd&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction'>
                              <img src='images/sys/forbidden.png' height='16' width='16' alt='"._AB_BLOCK."' title='"._AB_BLOCK."' border='0' />
                          </a>
                          <a href='".$admin_file.".php?op=ABTrackedRefersDelete&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction'>
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