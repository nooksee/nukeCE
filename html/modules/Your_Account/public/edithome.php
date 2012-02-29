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
    title(_HOMECONFIG);
    OpenTable();
    nav();
    CloseTable();
    echo "
          <br />
         ";
    if(empty($userinfo['theme'])) { $userinfo['theme'] = "$Default_Theme"; }
    OpenTable();
    echo "
          <div align=\"center\">
              <form action=\"modules.php?name=$module_name\" method=\"post\">
         ";
    if ($user_news == 1) {
        echo "
              <strong>
                  "._NEWSINHOME."
              </strong> 
              "._MAX127." 
              <input type=\"text\" name=\"storynum\" size=\"4\" maxlength=\"3\" value=\"$userinfo[storynum]\">
              <br />
              <br />
             ";
//        } else {
//            echo "<input type=\"hidden\" name=\"storynum\" value=\"$storyhome\">";
//        }
        echo "
                      <input type=\"hidden\" name=\"username\" value=\"$userinfo[username]\">
                      <input type=\"hidden\" name=\"user_id\" value=\"$userinfo[user_id]\">
                      <input type=\"hidden\" name=\"op\" value=\"savehome\">
                      <input type=\"submit\" value=\""._SAVECHANGES."\">
                  </form>
              </div>
             ";
    } else {
        echo "
              <div align=\"center\">
                  <b>
                      <em>
                          "._NOHOMECONFIG."
                      </em>
                  </b>
              </div>
             ";
    }
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
} else {
    mmain($user);
}

?>