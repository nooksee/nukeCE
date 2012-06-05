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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

function ya_userCheck($username) {
    global $stop, $user_prefix, $db, $ya_config, $prefix;
    $pagetitle = _USERAPPINFO;
    if(!Validate($username, 'username', '', 1, 1)) {
        $stop = DisplayErrorReturn(_ERRORINVNICK, 1);
    }
    if (strlen($username) > $ya_config['nick_max']) $stop = DisplayErrorReturn("Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!", 1);
    if (strlen($username) < $ya_config['nick_min']) $stop = DisplayErrorReturn("Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!", 1);
    $sql = "SELECT disallow_username FROM ".$prefix."_bbdisallow";
    $result = $db->sql_query($sql);
    $disallowed = $db->sql_fetchrowset($result);
    if(is_array($disallowed)) {
        foreach ($disallowed as $bad_nick) {
            $ya_config['bad_nick'] .= "\r\n" . $bad_nick['disallow_username'];
        }
    }
    if ($ya_config['bad_nick'] > "") {
        $ya_config['bad_nick'] = trim($ya_config['bad_nick']);
        $BadNickList = explode("\r\n",$ya_config['bad_nick']);
        for ($i=0; $i < count($BadNickList); $i++) {
            if(!empty($BadNickList[$i]) && !empty($username)) {
                if (eregi($BadNickList[$i], $username)) $stop = DisplayErrorReturn(_NAMERESTRICTED, 1);
            }
        }
    }
    if (strrpos($username,' ') > 0) $stop = DisplayErrorReturn(_NICKNOSPACES, 1);
    if ($db->sql_numrows($db->sql_query("SELECT username FROM ".$user_prefix."_users WHERE username='$username'")) > 0) $stop = DisplayErrorReturn(_NICKTAKEN, 1);
    if ($db->sql_numrows($db->sql_query("SELECT username FROM ".$user_prefix."_users_temp WHERE username='$username'")) > 0) $stop = DisplayErrorReturn(_NICKTAKEN, 1);
    return($stop);
}

function ya_mail($email, $subject, $message, $from) {
    global $ya_config, $adminmail;
    if ($ya_config['servermail'] == 0) {
        if (trim($from) == '') $from  = "From: $adminmail\n" . "Reply-To: $adminmail\n" . "Return-Path: $adminmail\n";
        nuke_mail($email, $subject, $message, $from);
    }
}

