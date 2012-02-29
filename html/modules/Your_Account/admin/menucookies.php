<?php

/********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ================================================================             */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion */
/********************************************************************************/

if(!defined('IN_YA')) {
    exit('Access Denied');
}

if(is_mod_admin($module_name)) {
    $pagetitle = ": "._COOKIECONFIG;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    amain();
    OpenTable();
    echo "
          <fieldset>
              <legend>
                  <span class='option'>
                      " . _COOKIECONFIG . "
                      &nbsp;
                  </span>
              </legend>
              <form action=\"".$admin_file.".php\" method=\"post\">
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              "._COOKIECHECKNOTE1."
                          </td>
                          <td colspan=\"3\">
         ";
    echo yesno_option('xcookiecheck', $ya_config['cookiecheck']);
    echo "
                  <span class=\"tiny\">
                      (" . _COOKIECHECKNOTE2 . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._COOKIECLEANERNOTE1."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xcookiecleaner', $ya_config['cookiecleaner']);
    echo "
                  <span class=\"tiny\">
                      (" . _COOKIECLEANERNOTE2 . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td valign='top'>
                  "._COOKIETIMELIFE."
              </td>
              <td colspan=\"3\">
                  <select name='xcookietimelife'>
                      <option value='-'"; if($ya_config['cookietimelife']=='-') {echo " selected";} echo ">
                          - "._YA_COOKIELOGOUTPAG."
                      </option>
                      <option value='0'"; if($ya_config['cookietimelife']=='0') {echo " selected";} echo ">
                          0 "._YA_COOKIEAUTOLOGOUT."
                      </option>
                      <option value='30'"; if($ya_config['cookietimelife']==30) {echo " selected";} echo ">
                          30 "._YA_SECONDS."
                      </option>
                      <option value='60'"; if($ya_config['cookietimelife']==60) {echo " selected";} echo ">
                          1 "._YA_MINUTE."
                      </option>
                      <option value='300'"; if($ya_config['cookietimelife']==300) {echo " selected";} echo ">
                          5 "._YA_MINUTES."
                      </option>
                      <option value='900'"; if($ya_config['cookietimelife']==900) {echo " selected";} echo ">
                          15 "._YA_MINUTES."
                      </option>
                      <option value='1800'"; if($ya_config['cookietimelife']==1800) {echo " selected";} echo ">
                          30 "._YA_MINUTES."
                      </option>
                      <option value='2700'"; if($ya_config['cookietimelife']==2700) {echo " selected";} echo ">
                          45 "._YA_MINUTES."
                      </option>
                      <option value='3600'"; if($ya_config['cookietimelife']==3600) {echo " selected";} echo ">
                          1 "._YA_HOUR."
                      </option>
                      <option value='7200'"; if($ya_config['cookietimelife']==7200) {echo " selected";} echo ">
                          2 "._YA_HOURS."
                      </option>
                      <option value='10800'"; if($ya_config['cookietimelife']==10800) {echo " selected";} echo ">
                          3 "._YA_HOURS."
                      </option>
                      <option value='14400'"; if($ya_config['cookietimelife']==14400) {echo " selected";} echo ">
                          4 "._YA_HOURS."
                      </option>
                      <option value='18000'"; if($ya_config['cookietimelife']==18000) {echo " selected";} echo ">
                          5 "._YA_HOURS."
                      </option>
                      <option value='36000'"; if($ya_config['cookietimelife']==36000) {echo " selected";} echo ">
                          10 "._YA_HOURS."
                      </option>
                      <option value='72000'"; if($ya_config['cookietimelife']==72000) {echo " selected";} echo ">
                          20 "._YA_HOURS."
                      </option>
                      <option value='86400'"; if($ya_config['cookietimelife']==86400) {echo " selected";} echo ">
                          1 "._YA_DAY."
                      </option>
                      <option value='172800'"; if($ya_config['cookietimelife']==172800) {echo " selected";} echo ">
                          2 "._YA_DAYS."
                      </option>
                      <option value='259200'"; if($ya_config['cookietimelife']==259200) {echo " selected";} echo ">
                          3 "._YA_DAYS."
                      </option>
                      <option value='345600'"; if($ya_config['cookietimelife']==345600) {echo " selected";} echo ">
                          4 "._YA_DAYS."
                      </option>
                      <option value='432000'"; if($ya_config['cookietimelife']==432000) {echo " selected";} echo ">
                          5 "._YA_DAYS."
                      </option>
                      <option value='518400'"; if($ya_config['cookietimelife']==518400) {echo " selected";} echo ">
                          6 "._YA_DAYS."
                      </option>
                      <option value='604800'"; if($ya_config['cookietimelife']==604800) {echo " selected";} echo ">
                          1 "._YA_WEEK."
                      </option>
                      <option value='1209600'"; if($ya_config['cookietimelife']==1209600) {echo " selected";} echo ">
                          2 "._YA_WEEKS."
                      </option>
                      <option value='1814400'"; if($ya_config['cookietimelife']==1814400){echo " selected";} echo ">
                          3 "._YA_WEEKS."
                      </option>
                      <option value='2592000'"; if($ya_config['cookietimelife']==2592000) {echo " selected";} echo ">
                          1 "._YA_MONTH."
                      </option>
         ";
    for ($i = 2; $i<=12; $i++) {
        $k = $i * 2592000;
        echo "
              <option value=$k"; if ($ya_config['cookietimelife'] == $k) { echo " selected"; } echo">
                  $i "._YA_MONTHS."
              </option>
             ";
    }
    echo "
                              </select>
                              <br />
                              <span class=\"tiny\">
                                  (" . _COOKIETIMELIFENOTE . ")
                              </span>
                          </td>
                      </tr>
                      <tr>
                          <td valign='top'>
                              "._COOKIEPATH."
                          </td>
                          <td colspan=\"3\">
                              <input type='text' name='xcookiepath' size='39' value='".$ya_config['cookiepath']."'>
                              <br />
                              <span class=\"tiny\">
                                  (" . _COOKIEPATHNOTE2 . ")
                              </span>
                          </td>
                      </tr>
                      <tr>
                          <td>
                              "._COOKIEINACTIVITY."
                          </td>
                          <td colspan=\"3\">
                              <select name='xcookieinactivity'>
                                  <option value='-'"; if ($ya_config['cookieinactivity'] == '-') { echo " selected"; }    echo ">
                                      - "._YA_COOKIEINACTNOTUSER."
                                  </option>
                                  <option value='0'"; if ($ya_config['cookieinactivity'] == '0') { echo " selected"; }    echo ">
                                      0 "._YA_COOKIEDELCOOKIE."
                                  </option>
                                  <option value='30'"; if ($ya_config['cookieinactivity'] == 30) { echo " selected"; } echo">
                                      30 "._YA_SECONDS."
                                  </option>
                                  <option value='60'"; if ($ya_config['cookieinactivity'] == 60) { echo " selected"; } echo">
                                      1 "._YA_MINUTE."
                                  </option>
                                  <option value='120'"; if ($ya_config['cookieinactivity'] == 120) { echo " selected"; } echo">
                                      2 "._YA_MINUTES."
                                  </option>
                                  <option value='180'"; if ($ya_config['cookieinactivity'] == 180) { echo " selected"; } echo">
                                      3 "._YA_MINUTES."
                                  </option>
                                  <option value='240'"; if ($ya_config['cookieinactivity'] == 240) { echo " selected"; } echo">
                                      4 "._YA_MINUTES."
                                  </option>
                                  <option value='300'"; if ($ya_config['cookieinactivity'] == 300) { echo " selected"; } echo">
                                      5 "._YA_MINUTES."
                                  </option>
                                  <option value='900'"; if ($ya_config['cookieinactivity'] == 900) { echo " selected"; } echo">
                                      15 "._YA_MINUTES."
                                  </option>
                                  <option value='1800'"; if ($ya_config['cookieinactivity'] == 1800) { echo " selected"; } echo">
                                      30 "._YA_MINUTES."
                                  </option>
                                  <option value='2700'"; if ($ya_config['cookieinactivity'] == 2700) { echo " selected"; } echo">
                                      45 "._YA_MINUTES."
                                  </option>
                                  <option value='3600'"; if ($ya_config['cookieinactivity'] == 3600) { echo " selected"; } echo">
                                      1 "._YA_HOUR."
                                  </option>
                              </select>
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
                  <div align=\"center\">
                      <input type=\"hidden\" name=\"op\" value=\"CookieConfigSave\">
                      <input type=\"submit\" value=\"" . _SAVECHANGES . "\">
                  </div>
              </form>
          </td>
         ";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>