<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_REFERTRACKING;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_REFERTRACKING);
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
if(!$column or $column=="") $column = "date";
if(!$direction or $direction=="") $direction = "desc";
$tid=intval($tid);
list($uname) = $db->sql_fetchrow($db->sql_query("SELECT `refered_from` FROM `".$prefix."_nsnst_tracked_ips` WHERE `tid`='$tid'"));
echo "
      <div align='center' class='content'>
          <b>
              $uname
          </b>
      </div>
      <br />
      <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
          <tr bgcolor=\"".$bgcolor2."\">
              <td align=\"left\">
                  <strong>
     ";
if($direction == "asc") {
    echo "
          <a href='".$admin_file.".php?op=ABTrackedRefersPages&tid=$tid&column=page&direction=desc&showmodule=$showmodule'>
              &nbsp;
              "._AB_PAGEVIEWED."
          </a>
         ";
} else {
    echo "
          <a href='".$admin_file.".php?op=ABTrackedRefersPages&tid=$tid&column=page&direction=asc&showmodule=$showmodule'>
              &nbsp;
              "._AB_PAGEVIEWED."
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
          <a href='".$admin_file.".php?op=ABTrackedRefersPages&tid=$tid&column=date&direction=desc&showmodule=$showmodule'>
              "._AB_HITDATE."
          </a>
         ";
} else {
    echo "
          <a href='".$admin_file.".php?op=ABTrackedRefersPages&tid=$tid&column=date&direction=asc&showmodule=$showmodule'>
              "._AB_HITDATE."
          </a>
         ";
}                      
echo "                      
          </strong>
          </td>
      </tr>
     ";
$result = $db->sql_query("SELECT `ip_addr`, `page`, `date` FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$uname' ORDER BY $column $direction LIMIT $min, $perpage");
    $bgcolor = $bgcolor3;
        while(list($lipaddr, $page, $date_time) = $db->sql_fetchrow($result)){
            $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
            if(strlen($page) > 60) {
                $upage = substr($page, 0, 60)."...";
            } else {
                $upage = $page;
            }
            echo "
                  <tr".$bgcolor.">
                      <td align=\"left\">
                          &nbsp;
                          <a href='$page'>
                              $upage
                          </a>
                      </td>
                      <td align=\"center\">
                          ".date("Y-m-d \@ H:i:s",$date_time)."
                      </td>
                  </tr>
                 ";
        }
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$uname'"));
abpagenums($op, $totalselected, $tid, "", $ip_addr, $min, $perpage, $max, $column, $direction, $showmodule);
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>