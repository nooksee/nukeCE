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

if (!is_user()) {
    include_once(NUKE_BASE_DIR.'header.php');
    if ($ya_config['servermail'] ==0) {
        $pagetitle = _USERREGLOGIN;
        title(_USERREGLOGIN);
        OpenTable();
        echo "
              <form action=\"modules.php?name=$module_name\" method=\"post\">
                  <b>
                      "._PASSWORDLOST."
                  </b>
                  <br>
                  <br>
                  "._NOPROBLEM."
                  <br>
                  <br>
                  <table border=\"0\">
                      <tr>
                          <td>
                              "._NICKNAME.":
                          </td>
                          <td>
                              <input type=\"text\" name=\"username\" size=\"15\" maxlength=\"25\">
                          </td>
                      </tr>
                      <tr>
                          <td>
                              "._EMAIL.":
                          </td>
                          <td>
                              <input type=\"text\" name=\"user_email\" size=\"15\" maxlength=\"50\">
                          </td>
                      </tr>
                      <tr>
                          <td>
                              "._CONFIRMATIONCODE.":
                          </td>
                          <td>
                              <input type=\"text\" name=\"code\" size=\"11\" maxlength=\"10\">
                          </td>
                      </tr>
                  </table>
                  <br>
                  <input type=\"hidden\" name=\"op\" value=\"mailpasswd\">
                  <input type=\"submit\" value=\""._SENDPASSWORD."\">
              </form>
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
    } else {
        DisplayError(_SERVERNOMAIL, 1);
    }
    include_once(NUKE_BASE_DIR.'footer.php');
} elseif (is_user()) {
    global $cookie;
    redirect("modules.php?name=$module_name&op=userinfo&username=$cookie[1]");
}

?>