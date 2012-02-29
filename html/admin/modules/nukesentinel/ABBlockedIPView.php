<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_VIEWIP;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();

$getIPs = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` WHERE `ip_addr`='$xIPs'"));
$getIPs['date'] = date("Y-m-d H:i:s",$getIPs['date']);

list($getIPs['reason']) = $db->sql_fetchrow($db->sql_query("SELECT `reason` FROM `".$prefix."_nsnst_blockers` WHERE `blocker`='".$getIPs['reason']."'"));
$lookupip = str_replace("*", "0", $xIPs);
echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " ._AB_VIEWIP . "
                  &nbsp;
              </span>
          </legend>
          <form action='".$admin_file.".php' method='post'>
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <tr>
                      <td>
                          "._AB_BLOCKEDIP.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$xIPs\" size=\"20\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_USER.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[username]\" size=\"30\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_AGENT.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[user_agent]\" size=\"30\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_BLOCKEDON.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[date]\" size=\"30\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_NOTES.":
                      </td>
                      <td colspan=\"3\">
                          <textarea value=\"$getIPs[notes]\" disabled=\"disabled\" $textrowcol>".$getIPs['notes']."</textarea>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._AB_REASON.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[reason]\" size=\"20\" disabled=\"disabled\">
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
     ";

$getIPs['get_string'] = htmlentities(base64_decode($getIPs['get_string']));
$getIPs['get_string'] = str_replace("%20", " ", $getIPs['get_string']);
$getIPs['get_string'] = str_replace("/**/", "/* */", $getIPs['get_string']);

echo "
      <tr>
          <td>
              "._AB_GET.":
          </td>
          <td colspan=\"3\">
              <input type=\"text\" value=\"$getIPs[get_string]\" size=\"40\" disabled=\"disabled\">
          </td>
      </tr>
     ";

$getIPs['post_string'] = htmlentities(base64_decode($getIPs['post_string']));
$getIPs['post_string'] = str_replace("%20", " ", $getIPs['post_string']);
$getIPs['post_string'] = str_replace("/**/", "/* */", $getIPs['post_string']);

echo "
                  <tr>
                      <td>
                          "._AB_POST.":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" value=\"$getIPs[post_string]\" size=\"40\" disabled=\"disabled\">
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
                  <input type='hidden' name='op' value='ABBlockedIPEdit' />
                  <input type='hidden' name='xIPs' value='$xIPs' />
                  <input type='hidden' name='xip[0]' value='$tip[0]' />
                  <input type='hidden' name='xip[1]' value='$tip[1]' />
                  <input type='hidden' name='xip[2]' value='$tip[2]' />
                  <input type='hidden' name='xip[3]' value='$tip[3]' />
                  <input type='hidden' name='xop' value='ABBlockedIP' />
                  <input type='hidden' name='min' value='0' />
                  <input type='hidden' name='column' value='date' />
                  <input type='hidden' name='direction' value='desc' /> 
                  <input type=submit value='"._AB_EDITBLOCKEDIP."' />
              </div>
          </form>
      </td>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>