<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_COUNTRYLISTING;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();
$perpage = 50;
if($perpage == 0) { $perpage = 25; }
if(!isset($min)) $min=0;
if(!isset($max)) $max=$min+$perpage;
if(!isset($column) or !$column or $column=="") $column = "country";
if(!isset($direction) or !$direction or $direction=="") $direction = "asc";
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM `".$prefix."_nsnst_countries`"));
if($totalselected > 0) {
    echo "
          <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"70%\">
              <tr bgcolor=\"".$bgcolor2."\">
                  <td align=\"center\">
                      <strong>
                          "._AB_FLAG."
                      </strong>
                  </td>
                  <td align=\"center\">
                      <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABCountryList&column=c2c&direction=desc'>
                  "._AB_C2CODE."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABCountryList&column=c2c&direction=asc'>
                  "._AB_C2CODE."
              </a>";
    }                      
    echo "                      
                      </strong>
                  </td>
                  <td align=\"center\">
                      <strong>
         ";
    if($direction == "asc") {
        echo "
              <a href='".$admin_file.".php?op=ABCountryList&column=country&direction=desc'>
                  "._AB_COUNTRY."
              </a>
             ";
    } else {
        echo "
              <a href='".$admin_file.".php?op=ABCountryList&column=country&direction=asc'>
                  "._AB_COUNTRY."
              </a>";
    }                      
    echo "                      
                      </strong>
                  </td>
              </tr>
         ";
    $result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_countries` ORDER BY $column $direction LIMIT $min,$perpage");
        $bgcolor = $bgcolor3;
            while($getIPs = $db->sql_fetchrow($result)) {
                $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                $getIPs['flag'] = flag_img($getIPs['c2c']);
                $getIPs['c2c'] = strtoupper($getIPs['c2c']);
                echo "
                      <tr".$bgcolor.">
                          <td align=\"center\">
                              ".$getIPs['flag']."
                          </td>
                          <td align=\"center\">
                              ".strtoupper($getIPs['c2c'])."
                          </td>
                          <td align=\"center\">
                              ".$getIPs['country']."
                          </td>
                      </tr>
                     ";
            }
    abpagenums($op, $totalselected, "", "", "", $min, $perpage, $max, $column, $direction, "");
} else {
    ErrorReturn(_AB_NOCOUNTRIES);
}
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>