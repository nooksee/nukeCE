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

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
require_once("modules/Your_Account/includes/constants.php");

if(!defined('IN_YA')) {
    exit('Access Denied');
}

include_once(NUKE_MODULES_DIR.$module_name.'/includes/functions.php');

$ya_config = ya_get_configs();
get_lang($module_name);
$userpage = 1;
global $cookie;
$username = Fix_Quotes($_REQUEST['username']);
$redirect = $_REQUEST['redirect'];
$module = $_REQUEST['module'];
$user_password = $_REQUEST['user_password'];
$mode = $_REQUEST['mode'];
$t = $_REQUEST['t'];
$p = $_REQUEST['p'];
/*****[BEGIN]******************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/
$user_id = $_REQUEST['user_id'];
$pic_id = $_REQUEST['pic_id'];
$cat_id = $_REQUEST['cat_id'];
$comment_id = $_REQUEST['comment_id'];
/*****[END]********************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/	

include(NUKE_MODULES_DIR.$module_name.'/navbar.php');
include(NUKE_MODULES_DIR.$module_name.'/includes/cookiecheck.php');

function ya_expire() {
    global $ya_config, $db, $user_prefix;
    if ($ya_config['expiring']!=0) {
        $past = time()-$ya_config['expiring'];
        $res = $db->sql_query("SELECT user_id FROM ".$user_prefix."_users_temp WHERE time < '$past'");
        while (list($uid) = $db->sql_fetchrow($res)) {
            $uid = intval($uid);
            $db->sql_query("DELETE FROM ".$user_prefix."_users_temp WHERE user_id = $uid");
        }
        $db->sql_query("OPTIMIZE TABLE ".$user_prefix."_users_temp");
    }
}

switch($op) {
    case "activate":
        include(NUKE_MODULES_DIR.$module_name.'/public/activate.php');
    break;

    case "delete":
        if ($ya_config['allowuserdelete'] == 1) {
            include(NUKE_MODULES_DIR.$module_name.'/public/delete.php');
        } else {
            DisplayError(_ACTDISABLED, 1);
        }
    break;

    case "deleteconfirm":
        if ($ya_config['allowuserdelete'] == 1) {
            include(NUKE_MODULES_DIR.$module_name.'/public/deleteconfirm.php');
        } else {
            DisplayError(_ACTDISABLED, 1);
        }
    break;

    case "edithome":
        include(NUKE_MODULES_DIR.$module_name.'/public/edithome.php');
    break;

    case "edittheme":
    break;

    case "chgtheme":
        if ($ya_config['allowusertheme']==0) {
            include(NUKE_MODULES_DIR.$module_name.'/public/chngtheme.php');
        } else {
            DisplayError(_ACTDISABLED, 1);
        }
    break;

    case "edituser":
        redirect("modules.php?name=Profile&mode=editprofile");
        exit;
    break;

    case "login":
        global $nsnst_const, $user_prefix;
        $pagetitle = _USERREGLOGIN;
        if(!compare_ips($username)) {
            DisplayError('Your IP is not valid for this user!', 1);
            exit;
        }
        $result = $db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE username='$username'");
        $setinfo = $db->sql_fetchrow($result);
        // menelaos: check of the member agreed with the TOS and update the database field
        if (($ya_config['tos'] == intval(1)) AND ($_POST['tos_yes'] == intval(1))) {
            $db->sql_query("UPDATE ".$user_prefix."_users SET agreedtos='1' WHERE username='$username'");
        }
        $forward = ereg_replace("redirect=", "", "$redirect");
        if (ereg("privmsg", $forward)) { $pm_login = "active"; }
        if ($db->sql_numrows($result) == 0) {
            DisplayErrorReturn(_SORRYNOUSERINFO, 1);
        } elseif ($db->sql_numrows($result) == 1 AND $setinfo['user_id'] != 1 AND !empty($setinfo['user_password']) AND $setinfo['user_active'] >0 AND $setinfo['user_level'] >0) {
            $dbpass = $setinfo['user_password'];
            $non_crypt_pass = $user_password;
            $old_crypt_pass = crypt($user_password,substr($dbpass,0,2));
            $new_pass = NukeCrypt($user_password);
            $new_pass = md5($user_password);
            $nuke_crypt = NukeCrypt($user_password);
        //Reset to md5x1
        if (($dbpass == $nuke_crypt) || (($dbpass == $non_crypt_pass) || ($dbpass == $old_crypt_pass))) {
            $db->sql_query("UPDATE ".$user_prefix."_users SET user_password='$new_pass' WHERE username='$username'");
            $result = $db->sql_query("SELECT user_password FROM ".$user_prefix."_users WHERE username='$username'");
            list($dbpass) = $db->sql_fetchrow($result);
        }
        if ($dbpass != $new_pass) {
            if (md5($dbpass) == $new_pass) {
                $db->sql_query("UPDATE ".$user_prefix."_users SET user_password='$new_pass' WHERE username='$username'");
                $result = $db->sql_query("SELECT user_password FROM ".$user_prefix."_users WHERE username='$username'");
                list($dbpass) = $db->sql_fetchrow($result);
                if ($dbpass != $new_pass) {
                    redirect("modules.php?name=$module_name&stop=1");
                    return;
                }
                } else {
                    redirect("modules.php?name=$module_name&stop=1");
                    return;
                }
        }
        $gfxchk = array(2,4,5,7);
        if (!security_code_check($_POST['gfx_check'], $gfxchk)) {
            redirect("modules.php?name=$module_name&stop=1");
            exit;
        } else {

            // menelaos: show a member the current TOS if he has not agreed yet
            if (($ya_config['tos'] == intval(1)) AND ($ya_config['tosall'] == intval(1)) AND ($setinfo['agreedtos'] != intval(1))) {
                if($_POST['tos_yes'] != intval(1)) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/ya_tos.php');
                    exit;
                }
            }
            // menelaos: show a member the current TOS if he has not agreed yet

            yacookie($setinfo['user_id'], $setinfo['username'], $new_pass, $setinfo['storynum'], $setinfo['umode'], $setinfo['uorder'], $setinfo['thold'], $setinfo['noscore'], $setinfo['ublockon'], $setinfo['theme'], $setinfo['commentmax']);
            $uname = $nsnst_const['remote_ip'];
            $db->sql_query("DELETE FROM ".$prefix."_session WHERE uname='$uname' AND guest='1'");
            $db->sql_query("UPDATE ".$user_prefix."_users SET last_ip='$uname' WHERE username='$username'");
        }

        // menelaos: the cookiecheck is run here
        if ($ya_config['cookiecheck']==1) {
            $cookiecheck = yacookiecheckresults();
        }
        if (!empty($pm_login)) {
            redirect("modules.php?name=Private_Messages&file=index&folder=inbox");
/*****[BEGIN]******************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/
        } else if (!empty($user_id))  {
            redirect("modules.php?name=Forums&file=$forward&user_id=$user_id");
        } else if (!empty($cat_id))  {
            redirect("modules.php?name=Forums&file=$forward&cat_id=$cat_id");
        } else if (!empty($pic_id))  {
            redirect("modules.php?name=Forums&file=$forward&pic_id=$pic_id");
        } else if (!empty($comment_id))  {
            redirect("modules.php?name=Forums&file=$forward&comment_id=$comment_id");
/*****[END]********************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/	
        } else if (!empty($t))  {
            redirect("modules.php?name=Forums&file=$forward&mode=$mode&t=$t");
        } else if (!empty($p))  {
            redirect("modules.php?name=Forums&file=$forward&mode=$mode&p=$p");
        } else if (empty($redirect)) {
            if ($board_config['loginpage'] == 1) {
                redirect("modules.php?name=Your_Account&op=userinfo&bypass=1&username=$username");
            } else {
                redirect("modules.php?name=Forums");
            }
        } else if (!empty($module)) {
            redirect("modules.php?name=$module");
        } else if (empty($mode)) {
            if(!empty($f)) {
                redirect("modules.php?name=Forums&file=$forward&f=$f");
            } else {
                redirect("modules.php?name=Forums&file=$forward");
            }
        } else {
            redirect("modules.php?name=Forums&file=$forward&mode=$mode&f=$f");
        }
        } elseif ($db->sql_numrows($result) == 1 AND ($setinfo['user_level'] < 1 OR $setinfo['user_active'] < 1)) {
            if ($setinfo['user_level'] == 0) {
                DisplayError(_ACCSUSPENDED, 1);
            } elseif ($setinfo['user_level'] == -1) {
                DisplayError(_ACCDELETED, 1);
            } else {
                DisplayErrorReturn(_SORRYNOUSERINFO, 1);
            }
        } else {
            redirect("modules.php?name=$module_name&stop=1");
        }
    break;

    case "logout":
        global $cookie;
        $r_uid = $cookie[0];
        $r_username = $cookie[1];
        setcookie("user");
        if (trim($ya_config['cookiepath']) != '') setcookie("user","expired",time()-604800,"$ya_config[cookiepath]"); //correct the problem of path change
        $db->sql_query("DELETE FROM ".$prefix."_session WHERE uname='$r_username'");
        $db->sql_query("OPTIMIZE TABLE ".$prefix."_session");
        $sql = "SELECT session_id FROM ".$prefix."_bbsessions WHERE session_user_id='$r_uid'";
        $row = $db->sql_fetchrow($db->sql_query($sql));
        $db->sql_query("DELETE FROM ".$prefix."_bbsessions WHERE session_user_id='$r_uid'");
        $db->sql_query("OPTIMIZE TABLE ".$prefix."_bbsessions");
        
        global $board_config;
        $cookiename = $board_config['cookie_name'];
        $cookiepath = $board_config['cookie_path'];
        $cookiedomain = $board_config['cookie_domain'];
        $cookiesecure = $board_config['cookie_secure'];
        $current_time = time();
        setcookie($cookiename . '_data', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
        setcookie($cookiename . '_sid', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
        
        $user = "";
        if (!empty($redirect)) {
            redirect("modules.php?name=$redirect");
            exit;
        } else {
            redirect("index.php");
            exit;
        }
    break;

    case "mailpasswd":
        include(NUKE_MODULES_DIR.$module_name.'/public/mailpass.php');
    break;

    case "new_user":
        if (is_user()) {
            mmain($user);
        } else {
            if ($ya_config['allowuserreg']==0) {
                if ($ya_config['coppa'] == intval(1)) {
                    if($_POST['coppa_yes']!= intval(1)) {
                        include(NUKE_MODULES_DIR.$module_name.'/public/ya_coppa.php');
                        exit;
                    }
                }
            if ($ya_config['tos'] == intval(1)) {
                if($_POST['tos_yes'] != intval(1)) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/ya_tos.php');
                    exit;
                }
            }
            if ($ya_config['coppa'] !== intval(1) OR $ya_config['coppa'] == intval(1) AND $_POST['coppa_yes'] = intval(1)){
                if ($ya_config['tos'] !== intval(1) OR $ya_config['tos'] == intval(1) AND $_POST['tos_yes']=intval(1)){
                    if ($ya_config['requireadmin'] == 1) {
                        include(NUKE_MODULES_DIR.$module_name.'/public/new_user1.php');
                    } elseif ($ya_config['requireadmin'] == 0 AND $ya_config['useactivate'] == 0) {
                        include(NUKE_MODULES_DIR.$module_name.'/public/new_user2.php');
                    } elseif ($ya_config['requireadmin'] == 0 AND $ya_config['useactivate'] == 1) {
                        include(NUKE_MODULES_DIR.$module_name.'/public/new_user3.php');
                    }
                }
            }
            } else {
                DisplayError(_ACTDISABLED, 1);
            }
        }
    break;

    case "new_confirm":
        if (is_user()) {
            mmain($user);
        } else {
            if ($ya_config['allowuserreg']==0) {
                if ($ya_config['requireadmin'] == 1) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/new_confirm1.php');
                } elseif ($ya_config['requireadmin'] == 0 AND $ya_config['useactivate'] == 0) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/new_confirm2.php');
                } elseif ($ya_config['requireadmin'] == 0 AND $ya_config['useactivate'] == 1) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/new_confirm3.php');
                }
            } else {
                DisplayError(_ACTDISABLED, 1);
            }
        }
    break;

    case "new_finish":
        ya_expire();
        if (is_user()) {
            mmain($user);
        } else {
            if ($ya_config['allowuserreg']==0) {
                if ($ya_config['requireadmin'] == 1) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/new_finish1.php');
                } elseif ($ya_config['requireadmin'] == 0 AND $ya_config['useactivate'] == 0) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/new_finish2.php');
                } elseif ($ya_config['requireadmin'] == 0 AND $ya_config['useactivate'] == 1) {
                    include(NUKE_MODULES_DIR.$module_name.'/public/new_finish3.php');
                }
            } else {
                DisplayError(_ACTDISABLED, 1);
            }
        }
    break;

    case "pass_lost":
        include(NUKE_MODULES_DIR.$module_name.'/public/passlost.php');
    break;

    case "saveactivate":
        include(NUKE_MODULES_DIR.$module_name.'/public/saveactivate.php');
    break;

    case "savehome":
        $pagetitle = _USERREGLOGIN;
        if (is_user()) {
            include(NUKE_MODULES_DIR.$module_name.'/public/savehome.php');
        } else {
            DisplayErrorReturn(_MUSTBEUSER, 1);
        }
    break;

    case "savetheme":
        $pagetitle = _USERREGLOGIN;
        if (is_user()) {
            if ($ya_config['allowusertheme']==0) {
                include(NUKE_MODULES_DIR.$module_name.'/public/savetheme.php');
            } else {
                DisplayError(_ACTDISABLED, 1);
            }
        } else {
            DisplayErrorReturn(_MUSTBEUSER, 1);
        }
    break;

    case "userinfo":
        list($uid) = $db->sql_ufetchrow('SELECT user_id FROM '.$user_prefix.'_users WHERE username="'.$username.'"', SQL_NUM);
        redirect("modules.php?name=Profile&mode=viewprofile&u=".$uid);
        exit;
    break;

    case "ShowCookiesRedirect":
        ShowCookiesRedirect();
    break;

    case "ShowCookies":
        ShowCookies();
    break;

    case "DeleteCookies":
        DeleteCookies();
    break;

    default:
        mmain($user);
    break;

}

?>