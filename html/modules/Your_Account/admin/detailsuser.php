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
    $pagetitle = ": "._USERADMIN." - "._DETUSER;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    asub();
    $sql = "SELECT * FROM ".$user_prefix."_users WHERE user_id='$chng_uid'";
    if($db->sql_numrows($db->sql_query($sql)) > 0) {
        $chnginfo = $db->sql_fetchrow($db->sql_query($sql));
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>
                          " . _DETUSER . "
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
                                  <input type=\"text\" value=\"$chnginfo[username]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _UREALNAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[name]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _EMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <a href=\"mailto:".$chnginfo[user_email]."\">
                                      <input type=\"text\" value=\"$chnginfo[user_email]\" size=\"30\" disabled=\"disabled\">
                                  </a>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _REGDATE . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_regdate]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _FAKEEMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[femail]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _URL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_website]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _ICQ . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_icq]\" size=\"20\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _AIM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_aim]\" size=\"20\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _YIM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_yim]\" size=\"20\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _MSNM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_msnm]\" size=\"20\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _LOCATION . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_from]\" size=\"25\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _OCCUPATION . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_occ]\" size=\"25\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _INTERESTS . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[user_interests]\" size=\"25\" disabled=\"disabled\">
                              </td>
                              </td>
                          </tr>
             ";
        if ($chnginfo['user_viewemail'] ==1) { $cuv = _YES; } else { $cuv = _NO; }
        echo "
              <tr>
                  <td>
                      " . _SHOWMAIL . ":
                  </td>
                  <td colspan=\"3\">
                      <input type=\"text\" value=\"$cuv\" size=\"3\" disabled=\"disabled\">
                  </td>
              </tr>
             ";
        if ($chnginfo['newsletter'] == 1) { $cnl = _YES; } else { $cnl = _NO; }
        echo "
              <tr>
                  <td>
                      " . _NEWSLETTER . ":
                  </td>
                  <td colspan=\"3\">
                      <input type=\"text\" value=\"$cnl\" size=\"3\" disabled=\"disabled\">
                  </td>
              </tr>
             ";
        $chnginfo[user_sig] = ereg_replace("\r\n", "<br />", $chnginfo[user_sig]);
        echo "
                          <tr>
                              <td>
                                  " . _SIGNATURE . ":
                              </td>
                              <td colspan=\"3\" valign=\"top\">
                                  <textarea value=\"$chnginfo[user_sig]\" disabled=\"disabled\" rows=\"6\" cols=\"45\">".$chnginfo['user_sig']."</textarea>
                              </td>
                          </tr>
                      </table>
              </fieldset>
              <br />
                      <div align=\"center\">
                     ";
              if (isset($min)) { echo "<input type='hidden' name='min' value='$min'>\n"; }
              if (isset($xop)) { echo "<input type='hidden' name='op' value='$xop'>\n"; }
        echo "
                          <input type='hidden' name='op' value='modifyUser'>
                          <input type='hidden' name='chng_uid' value='".$chnginfo['user_id']."'>
                          <input type=\"submit\" value=\"" . _MODIFY . "\">
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