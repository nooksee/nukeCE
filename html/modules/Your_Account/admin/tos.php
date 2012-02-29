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
    $pagetitle = ": "._EDITTOS;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    amain();
    if ($_POST['submit']) {
        $tos = Fix_Quotes($_POST['tos_text']);
        $db->sql_query("UPDATE " . $prefix . "_users_config SET config_value = '" . $tos . "' WHERE config_name = 'tos_text'");
        $cache->delete('ya_config');
        redirect($admin_file.".php?op=editTOS");
    } else {
        OpenTable();
        echo "
              <form action='".$admin_file.".php' method='post' name=\"tos\">
                  <div align=\"center\">
                      <span class=\"title\">
                          "._EDITTOS."
                      </span>
                      <br />
                      <br />
                      <i>
                          "._EDITTOS2."
                      </i>
                      <br />
                      <br />
                      "._EDITTOS3.":
                      <br />
                      <br />
             ";
        Make_TextArea('tos_text',  $ya_config['tos_text'], 'tos');
        echo "
                      <input type='hidden' name='op' value='editTOS'>
                      <input type=\"submit\" name=\"submit\" value=\"Submit\">
                  </div>
              </form>
             ";
        CloseTable();
    }
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>