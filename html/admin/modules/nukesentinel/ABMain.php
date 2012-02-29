<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_ADMINISTRATION;
include(NUKE_BASE_DIR."header.php");
$ip_sets = abget_configs();
GraphicAdmin();
OpenTable();
OpenMenu(_AB_ADMINISTRATION);
abmenu();
CarryMenu();
blankmenu();
CloseMenu();
CloseTable();
echo "
      <br />
     ";
OpenTable();
echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_GENERALSETTINGS . "
                  &nbsp;
              </span>
          </legend>
          <form action='".$admin_file.".php' method='post'>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <tr>
                      <td>
                          "._AB_DISABLESWITCH.":
                      </td>
                      <td colspan=\"3\">
     ";
$seldable1 = $seldable2 = "";
if($ip_sets['disable_switch'] == 1) { $seldable2 = "selected='selected'"; } else { $seldable1 = "selected='selected'"; }
echo "
              <select name='xdisable_switch'>
                  <option value='0' $seldable1>
                      "._AB_ENABLED."
                  </option>
                  <option value='1' $seldable2>
                      "._AB_DISABLED."
                  </option>
              </select>
          </td>
      </tr>
     ";
$seldns1=$seldns2='';
echo "
      <tr>
          <td>
              "._AB_IPLOOKUPSITE.":
          </td>
          <td colspan=\"3\">
     ";
if(stristr($ip_sets['lookup_link'], "DNSstuff.com/")) { $seldns2 = "selected='selected'"; } else { $seldns1 = "selected='selected'"; }
echo "
              <select name='xlookup_link'>
                  <option value='http://ws.arin.net/cgi-bin/whois.pl?queryinput=' $seldns1>
                      Arin Net
                  </option>
                  <option value='http://www.DNSstuff.com/tools/whois.ch?ip=' $seldns2>
                      DNS Stuff
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_FORCENUKEURL."?
          </td>
          <td colspan=\"3\">
     ";
echo yesno_option('xforce_nukeurl', $ip_sets['force_nukeurl']);
echo "
          </td>
      </tr>
      <tr>
          <td>
              "._AB_PAGEDELAY.":
          </td>
          <td colspan=\"3\">
              <select name='xpage_delay'>
     ";
$i=1;
while($i<=10) {
    echo "<option value='$i'";
    if($ip_sets['page_delay']==$i) { echo " selected='selected'"; }
    echo ">$i seconds</option>";
    $i++;
}
echo "
              </select>
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._AB_FLOODDELAY.":
          </td>
          <td colspan=\"3\">
              <select name='xflood_delay'>
     ";
$i=1;
while($i<=5) {
    echo "<option value='$i'";
    if($ip_sets['flood_delay']==$i) { echo " selected='selected'"; }
    echo ">$i seconds</option>\n";
    $i++;
}
echo "
              </select>
              <span class=\"tiny\">
                  ("._AB_FLOODNOTE.")
              </span>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_DISPLAYLINK.":
          </td>
          <td colspan=\"3\">
              <select name='xdisplay_link'>
     ";
$sel1 = $sel2 = $sel3 = $sel4 = "";
if($ip_sets['display_link']==1) { $sel2 = " selected='selected'"; } elseif($ip_sets['display_link']==2) { $sel3 = " selected='selected'"; } elseif($ip_sets['display_link']==3) { $sel4 = " selected='selected'"; } else { $sel1 = " selected='selected'"; }
echo "
                  <option value='0'$sel1>
                      "._AB_NONE."
                  </option>
                  <option value='1'$sel2>
                      "._AB_ADMINS."
                  </option>
                  <option value='2'$sel3>
                      "._AB_USERS."
                  </option>
                  <option value='3'$sel4>
                      "._AB_VISITORS."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
          "._AB_DISPLAYREASON.":
          </td>
          <td colspan=\"3\">
              <select name='xdisplay_reason'>
     ";
$sel1 = $sel2 = $sel3 = $sel4 = "";
if($ip_sets['display_reason']==1) { $sel2 = " selected='selected'"; } elseif($ip_sets['display_reason']==2) { $sel3 = " selected='selected'"; } elseif($ip_sets['display_reason']==3) { $sel4 = " selected='selected'"; } else { $sel1 = " selected='selected'"; }
echo "
                  <option value='0'$sel1>
                      "._AB_NONE."
                  </option>
                  <option value='1'$sel2>
                      "._AB_ADMINS."
                  </option>
                  <option value='2'$sel3>
                      "._AB_USERS."
                  </option>
                  <option value='3'$sel4>
                      "._AB_VISITORS."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_SITESWITCH.":
          </td>
          <td colspan=\"3\">
              <select name='xsite_switch'>
     ";
