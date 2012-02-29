<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_USERIPTRACKING;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_USERIPTRACKING);
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
$user_id=intval($user_id);
list($uname) = $db->sql_fetchrow($db->sql_query("SELECT `username` FROM `".$user_prefix."_users` WHERE `user_id`='$user_id'"));
echo "
      <div align='center' class='content'>
          <b>
              $uname ($user_id)
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
              <td align='center'>
                  <strong>
                      "._FUNCTIONS."
                  </strong>
              </td>
     ";
$result = $db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_id`='$user_id' ORDER BY `ip_long` LIMIT $min, $perpage");
    $bgcolor = $bgcolor3;
        while(list($lipaddr) = $db->sql_fetchrow($result)){
            $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
            $newrow = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_id`='$user_id' AND `ip_addr`='$lipaddr' ORDER BY `date` DESC LIMIT 1"));
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
                      <td align='center'>
                          <a href='".$admin_file.".php?op=ABTrackedDeleteUserIP&amp;user_id=$user_id&amp;ip_addr=$lipaddr&amp;min=$min&amp;xop=$op'>
                              <img src='images/delete.gif' height='17' width='17' alt='"._DELETE."' title='"._DELETE."' border='0' />
                          </a>
                      </td>
                  </tr>
                 ";
        }
$totalselected = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_id`='$user_id'"));
abpagenums($op, $totalselected, "", $user_id, $ip_addr, $min, $perpage, $max, $column, $direction, $showmodule);
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>