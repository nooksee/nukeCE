<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

echo "
      <!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
      <html>
          <head>
     ";
$pagetitle = _AB_SENTINEL.": "._AB_PRINTBLOCKEDIPS;
echo "
      <title>
          $pagetitle
      </title>
     ";
$theme_Sel = get_theme();
include_once("themes/$theme_Sel/theme.php");
echo "
              <LINK REL='StyleSheet' HREF='themes/$theme_Sel/style/style.css' TYPE='text/css' MEDIA='screen'>
          </head>
      <body>
          <h1 align='center'>
              $pagetitle
          </h1>
     ";
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips`"));
if($totalselected > 0) {
    echo "
          <table align='center' border='2' cellpadding='2' cellspacing='2'>
              <tr>
                  <td>
                      <strong>
                          "._AB_IPBLOCKED."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_COUNTRY."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._DATE."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_EXPIRES."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_REASON."
                      </strong>
                  </td>
              </tr>
         ";
    $result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_blocked_ips` ORDER BY `ip_addr`");
    while($getIPs = $db->sql_fetchrow($result)) {
        list($getIPs['reason']) = $db->sql_fetchrow($db->sql_query("SELECT `reason` FROM `".$prefix."_nsnst_blockers` WHERE `blocker`='".$getIPs['reason']."'"));
        $getIPs['reason'] = str_replace("Abuse-", "", $getIPs['reason']);
        $bdate = date("Y-m-d @ H:i:s", $getIPs['date']);
        $lookupip = str_replace("*", "0", $getIPs['ip_addr']);
        if($getIPs['expires']==0) { $bexpire = _AB_PERMENANT; } else { $bexpire = date("Y-m-d @ H:i:s", $getIPs['expires']); }
        list($bname) = $db->sql_fetchrow($db->sql_query("SELECT `username` FROM `".$user_prefix."_users` WHERE `user_id`='".$getIPs['user_id']."'"));
        echo "
              <tr>
             ";
        $qs = htmlentities(base64_decode($getIPs['query_string']));
        $qs = str_replace("%20", " ", $qs);
        $qs = str_replace("/**/", "/* */", $qs);
        $qs = str_replace("&", "<br />&", $qs);
        $ua = $getIPs['user_agent'];
        $ua = htmlentities($ua, ENT_QUOTES);
        echo "
              <td>
                  ".$getIPs['ip_addr']."
              </td>
             ";
        $countrytitleinfo = abget_countrytitle($getIPs['c2c']);
        echo "
                  <td align='center'>
                      ".$countrytitleinfo['country']."
                  </td>
                  <td align='center'>
                      $bdate
                  </td>
                  <td align='center'>
                      $bexpire
                  </td>
                  <td align='center'>
                      ".$getIPs['reason']."
                  </td>
              </tr>
             ";
    }
    echo "
          </table>
         ";
} else {
    DisplayError(_AB_NOIPS);
}
echo "
          </body>
      </html>
     ";

?>