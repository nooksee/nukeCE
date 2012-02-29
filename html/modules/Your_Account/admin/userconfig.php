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
    $pagetitle = ": "._USERSCONFIG;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    amain();
    OpenTable();
    echo "
          <fieldset>
              <legend>
                  <span class='option'>
                      " . _YA_USEROPTIONS . "
                      &nbsp;
                  </span>
              </legend>
              <form action=\"".$admin_file.".php\" method=\"post\">
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              "._ACTALLOWREG."
                          </td>
                          <td colspan=\"3\">
         ";
    echo ya_yesno('xallowuserreg', $ya_config['allowuserreg']);
    echo "
              </td>
          </tr>
          <tr>
              <td>
                  "._REQUIREADMIN."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xrequireadmin', $ya_config['requireadmin']);
    echo "
              </td>
          </tr>
          <tr>
              <td>
                  "._ACTALLOWDELETE."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xallowuserdelete', $ya_config['allowuserdelete']);
    echo "
              </td>
          </tr>
          <tr>
              <td>
                  "._DOUBLECHECKEMAIL."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xdoublecheckemail', $ya_config['doublecheckemail']);
    echo "
                  <span class=\"tiny\">
                      &nbsp;(" . _DOUBLECHECKEMAILNOTE . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._ACTIVATECOPPA."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xcoppa', $ya_config['coppa']);
    echo "
                  <span class=\"tiny\">
                      &nbsp;(" . _ACTIVATECOPPANOTE . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._ACTIVATETOS."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xtos', $ya_config['tos']);
    echo "
                  <span class=\"tiny\">
                      &nbsp;(" . _ACTIVATETOSNOTE . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._ACTIVATETOSALL."
              <td colspan=\"3\">
         ";
    echo yesno_option('xtosall', $ya_config['tosall']);
    echo "
                  <span class=\"tiny\">
                      &nbsp;(" . _ACTIVATETOSALLNOTE . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._THEMES_ALLOWCHANGE."
              </td>
              <td colspan=\"3\">
         ";
    echo ya_yesno('xallowusertheme', $ya_config['allowusertheme']);
    echo "
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
          <fieldset>
              <legend>
                  <span class='option'>
                      " . _YA_MAILOPTIONS . "
                      &nbsp;
                  </span>
              </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              "._SERVERMAIL."
                          </td>
                          <td colspan=\"3\">
         ";
    echo ya_yesno('xservermail', $ya_config['servermail']);
    echo "
              </td>
          </tr>
          <tr>
              <td>
                  "._ACTNOTIFYADD."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xsendaddmail', $ya_config['sendaddmail']);
    echo "
              </td>
          </tr>
          <tr>
              <td>
                  "._ACTNOTIFYDELETE."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xsenddeletemail', $ya_config['senddeletemail']);
    echo "
              </td>
          </tr>
          <tr>
              <td>
                  "._USEACTIVATE."
              </td>
              <td colspan=\"3\">
         ";
    echo ya_yesno('xuseactivate', $ya_config['useactivate']);
    echo "
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
          <fieldset>
              <legend>
                  <span class='option'>
                  " . _YA_EXPOPTIONS . "
                  &nbsp;
                  </span>
              </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              "._AUTOSUSPEND."
                          </td>
                          <td colspan=\"3\">
                              <select name='xautosuspend'>
         ";
    echo "<option value='0'";
    if ($ya_config['autosuspend'] == 0) { echo " selected"; }
    echo ">0 "._YA_NONEXPIRE."</option>";
    $i = 1;
    while ($i <= 52) {
        $k = $i * 604800;
        echo "<option value='$k'";
        if ($ya_config['autosuspend'] == $k) { echo " selected"; }
        echo">$i ";
        if ($i == 1) { echo _YA_WEEK; } else { echo _YA_WEEKS; }
        echo "</option>";
        $i++;
    }
    echo "
                  </select>
                  <span class=\"tiny\">
                      &nbsp;(" . _AUTOSUSNOTE . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_EXPIRING."
              </td>
              <td colspan=\"3\">
                  <select name='xexpiring'>
         ";
    echo "<option value='0'";
    if ($ya_config['expiring'] == 0) { echo " selected"; }
    echo ">0 "._YA_NONEXPIRE."</option>";
    $i = 1;
    while ($i <= 30) {
        $k = $i * 86400;
        echo "<option value='$k'";
        if ($ya_config['expiring'] == $k) { echo " selected"; }
        echo">$i ";
        if ($i == 1) { echo _YA_DAY; } else { echo _YA_DAYS; }
        echo "</option>";
        $i++;
    }
    echo "
                  </select>
                  <span class=\"tiny\">
                      &nbsp;(" . _YA_EXPIRINGNOTE . ")
                  </span>
              </td>
          </tr>
          <tr>
              <td>
                  "._AUTOSUSPENDMAIN."
              </td>
              <td colspan=\"3\">
         ";
    echo yesno_option('xautosuspendmain', $ya_config['autosuspendmain']);
    echo "
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
          <fieldset>
              <legend>
                  <span class='option'>
                  " . _YA_LMTOPTIONS . "
                  &nbsp;
                  </span>
              </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              "._YA_PERPAGE."
                          </td>
                          <td colspan=\"3\">
                              <select name='xperpage'>
         ";
    $i = 1;
    while ($i <= 5) {
        $k = $i * 25;
        echo "<option value='$k'";
        if ($ya_config['perpage'] == $k) { echo " selected"; }
        echo">$k "._YA_USERS."</option>";
        $i++;
    }
    echo "
                  </select>
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_BADNICK."
              </td>
              <td colspan=\"3\">
                  <textarea name='xbad_nick' rows='5' cols='40'>".$ya_config['bad_nick']."</textarea>
                  <br />
                  "._YA_1PERLINE."
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_BADMAIL."
              </td>
              <td colspan=\"3\">
                  <textarea name='xbad_mail' rows='5' cols='40'>".$ya_config['bad_mail']."</textarea>
                  <br />
                  "._YA_1PERLINE."
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_NICKMIN."
              </td>
              <td colspan=\"3\">
                  <select name='xnick_min'>
         ";
    for ($i = 3; $i <= 24; $i++) {
        echo "<option value='$i'";
        if ($ya_config['nick_min'] == $i) { echo " selected"; }
        echo">$i "._YA_CHARS."</option>";
    }
    echo "
                  </select>
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_NICKMAX."
              </td>
              <td colspan=\"3\">
                  <select name='xnick_max'>
         ";
    for ($i = 4; $i <= 25; $i++) {
        echo "<option value='$i'";
        if ($ya_config['nick_max'] == $i) { echo " selected"; }
        echo">$i "._YA_CHARS."</option>";
    }
    echo "
                  </select>
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_PASSMIN."
              </td>
              <td colspan=\"3\">
                  <select name='xpass_min'>
         ";
    for ($i = 3; $i <= 24; $i++) {
        echo "<option value='$i'";
        if ($ya_config['pass_min'] == $i) { echo " selected"; }
        echo">$i "._YA_CHARS."</option>";
    }
    echo "
                  </select>
              </td>
          </tr>
          <tr>
              <td>
                  "._YA_PASSMAX."
              </td>
              <td colspan=\"3\">
                  <select name='xpass_max'>
         ";
    for ($i = 4; $i <= 25; $i++) {
        echo "<option value='$i'";
        if ($ya_config['pass_max'] == $i) { echo " selected"; }
        echo">$i "._YA_CHARS."</option>";
    }
    echo "
                              </select>
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
                  <div align=\"center\">
                      <input type=\"hidden\" name=\"op\" value=\"UsersConfigSave\">
                      <input type=\"submit\" value=\"" . _SAVECHANGES . "\">
                  </div>
              </form>
          </td>
         ";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>