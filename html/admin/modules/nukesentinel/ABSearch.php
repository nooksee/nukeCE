<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_SEARCHIPS;
include(NUKE_BASE_DIR."header.php");
if(!empty($sip)) { $torun = 1; } else { $torun = 0; }
$sip = str_replace("X", "%", $sip);
sentinel_header();

$tempsip =  str_replace("%", "X", $sip);
$tempsip =  str_replace("*", "X", $tempsip);
$tempip = str_replace("*", "0", $sip);
$tempip = str_replace("%", "0", $tempip);
$tempip = sprintf("%u", ip2long($tempip));

OpenTable();
echo "
      <div align=\"center\">
          <em>
              "._AB_SEARCHNOTE."
          </em>
      </div>
      <br />
      <form action='".$admin_file.".php?op=ABSearch' method='post'>
          <table align='center' border='0' cellpadding='2' cellspacing='2'>
              <tr>
                  <td align='center'>
                      <strong>
                          "._AB_SEARCHFOR.":
                      </strong>
                  </td>
                  <td align='center'>
                      <input type='text' name='sip' value='%' />
                  </td>
                  <td align='center'>
                      <input type='submit' value='"._AB_GO."' />
                  </td>
              </tr>
          </table>
      </form>
     ";
CloseTable();
if($torun > 0) {
    //BLOCKED IP SEARCH RESULTS
    $totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` WHERE `ip_addr` LIKE '$sip'"));
    if($totalselected > 0) {
        echo "
              <br />
             ";
        OpenTable();
        echo "
              <div align='center'>
                  <span class='option'>
                      "._AB_SEARCHBLOCKEDIPS."
                  </span>
              </div>
              <br />
              <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
                  <tr bgcolor=\"".$bgcolor2."\">
                      <td align=\"left\">
                          <strong>
                              &nbsp;"._AB_IPBLOCKED."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_COUNTRY."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._DATE."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_EXPIRES."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_REASON."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._FUNCTIONS."
                          </strong>
                      </td>
                  </tr>
             ";
        $result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` WHERE `ip_addr` LIKE '$sip' ORDER BY `ip_long`");
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
                    $ua = $getIPs['user_agent'];
                    $ua = htmlentities($ua, ENT_QUOTES);
                    
                    echo "
                          <td align=\"left\">
                              <a href='".$ab_config['lookup_link']."$lookupip' target='$lookupip'>
                                  &nbsp;".$getIPs['ip_addr']."
                              </a>
                          </td>
                         ";
                    
                    $countrytitle = abget_countrytitle($getIPs['c2c']);
                    $flagimg = flag_img($countrytitle['c2c']);
                    
                    echo "
                              <td align=\"center\">
                                  $flagimg
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
                                  <a href='".$admin_file.".php?op=ABBlockedIPView&amp;xIPs=".$getIPs['ip_addr']."' target='_blank'>
                                      <img src='images/view.gif' border='0' alt='"._AB_VIEW."' title='"._AB_VIEW."' height='17' width='17' />
                                  </a>
                                  <a href='".$admin_file.".php?op=ABBlockedIPEdit&amp;xIPs=".$getIPs['ip_addr']."&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;xop=$op&amp;sip=$tempsip'>
                                      <img src='images/edit.gif' border='0' alt='"._EDIT."' title='"._EDIT."' height='17' width='17' />
                                  </a>
                                  <a href='".$admin_file.".php?op=ABBlockedIPDelete&amp;xIPs=".$getIPs['ip_addr']."&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;xop=$op&amp;sip=$tempsip'>
                                      <img src='images/delete.gif' border='0' alt='"._DELETE."' title='"._DELETE."' height='17' width='17' />
                                  </a>
                              </td>
                          </tr>
                         ";
                }
            echo "
                  </table>
                 ";
            CloseTable();
    }

    //TRACKED IP SEARCH RESULTS
    $totalselected = $db->sql_numrows($db->sql_query("SELECT `username`, `ip_addr`, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` WHERE `ip_addr` LIKE '$sip' GROUP BY 1,2"));
    if($totalselected > 0) {
        echo "
              <br />
             ";
        OpenTable();
        echo "
              <div align='center'>
                  <span class='option'>
                      "._AB_SEARCHTRACKEDIPS."
                  </span>
              </div>
              <br />
              <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
                  <tr bgcolor=\"".$bgcolor2."\">
                      <td align=\"left\">
                          <strong>
                              &nbsp;<img src='images/pix.gif' height='12' width='13' alt='' title='' border='0'>
                              "._AB_IPADDRESS."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_COUNTRY."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_LASTVIEWED."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_HITS."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._FUNCTIONS."
                          </strong>
                      </td>
                  </tr>
             ";
        $result = $db->sql_query("SELECT `user_id`, `username`, `ip_addr`, MAX(`date`), COUNT(*), MIN(`tid`), `c2c` FROM `".$prefix."_nsnst_tracked_ips` WHERE `ip_addr` LIKE '$sip' GROUP BY 2,3");
            $bgcolor = $bgcolor3;
                while(list($userid,$username,$ipaddr,$lastview,$hits,$tid, $c2c) = $db->sql_fetchrow($result)){
                    $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                    echo "
                          <tr".$bgcolor.">
                              <td align=\"left\">
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
                    
                    $countrytitle = abget_countrytitle($c2c);
                    $getIPs['country'] = $countrytitle['country'];
                    $flagimg = flag_img($countrytitle['c2c']);
                    
                    echo "
                              <td align=\"center\">
                                  $flagimg
                              </td>
                              <td align=\"center\">
                                  ".date("Y-m-d \@ H:i:s",$lastview)."
                              </td>
                              <td align=\"center\">
                                  $hits
                              </td>
                              <td align=\"center\">
                                  <a href='".$admin_file.".php?op=ABPrintTrackedPages&amp;user_id=$userid&amp;ip_addr=$ipaddr' target='_blank'>
                                      <img src='images/sys/print.png' height='16' width='16' alt='"._AB_PRINT."' title='"._AB_PRINT."' border='0' />
                                  </a>
                                  <a href='".$admin_file.".php?op=ABTrackedPages&amp;user_id=$userid&amp;ip_addr=$ipaddr' target='_blank'>
                                      <img src='images/view.gif' height='17' width='17' alt='"._AB_VIEW."' title='"._AB_VIEW."' border='0' />
                                  </a>
                                  <a href='".$admin_file.".php?op=ABTrackedAdd&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;showmodule=$showmodule' target='_blank'>
                                      <img src='images/sys/forbidden.png' height='16' width='16' alt='"._AB_BLOCK."' title='"._AB_BLOCK."' border='0' />
                                  </a>
                                  <a href='".$admin_file.".php?op=ABTrackedDelete&amp;tid=$tid&amp;min=$min&amp;column=$column&amp;direction=$direction&amp;showmodule=$showmodule&amp;xop=$op&amp;sip=$tempsip'>
                                      <img src='images/delete.gif' height='17' width='17' alt='"._DELETE."' title='"._DELETE."' border='0' />
                                  </a>
                              </td>
                          </tr>
                         ";
                }
        echo "
              </table>
             ";
        CloseTable();
    }

    //USER IP SEARCH RESULTS
    $totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$user_prefix."_users` WHERE `last_ip` LIKE '$sip'"));
    if($totalselected > 0) {
        echo "
              <br />
             ";
        OpenTable();
        echo "
              <div align='center'>
                  <span class='option'>
                      "._AB_USERSDB."
                  </span>
              </div>
              <br />
              <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
                  <tr bgcolor=\"".$bgcolor2."\">
                      <td align=\"center\">
                          <strong>
                              "._AB_USERID."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._USERNAME."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_USEREMAIL."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_LASTIP."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._AB_REGDATE."
                          </strong>
                      </td>
                  </tr>
             ";
        $result = $db->sql_query("SELECT * FROM `".$user_prefix."_users` WHERE `last_ip` LIKE '$sip'");
            $bgcolor = $bgcolor3;
                while($chnginfo = $db->sql_fetchrow($result)) {
                    $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                    echo "
                          <tr".$bgcolor.">
                              <td align=\"center\">
                                  ".$chnginfo['user_id']."
                              </td>
                              <td align=\"center\">
                                  <a href='modules.php?name=Your_Account&op=userinfo&amp;username=".$chnginfo['username']."' target='_blank'>
                                      ".$chnginfo['username']."
                                  </a>
                              </td>
                              <td align=\"center\">
                                  <a href='mailto:".$chnginfo['user_email']."'>
                                      ".$chnginfo['user_email']."
                                  </a>
                              </td>
                              <td align=\"center\">
                                  <a href='".$ab_config['lookup_link']."".$chnginfo['last_ip']."' target='_blank'>
                                      ".$chnginfo['last_ip']."
                                  </a>
                              </td>
                              <td align=\"center\">
                                  ".$chnginfo['user_regdate']."
                              </td>
                          </tr>
                         ";
                }
        echo "
              </table>
             ";
        CloseTable();
    }
}
include(NUKE_BASE_DIR."footer.php");

?>