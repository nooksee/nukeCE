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

include_once(NUKE_BASE_DIR.'header.php');
title(_USERAPPLOGIN);
$sel1 = "checked";
$sel2 = "";
if (isset($_POST['tos_yes']) AND $ya_config['tos'] == intval(1)) {
    if ($setinfo[agreedtos] == '0') {
        OpenTable();
        echo "
              <div align=\"center\">
                  <span class=\"option\">
                      <b>
                          <em>
                              "._YATOS5."
                          </em>
                      </b>
                  </span>
                  <br />
                  <br />
                  "._GOBACK."
              </div>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    } else {
        OpenTable();
        echo "
              <div align=\"center\">
                  <span class=\"option\">
                      <b>
                          <em>
                              "._YATOS4."
                          </em>
                      </b>
                  </span>
                  <br />
                  <br />
                  "._GOBACK."
              </div>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }
} else {
    // menelaos: shows top table (differently for new users and current members)
    if ($setinfo[agreedtos] == '0') {
        info_box("caution", _YATOSINTRO1);
    } else {
        info_box("caution", _YATOSINTRO2);
    }
    // menelaos: shows bottom table (differently for new users and current members)
    OpenTable();
    echo "
          
              <table width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" border=\"0\">
                  <tr align=\"center\">
         ";
    if ($setinfo[agreedtos] == '0') {
        echo "
              <form name=\"tos1\" action=\"modules.php?name=$module_name\" method=\"POST\">
                  <td colspan=\"2\">
                      <input type=\"hidden\" name=\"username\" value=$username>
                      <input type=\"hidden\" name=\"user_password\" value=$user_password>
                      <input type=\"hidden\" name=\"random_num\" value=$random_num>
                      <input type=\"hidden\" name=\"gfx_check\" value=$gfx_check>
                      <input type=\"hidden\" name=\"redirect\" value=$redirect>
                      <input type=\"hidden\" name=\"mode\" value=$mode>
                      <input type=\"hidden\" name=\"f\" value=$f>
                      <input type=\"hidden\" name=\"t\" value=$t>
                      <input type=\"hidden\" name=\"op\" value=\"login\">";
    } else {
        echo "
              <form name=\"tos1\" action=\"modules.php?name=$module_name&amp;op=new_user\" method=\"POST\">
                  <td colspan=\"2\">";
    }
    if($_POST['coppa_yes']== intval(1)) {
        echo "
              <input type=\"hidden\" name=\"coppa_yes\" value='1'>
             ";
    }
    echo "
                  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"20\" border=\"0\">
                      <tr>
                          <td class=\"title\">
                              <strong>
                                  $sitename - "._YATOS1."
                              </strong>
                              <P>
                                  ".nl2br(BBCode2Html($ya_config['tos_text']))."
                          </td>
                      </tr>
                  </table>
              </td>
          </tr>
          <tr align=\"right\">
              <td width=\"100%\" valign=\"top\">
                  "._YATOS3."
                  <br />
              </td>
              <td align=\"left\">
                  <input type=\"radio\" name=\"tos_yes\" value='1' $sel2>
                  "._YES."
                  <br />
                  <input type=\"radio\" name=\"tos_yes\" value='0' $sel1>
                  "._NO."
                  <br />
                  <br />
                  <input type=\"submit\" value='"._YA_CONTINUE."'>";
}
echo "
                   </td>
               </form>
           </tr>
       </table>
      ";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>