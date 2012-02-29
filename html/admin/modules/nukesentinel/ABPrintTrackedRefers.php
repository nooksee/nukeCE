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
$pagetitle = _AB_SENTINEL.": "._AB_PRINTTRACKEDREFERS;
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
$totalselected = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`refered_from`) FROM `".$prefix."_nsnst_tracked_ips` GROUP BY 1"));
if($totalselected > 0) {
echo "
      <div align='center'>
          <strong>
              "._AB_USERREFER."
          </strong>
      </div>
      <br />
      <table align='center' cellpadding='2' cellspacing='2' border='2'>
          <tr>
              <td align='center'>
                  <strong>
                      "._AB_REFEREDFROM."
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
  $result = $db->sql_query("SELECT DISTINCT(`refered_from`), tid, MAX(`date`), COUNT(*) FROM `".$prefix."_nsnst_tracked_ips` GROUP BY 1 ORDER BY `refered_from`");
  while(list($refered_from, $tid, $lastview, $hits) = $db->sql_fetchrow($result)){
    echo "
          <tr>
         ";
    if(strlen($refered_from) > 50) {
        $rfrom = substr($refered_from, 0, 50)."...";
    } else {
        $rfrom = $refered_from;
    }
    echo "
          <td>
              $rfrom
          </td>
         ";
    $trackedips = $db->sql_numrows($db->sql_query("SELECT DISTINCT(`ip_addr`) FROM `".$prefix."_nsnst_tracked_ips` WHERE `refered_from`='$refered_from'"));
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