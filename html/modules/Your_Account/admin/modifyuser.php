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
    $pagetitle = ": "._USERADMIN." - "._USERUPDATE;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    asub();
    $result = $db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_id='$chng_uid' OR username='$chng_uid'");
    if($db->sql_numrows($result) > 0) {
        $chnginfo = $db->sql_fetchrow($result);
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>
                          " . _USERUPDATE . "
                          &nbsp;
                      </span>
                  </legend>
                  <form action=\"".$admin_file.".php\" method=\"post\">
                      <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                          <tr>
                              <td>
                                  " . _USERID . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_id]\" size=\"5\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _NICKNAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_uname\" value=\"$chnginfo[username]\" size=\"30\" maxlength=\"".$ya_config['nick_max']."\"> 
                                  <span class=\"tiny\">
                                      " . _YA_CHNGRISK . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _UREALNAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_name\" value=\"$chnginfo[name]\" size=\"30\" maxlength=\"50\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _EMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_email\" value=\"$chnginfo[user_email]\" size=\"30\" maxlength=\"60\">
                                  <span class=\"tiny\">
                                      " . _REQUIRED . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _FAKEEMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_femail\" value=\"$chnginfo[femail]\" size=\"30\" maxlength=\"60\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _URL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_url\" value=\"$chnginfo[user_website]\" size=\"30\" maxlength=\"60\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _ICQ . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_icq\" value=\"$chnginfo[user_icq]\" size=\"20\" maxlength=\"20\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _AIM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_aim\" value=\"$chnginfo[user_aim]\" size=\"20\" maxlength=\"20\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _YIM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_yim\" value=\"$chnginfo[user_yim]\" size=\"20\" maxlength=\"20\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _MSNM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_msnm\" value=\"$chnginfo[user_msnm]\" size=\"20\" maxlength=\"20\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _LOCATION . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_from\" value=\"$chnginfo[user_froml]\" size=\"25\" maxlength=\"60\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _OCCUPATION . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_occ\" value=\"$chnginfo[user_occ]\" size=\"25\" maxlength=\"60\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _INTERESTS . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_user_interests\" value=\"$chnginfo[user_interests]\" size=\"25\" maxlength=\"255\">
                              </td>
                          </tr>
             ";
        if ($chnginfo['user_viewemail'] ==1) { $cuv = "checked"; } else { $cuv = ""; }
        echo "
              <tr>
                  <td>
                      " . _OPTION . ":
                  </td>
                  <td colspan=\"3\">
                      <input type=\"checkbox\" name=\"chng_user_viewemail\" VALUE=\"1\" $cuv>
                       "._ALLOWUSERS."
                  </td>
              </tr>
             ";
        if ($chnginfo['newsletter'] == 1) { $cnl = "checked"; } else { $cnl = ""; }
        echo "
                          <tr>
                              <td>
                                  " . _NEWSLETTER . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"checkbox\" name=\"chng_newsletter\" VALUE=\"1\" $cnl>
                                  "._YES."
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _SIGNATURE . ":
                              </td>
                              <td colspan=\"3\" valign=\"top\">
                                  <textarea name=\"chng_user_sig\" rows=\"6\" cols=\"45\">".$chnginfo['user_sig']."</textarea>
                              </td>
                          </tr>
                          <tr>
                              <td valign='top'>
                                  "._PASSWORD.":
                              </td>
                              <td>
                                  <input type='password' name='chng_pass' size='11' maxlength='".$ya_config['pass_max']."' onkeyup='chkpwd(chng_pass.value)' onblur='chkpwd(chng_pass.value)' onmouseout='chkpwd(chng_pass.value)'>
                                  <br>
                                  "._FORCHANGES."
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
                              <td valign='top' nowrap>
                                  "._RETYPEPASSWORD.":
                              </td>
                              <td>
                                  <input type='password' name='chng_pass2' size='11' maxlength='".$ya_config['pass_max']."'>
                              </td>
                          </tr>
                      </table>
              </fieldset>
              <br />
                      <div align=\"center\">
                          <input type='hidden' name='chng_avatar' value='".$chnginfo['user_avatar']."'>
                          <input type='hidden' name='chng_uid' value='$chng_uid'>
                          <input type='hidden' name='old_uname' value='".$chnginfo['username']."'>
                          <input type='hidden' name='old_email' value='".$chnginfo['user_email']."'>
                          <input type='hidden' name='op' value='modifyUserConf'>
                         ";
                    if (isset($query)) { echo "<input type='hidden' name='query' value='$query'>"; }
                    if (isset($min)) { echo "<input type='hidden' name='min' value='$min'>"; }
                    if (isset($xop)) { echo "<input type='hidden' name='xop' value='$xop'>"; }
                    echo "
                          <input type=\"submit\" value=\"" . _SAVECHANGES . "\">
                      </div>
                  </form>
              </td>
             ";
        CloseTable();
    } else {
        ErrorReturn(_USERNOEXIST, 1);
    }
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>