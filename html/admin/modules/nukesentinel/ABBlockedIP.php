<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_BLOCKEDIPS;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();
$perpage = $ab_config['block_perpage'];
if($perpage == 0) { $perpage = 25; }
if(!isset($min)) { $min=0; }
if(!isset($max)) { $max=$min+$perpage; }
if(!isset($column) || empty($column)) $column = $ab_config['block_sort_column'];
if(!isset($direction) || empty($direction)) $direction = $ab_config['block_sort_direction'];
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips`"));
if($totalselected > 0) {
    echo "
          <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
              <tr>
                  <td align='left' valign='bottom'>
                      &nbsp;
                      [
                      <a href='".$admin_file.".php?op=ABPrintBlockedIP' target='_blank'>
                          "._AB_PRINTPAGE."
                      </a>
                      |
                      <a href='".$admin_file.".php?op=ABBlockedIPClear'>
                          "._AB_CLEAR."
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
         ";
    echo "
          <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
              <tr bgcolor=\"".$bgcolor2."\">
                  <td align=\"left\">
                      <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABBlockedIP&column=ip_long&direction=desc'>
                  &nbsp;"._AB_IPBLOCKED."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABBlockedIP&column=ip_long&direction=asc'>
                  &nbsp;"._AB_IPBLOCKED."
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
              <a href='".$admin_file.".php?op=ABBlockedIP&column=c2c&direction=desc'>
                  "._AB_COUNTRY."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABBlockedIP&column=c2c&direction=asc'>
                  "._AB_COUNTRY."
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
              <a href='".$admin_file.".php?op=ABBlockedIP&column=date&direction=desc'>
                  "._DATE."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABBlockedIP&column=date&direction=asc'>
                  "._DATE."
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
              <a href='".$admin_file.".php?op=ABBlockedIP&column=expires&direction=desc'>
                  "._AB_EXPIRES."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABBlockedIP&column=expires&direction=asc'>
                  "._AB_EXPIRES."
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
              <a href='".$admin_file.".php?op=ABBlockedIP&column=reason&direction=desc'>
                  "._AB_REASON."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABBlockedIP&column=reason&direction=asc'>
                  "._AB_REASON."
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
    $result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` ORDER BY `$column` $direction LIMIT $min,$perpage");
        $bgcolor = $bgcolor3;
            while($getIPs = $db->sql_fetchrow($result)) {
                $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                list($getIPs['reason']) = $db->sql_fetchrow($db->sql_query("SELECT `reason` FROM `".$prefix."_nsnst_blockers` WHERE `blocker`='".$getIPs['reason']."'"));
                $getIPs['reason'] = str_replace("Abuse-", "", $getIPs['reason']);
                $bdate = date("Y-m-d @ H:i:s", $getIPs['date']);
                $lookupip = str_replace("*", "0", $getIPs['ip_addr']);
                if($getIPs['expires']==0) { $bexpire = _AB_PERMENANT; } else { $bexpire = date("Y-m-d @ H:i:s", $getIPs['expires']); }
                list($bname) = $db->sql_fetchrow($db->sql_query("SELECT `username` FROM `".$user_prefix."_users` WHERE `user_id`='".$getIPs['user_id']."'"));
                echo "
                      <tr".$bgcolor.">
                     ";
                
                $qs = htmlentities(base64_decode($getIPs['query_string']));
                $qs = str_replace("%20", " ", $qs);
                $qs = str_replace("/**/", "/* */", $qs);
                $qs = str_replace("&", "<br />&", $qs);
                $gs = htmlentities(base64_decode($getIPs['get_string']));
                $gs = str_replace("%20", " ", $gs);
                $gs = str_replace("/**/", "/* */", $gs);
                $gs = str_replace("&", "<br />&", $gs);
                $ps = htmlentities(base64_decode($getIPs['post_string']));
                $ps = str_replace("%20", " ", $ps);
                $ps = str_replace("/**/", "/* */", $ps);
                $ps = str_replace("&", "<br />&", $ps);
                $ua = $getIPs['user_agent'];
                $ua = htmlentities($ua, ENT_QUOTES);
                $getIPs['flag_img'] = flag_img($getIPs['c2c']);
                
                echo "
                          <td align=\"left\">
                              <a href=\"".$ab_config['lookup_link']."$lookupip\" target=\"".$getIPs['ip_long']."\">
                                  &nbsp;".$getIPs['ip_addr']."
                              </a>
                          </td>
                          <td align=\"center\">
                              ".$getIPs['flag_img']."
                          </td>
                          <td align=\"center\">
                              $bdate
                          </td>
                          <td align=\"center\">
                              $bexpire
                          </td>
                          <td align=\"center\">
                              ".$getIPs['reason']."
                          </td>
                          <td align=\"center\">
                              <a href='".$admin_file.".php?op=ABPrintBlockedIPView&amp;xIPs=".$getIPs['ip_addr']."' target='_blank'>
                                  <img src='images/sys/print.png' border='0' alt='"._AB_PRINT."' title='"._AB_PRINT."' height='16' width='16' />
                              </a>
                              <a href='".$admin_file.".php?op=ABBlockedIPView&amp;xIPs=".$getIPs['ip_addr']."'>
                                  <img src='images/view.gif' border='0' alt='"._AB_VIEW."' title='"._AB_VIEW."' height='17' width='17' />
                              </a>
                              <a href='".$admin_file.".php?op=ABBlockedIPEdit&amp;xIPs=".$getIPs['ip_addr']."&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;xop=$op'>
                                  <img src='images/edit.gif' border='0' alt='"._EDIT."' title='"._EDIT."' height='17' width='17' />
                              </a>
                              <a href='".$admin_file.".php?op=ABBlockedIPDelete&amp;xIPs=".$getIPs['ip_addr']."&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;xop=$op'>
                                  <img src='images/delete.gif' border='0' alt='"._DELETE."' title='"._DELETE."' height='17' width='17' />
                              </a>
                          </td>
                      </tr>
                     ";
            }
    abpagenums($op, $totalselected, "", "", "", $min, $perpage, $max, $column, $direction, "");
                echo "
                  <a href='".$admin_file.".php?op=".$op."&tid=$tid&user_id=$user_id&ip_addr=$ip_addr&min=".($min - $perpage)."&column=$column&direction=$direction&showmodule=$showmodule'>
                      <font face='Verdana' size='2'>
                          &laquo; "._PREVPAGE."
                      </font>
                  </a>
                 ";

} else {
    ErrorReturn(_AB_NOIPS);
}
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>