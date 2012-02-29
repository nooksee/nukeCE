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

$coppa=intval($_POST['coppa_yes']);
if (isset($_POST['coppa_yes']) AND $ya_config['coppa'] == intval(1)) {
    $coppa=intval($_POST['coppa_yes']); 
    if($coppa != intval(1)){
        include_once(NUKE_BASE_DIR.'header.php');
        title(""._USERAPPLOGIN."");
        OpenTable();
        echo "
              <div align=\"center\">
                  <span class=\"title\">
                      "._YACOPPA1."
                  </span>
                  <br />
                  <br />
                  <font color=\"#FF3333\">
                      <b>
                          "._YACOPPA4."
                          <br />
                          "._YACOPPA5."
                      </b>
                  </font>
                  <br />
                  <br />
                  <em>
                      "._YACOPPA6."
                  </em>
                  <br />
                  <br />
                  "._YACOPPAFAX."
              </div>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
        exit;
    }
}

$sel1 = "checked";
$sel2 = "";
include_once(NUKE_BASE_DIR.'header.php');
title(_USERAPPLOGIN);
info_box("warning", _YACOPPA2);
OpenTable();
echo "
      <form name=\"coppa1\" action=\"modules.php?name=$module_name&amp;op=new_user\" method=\"POST\">
          <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
              <tr>
                  <td align=\"center\" colspan=\"2\" class=\"title\">
                      "._YACOPPA1."
                  </td>
              </tr>
              <tr>
                  <td align=\"center\" colspan=\"2\" >
                      <p class=\"content\">
                          "._YACOPPA3."
                      <P>
                  </td>
              </tr>
              <tr>
                  <td align=\"right\">
                      "._YES."&nbsp;
                  </td>
                  <td align=\"left\">
                      <input type=\"radio\" name=\"coppa_yes\" value='1' $sel2>
                  </td>
              </tr>
              <tr>
                  <td align=\"right\">
                      "._NO."&nbsp;
                  </td>
                  <td align=\"left\">
                      <input type=\"radio\" name=\"coppa_yes\" value='0' $sel1>
                  </td>
              </tr>
              <tr>
                  <td align=\"center\" colspan=\"2\">
                      <br />
                      <input type=\"submit\" value='"._YA_CONTINUE."'>
                  </td>
              </tr>
          </table>
      </form>
     ";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>