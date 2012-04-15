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
    $pagetitle = _USERADMIN." &raquo; "._ADDUSER;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    asub();
    OpenTable();
    echo "
          <fieldset>
              <legend>
                  <span class='option'>" . _ADDUSER . "&nbsp;</span>
              </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <form action=\"".$admin_file.".php\" method=\"post\">
                      <tr>
                          <td>" . _NICKNAME . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_uname\" size=\"30\" maxlength=\"".$ya_config['nick_max']."\"><span class=\"tiny\">&nbsp;" . _REQUIRED . "</span></td>
                      </tr>
                      <tr>
                          <td>" . _UREALNAME . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"></td>
                      </tr>
                      <tr>
                          <td>" . _EMAIL . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\"><span class=\"tiny\">&nbsp;" . _REQUIRED . "</span></td>
                      </tr>
                      <tr>
                          <td>" . _RETYPEEMAIL . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_email2\" size=\"30\" maxlength=\"60\"><span class=\"tiny\">&nbsp;" . _REQUIRED . "</span></td>
                      </tr>
                      <tr>
                          <td> " . _FAKEEMAIL . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_femail\" size=\"30\" maxlength=\"60\"></td>
                      </tr>
                      <tr>
                          <td>" . _URL . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\"></td>
                      </tr>
                      <tr>
                          <td>" . _ICQ . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_icq\" size=\"20\" maxlength=\"20\"></td>
                      </tr>
                      <tr>
                          <td>" . _AIM . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_aim\" size=\"20\" maxlength=\"20\"></td>
                      </tr>
                      <tr>
                          <td>" . _YIM . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_yim\" size=\"20\" maxlength=\"20\"></td>
                      </tr>
                      <tr>
                          <td>" . _MSNM . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_msnm\" size=\"20\" maxlength=\"20\"></td>
                      </tr>
                      <tr>
                          <td> " . _LOCATION . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_from\" size=\"25\" maxlength=\"60\"></td>
                      </tr>
                      <tr>
                          <td>" . _OCCUPATION . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_occ\" size=\"25\" maxlength=\"60\"></td>
                      </tr>
                      <tr>
                          <td>" . _INTERESTS . ":</td>
                          <td colspan=\"3\"><input type=\"text\" name=\"add_user_intrest\" size=\"25\" maxlength=\"255\"></td>
                      </tr>
                      <tr>
                          <td>" . _OPTION . ":</td>
                          <td colspan=\"3\"><input type=\"checkbox\" name=\"add_user_viewemail\" VALUE=\"1\">&nbsp;"._ALLOWUSERS."</td>
                      </tr>
                      <tr>
                          <td>" . _NEWSLETTER . ": </td>
                          <td colspan=\"3\"><input type=\"checkbox\" name=\"add_newsletter\" VALUE=\"1\">&nbsp;"._YES."</td>
                      </tr>
                      <tr>
                          <td>" . _SIGNATURE . ":</td>
                          <td colspan=\"3\" valign=\"top\"><textarea name=\"add_user_sig\" rows=\"6\" cols=\"45\"></textarea></td>
                      </tr>
                      <tr>
                          <td valign='top'> "._PASSWORD.":</td>
                          <td colspan=\"3\"><input type='password' name='add_pass' size='11' maxlength='".$ya_config['pass_max']."' onkeyup='chkpwd(add_pass.value)' onblur='chkpwd(add_pass.value)' onmouseout='chkpwd(add_pass.value)'><br>("._BLANKFORAUTO.")<br><br />
                              <table width='300' cellpadding='2' cellspacing='0' border='1' bgcolor='#EBEBEB' style='border-collapse: collapse;'>
                                  <tr>
                                      <td id='td1' width='100' align='center'><div ID='div1'></div></td>
                                      <td id='td2' width='100' align='center'><div ID='div2'></div></td>
                                      <td id='td3' width='100' align='center'><div ID='div3'>"._PSM_NOTRATED."</div></td>
                                      <td id='td4' width='100' align='center'><div ID='div4'></div></td>
                                      <td id='td5' width='100' align='center'><div ID='div5'></div></td>
                                  </tr>
                              </table>
                              <div ID='divTEMP'></div>"._PSM_CLICK."&nbsp;<a href=\"includes/help/passhelp.php\" rel='4' class='newWindow'>"._PSM_HERE."</a>&nbsp;"._PSM_HELP."<br />
                          </td>
                      </tr>
                      <tr>
                          <td valign='top' nowrap>"._RETYPEPASSWORD.":</td>
                          <td><input type='password' name='add_pass2' size='11' maxlength='".$ya_config['pass_max']."'><br />(Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!)</td>
                      </tr>
                  </table>
          </fieldset>
          <br />
                  <div align=\"center\">
                      <input type='hidden' name='add_avatar' value='gallery/blank.png'>
                      <input type='hidden' name='op' value='addUserConf'>
         ";
    if (isset($min)) { echo "<input type='hidden' name='min' value='$min'>"; }
    if (isset($xop)) { echo "<input type='hidden' name='xop' value='$xop'>"; }
    echo "                      
                      <input type=\"submit\" value=\"" . _ADDUSERBUT . "\">
                  </div>
              </form>
          </td>
         ";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>