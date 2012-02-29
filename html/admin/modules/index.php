<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

function adminMain() {
    global $language, $admin, $aid, $prefix, $file, $db, $sitename, $user_prefix, $admin_file, $bgcolor1, $sysconfig, $admdata, $dbtype, $cache;
    define('ADMIN_POS', true);
    include_once(NUKE_BASE_DIR.'header.php');
    
    $dummy = 0;
    $month = date('M');
    $curDate2 = "%".$month[0].$month[1].$month[2]."%".date('d')."%".date('Y')."%";
    $ty = time() - 86400;
    $preday = strftime('%d', $ty);
    $premonth = strftime('%B', $ty);
    $preyear = strftime('%Y', $ty);
    $curDateP = "%".$premonth[0].$premonth[1].$premonth[2]."%".$preday."%".$preyear."%";
    GraphicAdmin();
    $aid = substr($aid, 0,25);
    $radminsuper = is_mod_admin();
    $admlanguage = addslashes($admdata['admlanguage']);
    $result = $db->sql_query("SELECT admins FROM ".$prefix."_modules WHERE title='News'");
    $aidname = $admdata['name'];
    $radminarticle = 0;
    
    while (list($admins) = $db->sql_fetchrow($result)) {
        $admins = explode(",", $admins);
        $auth_user = 0;
        for ($i=0, $maxi=count($admins); $i < $maxi; $i++) {
            if ($aidname == $admins[$i]) {
                $auth_user = 1;
            }
        }
        if ($auth_user == 1) {
            $radminarticle = 1;
        }
    }
    $row3 = $db->sql_fetchrow($db->sql_query("SELECT main_module from ".$prefix."_main"));
    $main_module = $row3['main_module'];
    OpenTable();
    echo "
          <div align=\"center\">
              <b>
                  $sitename: "._DEFHOMEMODULE."
              </b>
              <br>
              <br>
              "._MODULEINHOME." 
              <b>
                  $main_module
              </b>
              <br>
              [ 
              <a href=\"".$admin_file.".php?op=modules\">
                  "._CHANGE."
              </a> 
              ]
          </div>
         ";
    CloseTable();
    echo "
          <br>
         ";
    OpenTable();
    
    $guest_online_num = intval($db->sql_numrows($db->sql_query("SELECT uname FROM ".$prefix."_session WHERE guest='1'")));
    $member_online_num = intval($db->sql_numrows($db->sql_query("SELECT uname FROM ".$prefix."_session WHERE guest='0'")));
    $who_online_num = $guest_online_num + $member_online_num;
    $who_online = "<font class=\"option\">"._WHOSONLINE."</font><br><br><font class=\"content\">"._CURRENTLY." $guest_online_num "._GUESTS." $member_online_num "._MEMBERS."<br>";
    
    list($userCount) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(user_id) AS userCount from ".$user_prefix."_users WHERE user_regdate LIKE '$curDate2'"));
    list($userCount2) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(user_id) AS userCount FROM ".$user_prefix."_users WHERE user_regdate LIKE '$curDateP'"));
    list($lastuser) = $db->sql_fetchrow($db->sql_query("SELECT username FROM " . $user_prefix . "_users ORDER BY user_id DESC limit 0,1"));
    echo "
          <div align=\"center\">
              $who_online
              <br>
              "._BTD.": 
              <b>
                  $userCount
              </b> 
              - 
              "._BYD.": 
              <b>
                  $userCount2
              </b>
              <br>
              <br>
              " . _BWD . ": 
              <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$lastuser\">
                  <b>
                      $lastuser
                  </b>
              </a>
          </div>
         ";
    CloseTable();
    echo "
          <br />
         ";
    if ($radminsuper) {
    OpenTable();
    echo "
          <div align=\"center\">
              <font class=\"option\">
                  <a href=\"$admin_file.php?op=database\">
                      " . _DATABASE_STATUS . "
                  </a>
              </font>
              <br />
              <br />
         ";
        if (SQL_LAYER == 'mysql' || SQL_LAYER == 'mysqli') {
        $result = $db->sql_query('SELECT VERSION()');
        list($mysqlversion) = $db->sql_fetchrow($result);
        if ($mysqlversion[0] > 3) {
	    echo "
                  <font class=\"content\">
                      " . _DATABASE_VERS . ":
                      <b>
                          " . $mysqlversion . "
                      </b> 
                      - 
                      " . _DATABASE_CLIENT . ":
                      <b>
                          " . mysql_get_client_info() . "
                      </b> 
                      - 
                 ";
        }
    }
    $res = mysql_list_processes();
        while ($row = $db->sql_fetchrow($res)) {
	    echo "
                  " . _DATABASE_USER . ":
                  <b>
                      " . $row['User'] . "
                  </b> 
                  - 
                  " . _DATABASE_DB . ":
                  <b>
                      " . $row['db'] . "
                  </b>
                 ";
        }
    echo "
              </font>
          </div>
          <br />
         ";
    CloseTable();
    echo "<br />";
    OpenTable();
    echo "
          <div align=\"center\">
              <font class=\"option\">
                  " . _SEC_STATUS . "
              </font>
              <br>
              <br>
              <font class=\"content\">
         ";
    if(defined('ADMIN_IP_LOCK')) {
        echo "
              " . _ADMIN_IP_LOCK . ": 
              <b>
                  " . _SEC_ON . "
              </b> 
              - 
             ";
    } else {
        echo "
              " . _ADMIN_IP_LOCK . ": 
              <b>
                  " . _SEC_OFF . "
              </b> 
              - 
             ";
    }
    if(defined('NUKESENTINEL_IS_LOADED')) {
        echo "
              " . _AB_SENTINEL . ": 
              <b>
                  " . _SEC_ON . "
              </b>
              <br>
              <br>
             ";
    } else {
        echo "
              " . _AB_SENTINEL . ": 
              <b>
                  " . _SEC_OFF . "
              </b>
              <br>
              <br>
             ";
    }
    
    $ret_log = log_size('admin');
    
    echo "
          Admin Tracker:
         ";
    if($ret_log == -1) {
        echo "
              <font color='red'>
                  " . _ADMIN_LOG_ERR . "
              </font>
              <br />
             ";
    } elseif($ret_log == -2) {
        echo "
              <font color='red'>
                  " . _ADMIN_LOG_ERRCHMOD . "
              </font>
              <br />
             ";
    } elseif($ret_log) {
        echo "
              <font color='red'>
                  " . _ADMIN_LOG_CHG . "
              </font>
              <br />
             ";
    } else {
        echo "
              <font color='green'>
                  " . _ADMIN_LOG_FINE . "
              </font>
              <br />
             ";
    }
    if($ret_log != -1 && $ret_log != -2) {
        echo "
              [ 
              <a href='".$admin_file.".php?op=viewadminlog&amp;log=admin'>
                  "._ADMIN_LOG_VIEW."
              </a>
              " . (($ret_log) ? " 
              | 
              <a href='".$admin_file.".php?op=adminlog_ack&log=admin'>
                  "._ADMIN_LOG_ACK."
              </a>" : "") ." 
              ]
              <br />
              <br />
             ";
    }
            
    $ret_log = log_size('error');
    
    echo "
          Error Logger:
         ";
    if($ret_log == -1) {
        echo "
              <font color='red'>
                  " . _ERROR_LOG_ERR . "
              </font>
              <br />
             ";
    } elseif($ret_log == -2) {
        echo "
              <font color='red'>
                  " . _ERROR_LOG_ERRCHMOD . "
              </font>
              <br />
             ";
    } elseif($ret_log) {
        echo "
              <font color='red'>
                  " . _ERROR_LOG_CHG . "
              </font>
              <br />
             ";
    } else {
        echo "
              <font color='green'>
                  " . _ERROR_LOG_FINE . "
              </font>
              <br />
             ";
    }
    if($ret_log != -1 && $ret_log != -2) {
        echo "
              [ 
              <a href='".$admin_file.".php?op=viewadminlog&amp;log=error'>
                  "._ADMIN_LOG_VIEW."
              </a>
              " . (($ret_log) ? " 
              | 
              <a href='".$admin_file.".php?op=adminlog_ack&amp;log=error'>
                  "._ADMIN_LOG_ACK."
              </a>" : "") ." 
              ]
             ";
    }
    echo "
              </font>
          </div>
         ";
    CloseTable();
    }
    
    include(NUKE_BASE_DIR.'footer.php');
}

?>