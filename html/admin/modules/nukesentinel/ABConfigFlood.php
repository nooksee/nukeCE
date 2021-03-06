<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_CONFIGURATION.": "._AB_FLOODBLOCKER;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
OpenTable();
OpenMenu(_AB_FLOODBLOCKER);
configmenu();
CarryMenu();
configsubmenu();
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
                  " ._AB_FLOODBLOCKER . "
                  &nbsp;
              </span>
          </legend>
          <form action='".$admin_file.".php' method='post'>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
     ";
$blocker_row = abget_blocker("flood");
$blocker_row['duration'] = $blocker_row['duration'] / 86400;
echo "
      <tr>
          <td>
              "._AB_ACTIVATE.":
          </td>
          <td colspan=\"3\">
     ";
if(!empty($ab_config['ftaccess_path']) AND is_writable($ab_config['ftaccess_path'])) {
    $sel0 = $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = $sel7 = $sel8 = $sel9 = "";
    if($blocker_row['activate']==1) { $sel1 = " selected"; } elseif($blocker_row['activate']==2) { $sel2 = " selected"; } elseif($blocker_row['activate']==3) { $sel3 = " selected"; } elseif($blocker_row['activate']==4) { $sel4 = " selected"; } elseif($blocker_row['activate']==5) { $sel5 = " selected"; } elseif($blocker_row['activate']==6) { $sel6 = " selected"; } elseif($blocker_row['activate']==7) { $sel7 = " selected"; } elseif($blocker_row['activate']==8) { $sel8 = " selected"; } elseif($blocker_row['activate']==9) { $sel9 = " selected"; } else { $sel0 = " selected"; }
    echo "
          <select name='xblocker_row[activate]'>
              <option value='0'$sel0>
                  "._AB_OFF."
              </option>
              <option value='1'$sel1>
                  "._AB_EMAILONLY."
              </option>
              <option value='6'$sel6>
                  "._AB_FORWARDONLY."
              </option>
              <option value='7'$sel7>
                  "._AB_TEMPLATEONLY."
              </option>
              <option value='2'$sel2>
                  "._AB_EMAILFORWARD."
              </option>
              <option value='3'$sel3>
                  "._AB_EMAILTEMPLATE."
              </option>
              <option value='8'$sel8>
                  "._AB_BLOCKFORWARD."
              </option>
              <option value='9'$sel9>
                  "._AB_BLOCKTEMPLATE."
              </option>
              <option value='4'$sel4>
                  "._AB_EMAILBLOCKFORWARD."
              </option>
              <option value='5'$sel5>
                  "._AB_EMAILBLOCKTEMPLATE."
              </option>
          </select>
         ";
} else {
    echo "
          <strong>
              "._AB_FTACCESSFAILED."
          </strong>
         ";
}

echo "
          </td>
      </tr>
      <tr>
          <td>
              "._AB_HTWRITE."?
          </td>
          <td colspan=\"3\">
     ";
if(stristr($_SERVER['SERVER_SOFTWARE'], "Apache") AND $ab_config['htaccess_path'] > "") {
    echo yesno_option('xblocker_row[htaccess]', $blocker_row['htaccess']);
} else {
    echo "
          <strong>
              "._AB_HTACCESSFAILED."
          </strong>
          <input type='hidden' name='xblocker_row[ftaccess]' value='0' />
         ";
}
echo "
          </td>
      </tr>
      <tr>
          <td>
              "._AB_FORWARD.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xblocker_row[forward]' size='50' value='".$blocker_row['forward']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._AB_BLOCKTYPE.":
          </td>
          <td colspan=\"3\">
              <select name='xblocker_row[block_type]'>
     ";
$sel1 = $sel2 = $sel3 = $sel4 = "";
if($blocker_row['block_type']==0) { $sel1 = " selected"; } elseif($blocker_row['block_type']==1) { $sel2 = " selected"; } elseif($blocker_row['block_type']==2) { $sel3 = " selected"; } else { $sel4 = " selected"; }
echo "
                  <option value='0'$sel1>
                      "._AB_0OCTECT."
                  </option>
                  <option value='1'$sel2>
                      "._AB_1OCTECT."
                  </option>
                  <option value='2'$sel3>
                      "._AB_2OCTECT."
                  </option>
                  <option value='3'$sel4>
                      "._AB_3OCTECT."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_TEMPLATE.":
          </td>
          <td colspan=\"3\">
              <select name='xblocker_row[template]'>
     ";
$templatedir = dir(NUKE_INCLUDE_DIR.'abuse/');
$templatelist = '';
while($func=$templatedir->read()) {
    if(substr($func, 0, 6) == "abuse_") { $templatelist .= "$func "; }
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
        if($templatelist[$i]==$blocker_row['template']) { echo "selected "; }
        echo "value='$templatelist[$i]'>".ucfirst($bl)."</option>\n";
    }
}
echo "
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_EMAILLOOKUP.":
          </td>
     ";
$mailtest = @mail();
if(!$mailtest AND !stristr($_SERVER['SERVER_SOFTWARE'], "PHP-CGI")) {
    $sel0 = $sel1 = $sel2 = "";
    if($blocker_row['email_lookup']==1) { $sel1 = " selected"; } elseif($blocker_row['email_lookup']==2) { $sel2 = " selected"; } else { $sel0 = " selected"; }
    echo "
          <td colspan=\"3\">
              <select name='xblocker_row[email_lookup]'>
                  <option value='0'$sel0>
                      "._AB_OFF."
                  </option>
                  <option value='1'$sel1>
                      Arin.net
                  </option>
                  <option value='2'$sel2>
                      DNSStuff.com
                  </option>
              </select>
          </td>
         ";
} else {
    echo "
          <td>
              <strong>
                  "._AB_NOTAVAILABLE."
              </strong>
              <input type='hidden' name='xblocker_row[email_lookup]' value='0' />
          <td>
         ";
}
echo "
      </tr>
      <tr>
          <td>
              "._AB_REASON.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xblocker_row[reason]' size='20' maxlength='20' value='".$blocker_row['reason']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._AB_DURATION.":
          </td>
          <td colspan=\"3\">
              <select name='xblocker_row[duration]'>
     ";
echo "<option value='0'";
if($blocker_row['duration']==0) { echo " selected"; }
echo ">"._AB_PERMENANT."</option>\n";
$i=1;
while($i<=365) {
    echo "<option value='$i'";
    if($blocker_row['duration']==$i) { echo " selected"; }
    $expiredate = date("Y-m-d", time() + ($i * 86400));
    echo ">$i ($expiredate)</option>\n";
    $i++;
}
echo "
                          </select>
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
              <div align=\"center\">
                  <input type='hidden' name='xblocker_row[block_name]' value='flood' />
                  <input type='hidden' name='xop' value='$op' />
                  <input type='hidden' name='op' value='ABConfigSave' />                   
                  <input type=submit value='"._SAVECHANGES."' />
              </div>
          </form>
      </td>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>