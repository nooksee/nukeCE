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

require_once("mainfile.php");
require_once(NUKE_MODULES_DIR.'Web_Links/l_config.php');

include_once(NUKE_BASE_DIR.'header.php');
$pagetitle = _USERREGLOGIN;
title(_USERREGLOGIN);
OpenTable();
echo "
      <form action='modules.php?name=$module_name' method='post' name='newuser'>
          <b>
              "._REGNEWUSER."
          </b>
          ("._ALLREQUIRED.")
          <br>
          <br>
          <table cellpadding=\"0\" cellspacing=\"10\" border=\"0\">
              <tr>
                  <td>
                      "._NICKNAME.":
                  </td>
                  <td>
                      <input type='text' name='ya_username' size='30' maxlength='".$ya_config['nick_max']."'>
                  </td>
              </tr>
              <tr>
                  <td>
                      "._UREALNAME.":
                  </td>
                  <td>
                      <input type='text' name='ya_realname' size='30' maxlength='60'>
                  </td>
                  </tr>
              <tr>
                  <td>
                      "._EMAIL.":
                  </td>
                  <td>
                      <input type='text' name='ya_user_email' size='30' maxlength='255'>
                  </td>
              </tr>
     ";
if ($ya_config['doublecheckemail']==1) {
    echo "
          <tr>
              <td>
                  "._RETYPEEMAIL.":
              </td>
              <td>
                  <input type='text' name='ya_user_email2' size='30' maxlength='255'>
              </td>
          </tr>
         ";
} else {
    echo "
          <input type='hidden' name='ya_user_email2' value='ya_user_email'>
         ";
}
echo "
      <tr>
          <td valign='top'>
              "._PASSWORD.":
          </td>
          <td>
              <input type='password' name='user_password' size='11' maxlength='".$ya_config['pass_max']."' onkeyup='chkpwd(newuser.user_password.value)' onblur='chkpwd(newuser.user_password.value)' onmouseout='chkpwd(newuser.user_password.value)'>
              <br>
              ("._BLANKFORAUTO.")
              <br>
              <br />
              <table width='300' cellpadding='2' cellspacing='0' border='1' bgcolor='#EBEBEB' style='border-collapse: collapse;'>
                  <tr>
                      <td id='td1' width='100' align='center'>
                          <div ID='div1'>
                          </div>
                      </td>
                      <td id='td2' width='100' align='center'>
                          <div ID='div2'>
                          </div>
                      </td>
                      <td id='td3' width='100' align='center'>
                          <div ID='div3'>
                              "._PSM_NOTRATED."
                          </div>
                      </td>
                      <td id='td4' width='100' align='center'>
                          <div ID='div4'>
                          </div>
                      </td>
                      <td id='td5' width='100' align='center'>
                          <div ID='div5'>
                          </div>
                      </td>
                  </tr>
              </table>
              <div ID='divTEMP'>
              </div>
              "._PSM_CLICK."
              <a href=\"includes/help/passhelp.php\" rel='4' class='newWindow'>
                  "._PSM_HERE."
              </a>
              "._PSM_HELP."
              <br />
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._RETYPEPASSWORD.":
          </td>
          <td>
              <input type='password' name='user_password2' size='11' maxlength='".$ya_config['pass_max']."'>
              <br />
              (Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!)
          </td>
      </tr>
     ";
$gfxchk = array(3,4,6);
$gfx = security_code($gfxchk, 'stacked');
if(!empty($gfx)) {
    echo security_code(array(7), 'normal', 1);
}
echo "
      <tr>
          <td colspan='2'>
              <input type='hidden' name='op' value='new_confirm'>
              <input type='submit' value='"._YA_CONTINUE."'>
          </td>
      </tr>
      </table>
      </form>
      <br />
      "._YOUWILLRECEIVE."
      <br>
      <br>
      "._COOKIEWARNING."
      <br />
      "._ASREGUSER."
      <br />
      <ul>
          <li>
              "._ASREG1."
          <li>
              "._ASREG2."
          <li>
              "._ASREG4."
     ";
list($show_download) = $db->sql_fetchrow($db->sql_query("SELECT config_value FROM ".$prefix."_downloads_config WHERE config_name = 'show_download'"));
if ($show_download== 0) { echo "<li>"._SHOWDOWNLOAD.""; }
if ($ya_config['allowusertheme']==0) { echo "<li>"._CHOOSETHEME.""; }
if ($links_anonaddlinklock == 0) { echo "<li>"._ASREG5.""; }
if (is_active('Surveys')) { echo "<li>"._ASREG3.""; }

$sql = "SELECT title, custom_title FROM ".$prefix."_modules WHERE active='1' AND view='3' AND inmenu='1'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $title = $row[title];
    $custom_title = $row[custom_title];
    if (!empty($custom_title)) {
        if(substr($row[title],0,3) == '~l~') {
            echo "<li>"._ACCESSTO." ".substr($row[title],3)."";
        } else {
            echo "<li>"._ACCESSTO." $custom_title";
        }
    }
}

$sql = "SELECT title FROM ".$prefix."_blocks WHERE active='1' AND view='3'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $b_title = $row[title];
    if (!empty($b_title)) { echo "<li>"._ACCESSTO." $b_title"; }
}
if (is_active("Journal")) { echo "<li>"._CREATEJOURNAL.""; }
echo "
          <li>
              "._ASREG7."
      </ul>
      "._REGISTERNOW."
      <br />
      "._WEDONTGIVE."
      <br />
      <br />
      <div align=\"center\">
          [
          <a href='modules.php?name=$module_name'>
              "._USERLOGIN."
          </a>
          |
          <a href='modules.php?name=$module_name&amp;op=pass_lost'>
              "._PASSWORDLOST."
          </a>
          ]
      </div>
     ";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>