function ya_mailCheck($user_email) {
    global $stop, $user_prefix, $db, $ya_config;
    $pagetitle = _USERAPPINFO;
    $user_email = strtolower($user_email);
    if ((!$user_email) || (empty($user_email)) || (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$",$user_email))) $stop = DisplayErrorReturn(_ERRORINVEMAIL, 1);
    if ($ya_config['bad_mail'] > "") {
        $BadMailList = explode("\r\n",$ya_config['bad_mail']);
        for ($i=0; $i < count($BadMailList); $i++) {
            if (eregi($BadMailList[$i], $user_email)) $stop = DisplayError(""._MAILBLOCKED." ".$BadMailList[$i]."", 1);
        }
    }
    if (strrpos($user_email,' ') > 0) $stop = DisplayErrorReturn(_ERROREMAILSPACES, 1);
    if ($db->sql_numrows($db->sql_query("SELECT user_email FROM ".$user_prefix."_users WHERE user_email='$user_email'")) > 0) $stop = DisplayErrorReturn(_EMAILREGISTERED, 1);
    if ($db->sql_numrows($db->sql_query("SELECT user_email FROM ".$user_prefix."_users WHERE user_email='".md5($user_email)."'")) > 0) $stop = DisplayErrorReturn(_EMAILNOTUSABLE, 1);
    if ($db->sql_numrows($db->sql_query("SELECT user_email FROM ".$user_prefix."_users_temp WHERE user_email='$user_email'")) > 0) $stop = DisplayErrorReturn(_EMAILREGISTERED, 1);
    return($stop);
}

function ya_passCheck($user_pass1, $user_pass2) {
    global $stop, $ya_config;
    $pagetitle = _USERAPPINFO;
    if (strlen($user_pass1) > $ya_config['pass_max']) $stop = "Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!";
    if (strlen($user_pass1) < $ya_config['pass_min']) $stop = "Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!";
    if ($user_pass1 != $user_pass2) $stop = DisplayErrorReturn(_PASSWDNOMATCH, 1);
    return($stop);
}

function ya_fixtext($ya_fixtext) {
    if (empty($ya_fixtext)) { return $ya_fixtext; }
    $ya_fixtext = Fix_Quotes($ya_fixtext);
    return $ya_fixtext;
}

// function improved by Peter
function ya_save_config($config_name, $config_value, $config_param=""){
    global $prefix, $db, $cache;
    Fix_Quotes($config_value);
    if($config_param == 'html') {
        $config_name = check_html($config_name, 'nohtml');
        $config_value = check_html($config_value, 'html');
        $db -> sql_query("UPDATE ".$prefix."_users_config SET config_value='$config_value' WHERE config_name='$config_name'");
    }
    if($config_param == 'nohtml') {
        $config_name = check_html($config_name, 'nohtml');
        $config_value = check_html($config_value, 'nohtml');
        $db -> sql_query("UPDATE ".$prefix."_users_config SET config_value='$config_value' WHERE config_name='$config_name'");
    } else {
        $config_name=check_html($config_name, 'nohtml');
        $config_value = intval($config_value);
        $db -> sql_query("UPDATE ".$prefix."_users_config SET config_value='$config_value' WHERE config_name='$config_name'");
    }
}

function ya_get_configs(){
    global $prefix, $db, $cache;
    static $ya_config;
    if(isset($ya_config)) return $ya_config;
    if(($ya_config = $cache->load('ya_config', 'config')) === false) {
      $configresult = $db->sql_query("SELECT config_name, config_value FROM ".$prefix."_users_config");
      while (list($config_name, $config_value) = $db->sql_fetchrow($configresult)) {
          if (!get_magic_quotes_gpc()) { $config_value = stripslashes($config_value); }
          $ya_config[$config_name] = $config_value;
      }
      $db->sql_freeresult($configresult);
      $cache->save('ya_config', 'config', $ya_config);
    }
    return $ya_config;
}

function yacookie($setuid, $setusername, $setpass, $setstorynum, $setumode, $setuorder, $setthold, $setnoscore, $setublockon, $settheme, $setcommentmax) {
    global $ya_config, $db, $prefix, $client;
    $client = new Client();
    $ip = $client->getIp();
    $result = $db->sql_query("SELECT time FROM ".$prefix."_session WHERE uname='$setusername'");
    $ctime = time();
    if (!empty($setusername)) {
        $uname = substr($setusername, 0,25);
        if ($row = $db->sql_fetchrow($result)) {
            $db->sql_query("UPDATE ".$prefix."_session SET uname='$setusername', time='$ctime', host_addr='$ip', guest='$guest' WHERE uname='$uname'");
        } else {
            $db->sql_query("INSERT INTO ".$prefix."_session (uname, time, host_addr, guest) VALUES ('$uname', '$ctime', '$ip', '$guest')");
        }
    }
    $db->sql_freeresult($result);

    $info = base64_encode("$setuid:$setusername:$setpass:$setstorynum:$setumode:$setuorder:$setthold:$setnoscore:$setublockon:$settheme:$setcommentmax");
    if ($ya_config['cookietimelife'] != '-') {
        if (trim($ya_config['cookiepath']) != '') @setcookie("user",$info,time()+$ya_config['cookietimelife'],$ya_config['cookiepath']);
        else @setcookie("user","$info",time()+$ya_config['cookietimelife']);
    } else {
        @setcookie("user","$info");
    }
}

function YA_CoolSize($size) {
    $mb = 1024*1024;
    if ( $size > $mb ) {
        $mysize = sprintf ("%01.2f",$size/$mb) . " MB";
    } elseif ( $size >= 1024 ) {
        $mysize = sprintf ("%01.2f",$size/1024) . " Kb";
    } else {
        $mysize = $size . " bytes";
    }
    return $mysize;
}

// Borrowed from Nuke 7.8 Ads module
function YA_MakePass() {
    static $makepass;
    if(isset($makepass)) return $makepass;
    $cons = 'bcdfghjklmnpqrstvwxyz';
    $vocs = 'aeiou';
    for ($x=0; $x < 6; $x++) {
        mt_srand ((double) microtime() * 1000000);
        $con[$x] = substr($cons, mt_rand(0, strlen($cons)-1), 1);
        $voc[$x] = substr($vocs, mt_rand(0, strlen($vocs)-1), 1);
    }
    mt_srand((double)microtime()*1000000);
    $num1 = mt_rand(0, 9);
    $num2 = mt_rand(0, 9);
    $makepass = $con[0] . $voc[0] .$con[2] . $num1 . $num2 . $con[3] . $voc[3] . $con[4];
    return $makepass;
}

function amain() {
    global $ya_config, $db, $user_prefix, $prefix, $find, $what, $match, $query, $admin_file;
    OpenTable();
    
    $act = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_level>'0' AND user_id>'1'"));
    $sus = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_level='0' AND user_id>'1'"));
    $del = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_level='-1' AND user_id>'1'"));
    $nor = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_id>'1'"));
    $pen = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users_temp"));
    
    echo "
          <table align='center' cellpadding='2' cellspacing='2' border='0' width='100%'>
              <tr>
                  <td align='center' colspan='3'>
                      <a href=\"$admin_file.php?op=YAMain\">
                          <strong>
                              " . _USERADMIN . "
                          </strong>
                      </a>
                  </td>
              </tr>
              <tr>
                  <td align='center' width='33%'>
                      <a href='".$admin_file.".php?op=addUser'>
                          "._ADDUSER."
                      </a>
                  </td>
                  <td align='center' width='33%'>
                      <a href='".$admin_file.".php?op=UsersConfig'>
                          "._USERSCONFIG."
                      </a>
                  </td>
         ";
    if ($act > 0) {
        echo "
              <td align='center' width='33%'>
                  <a href='".$admin_file.".php?op=listnormal&amp;query=1'>
                      "._ACTIVEUSERS." ($act)
                  </a>
              </td>
             ";
    }
    echo "
          </tr>
          <tr>
              <td align='center' width='33%'>
                  <a href=\"modules/Forums/admin/admin_xdata_fields.php?mode=add\" rel='5' class='newWindow'\">
                      "._YA_ADDFIELD."
                  </a>
              </td>
              <td align='center' width='33%'>
                  <a href='".$admin_file.".php?op=CookieConfig'>
                      "._COOKIECONFIG."
                  </a>
              </td>
         ";
    if ($nor > 0) {
        echo "
              <td align='center' width='33%'>
                  <a href='".$admin_file.".php?op=listnormal&amp;query=a'>
                      "._NORMALUSERS." ($nor)
                  </a>
              </td>
             ";
    }
    echo "
          </tr>
          <tr>
              <td align='center' width='33%'>
                  <a href='".$admin_file.".php?op=searchUser'>
                      "._SEARCHUSERS."
                  </a>
              </td>
              <td align='center' width='33%'>
                  <a href='".$admin_file.".php?op=editTOS'>
                      "._EDITTOS."
                  </a>
              </td>
         ";
    if ($del > 0) {
        echo "
                  <td align='center' width='33%'>
                      <a href='".$admin_file.".php?op=listnormal&amp;query=-1'>
                          "._DELETEUSERS." ($del)
                      </a>
                  </td>
              </tr>
              <tr>
                  <td align='center' width='33%'>
                  </td>
                  <td align='center' width='33%'>
                  </td>
             ";
    }
    if ($sus > 0) {
        echo "
                  <td align='center' width='33%'>
                      <a href='".$admin_file.".php?op=listnormal&amp;query=0'>
                          "._SUSPENDUSERS." ($sus)
                      </a>
                  </td>
              </tr>
              <tr>
                  <td align='center' width='33%'>
                  </td>
                  <td align='center' width='33%'>
                  </td>
             ";
    }
    if ($pen > 0) {
        echo "
              <td align='center' width='33%'>
                  <a href='".$admin_file.".php?op=listpending'>
                      "._WAITINGUSERS." ($pen)
                  </a>
              </td>
             ";
    }
    echo "
              </tr>
          </table>
         ";
    CloseTable();
    echo '
          <br />
         ';
}

function asub() {
    global $ya_config, $db, $user_prefix, $prefix, $find, $what, $match, $query, $admin_file;
    OpenTable();
    echo "
          <table align='center' cellpadding='2' cellspacing='2' border='0' width='100%'>
              <tr>
                  <td align='center' colspan='3'>
                      <a href=\"$admin_file.php?op=YAMain\">
                          <strong>
                              " . _USERADMIN . "
                          </strong>
                      </a>
                  </td>
              </tr>
          </table>
         ";
    CloseTable();
    echo '
          <br />
         ';
}

function asearch() {
    global $admin_file, $find, $what, $match, $query, $db, $user_prefix;
    OpenTable();
    echo "
          <form method='post' action='".$admin_file.".php'>
              <table align='center' cellpadding='2' cellspacing='2' border='0'>
                  <tr>
        ";
    if ($find == "tempUser") { $sel1 = ""; $sel2 = " selected"; } else { $sel1 = " selected"; $sel2 = ""; }
    echo "
          <td align='center'>
              <select name='find'>
                  <option value='findUser'$sel1>
                      "._YA_REGLUSER."
                  </option>
                  <option value='tempUser'$sel2>
                      "._YA_TEMPUSER."
                  </option>
              </select>
          </td>
          <td align='center'>
              <select name='what'>
         ";
    $result = $db->sql_query("DESCRIBE " . $user_prefix . "_users");
    while($row = $db->sql_fetchrow($result)){
        if($row[0] != "user_password") {
            echo "
                  <option value='" . $row[0]."' " . ((($what == $row[0]) || (empty($what) && $row[0] == "username") )? "selected" : "") . ">
                      " . ucwords(str_replace("_", " ", $row[0])) . "
                  </option>
                 ";
        }
    }
    echo "
              </select>
          </td>
         ";
    if ($match == "equal") { $sel1 = ""; $sel2 = " selected"; } else { $sel1 = " selected"; $sel2 = ""; }
    echo "
                      <td align='center'>
                          <select name='match'>
                              <option value='like' $sel1>
                                  "._YA_LIKE."
                              </option>
                              <option value='equal' $sel2>
                                  "._YA_EQUAL."
                              </option>
                          </select>
                      </td>
                      <td align='center'>
                          <input type='text' name='query' value='$query' size='30' maxlength='60'>
                      </td>
                      <td align='center'>
                          <input type='hidden' name='op' value='listresults'>
                          <input type='submit' value='"._YA_SEARCH."'>
                      </td>
                  </tr>
              </table>
          </form>
         ";
    CloseTable();
    echo '
          <br />
         ';
}

function mmain($user) {
    global $stop, $module_name, $redirect, $mode, $t, $f, $user_id, $pic_id, $cat_id, $comment_id, $ya_config, $user, $p;
    if(!is_user()) {
        include_once(NUKE_BASE_DIR.'header.php');
        if ($stop) {
            OpenTable();
            echo "
                  <div align=\"center\">
                      <font class=\"title\">
                          "._LOGININCOR."
                      </font>
                  </div>
                 ";
            CloseTable();
            echo "
                  <br>
                 ";
        } else {
            OpenTable();
            echo "
                  <div align=\"center\">
                      <font class=\"title\">
                          "._USERREGLOGIN."
                      </font>
                  </div>
                 ";
            CloseTable();
            echo "
                  <br>
                 ";
        }
        OpenTable();
        mt_srand ((double)microtime()*1000000);
        $maxran = 1000000;
        $random_num = mt_rand(0, $maxran);
        echo "
              <form action=\"modules.php?name=$module_name\" method=\"post\">
                  <b>
                      "._USERLOGIN."
                  </b>
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
                              "._PASSWORD.":
                          </td>
                          <td>
                              <input type=\"password\" name=\"user_password\" size=\"15\" maxlength=\"20\">
                          </td>
                      </tr>
             ";
        $yachk = array(2,4,5,7);
        echo security_code($yachk, true);
        echo "
                  </table>
                  <input type=\"hidden\" name=\"redirect\" value=\"$redirect\">
                  <input type=\"hidden\" name=\"mode\" value=$mode>
                  <input type=\"hidden\" name=\"user_id\" value=$user_id>
                  <input type=\"hidden\" name=\"cat_id\" value=$cat_id>
                  <input type=\"hidden\" name=\"pic_id\" value=$pic_id>
                  <input type=\"hidden\" name=\"comment_id\" value=$comment_id>
                  <input type=\"hidden\" name=\"f\" value=$f>
                  <input type=\"hidden\" name=\"t\" value=$t>
                  <input type=\"hidden\" name=\"p\" value=$p>
                  <input type=\"hidden\" name=\"op\" value=\"login\">
                  <input type=\"submit\" value=\""._LOGIN."\">
              </form>
             ";
        if ($ya_config['cookiecleaner']==1) {
            echo "
                  <br />
                  <div align=\"center\">
                      <font class=\"content\">
                          [ 
                          <a href=\"modules.php?name=$module_name&amp;op=pass_lost\">
                              "._PASSWORDLOST."
                          </a> 
                          | 
                          <a href=\"modules.php?name=$module_name&amp;op=new_user\">
                              "._REGNEWUSER."
                          </a> 
                          | 
                          <a href=\"modules.php?name=$module_name&amp;op=ShowCookiesRedirect\">
                              "._YA_COOKIEDELALL."
                          </a> 
                          ]
                      </font>
                  </div>
                 ";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        } else {		
            echo "
                  <br />
                  <div align=\"center\">
                      <font class=\"content\">
                          [ 
                          <a href=\"modules.php?name=$module_name&amp;op=pass_lost\">
                              "._PASSWORDLOST."
                          </a> 
                          | 
                          <a href=\"modules.php?name=$module_name&amp;op=new_user\">
                              "._REGNEWUSER."
                          </a> 
                          ]
                      </font>
                  </div>
                 ";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        }
    } elseif (is_user()) {
        global $cookie;
        redirect("modules.php?name=$module_name&op=userinfo&username=$cookie[1]");
    }
}

function yapagenums($op, $totalselected, $min, $perpage, $max, $find, $what, $match, $query) {
    global $admin_file;
    $pagesint = ($totalselected / $perpage);
    $pageremainder = ($totalselected % $perpage);
    if ($pageremainder != 0) {
        $pages = ceil($pagesint);
        if ($totalselected < $perpage) { $pageremainder = 0; }
    } else {
        $pages = $pagesint;
    }
    if ($pages != 1 && $pages != 0) {
        $counter = 1;
        $currentpage = ($max / $perpage);
        echo "
              <table align='center' border='0' cellspacing='0' cellpadding='0' width='70%'>
                  <tr>
                      <td colspan='6'>
                          <img src='images/pix.gif' height='2' width='2' alt='' title='' />
                          <br />
                          <br />
                      </td>
                  </tr>
                  <tr>
                      <td colspan='6'>
                          <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tr>
                                  <td align='left' width='20%'>";
        if($currentpage <= 1) {
            echo "";
        } else {
            echo "
                  <a href='".$admin_file.".php?op=".$op."&query=".$query."&min=".($min - $perpage)."&find=$find&what=$what&match=$match'>
                      <font face='Verdana' size='2'>
                          &laquo; "._PREVPAGE."
                      </font>
                  </a>
                 ";
        }
        echo "
              </td>
              <td align='center' width='60%'>
                  <font face='Verdana' size='2'>
                      "._PAGE.":
                  </font>
            ";
        $start_page = ($currentpage-5<1)?1:($currentpage-5); //Set starting page to page-5, or 1 if less than 1
        $end_page = ($currentpage+3>$pages)?$pages:($currentpage+3); //Set ending page to page+5, or pages if more than 1
        for($counter=$start_page; $counter<=$end_page; $counter++) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if($counter == $currentpage) {
                echo "
                      <font face='Verdana' size='2' color=red>
                          $counter
                      </font>
                      &nbsp;
                     ";
            } else {
                echo "
                      <a href='".$admin_file.".php?op=".$op."&query=$query&min=$mintemp&find=$find&what=$what&match=$match'>
                          <font face='Verdana' size='2'>
                              $counter
                          </font>
                      </a>
                      &nbsp;
                     ";
            }
        }
        echo "
              </td>
              <td align='right' width='20%'>
             ";
        if($currentpage >= $pages) {
            echo "";
        } else {
            echo "
                  <a href='".$admin_file.".php?op=".$op."&query=".$query."&min=".($min + $perpage)."&find=$find&what=$what&match=$match'>
                      <font face='Verdana' size='2'>
                          "._NEXTPAGE." &raquo;
                      </font>
                  </a>
                 ";
        }
        echo "
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
             ";
    }
    echo "
          </table>
         ";
}

function ya_yesno($name, $value=0) {
    $value = ($value>0) ? 1 : 0;
    $sel[$value] = ' checked="checked"';
    return '
            <input type="radio" name="'.$name.'" id="'.$name.'" value="0" '.$sel[0].' /><label for="'.$name.'">'._YES.'</label>
            <input type="radio" name="'.$name.'" id="'.$name.'" value="1" '.$sel[1].' /><label for="'.$name.'">'._NO.'</label>
           ';
}

function ya_confirm($msg, $cop, $cname, $cid, $loc) {
    global $admin_file;
    echo "
          <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
              <tr>
                  <th class=\"thHead\" height=\"25\" valign=\"middle\">
                      <span class=\"tableTitle\">
                          Confirm
                      </span>
                  </th>
              </tr>
              <tr>
                  <td class=\"row1\" align=\"center\">
                      <span class=\"gen\">
                          <br />
                          $msg
                          <form action=\"".$admin_file.".php\" method=\"post\">";
    if (isset($query)) { echo "<input type=\"hidden\" name=\"query\" value=\"".$query."\">"; }
    if (isset($min)) { echo "<input type=\"hidden\" name=\"min\" value=\"".$min."\">"; }
    if (isset($xop)) { echo "<input type=\"hidden\" name=\"xop\" value=\"".$xop."\">"; }
    echo "
                              <input type=\"hidden\" name=\"op\" value=\"".$cop."\">
                              <input type=\"hidden\" name=\"".$cname."\" value=\"".$cid."\">
                              <br />
                              <input type=\"submit\" value=\""._YES."\" class=\"mainoption\" />
                              <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$admin_file.".php?op=".$loc."'\" />
                          </form>
                      </span>
                  </td>
              </tr>
          </table>
          <br clear=\"all\" />
         ";
}

function ya_confirm_note($msg, $cop, $cname, $cid, $cmsg, $cnote, $loc) {
    global $admin_file;
    echo "
          <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
              <tr>
                  <th class=\"thHead\" height=\"25\" valign=\"middle\">
                      <span class=\"tableTitle\">
                          Confirm
                      </span>
                  </th>
              </tr>
              <tr>
                  <td class=\"row1\" align=\"center\">
                      <span class=\"gen\">
                          <br />
                          $msg
                          <form action=\"".$admin_file.".php\" method=\"post\">";
    if (isset($query)) { echo "<input type=\"hidden\" name=\"query\" value=\"".$query."\">"; }
    if (isset($min)) { echo "<input type=\"hidden\" name=\"min\" value=\"".$min."\">"; }
    if (isset($xop)) { echo "<input type=\"hidden\" name=\"xop\" value=\"".$xop."\">"; }
    echo "
                              <input type=\"hidden\" name=\"op\" value=\"".$cop."\">
                              <input type=\"hidden\" name=\"".$cname."\" value=\"".$cid."\">
         ";
    if ($ya_config['servermail'] == 0) {
        echo "
                              <br />
                              $cmsg
                              <br />
                              <textarea name=\"".$cnote."\" rows=\"5\" cols=\"40\" wrap=\"virtual\"></textarea>
             ";
    }
    echo "
                              <br />
                              <br />
                              <input type=\"submit\" value=\""._YES."\" class=\"mainoption\" />
                              <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$admin_file.".php?op=".$loc."'\" />
                          </form>
                      </span>
                  </td>
              </tr>
          </table>
          <br clear=\"all\" />
         ";
}

?>