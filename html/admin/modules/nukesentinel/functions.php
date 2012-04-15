<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

function abget_country($tempip){
    global $prefix, $db;
    $tempip = str_replace(".*", ".0", $tempip);
    $tempip = sprintf("%u", ip2long($tempip));
    $countryinfo = $db->sql_fetchrow($db->sql_query("SELECT `cc2` FROM `".$prefix."_country_ip` WHERE `ip_from`<='$tempip' AND `ip_to`>='$tempip' LIMIT 0,1"));
    $ctitle = abget_countrytitle($countryinfo['c2c']);
    $countryinfo['country'] = $ctitle['country'];
    if(!$countryinfo) {
      $countryinfo['c2c'] = "00";
      $countryinfo['country'] = _AB_UNKNOWN;
    } else {
      if(!file_exists("images/nukesentinel/countries/".$countryinfo['c2c'].".png")) { $countryinfo['c2c']="00"; }
    }
    return $countryinfo;
}

function abget_countrytitle($c2c){
    global $prefix, $db;
    $countrytitleinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_countries` WHERE `c2c`='$c2c'"));
    if(!$countrytitleinfo) {
        $countrytitleinfo['c2c'] = "00";
        $countrytitleinfo['country'] = _AB_UNKNOWN;
    } else {
        if(!file_exists("images/nukesentinel/countries/".$countrytitleinfo['c2c'].".png")) { $countrytitleinfo['c2c']="00"; }
    }
    return $countrytitleinfo;
}

function absave_config($config_name, $config_value){
    global $prefix, $db, $cache;
    $resultnum = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_config` WHERE `config_name`='$config_name'"));
    if($resultnum < 1) {
        $db->sql_query("INSERT INTO `".$prefix."_nsnst_config` (`config_name`, `config_value`) VALUES ('$config_name', '$config_value')");
    } else {
        $db->sql_query("UPDATE `".$prefix."_nsnst_config` SET `config_value`='$config_value' WHERE `config_name`='$config_name'");
    }
    $cache->delete('sentinel', 'config');
    $cache->resync();
}

function absave_blocker($blocker_row){
    global $prefix, $db, $cache;
    $block_list = explode("\r\n", $blocker_row['list']);
    sort($block_list);
    $blocker_row['list'] = implode("\r\n", $block_list);
    $blocker_row['list'] = str_replace("\r\n\r\n", "\r\n", $blocker_row['list']);
    $blocker_row['duration'] = $blocker_row['duration'] * 86400;
    $db->sql_query("UPDATE `".$prefix."_nsnst_blockers` SET `activate`='".$blocker_row['activate']."', `block_type`='".$blocker_row['block_type']."', `email_lookup`='".$blocker_row['email_lookup']."', `forward`='".$blocker_row['forward']."', `reason`='".$blocker_row['reason']."', `template`='".$blocker_row['template']."', `duration`='".$blocker_row['duration']."', `htaccess`='".$blocker_row['htaccess']."', `list`='".$blocker_row['list']."' WHERE `block_name`='".$blocker_row['block_name']."'");
    $cache->delete('', 'sentinel');
    $cache->resync();
}

function flag_img($c2c) {
    global $prefix, $db;
    $c2c = strtolower($c2c);
    list($xcountry) = $db->sql_fetchrow($db->sql_query("SELECT `country` FROM `".$prefix."_nsnst_countries` WHERE `c2c`='$c2c'"));
    if(!file_exists("images/nukesentinel/countries/".$c2c.".png")) {
        return "<img src=\"images/nukesentinel/countries/00.png\" border=\"0\" height=\"15\" width=\"25\" alt=\"($c2c) $xcountry\" title=\"($c2c) $xcountry\" />";
    } else {
        return "<img src=\"images/nukesentinel/countries/$c2c.png\" border=\"0\" height=\"15\" width=\"25\" alt=\"($c2c) $xcountry\" title=\"($c2c) $xcountry\" />";
    }
}

