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
$pagetitle = _AB_SENTINEL.": "._AB_PAGETRACKING;
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
if(!$column or $column=="") $column = "date";
if(!$direction or $direction=="") $direction = "desc";
$tid=intval($tid);
$totalselected = $db->sql_numrows($db->sql_query("SELECT `tid`, `page`, `date` FROM `".$prefix."_nsnst_tracked_ips` WHERE `ip_addr`='$ip_addr' AND `user_id`='$user_id'"));
if($totalselected > 0) {
    $result = $db->sql_query("SELECT `ip_long` FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_id`='$user_id' AND `ip_addr`='$ip_addr'");
    list($ip_long) = $db->sql_fetchrow($result);
    echo "
          <div align='center'>
              <strong>
                  $ip_addr ($ip_long)
              </strong>
          </div>
          <br />
          <table align='center' cellpadding='2' cellspacing='2' border='2'>
              <tr>
                  <td width='70%'>
                      <strong>
                          "._AB_PAGEVIEWED."
                      </strong>
                  </td>
                  <td width='30%'>
                      <strong>
                          "._AB_HITDATE."
                      </strong>
                  </td>
         ";
    $result = $db->sql_query("SELECT `tid`, `page`, `date` FROM `".$prefix."_nsnst_tracked_ips` WHERE `ip_addr`='$ip_addr' AND `user_id`='$user_id' ORDER BY $column $direction");
    while(list($ltid, $page,$date_time) = $db->sql_fetchrow($result)){
        echo "
              <tr>
                  <td>
                      <a href=\"$page\">
                          $page
                      </a>
                  </td>
                  <td>
                      ".date("Y-m-d \@ H:i:s",$date_time)."
                  </td>
              </tr>
             ";
    }
    $db->sql_freeresult($result);
    echo "
          </table>
         ";
} else {
    DisplayError(_AB_NOPAGES);
}
echo "</body></html>\n";

?>