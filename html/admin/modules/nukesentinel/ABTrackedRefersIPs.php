<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_REFERIPTRACKING;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_REFERIPTRACKING);
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
                      &nbsp;
                      "._AB_IPADDRESSES."
                  </strong>
              </td>
              <td align='center'>
                  <strong>
                      "._AB_HITDATE."
                  </strong>
              </td>
              <td align='center'>
                  <strong>
                      "._AB_COUNTRY."
                  </strong>
              </td>
     ";
$result = $db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$uname' ORDER BY `ip_long` LIMIT $min, $perpage");
    $bgcolor = $bgcolor3;
        while(list($lipaddr) = $db->sql_fetchrow($result)){
            $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
            $newrow = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$uname' AND `ip_addr`='$lipaddr' ORDER BY `date` DESC LIMIT 1"));
            echo "
                  <tr".$bgcolor.">
                      <td align=\"left\">
                          &nbsp;
                          $lipaddr
                      </td>
                      <td align='center'>
                          ".date("Y-m-d \@ H:i:s",$newrow['date'])."
                      </td>
                 ";
            $countrytitle = abget_countrytitle($newrow['c2c']);
            echo "
                      <td align='center'>
                          ".$countrytitle['country']."
                      </td>
                  </tr>
                 ";
        }
$totalselected = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$uname'"));
abpagenums($op, $totalselected, $tid, "", $ip_addr, $min, $perpage, $max, $column, $direction, $showmodule);
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>