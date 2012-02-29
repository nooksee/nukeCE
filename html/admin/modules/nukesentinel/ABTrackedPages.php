<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_PAGETRACKING;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_PAGETRACKING);
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
if(!$column or $column=="") $column = "date";
if(!$direction or $direction=="") $direction = "desc";
$tid=intval($tid);
$result = $db->sql_query("SELECT `ip_long` FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_id`='$user_id' AND `ip_addr`='$ip_addr'");
list($ip_long) = $db->sql_fetchrow($result);
echo "
      <div align='center' class='content'>
          <b>
              $ip_addr
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
          <a href='".$admin_file.".php?op=ABTrackedPages&user_id=$user_id&ip_addr=$ip_addr&column=page&direction=desc&showmodule=$showmodule'>
              &nbsp;
              "._AB_PAGEVIEWED."
          </a>
         ";
} else {
    echo "
          <a href='".$admin_file.".php?op=ABTrackedPages&user_id=$user_id&ip_addr=$ip_addr&column=page&direction=asc&showmodule=$showmodule'>
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
          <a href='".$admin_file.".php?op=ABTrackedPages&user_id=$user_id&ip_addr=$ip_addr&column=date&direction=desc&showmodule=$showmodule'>
              "._AB_HITDATE."
          </a>
         ";
} else {
    echo "
          <a href='".$admin_file.".php?op=ABTrackedPages&user_id=$user_id&ip_addr=$ip_addr&column=date&direction=asc&showmodule=$showmodule'>
              "._AB_HITDATE."
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
$result = $db->sql_query("SELECT `tid`, `page`, `date` FROM `".$prefix."_nsnst_tracked_ips` WHERE `ip_addr`='$ip_addr' AND `user_id`='$user_id' ORDER BY $column $direction LIMIT $min, $perpage");
    $bgcolor = $bgcolor3;
        while(list($ltid, $page,$date_time) = $db->sql_fetchrow($result)){
            $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
            if(strlen($page) > 53) {
                $upage = substr($page, 0, 53)."...";
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
                      <td align='center'>
                          <a href='".$admin_file.".php?op=ABTrackedDeleteSave&amp;tid=$ltid&amp;user_id=$user_id&amp;ip_addr=$ip_addr&amp;column=$column&amp;direction=$direction&amp;min=$min'>
                              <img src='images/delete.gif' height='17' width='17' alt='"._DELETE."' title='"._DELETE."' border='0' />
                          </a>
                      </td>
                  </tr>
                 ";
        }
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_tracked_ips` WHERE `ip_addr`='$ip_addr' AND `user_id`='$user_id'"));
abpagenums($op, $totalselected, "", $user_id, $ip_addr, $min, $perpage, $max, $column, $direction, $showmodule);
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>