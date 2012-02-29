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
$pagetitle = _AB_SENTINEL.": "._AB_PRINTTRACKEDAGENTS;
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
$totalselected = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`user_agent`) FROM `".$prefix."_nsnst_tracked_ips` $modfilter GROUP BY 1"));
if($totalselected > 0) {
    echo "
          <table align='center' border='2' cellpadding='2' cellspacing='2'>
              <tr>
                  <td>
                      <strong>
                          "._AB_USERAGENT."
                      </strong>
                  </td>
                  <td align='center'>
                      <strong>
                          "._AB_IPSTRACKED."
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
    $result = $db->sql_query("SELECT `user_agent`, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` GROUP BY 1");
    while(list($user_agent, $lastview, $hits) = $db->sql_fetchrow($result)){
        echo "
              <tr>
                  <td>
                      $user_agent
                  </td>
             ";
        $trackedips = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_agent`='$user_agent'"));
        echo "
                  <td align='center'>
                      $trackedips
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
    DisplayError(_AB_NOUSERS);
}
echo "</body></html>\n";

?>