function abview_template($template="") {
    global $nuke_config, $ab_config, $nsnst_const, $db, $prefix, $ip;
    if(empty($template)) { $template = "abuse_default.tpl"; }
    $sitename = $nuke_config['sitename'];
    $adminmail = $nuke_config['adminmail'];
    $adminmail = str_replace("@", "(at)", $adminmail);
    $adminmail = str_replace(".", "(dot)", $adminmail);
    $adminmail2 = urlencode($nuke_config['adminmail']);
    $querystring = get_query_string();
    $filename = NUKE_INCLUDE_DIR."abuse/".$template;
    if(!file_exists($filename)) { $filename = "abuse/abuse_default.tpl"; }
    $handle = @fopen($filename, "r");
    $display_page = fread($handle, filesize($filename));
    @fclose($handle);
    $display_page = str_replace("__SITENAME__", $sitename, $display_page);
    $display_page = str_replace("__ADMINMAIL1__", $adminmail, $display_page);
    $display_page = str_replace("__ADMINMAIL2__", $adminmail2, $display_page);
    $display_page = str_replace("__REMOTEPORT__", $nsnst_const['remote_port'], $display_page);
    $display_page = str_replace("__REQUESTMETHOD__", $nsnst_const['request_method'], $display_page);
    $display_page = str_replace("__SCRIPTNAME__", $nsnst_const['script_name'], $display_page);
    $display_page = str_replace("__HTTPHOST__", $nsnst_const['http_host'], $display_page);
    $display_page = str_replace("__USERAGENT__", $nsnst_const['user_agent'], $display_page);
    $display_page = str_replace("__CLIENTIP__", $nsnst_const['client_ip'], $display_page);
    $display_page = str_replace("__FORWARDEDFOR__", $nsnst_const['forward_ip'], $display_page);
    $display_page = str_replace("__REMOTEADDR__", $nsnst_const['remote_addr'], $display_page);
    $display_page = str_replace("__TIMEDATE__", date("Y-m-d \@ H:i:s T \G\M\T O", $nsnst_const['ban_time']), $display_page);
    $display_page = str_replace("__DATEEXPIRES__", _AB_UNKNOWN, $display_page);
    return $display_page;
}

function sentinel_header() {
global $admin_file;
    GraphicAdmin();
    OpenTable();
    echo "
          <div align='center'>
              <font class='title'>
                  <a href='$admin_file.php?op=ABMain'>
                      "._AB_SENTINEL." "._AB_ADMINISTRATION."
                  </a>
              </font>
              <a href='includes/nscopyright.php' rel='4' class='newWindow'>
                  &copy;
              </a>    
          </div>
         ";
    CloseTable();
    echo "
          <br />
         ";
}

function OpenMenu($adsection="") {
    global $admin_file, $ab_config, $nsnab_ver;
    if($ab_config['disable_switch'] == 1) { $nsnststatus = _AB_DISABLED; } else { $nsnststatus = _AB_ENABLED; }
    if(!empty($adsection)) { $adsection = ": ".$adsection; }
    echo "
          <div align='center'>
              <font class='title'>
                  <a href='$admin_file.php?op=ABMain'>
                    "._AB_SENTINEL." ".$ab_config['version_number'].": ".$nsnststatus."$adsection
                  </a>
              </font>
              <a href='includes/nscopyright.php' rel='4' class='newWindow'>
                  &copy;
              </a>
          </div>
          <table width='100%' border='0' cellspacing='1' cellpadding='4'>
              $nsnab_ver
              <tr>
                  <td align='center' valign='top' width='66%'>
         ";
}

function abmenu() {
    global $ab_config, $getAdmin, $prefix, $db, $op, $admin, $admin_file, $nsnab_ver;
    $sapi_name = strtolower(php_sapi_name());
    echo "
          <table align='center' border='0' cellpadding='2' cellspacing='2' width='90%'>
              <tr>
                  <td align='center' valign='top' width='50%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfig'>
                                      "._AB_BLOCKERCONFIG."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABBlockedIPMenu'>
                                      "._AB_BLOCKEDIPMENU."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABTrackedMenu'>
                                      "._AB_TRACKEDIPMENU."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABTemplate'>
                                      "._AB_TEMPLATES."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td align='center' valign='top' width='50%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABSearch'>
                                      "._AB_SEARCHIPS."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABCountryList'>
                                      "._AB_COUNTRYLISTING."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
         ";
    if(is_god($admin)) { echo "<a href='".$admin_file.".php?op=ABAuthList'>"; }
    echo " 
                  "._AB_LISTHTTPAUTH."
                  </a>
              </td>
          </tr>
          <tr>
              <td align='center'>
         ";
    if(is_god($admin)) { echo "<a href='".$admin_file.".php?op=ABAuthListScan'>"; }
    echo " 
                                  "._AB_SCANADMINS."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
}

function configmenu() {
    global $ab_config, $admin_file;
    echo "
          <table align='center' border='0' cellpadding='2' cellspacing='2' width='90%'>
              <tr>
                  <td align='center' valign='top' width='50%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigAdmin'>
                                      "._AB_ADMINBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigAuthor'>
                                      "._AB_AUTHORBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigClike'>
                                      "._AB_CLIKEBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigUnion'>
                                      "._AB_UNIONBLOCKER."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td align='center' valign='top' width='50%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigFilter'>
                                      "._AB_FILTERBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigFlood'>
                                      "._AB_FLOODBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigHarvester'>
                                      "._AB_HARVESTBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigReferer'>
                                      "._AB_REFERERBLOCKER."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
}

function CarryMenu() {
    echo "
          </td>
          <td align='center' valign='top' width='34%'>
         ";
}

function blankmenu() {
    echo "
          <div align='center'>
              "._AB_SUBMENU."
          </div>
         ";
}

