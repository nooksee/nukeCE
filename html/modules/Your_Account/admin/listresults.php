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
    $pagetitle = ": "._USERADMIN." - "._SEARCHUSERS;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    asub();
    asearch();
    OpenTable();
    $query = str_replace("\"","",$query);
    $query = str_replace("\'","",$query);
    $perpage = $ya_config['perpage'];
    if($perpage == 0) { $perpage = 25; }
    if ($find == "findUser") { $usertable = $user_prefix."_users"; } else { $usertable = $user_prefix."_users_temp"; }
    if ($match == "equal") { $sign = "='$query'"; } else { $sign = "LIKE '%".$query."%'"; }
    if (!isset($min)) $min=0;
    if (!isset($max)) $max=$min+$ya_config['perpage'];
    $totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM $usertable WHERE $what $sign"));
    if($totalselected > 0) {
        echo "
              <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
                  <tr bgcolor=\"".$bgcolor2."\">
                      <td align=\"center\">
                          <strong>
                              "._USERID."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._USERNAME."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._UREALNAME."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._EMAIL."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._REGDATE."
                          </strong>
                      </td>
                      <td align=\"center\">
                          <strong>
                              "._FUNCTIONS."
                          </strong>
                      </td>
                  </tr>
             ";
        $result = $db->sql_query("SELECT * FROM $usertable WHERE $what $sign ORDER BY username LIMIT $min,".$ya_config['perpage']."");
            $bgcolor = $bgcolor3;
                while($chnginfo = $db->sql_fetchrow($result)) {
                    $bgcolor = ($bgcolor == '') ? ' bgcolor="'.$bgcolor3.'"' : '';
                    echo "
                          <tr".$bgcolor.">
                              <td align='center'>
                                  ".$chnginfo['user_id']."
                              </td>
                              <td align='center'>
                                  ".$chnginfo['username']."
                              </td>
                              <td align='center'>
                                  ".$chnginfo['name']."
                              </td>
                              <td align='center'>
                                  ".$chnginfo['user_email']."
                              </td>
                              <td align='center'>
                                  ".$chnginfo['user_regdate']."
                              </td>
                              <td align='center'>
                         ";
                    if ($find == "tempUser") {
                        echo "
                              <a href=\"".$admin_file.".php?op=detailsTemp&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/view.gif\" alt=\""._DETUSER."\" title=\""._DETUSER."\" border=\"0\" width=\"17\" height=\"17\">
                              </a>
                              <a href=\"".$admin_file.".php?op=modifyTemp&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/edit.gif\" alt=\""._MODIFY."\" title=\""._MODIFY."\" border=\"0\" width=\"17\" height=\"17\">
                              </a>
                              <a href=\"".$admin_file.".php?op=resendMail&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/resend.gif\" alt=\""._RESEND."\" title=\""._RESEND."\" border=\"0\" width=\"17\" height=\"17\">
                              </a>
                              <a href=\"".$admin_file.".php?op=approveUser&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/approve.gif\" alt=\""._YA_APPROVE."\" title=\""._YA_APPROVE."\" border=\"0\" width=\"16\" height=\"16\">
                              </a>
                              <a href=\"".$admin_file.".php?op=activateUser&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/active.gif\" alt=\""._YA_ACTIVATE."\" title=\""._YA_ACTIVATE."\" border=\"0\" width=\"16\" height=\"16\">
                              </a>
                              <a href=\"".$admin_file.".php?op=denyUser&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/sys/forbidden.png\" alt=\""._DENY."\" title=\""._DENY."\" border=\"0\" width=\"16\" height=\"16\">
                              </a>
                             ";
                    } else {
                        echo "
                              <a href=\"".$admin_file.".php?op=detailsUser&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/view.gif\" alt=\""._DETUSER."\" title=\""._DETUSER."\" border=\"0\" width=\"17\" height=\"17\">
                              </a>
                              <a href=\"".$admin_file.".php?op=modifyUser&chng_uid=".$chnginfo['user_id']."\">
                                  <img src=\"images/edit.gif\" alt=\""._MODIFY."\" title=\""._MODIFY."\" border=\"0\" width=\"17\" height=\"17\">
                              </a>
                             ";
                        // suspended
                        if ($chnginfo['user_level'] == 0) { 
                            echo "
                                  <a href=\"".$admin_file.".php?op=restoreUser&chng_uid=".$chnginfo['user_id']."\">
                                      <img src=\"images/active.gif\" alt=\""._RESTORE."\" title=\""._RESTORE."\" border=\"0\" width=\"16\" height=\"16\">
                                  </a>
                                 "; 
                        }
                        // deactivated
                        if ($chnginfo['user_level'] == -1) { 
                            echo "
                                  <a href=\"".$admin_file.".php?op=removeUser&chng_uid=".$chnginfo['user_id']."\">
                                      <img src=\"images/delete.gif\" alt=\""._REMOVE."\" title=\""._REMOVE."\" border=\"0\" width=\"17\" height=\"17\">
                                  </a>
                                 "; 
                        }
                        // active
                        if ($chnginfo['user_level'] > 0 && is_mod_admin('super')) { 
                            echo "
                                  <a href=\"".$admin_file.".php?op=promoteUser&chng_uid=".$chnginfo['user_id']."\">
                                      <img src=\"images/key.gif\" alt=\""._PROMOTE."\" title=\""._PROMOTE."\" border=\"0\" width=\"17\" height=\"17\">
                                  </a>
                                 "; 
                        }
                        if ($chnginfo['user_level'] == 1) { 
                            echo "
                                  <a href=\"".$admin_file.".php?op=suspendUser&chng_uid=".$chnginfo['user_id']."\">
                                      <img src=\"images/sys/forbidden.png\" alt=\""._SUSPEND."\" title=\""._SUSPEND."\" border=\"0\" width=\"16\" height=\"16\">
                                  </a>
                                  "; 
                        }
                        if ($chnginfo['user_level'] > -1) { 
                            echo "
                                  <a href=\"".$admin_file.".php?op=deleteUser&chng_uid=".$chnginfo['user_id']."\">
                                      <img src=\"images/inactive.gif\" alt=\""._YA_DEACTIVATE."\" title=\""._YA_DEACTIVATE."\" border=\"0\" width=\"16\" height=\"16\">
                                  </a>
                                 "; 
                        }
                    }
                    echo "
                              </td>
                          </tr>
                         ";
                }
            yapagenums($op, $totalselected, $min, $ya_config['perpage'], $max, $find, $what, $match, $query);
        } else {
            ErrorReturn(_NOSEARCHUSERS);
        }
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');

}

?>