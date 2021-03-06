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

if(!isset($_SESSION)) { session_start(); }
if (!isset($_SESSION['YA1']) || isset($_SESSION['YA2'])) {
    global $debugger;
    $debugger->handle_error('Session not valid for user: Name - '.Fix_Quotes($ya_username).' Email - '.Fix_Quotes($femail), 'Error');
    redirect('modules.php?name='.$module_name.'&op=new_user');
}

$pagetitle = _USERAPPLOGIN;
$_SESSION['YA2'] = true;
define_once('XDATA', true);
include_once(NUKE_MODULES_DIR.'Your_Account/public/custom_functions.php');
include_once(NUKE_BASE_DIR.'header.php');

// menelaos: makes the 'realname' field a required field
if (empty($ya_realname)) {
    DisplayErrorReturn(_YA_NOREALNAME, 1);
    exit;
}
// menelaos: added configurable doublecheck email routine
if ($ya_config['doublecheckemail'] == 0) {
    $ya_user_email2 == $ya_user_email;
} else {
    if ($ya_user_email != $ya_user_email2) {
        DisplayErrorReturn(_EMAILDIFFERENT, 1);
        exit;
    }
}
$user_viewemail = "0";
$ya_user_email = strtolower($ya_user_email);
ya_userCheck($ya_username);
ya_mailCheck($ya_user_email);
if (!$stop) {
    $datekey = date("F j");
    global $sitekey, $sysconfig;
    $rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $_POST['random_num'] . $datekey));
    $code = substr($rcode, 2, $sysconfig['codesize']);
    $gfxchk = array(3,4,6);
    if (!security_code_check($_POST['gfx_check'], $gfxchk)) {
        DisplayErrorReturn(_SECCODEINCOR, 1);
        exit;
    }
    if (empty($user_password) AND empty($user_password2)) {
        $user_password = YA_MakePass();
    } elseif ($user_password != $user_password2) {
        DisplayErrorReturn(_PASSDIFFERENT, 1);
        exit;
    } elseif ($user_password == $user_password2 AND (strlen($user_password) < $ya_config['pass_min'] OR strlen($user_password) > $ya_config['pass_max'])) {
        DisplayErrorReturn("Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!", 1);
        exit;
    }
    title(_USERREGLOGIN);
    OpenTable();
    echo "
          <form action='modules.php?name=$module_name' method='post'>
              <div align=\"center\">
                  <span class=\"title\">
                      "._USERFINALSTEP."
                  </span>
                  <br />
                  <br />
                  "._USERCHECKDATA1." $ya_username, "._USERCHECKDATA2."
                  <br />
                  "._USERCHECKDATA3."
                  <br />
                  "._USERCHECKDATA4."
                  <br />
                  <br />
                  <input type='hidden' name='random_num' value=\"$random_num\">
                  <input type='hidden' name='gfx_check' value=\"$gfx_check\">
                  <input type='hidden' name='ya_username' value=\"$ya_username\">
                  <input type='hidden' name='ya_user_email' value=\"$ya_user_email\">
                  <input type='hidden' name='user_password' value=\"$user_password\">
                  <input type='hidden' name='realname' value=\"$ya_realname\">
                  <input type='hidden' name='femail' value=\"$femail\">
                  <input type='hidden' name='user_website' value=\"$user_website\">
                  <input type='hidden' name='user_icq' value=\"$user_icq\">
                  <input type='hidden' name='user_aim' value=\"$user_aim\">
                  <input type='hidden' name='user_yim' value=\"$user_yim\">
                  <input type='hidden' name='user_msnm' value=\"$user_msnm\">
                  <input type='hidden' name='user_from' value=\"$user_from\">
                  <input type='hidden' name='user_occ' value=\"$user_occ\">
                  <input type='hidden' name='user_interests' value=\"$user_interests\">
                  <input type='hidden' name='newsletter' value=\"$newsletter\">
                  <input type='hidden' name='user_viewemail' value=\"$user_viewemail\">
                  <input type='hidden' name='user_allow_viewonline' value=\"$user_allow_viewonline\">
                  <input type='hidden' name='user_timezone' value=\"$user_timezone\">
                  <input type='hidden' name='user_dateformat' value=\"$user_dateformat\">
                  <input type='hidden' name='user_sig' value=\"$user_sig\">
                  <input type='hidden' name='bio' value=\"$bio\">";
    $xdata = array();
    $xd_meta = get_xd_metadata();
    foreach ($xd_meta as $name => $info) {
        if ( isset($HTTP_POST_VARS[$name]) && $info['handle_input'] ) {
            $xdata[$name] = trim($HTTP_POST_VARS[$name]);
            echo "<input type='hidden' name='".$name."' value=\"".$xdata[$name]."\">";
        }
    }
    echo "
                  <input type='hidden' name='op' value='new_finish'>
                  <input type='submit' value='"._FINISH."'>
              </div>
          </form>
         ";
    CloseTable();
} else {
    DisplayErrorReturn($stop, 1);
}

include_once(NUKE_BASE_DIR.'footer.php');

?>