function configsubmenu() {
    global $ab_config, $admin_file;
    echo "
          <table align='center' border='0' cellpadding='2' cellspacing='2' width='90%'>
              <tr>
                  <td align='center' valign='top' width='33%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigScript'>
                                      "._AB_SCRIPTBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigRequest'>
                                      "._AB_REQUESTBLOCKER."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABConfigString'>
                                      "._AB_STRINGBLOCKER."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
}

function blockedipmenu() {
    global $admin, $admin_file;
    echo "
          <table align='center' border='0' cellpadding='2' cellspacing='2' width='90%'>
              <tr>
                  <td align='center' valign='top' width='33%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABBlockedIPAdd'>
                                      "._AB_ADDIP."
                                  </a>
                              </td>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABBlockedIP'>
                                      "._AB_BLOCKEDIPS."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABBlockedIPClear'>
                                      "._AB_CLEARIP."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABBlockedIPClearExpired'>
                                      "._AB_CLEAREXPIRED."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
}

function trackedmenu() {
    global $admin, $admin_file;
    echo "
          <table align='center' border='0' cellpadding='2' cellspacing='2' width='90%'>
              <tr>
                  <td align='center' valign='top' width='33%'>
                      <table align='center' border='0' cellpadding='2' cellspacing='2'>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABTracked'>
                                      "._AB_TRACKEDIPS."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABTrackedUsers'>
                                      "._AB_TRACKEDUSERS."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABTrackedAgents'>
                                      "._AB_TRACKEDAGENTS."
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td align='center'>
                                  <a href='".$admin_file.".php?op=ABTrackedRefers'>
                                      "._AB_TRACKEDREFERS."
                                  </a>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
}

function CloseMenu() {
    echo "
                  </td>
              </tr>
          </table>
         ";
}

function abpagenums($op, $totalselected, $tid, $user_id, $ip_addr, $min, $perpage, $max, $column, $direction, $showmodule) {
    global $admin_file;
    $pagesint = ($totalselected / $perpage);
    $pageremainder = ($totalselected % $perpage);
    if ($pageremainder != 0) {
        $pages = ceil($pagesint);
        if ($totalselected < $perpage) { $pageremainder = 0; }
    } else {
        $pages = $pagesint;
    }
    if ($pages != 1 && $pages != 0) {
        $counter = 1;
        $currentpage = ($max / $perpage);
        echo "
              <table align='center' border='0' cellspacing='0' cellpadding='0' width='70%'>
                  <tr>
                      <td colspan='6'>
                          <img src='images/pix.gif' height='2' width='2' alt='' title='' />
                          <br />
                          <br />
                      </td>
                  </tr>
                  <tr>
                      <td colspan='6'>
                          <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tr>
                                  <td align='left' width='20%'>";
        if($currentpage <= 1) {
            echo "";
        } else {
            echo "
                  <a href='".$admin_file.".php?op=".$op."&tid=$tid&user_id=$user_id&ip_addr=$ip_addr&min=".($min - $perpage)."&column=$column&direction=$direction&showmodule=$showmodule'>
                      <font face='Verdana' size='2'>
                          &laquo; "._PREVPAGE."
                      </font>
                  </a>
                 ";
        }
        echo "
              </td>
              <td align='center' width='60%'>
                  <font face='Verdana' size='2'>
                      "._PAGE.":
                  </font>
            ";
        $start_page = ($currentpage-5<1)?1:($currentpage-5); //Set starting page to page-5, or 1 if less than 1
        $end_page = ($currentpage+4>$pages)?$pages:($currentpage+4); //Set ending page to page+5, or pages if more than 1
        for($counter=$start_page; $counter<=$end_page; $counter++) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if($counter == $currentpage) {
                echo "
                      <font face='Verdana' size='2' color=red>
                          $counter
                      </font>
                      &nbsp;
                     ";
            } else {
                echo "
                      <a href='".$admin_file.".php?op=".$op."&tid=$tid&user_id=$user_id&ip_addr=$ip_addr&min=$mintemp&column=$column&direction=$direction&showmodule=$showmodule'>
                          <font face='Verdana' size='2'>
                              $counter
                          </font>
                      </a>
                      &nbsp;
                     ";
            }
        }
        echo "
              </td>
              <td align='right' width='20%'>
             ";
        if($currentpage >= $pages) {
            echo "";
        } else {
            echo "
                  <a href='".$admin_file.".php?op=".$op."&tid=$tid&user_id=$user_id&ip_addr=$ip_addr&min=".($min + $perpage)."&column=$column&direction=$direction&showmodule=$showmodule'>
                      <font face='Verdana' size='2'>
                          "._NEXTPAGE." &raquo;
                      </font>
                  </a>
                 ";
        }
        echo "
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
             ";
    }
    echo "
          </table>
         ";
}

?>