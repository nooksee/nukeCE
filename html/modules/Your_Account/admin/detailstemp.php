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
    $result = $db->sql_query("SELECT * FROM ".$user_prefix."_users_temp WHERE user_id='$chng_uid'");
    if($db->sql_numrows($result) > 0) {
        $chnginfo = $db->sql_fetchrow($result);
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>
                          "._DETUSER."
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
                                  <input type=\"text\" value=\"$chnginfo[realname]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _EMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <a href=\"mailto:".$chnginfo['user_email']."\">
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
             ";
        $chnginfo['time'] = date("D M j H:i T Y", $chnginfo['time']);
        echo "
                          <tr>
                              <td>
                                  " . _YA_APPROVE2 . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=\"$chnginfo[time]\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _CHECKNUM . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" value=".$chnginfo['check_num']." size=\"35\" disabled=\"disabled\">
                              </td>
                          </tr>
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
                          <input type='hidden' name='op' value='modifyTemp'>
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