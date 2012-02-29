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
$pagetitle = _AB_SENTINEL.": "._AB_AGENTTRACKING;
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
$tid=intval($tid);
list($uname) = $db->sql_fetchrow($db->sql_query("SELECT `user_agent` FROM `".$prefix."_nsnst_tracked_ips` WHERE `tid`='$tid'"));
echo "
      <div align='center'>
          <strong>
              $uname
          </strong>
      </div>
      <br />
      <table align='center' cellpadding='2' cellspacing='2' border='2'>
          <tr>
              <td nowrap>
                  <strong>
                      "._AB_PAGEVIEWED."
                  </strong>
              </td>
              <td nowrap>
                  <strong>
                      "._AB_HITDATE."
                  </strong>
              </td>
     ";
$result = $db->sql_query("SELECT `page`, `date` FROM `".$prefix."_nsnst_tracked_ips` WHERE `user_agent`='$uname' ORDER BY `date` DESC");
while(list($page, $date_time) = $db->sql_fetchrow($result)){
  echo "
        <tr>
            <td>
                $page
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
          </body>
      </html>
     ";

?>