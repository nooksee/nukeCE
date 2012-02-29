<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_ADDIP;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();
echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_ADDIP . "
                  &nbsp;
              </span>
          </legend>
          <form action='".$admin_file.".php' method='post'>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
     ";
// Start submitted by technocrat
if(!isset($tip)) {
    $tip[0]=""; $tip[1]=$tip[2]=$tip[3]="0";
} else {
    if(ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $tip)) {
        $tip = explode(".", $tip);
    } else {
        $tip[0]=""; $tip[1]=$tip[2]=$tip[3]="0";
    }
}
// End submitted by technocrat
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
                  (" . _AB_ADDIPS . ")
              </span>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_USERID.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xuser_id' size='5' value='1' />
          </td>
      </tr>
      <tr>
          <td>
              "._USERNAME.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xusername' size='30' value='$anonymous' />
          </td>
      </tr>
      <tr>
          <td>
              "._AB_AGENT.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='xuser_agent' size='30' value='"._AB_UNKNOWN."' />
          </td>
      </tr>
      <tr>
          <td valign=\"top\">
              "._AB_EXPIRESIN.":
          </td>
          <td colspan=\"3\">
              <select name='xexpires'>
                  <option value='0'>
                      "._AB_PERMENANT."
                  </option>
     ";
$i=1;
while($i<=365) {
    $expiredate = date("Y-m-d", time() + ($i * 86400));
    echo "
          <option value='$i'>
              $i ($expiredate)
          </option>
         ";
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
                  <option value='00' selected='selected'>
                      "._AB_SELECTCOUNTRY."
                  </option>
     ";
$result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_countries` ORDER BY `c2c`");
while($countryrow = $db->sql_fetchrow($result)) {
    echo "
          <option value='".$countryrow['c2c']."'>
              ".strtoupper($countryrow['c2c'])." - ".$countryrow['country']."
          </option>
         ";
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
              <textarea name='xnotes' $textrowcol>"._AB_ADDBY." $aid</textarea>
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
    echo "
          <option value='".$blockerrow['blocker']."'>
              ".$blockerrow['reason']."
          </option>
         ";
}
echo "
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_QUERY.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xquery_string' size='30' value='"._AB_UNKNOWN."' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_X_FORWARDED.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xx_forward_for' size='30' value='none' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_CLIENT_IP.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xclient_ip' size='30' value='none' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REMOTE_ADDR.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xremote_addr' size='30' value='none' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REMOTE_PORT.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xremote_port' size='30' value='"._AB_UNKNOWN."' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REQUEST_METHOD.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='xrequest_method' size='30' value='"._AB_UNKNOWN."' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_ADDANOTHERIP.":
                      </td>      
                      <td colspan=\"3\">
                          <input type='checkbox' name='another' value='1' checked='checked' />
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
              <div align=\"center\">
                  <input type='hidden' name='op' value='ABBlockedIPAddSave' />                      
                  <input type=submit value='"._AB_ADDIP."' />
              </div>
          </form>
      </td>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>