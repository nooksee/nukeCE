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

if ($ya_config['expiring']!=0) {
    $past = time()-$ya_config['expiring'];
    $res = $db->sql_query("SELECT user_id FROM ".$user_prefix."_users_temp WHERE time < '$past'");
    while (list($uid) = $db->sql_fetchrow($res)) {
        $uid = intval($uid);
        $db->sql_query("DELETE FROM ".$user_prefix."_users_temp WHERE user_id = $uid");
    }
    $db->sql_query("OPTIMIZE TABLE ".$user_prefix."_users_temp");
}

$pagetitle = _USERAPPINFO;
$username  = trim(check_html($username, 'nohtml'));
$check_num = trim(check_html($check_num, 'nohtml'));
$result = $db->sql_query("SELECT * FROM ".$user_prefix."_users_temp WHERE username='$username' AND check_num='$check_num'");
if ($db->sql_numrows($result) == 1) {
    $row_act = $db->sql_fetchrow($result);
    $ya_username = $row_act['username'];
    $ya_realname = $row_act['realname'];
    $ya_useremail = $row_act['user_email'];
    $ya_time = $row_act['time'];
    $lv = time();
    include_once(NUKE_BASE_DIR.'header.php');
    title(_USERAPPINFO);
    info_box("caution", _FORACTIVATION);
    OpenTable();
    echo "
          <form name=\"Register\" action=\"modules.php?name=$module_name\" method=\"post\">
              <b>
                  "._PERSONALINFO."
              </b>
              <br>
              <br>
              <table cellpadding=\"0\" cellspacing=\"10\" border=\"0\">
                  <tr>
                      <td>
                          "._NICKNAME.":
                      </td>
                      <td>
                          <input type=\"text\" value=\"$ya_username\" size=\"30\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._UREALNAME.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"realname\" value=\"$ya_realname\" size=\"30\" maxlength=\"60\">
                          <span class=\"tiny\">
                              " . _REQUIRED . "
                          </span>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._EMAIL.":
                      </td>
                      <td>
                          <input type=\"text\" value=\"$ya_useremail\" size=\"30\" disabled=\"disabled\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._UFAKEMAIL.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"femail\" value=\"\" size=\"40\" maxlength=\"255\">
                          <br />
                          "._EMAILPUBLIC."
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YICQ.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_icq\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YAIM.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_aim\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YMSNM.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_msnm\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YYIM.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_yim\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YOURHOMEPAGE.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_website\" value=\"\" size=\"40\" maxlength=\"255\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YLOCATION.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_from\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YOCCUPATION.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_occ\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._YINTERESTS.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_interests\" value=\"\" size=\"30\" maxlength=\"100\">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._SIGNATURE.":
                          <br />
                          "._NOHTML."
                      </td>
                      <td>
                          <textarea wrap=\"virtual\" cols=\"50\" rows=\"5\" name=\"user_sig\">$userinfo[user_sig]</textarea>
                          <br />
                          "._255CHARMAX."
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._RECEIVENEWSLETTER."
                      </td>
                      <td>
                          <select name='newsletter'>
                              <option value=\"1\">
                                  "._YES."
                              </option>
                              <option value=\"0\" selected>
                                  "._NO."
                              </option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._ALWAYSSHOWEMAIL.":
                      </td>
                      <td>
                          <select name='user_viewemail'>
                              <option value=\"1\">
                                  "._YES."
                              </option>
                              <option value=\"0\" selected>
                                  "._NO."
                              </option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._HIDEONLINE.":
                      </td>
                      <td>
                          <select name='user_allow_viewonline'>
                              <option value=\"0\">
                                  "._YES."
                              </option>
                              <option value=\"1\" selected>
                                  "._NO."
                              </option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._FORUMSTIME.":
                      </td>
                      <td>
                          <select name='user_timezone'>
         ";
    $utz = date("Z");
    $utz = round($utz/3600);
    for ($i=-12; $i<13; $i++) {
        if ($i == 0) {
            $dummy = "GMT";
        } else {
            if (!ereg("-", $i)) { $i = "+$i"; }
            $dummy = "GMT $i "._HOURS."";
        }
        if ($utz == $i) {
            echo "<option name=\"user_timezone\" value=\"$i\" selected>$dummy</option>";
        } else {
            echo "<option name=\"user_timezone\" value=\"$i\">$dummy</option>";
        }
    }
    echo "
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._FORUMSDATE.":
                      </td>
                      <td>
                          <input type=\"text\" name=\"user_dateformat\" value=\"D M d, Y g:i a\" size='15' maxlength='14'>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          "._EXTRAINFO.":
                          <br />
                          "._NOHTML."
                      </td>
                      <td>
                          <textarea wrap=\"virtual\" cols=\"50\" rows=\"5\" name=\"bio\">$userinfo[bio]</textarea>
                          <br />
                          "._CANKNOWABOUT."
                      </td>
                  </tr>
                  <tr>
                      <td colspan='2'>
                          <input type=\"hidden\" name=\"ya_username\" value=\"$ya_username\">
                          <input type=\"hidden\" name=\"check_num\" value=\"$check_num\">
                          <input type=\"hidden\" name=\"ya_time\" value=\"$ya_time\">
                          <input type=\"hidden\" name=\"op\" value=\"saveactivate\">
                          <input type=\"submit\" value=\""._SAVECHANGES."\">
                      </td>
                  </tr>
              </table>
          </form>
         ";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
    exit;
} else {
    DisplayErrorReturn(_ACTERROR, 1);
    exit;
}

?>