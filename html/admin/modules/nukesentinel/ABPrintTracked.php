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
$pagetitle = _AB_SENTINEL.": "._AB_PRINTTRACKEDIPS;
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
$totalselected = $db->sql_numrows($db->sql_query("SELECT `username`, `ip_addr`, `ip_long`, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` GROUP BY 1,2,3"));
if($totalselected > 0) {
    echo "
          <table align='center' cellpadding='2' cellspacing='2' border='2'>
              <tr>
                  <td>
                      <strong>
                          "._AB_USER."
                      </strong>
                  </td>
                  <td>
                      <strong>
                          "._AB_IPADDRESS."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_COUNTRY."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_HOSTNAME."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_LASTVIEWED."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_HITS."
                      </strong>
                  </td>
         ";
    $result = $db->sql_query("SELECT `user_id`, `username`, `ip_addr`, `ip_long`, MAX(`date`), COUNT(*), MIN(`tid`), `c2c` FROM `".$prefix."_nsnst_tracked_ips` $modfilter GROUP BY 2,3,4 ORDER BY `ip_addr`");
    while(list($userid,$username,$ipaddr,$ip_long,$lastview,$hits,$tid,$c2c) = $db->sql_fetchrow($result)){
        echo "
              <tr>
             ";
        if($userid != 1) { echo "<td>$username</td>"; } else { echo "<td>&nbsp;</td>"; }
        echo "
              <td>
                  $ipaddr
              </td>
             ";
        $countrytitleinfo = abget_countrytitle($c2c);
        echo "
                  <td align='center'>
                      ".$countrytitleinfo['country']."
                  </td>
                  <td align='center'>
                      $ip_long
                  </td>
                  <td align='center'>
                      ".date("Y-m-d \@ H:i:s",$lastview)."
                  </td>
                  <td align='center'>
                      $hits
                  </td>
              </tr>
             ";
    }
    $db->sql_freeresult($result);
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