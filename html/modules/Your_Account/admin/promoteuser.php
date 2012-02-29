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

    if (is_mod_admin('super')) {
        $pagetitle = ": "._USERADMIN." - "._PROMOTEUSER;
        include_once(NUKE_BASE_DIR.'header.php');
        GraphicAdmin();
        asub();
        OpenTable();
        list($uname, $rname, $email, $site, $upass) = $db->sql_fetchrow($db->sql_query("SELECT username, name, user_email, user_website, user_password FROM ".$user_prefix."_users WHERE user_id='$chng_uid'"));
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>
                          " . _PROMOTEUSER . "
                          &nbsp;
                      </span>
                  </legend>
                  <form action='".$admin_file.".php' method='post'>
                      <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                          <tr>
                              <td>
                                  " . _NAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_name\" value=\"$rname\" size=\"30\" maxlength=\"50\">
                                  <span class=\"tiny\">
                                      " . _REQUIREDNOCHANGE . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _NICKNAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_aid\" value=\"$uname\" size=\"30\" maxlength=\"25\">
                                  <span class=\"tiny\">
                                      " . _REQUIRED . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _EMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_email\" value=\"$email\" size=\"30\" maxlength=\"60\">
                                  <span class=\"tiny\">
                                      " . _REQUIRED . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _URL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_url\" value=\"$site\" size=\"30\" maxlength=\"60\">
                              </td>
                          </tr>
             ";
        if ($multilingual == 1) {
            echo "
                  <tr>
                      <td>
                          " . _LANGUAGE . ":
                      </td>
                      <td colspan=\"3\">
                          <select name=\"add_admlanguage\">
                 ";
            $languageslist = lang_list();
            for ($i=0, $maxi = count($languagelist); $i < $maxi; $i++) {
                if(!empty($languageslist[$i])) {
                    echo "<option name='xlanguage' value='".$languageslist[$i]."' ";
                    if($languageslist[$i]==$language) echo "selected";
                    echo ">".ucwords($languageslist[$i])."";
                }
            }
            echo "
                              <option value=\"\">
                                  " . _ALL . "
                              </option>
                          </select>
                      </td>
                  </tr>
                 ";
        } else {
            echo "
                  <input type=\"hidden\" name=\"add_admlanguage\" value=\"\">
                 ";
        }
        echo "
              <tr>
                  <td>
                      " . _PERMISSIONS . ":
                  </td>
             ";
        $result = $db->sql_query("SELECT mid, title FROM ".$prefix."_modules ORDER BY title ASC");
        $a = 0;
        while ($row = $db->sql_fetchrow($result)) {
            $title = str_replace("_", " ", $row['title']);
            if (file_exists("modules/".$row['title']."/admin/index.php") AND file_exists("modules/".$row['title']."/admin/links.php") AND file_exists("modules/".$row['title']."/admin/case.php")) {
                echo "<td><input type=\"checkbox\" name=\"auth_modules[]\" value=\"".intval($row['mid'])."\"> $title</td>";
                if ($a == 2) {
                    echo "</tr><tr><td>&nbsp;</td>";
                    $a = 0;
                } else {
                    $a++;
                }
            }
        }
        $db->sql_freeresult($result);
        echo "
                          </tr>
                          <tr>
                              <td>
                                  &nbsp;
                              </td>
                              <td>
                                  <input type=\"checkbox\" name=\"add_radminsuper\" value=\"1\">
                                  <strong>
                                      " . _SUPERUSER . "
                                  </strong>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  &nbsp;
                              </td>
                              <td colspan=\"3\">
                                  <span class=\"tiny\">
                                      <i>
                                          " . _SUPERWARNING . "
                                      </i>
                                  </span>
                              </td>
                          </tr>
                      </table>
              </fieldset>
              <br />
                      <div align=\"center\">
             ";
        if (isset($min)) { echo "<input type='hidden' name='min' value='$min'>\n"; }
        if (isset($xop)) { echo "<input type='hidden' name='xop' value='$xop'>\n"; }
        echo "
                          <input type='hidden' name='add_password' value='$upass'>
                          <input type='hidden' name='op' value='promoteUserConf'>
                          <input type=\"submit\" value=\"" . _PROMOTEUSER . "\">
                      </div>
                  </form>
              </td>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    } else {
        redirect("../../../index.php");
        die ();
    }

?>