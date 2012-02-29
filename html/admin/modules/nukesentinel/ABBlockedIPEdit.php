<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_EDITIP;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();

$getIPs = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` WHERE `ip_addr`='$xIPs'"));
$getIPs['date'] = date("Y-m-d H:i:s",$getIPs['date']);
$getIPs['expires'] = round(($getIPs['expires'] - time()) / 86400);

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_EDITIP . "
                  &nbsp;
              </span>
          </legend>
          <form action='".$admin_file.".php' method='post'>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
     ";
$tip = explode(".", $xIPs);
echo "
      <tr>
          <td>
              "._AB_IPBLOCKED.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xip[0]' value='$tip[0]' size='4' maxlength='3' align='right' />
              .
              <input type='text' name='xip[1]' value='$tip[1]' size='4' maxlength='3' align='right' />
              .
              <input type='text' name='xip[2]' value='$tip[2]' size='4' maxlength='3' align='right' />
              .
              <input type='text' name='xip[3]' value='$tip[3]' size='4' maxlength='3' align='right' />
              <span class=\"tiny\">
                  (" . _AB_EDITIPS . ")
              </span>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_USERID.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xuser_id' size='5' value='".$getIPs['user_id']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._USERNAME.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xusername' size='30' value='".$getIPs['username']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._AB_AGENT.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xuser_agent' size='30' value='".$getIPs['user_agent']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._AB_BLOCKEDON.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xdatetime' size='20' value='".$getIPs['date']."' />
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._AB_EXPIRESIN.":
          </td>
          <td colspan=\"3\">
              <select name='xexpires'>
                  <option value='0'";
if($getIPs['expires']==0) { echo " selected"; }
echo ">"._AB_PERMENANT."</option>";
$i=1;
while($i<=365) {
    echo "<option value='$i'";
    if($getIPs['expires']==$i) { echo " selected"; }
    $expiredate = date("Y-m-d", time() + ($i * 86400));
    echo ">$i ($expiredate)</option>";
    $i++;
}
echo "
              </select>
              <br />
              "._AB_EXPIRESINS."
          </td>
      </tr>
      <tr>
          <td>
              "._AB_COUNTRY.":
          </td>
          <td colspan=\"3\">
              <select name='xc2c'>
                  <option value='00'>
                      "._AB_SELECTCOUNTRY."
                  </option>
     ";
$result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_countries` ORDER BY `c2c`");
while($countryrow = $db->sql_fetchrow($result)) {
    echo "<option value='".$countryrow['c2c']."'";
    if($countryrow['c2c'] == $getIPs['c2c']) { echo " selected"; }
    echo ">".strtoupper($countryrow['c2c'])." - ".$countryrow['country']."</option>";
}
echo "
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_NOTES.":
          </td>
          <td colspan=\"3\">
              <textarea name='xnotes' $textrowcol>".$getIPs['notes']."</textarea>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_REASON.":
          </td>
          <td colspan=\"3\">
              <select name='xreason'>
     ";
$result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_blockers` ORDER BY `block_name`");
while($blockerrow = $db->sql_fetchrow($result)) {
    echo "<option value='".$blockerrow['blocker']."'";
    if($getIPs['reason']==$blockerrow['blocker']) { echo " selected"; }
    echo ">".$blockerrow['reason']."</option>\n";
}
echo "
              </select>
          </td>
      </tr>
      <tr>
     ";

$getIPs['query_string'] = htmlentities(base64_decode($getIPs['query_string']));
$getIPs['query_string'] = str_replace("%20", " ", $getIPs['query_string']);
$getIPs['query_string'] = str_replace("/**/", "/* */", $getIPs['query_string']);

echo "
                  <tr>
                      <td>
                          "._AB_QUERY.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[query_string]\" size=\"40\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_X_FORWARDED.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[x_forward_for]\" size=\"40\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_CLIENT_IP.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[client_ip]\" size=\"40\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REMOTE_ADDR.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[remote_addr]\" size=\"40\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REMOTE_PORT.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[remote_port]\" size=\"40\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REQUEST_METHOD.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[request_method]\" size=\"40\" disabled=\"disabled\">
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
              <div align=\"center\">
                  <input type='hidden' name='op' value='ABBlockedIPEditSave' />
                  <input type='hidden' name='xop' value='$xop' />
                  <input type='hidden' name='sip' value='$sip' />
                  <input type='hidden' name='old_xIPs' value='$xIPs' />
                  <input type='hidden' name='min' value='$min' />
                  <input type='hidden' name='column' value='$column' />
                  <input type='hidden' name='direction' value='$direction' />                      
                  <input type=submit value='"._SAVECHANGES."' />
              </div>
          </form>
      </td>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>