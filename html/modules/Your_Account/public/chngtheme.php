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

global $cookie, $userinfo;
if ((is_user()) AND (strtolower($userinfo['username']) == strtolower($cookie[1])) AND ($userinfo['user_password'] == $cookie[2])) {
    include_once(NUKE_BASE_DIR.'header.php');
    title(_THEMESELECTION);
    OpenTable();
    nav();
    CloseTable();
    echo "
          <br />
         ";
    OpenTable();
    echo "
          <form action=\"modules.php?name=$module_name\" method=\"post\">
              <div align=\"center\">
                  <strong>"._SELECTTHEME."</strong>:
         ";
    echo GetThemeSelect('theme');
    echo "
                  <br />
                  <br />
                  "._THEMETEXT1."
                  <br />
                  "._THEMETEXT2."
                  <br />
                  "._THEMETEXT3."
                  <br />
                  <br />
                  <input type=\"hidden\" name=\"user_id\" value=\"$userinfo[user_id]\">
                  <input type=\"hidden\" name=\"op\" value=\"savetheme\">
                  <input type=\"submit\" value=\""._SAVECHANGES."\">
              </div>
          </form>
         ";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
} else {
    mmain($user);
}

?>