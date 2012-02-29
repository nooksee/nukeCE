<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_ADDIP;
$tid = intval($tid);
$tidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_tracked_ips` WHERE `tid`='$tid'"));
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
$tip = explode(".", $tidinfo['ip_addr']);
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
              <input type='text' name='tidinfo[user_id]' size='5' value='".$tidinfo['user_id']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._USERNAME.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='tidinfo[username]' size='30' value='".$tidinfo['username']."' />
          </td>
      </tr>
      <tr>
          <td>
              "._AB_AGENT.":
          </td>
          <td colspan=\"3\">
              <input type='text' name='tidinfo[user_agent]' size='30' value='".$tidinfo['user_agent']."' />
          </td>
      </tr>
     ";
$tidinfo['date'] = date("Y-m-d H:i:s",time());
echo "
      <tr>
          <td>
              "._DATE.":
          </td>
          <td colspan=\"3\">
              <input type='text' value='".$tidinfo['date']."' size='20' disabled='disabled'>
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._AB_EXPIRESIN.":
          </td>
          <td colspan=\"3\">
              <select name='tidinfo[expires]'>
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
              "._AB_NOTES.":
          </td>
          <td colspan=\"3\">
              <textarea name='tidinfo[notes]' $textrowcol>".$getIPs['notes']."</textarea>
          </td>
      </tr>
      <tr>
          <td>
              "._AB_REASON.":
          </td>
          <td colspan=\"3\">
              <select name='tidinfo[reason]'>
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
     ";
$tidinfo['page'] = str_replace("%20", " ", $tidinfo['page']);
$tidinfo['page'] = str_replace("/**/", "/* */", $tidinfo['page']);
echo "
                  <tr>
                      <td>
                          "._AB_QUERY.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' name='' size='30' value='"._AB_UNKNOWN."' disabled='disabled' />
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_X_FORWARDED.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' value='".$tidinfo['x_forward_for']."' size='30' disabled='disabled'>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_CLIENT_IP.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' value='".$tidinfo['client_ip']."' size='30' disabled='disabled'>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REMOTE_ADDR.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' value='".$tidinfo['remote_addr']."' size='30' disabled='disabled'>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REMOTE_PORT.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' value='".$tidinfo['remote_port']."' size='30' disabled='disabled'>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REQUEST_METHOD.":
                      </td>
                      <td colspan=\"3\">
                          <input type='text' value='".$tidinfo['request_method']."' size='30' disabled='disabled'>
                      </td>
                  </tr>
              </table>
      </fieldset>
      <br />
              <div align=\"center\">
                  <input type='hidden' name='tidinfo[c2c]' value='".$tidinfo['c2c']."' />
                  <input type='hidden' name='tidinfo[date]' value='".time()."' />
                  <input type='hidden' name='tidinfo[old_ip]' value='".$tidinfo['ip_addr']."' />
                  <input type='hidden' name='tidinfo[query_string]' value='".$tidinfo['page']."' />
                  <input type='hidden' name='op' value='ABTrackedAddSave' />
                  <input type='hidden' name='tidinfo[x_forward_for]' value='".$tidinfo['x_forward_for']."' />
                  <input type='hidden' name='tidinfo[client_ip]' value='".$tidinfo['client_ip']."' />
                  <input type='hidden' name='tidinfo[remote_addr]' value='".$tidinfo['remote_addr']."' />
                  <input type='hidden' name='tidinfo[remote_port]' value='".$tidinfo['remote_port']."' />
                  <input type='hidden' name='tidinfo[request_method]' value='".$tidinfo['request_method']."' />
                  <input type='hidden' name='column' value='$column' />
                  <input type='hidden' name='direction' value='$direction' />
                  <input type='hidden' name='showmodule' value='$showmodule' />
                  <input type='hidden' name='min' value='$min' />
                  <input type=submit value='"._AB_ADDIP."' />
              </div>
          </form>
      </td>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>