$sel1 = $sel2 = "";
if($ip_sets['site_switch']==1) { $sel2 = " selected='selected'"; } else { $sel1 = " selected='selected'"; }
echo "
                  <option value='0'$sel1>
                      "._AB_SITEENABLED."
                  </option>
                  <option value='1'$sel2>
                      "._AB_SITEDISABLED."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_TEMPLATE.":
          </td>
          <td colspan=\"3\">
              <select name='xsite_reason'>
     ";
$templatedir = dir(NUKE_INCLUDE_DIR.'abuse/');
$templatelist = "";
while($func=$templatedir->read()) {
    if(substr($func, 0, 6) == "admin_") {
        $templatelist .= "$func ";
    }
}
closedir($templatedir->handle);
$templatelist = explode(" ", $templatelist);
sort($templatelist);
for($i=0; $i < sizeof($templatelist); $i++) {
    if($templatelist[$i]!="") {
        $bl = ereg_replace("admin_","",$templatelist[$i]);
        $bl = ereg_replace(".tpl","",$bl);
        $bl = ereg_replace("_"," ",$bl);
        echo "<option ";
        if($templatelist[$i]==$ip_sets['site_reason']) { echo "selected='selected' "; }
        echo "value='$templatelist[$i]'>".ucfirst($bl)."</option>";
    }
}
echo "
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_PROXYBLOCKER.":
          </td>
          <td colspan=\"3\">
     ";
$selproxy1=$selproxy2=$selproxy3=$selproxy4='';
if($ip_sets['proxy_switch'] == 1) { $selproxy2 = "selected='selected'"; } elseif($ip_sets['proxy_switch'] == 2) { $selproxy3 = "selected='selected'"; } elseif($ip_sets['proxy_switch'] == 3) { $selproxy4 = "selected='selected'"; } else { $selproxy1 = "selected='selected'"; }
echo "
              <select name='xproxy_switch'>
                  <option value='0' $selproxy1>
                      "._AB_OFF."
                  </option>
                  <option value='1' $selproxy2>
                      "._AB_PROXYLITE."
                  </option>
                  <option value='2' $selproxy3>
                      "._AB_PROXYMILD."
                  </option>
                  <option value='3' $selproxy4>
                      "._AB_PROXYSTRONG."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_TEMPLATE.":
          </td>
          <td colspan=\"3\">
              <select name='xproxy_reason'>
     ";
$templatedir = dir(NUKE_INCLUDE_DIR.'abuse/');
$templatelist = "";
while($func=$templatedir->read()) {
    if(substr($func, 0, 6) == "abuse_") {
        $templatelist .= "$func ";
    }
}
closedir($templatedir->handle);
$templatelist = explode(" ", $templatelist);
sort($templatelist);
for($i=0; $i < sizeof($templatelist); $i++) {
    if($templatelist[$i]!="") {
        $bl = ereg_replace("abuse_","",$templatelist[$i]);
        $bl = ereg_replace(".tpl","",$bl);
        $bl = ereg_replace("_"," ",$bl);
        echo "<option ";
        if($templatelist[$i]==$ip_sets['proxy_reason']) { echo "selected='selected' "; }
        echo "value='$templatelist[$i]'>".ucfirst($bl)."</option>";
    }
}
echo "
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_SELFEXPIRE.":
          </td>
          <td colspan=\"3\">
     ";
echo onoff_option('xself_expire', $ip_sets['self_expire']);
echo "
          </td>
      </tr>
      <tr>
          <td>
              "._AB_SANTYPROTECTION.":
          </td>
          <td colspan=\"3\">
     ";
echo onoff_option('xsanty_protection', $ip_sets['santy_protection']);
echo "
          </td>
      </tr>
      <tr>
          <td>
              "._AB_PREVENTDOS.":
          </td>
          <td colspan=\"3\">
     ";
echo onoff_option('xprevent_dos', $ip_sets['prevent_dos']);
echo "
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_ADMINISTRATIVE . "
                  &nbsp;
              </span>
          </legend>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <tr>
                      <td valign='top'>
                          "._AB_ADMINAUTH.":
                      </td>
                      <td colspan=\"3\">
     ";
$apass = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_admins` WHERE `password_md5`='' OR `password`='' OR `password_crypt`=''"));
$sapi_name = strtolower(php_sapi_name());
$selauth1=$selauth2=$selauth3='';
if($ip_sets['http_auth'] == 1) { $selauth2 = "selected='selected'"; } else if($ip_sets['http_auth'] == 2) { $selauth3 = "selected='selected'"; } else { $selauth1 = "selected='selected'"; }
echo "
      <select name='xhttp_auth'>
          <option value='0' $selauth1>
              "._AB_OFF."
          </option>
     ";
if(strpos($sapi_name,"cgi")===FALSE && ini_get("register_globals")) {
    echo "
          <option value='1' $selauth2>
              "._AB_HTTPAUTH."
          </option>
         ";
}
echo "
                  <option value='2' $selauth3>
                  "._AB_CGIAUTH."
                  </option>
              </select>
      <tr>
          <td valign='top'>
              "._AB_HTACCESSPATH.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xhtaccess_path' size='50' value='".$ip_sets['htaccess_path']."' />
     ";
$rp = strtolower(str_replace ('index.php', '', realpath('index.php')));
echo "
      <br />
      "._AB_NORMALLY.": ".$rp.".htaccess
     ";
if($ip_sets['htaccess_path']>"") {
    $httest = is_writable($ip_sets['htaccess_path']);
    if(!$httest) { echo "<br /><em><font color='red'>"._AB_HTWARNING."</font></em>"; }
    if(!stristr($_SERVER['SERVER_SOFTWARE'], "apache")) { echo "<br /><em><b>"._AB_NOTSUPPORTED."</b></em>"; }
}
echo "
      <tr>
          <td valign='top'>
              "._AB_STACCESSPATH.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xstaccess_path' size='50' value='".$ip_sets['staccess_path']."' />
     ";
$rp = strtolower(str_replace ('index.php', '', realpath('index.php')));
echo "
      <a href='".$admin_file.".php?op=ABCGIAuth' target='_blank'>
          <img src='images/icon_help.gif' height='13' width='13' border='0' alt='CGIAuth Setup ' title='CGIAuth Setup ' />
      </a>
      <br />
      "._AB_NORMALLY.": ".$rp.".staccess
     ";
if($ip_sets['staccess_path']>"") {
    $httest = is_writable($ip_sets['staccess_path']);
    if(!$httest) { echo "<br /><em><font color='red'>"._AB_STWARNING."</font></em>"; }
    if(!stristr($_SERVER['SERVER_SOFTWARE'], "apache")) { echo "<br /><em><b>"._AB_NOTSUPPORTED."</b></em>"; }
}
echo "
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._AB_FTACCESSPATH.":
          </td>
          <td colspan=\"3\">
     ";
if(stristr($_SERVER['SERVER_SOFTWARE'], "Apache")) {
    echo "<input type='text' name='xftaccess_path' size='50' value='".$ip_sets['ftaccess_path']."' />";
    $rp = strtolower(str_replace ('index.php', '', realpath('index.php')));
    echo "
          <br />
          "._AB_NORMALLY.": ".$rp.".ftaccess
         ";
    if($ip_sets['ftaccess_path']>"") {
        $fttest = is_writable($ip_sets['ftaccess_path']);
        if(!$fttest) {
            echo "
                  <br />
                  <em>
                      <font color='red'>
                          " . _AB_FTWARNING . "
                      </font>
                  </em>
                 ";
        }
    }
} else {
    echo "
          <br />
          <em>
              <b>
                  " . _AB_NOTAVAILABLE . "
              </b>
          </em>
          <input type='hidden' name='xftaccess_path' value='' />
         ";
}
echo "
                  <tr>
                      <td>
                          "._AB_CRYPTSALT.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xcrypt_salt' size='3' maxlength='2' value='".$ip_sets['crypt_salt']."' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_ADMINLIST.":
                      </td>
                      <td colspan=\"3\">
                          <textarea name='xadmin_contact' $textrowcol>".$ip_sets['admin_contact']."</textarea>
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_IPTRACKERSETTINGS . "
                  &nbsp;
              </span>
          </legend>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <tr>
                      <td>
                          "._AB_IPTRACKER.":
                      </td>
                      <td colspan=\"3\">
     ";
echo onoff_option('xtrack_active', $ip_sets['track_active']);
echo "
          </td>
      </tr>
      <tr>
          <td>
              "._AB_MAXIMUMDAYS.":
          </td>
          <td colspan=\"3\">
              <select name='xtrack_max'>
     ";
$selmax='';
if($ip_sets['track_max']==0) { $selmax = _AB_UNLIMITED; }
echo "
      <option value='0'$selmax>
          "._AB_UNLIMITED."
      </option>
     ";
$i=1;
while($i<=31) {
    $j = $i * 86400;
    echo "<option value='$j'";
    if($ip_sets['track_max']==$j) { echo " selected='selected'"; }
    echo ">$i</option>";
    $i++;
}
echo "
                          </select>
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_BLOCKEDPAGE . "
                  &nbsp;
              </span>
          </legend>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <tr>
                      <td>
                          "._AB_IPSPERPAGE.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xblock_perpage' size='5' value='".$ip_sets['block_perpage']."' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_SORTCOLUMN.":
                      </td>
                      <td colspan=\"3\">
                          <select name='xblock_sort_column'>
     ";
$selcolumn1 = $selcolumn2 = $selcolumn3 = $selcolumn4 = $selcolumn5 = "";
if($ip_sets['block_sort_column'] == "ip_long") $selcolumn1 = "selected='selected'";
if($ip_sets['block_sort_column'] == "expires") $selcolumn2 = "selected='selected'";
if($ip_sets['block_sort_column'] == "date") $selcolumn3 = "selected='selected'";
if($ip_sets['block_sort_column'] == "reason") $selcolumn4 = "selected='selected'";
if($ip_sets['block_sort_column'] == "c2c") $selcolumn5 = "selected='selected'";
echo "
                  <option value='ip_long'$selcolumn1>
                      "._AB_IPBLOCKED."
                  </option>
                  <option value='expires'$selcolumn2>
                      "._AB_EXPIRES."</option>
                  <option value='date'$selcolumn3>
                      "._DATE."
                  </option>
                  <option value='reason'$selcolumn4>
                      "._AB_REASON."
                  </option>
                  <option value='c2c'$selcolumn5>
                      "._AB_C2CODE."
                  </option
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_SORTDIRECTION.":
          </td>
          <td colspan=\"3\">
              <select name='xblock_sort_direction'>
     ";
$seldirection1 = $seldirection2 = "";
if($ip_sets['block_sort_direction'] == "asc") $seldirection1 = "selected='selected'";
if($ip_sets['block_sort_direction'] == "desc") $seldirection2 = "selected='selected'";
echo "
                              <option value='asc'$seldirection1>
                                  "._AB_ASC."
                              </option>
                              <option value='desc'$seldirection2>
                                  "._AB_DESC."
                              </option>
                          </select>
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_TRACKEDPAGE . "
                  &nbsp;
              </span>
          </legend>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <tr>
                      <td>
                          "._AB_IPSPERPAGE.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xtrack_perpage' size='5' value='".$ip_sets['track_perpage']."' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_SORTCOLUMN.":
                      </td>
                      <td colspan=\"3\">
                          <select name='xtrack_sort_column'>
     ";
$selcolumn1 = $selcolumn2 = $selcolumn3 = $selcolumn4 = $selcolumn5 = $selcolumn6 = "";
if($ip_sets['track_sort_column'] == "ip_long") $selcolumn1 = "selected='selected'";
if($ip_sets['track_sort_column'] == "date") $selcolumn3 = "selected='selected'";
if($ip_sets['track_sort_column'] == "username") $selcolumn4 = "selected='selected'";
if($ip_sets['track_sort_column'] == 5) $selcolumn5 = "selected='selected'";
if($ip_sets['track_sort_column'] == "c2c") $selcolumn6 = "selected='selected'";
echo "
                  <option value='ip_long'$selcolumn1>
                      "._AB_IPTRACKED."
                  </option>
                  <option value='date'$selcolumn3>
                      "._DATE."
                  </option>
                  <option value='username'$selcolumn4>
                      "._USERNAME."
                  </option>
                  <option value=5 $selcolumn5>
                      "._AB_HITS."
                  </option>
                  <option value='c2c'$selcolumn6>
                      "._AB_C2CODE."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_SORTDIRECTION.":
          </td>
          <td colspan=\"3\">
              <select name='xtrack_sort_direction'>
     ";
$seldirection1 = $seldirection2 = "";
if($ip_sets['track_sort_direction'] == "asc") $seldirection1 = "selected='selected'";
if($ip_sets['track_sort_direction'] == "desc") $seldirection2 = "selected='selected'";
echo "
                              <option value='asc'$seldirection1>
                                  "._AB_ASC."
                              </option>
                              <option value='desc'$seldirection2>
                                  "._AB_DESC."
                              </option>
                          </select>
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
              <div align=\"center\">
                  <input type='hidden' name='op' value='ABMainSave' />                      
                  <input type=submit value='"._SAVECHANGES."' />
              </div>
          </form>
      </td>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>