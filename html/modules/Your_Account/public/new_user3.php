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

require_once(NUKE_MODULES_DIR.'Web_Links/l_config.php');

if(!isset($_SESSION)) { session_start(); }
unset($_SESSION['YA1']);
unset($_SESSION['YA2']);
$_SESSION['YA1'] = true;

function tz_select($default, $select_name = 'timezone') {
    
    $lang['tz']['-12'] = 'GMT - 12 Hours';
    $lang['tz']['-11'] = 'GMT - 11 Hours';
    $lang['tz']['-10'] = 'GMT - 10 Hours';
    $lang['tz']['-9'] = 'GMT - 9 Hours';
    $lang['tz']['-8'] = 'GMT - 8 Hours';
    $lang['tz']['-7'] = 'GMT - 7 Hours';
    $lang['tz']['-6'] = 'GMT - 6 Hours';
    $lang['tz']['-5'] = 'GMT - 5 Hours';
    $lang['tz']['-4'] = 'GMT - 4 Hours';
    $lang['tz']['-3.5'] = 'GMT - 3.5 Hours';
    $lang['tz']['-3'] = 'GMT - 3 Hours';
    $lang['tz']['-2'] = 'GMT - 2 Hours';
    $lang['tz']['-1'] = 'GMT - 1 Hours';
    $lang['tz']['0'] = 'GMT';
    $lang['tz']['1'] = 'GMT + 1 Hour';
    $lang['tz']['2'] = 'GMT + 2 Hours';
    $lang['tz']['3'] = 'GMT + 3 Hours';
    $lang['tz']['3.5'] = 'GMT + 3.5 Hours';
    $lang['tz']['4'] = 'GMT + 4 Hours';
    $lang['tz']['4.5'] = 'GMT + 4.5 Hours';
    $lang['tz']['5'] = 'GMT + 5 Hours';
    $lang['tz']['5.5'] = 'GMT + 5.5 Hours';
    $lang['tz']['6'] = 'GMT + 6 Hours';
    $lang['tz']['6.5'] = 'GMT + 6.5 Hours';
    $lang['tz']['7'] = 'GMT + 7 Hours';
    $lang['tz']['8'] = 'GMT + 8 Hours';
    $lang['tz']['9'] = 'GMT + 9 Hours';
    $lang['tz']['9.5'] = 'GMT + 9.5 Hours';
    $lang['tz']['10'] = 'GMT + 10 Hours';
    $lang['tz']['11'] = 'GMT + 11 Hours';
    $lang['tz']['12'] = 'GMT + 12 Hours';
    $lang['tz']['13'] = 'GMT + 13 Hours';

    if ( !isset($default) ) {
        $default == $sys_timezone;
    }
    $tz_select = '<select name="user_timezone">';
    while( list($offset, $zone) = @each($lang['tz']) ) {
        $selected = ( $offset == $default ) ? ' selected="selected"' : '';
        $tz_select .= '<option value="' . $offset . '"' . $selected . '>' . str_replace('GMT', 'UTC', $zone) . '</option>';
    }
    $tz_select .= '</select>';
    return $tz_select;
}

define_once('XDATA', true);
include_once(NUKE_MODULES_DIR.'Your_Account/public/custom_functions.php');
    
include_once(NUKE_BASE_DIR.'header.php');
$pagetitle = _USERREGLOGIN;
title(_USERREGLOGIN);
OpenTable();
echo "
      <form action='modules.php?name=$module_name' method='post' name='newuser'>
          <b>
              "._REGNEWUSER."
          </b>
          <br>
          <br>
          <table cellpadding=\"0\" cellspacing=\"10\" border=\"0\">
              <tr>
                  <td>
                      "._NICKNAME.":
                  </td>
                  <td>
                      <input type='text' name='ya_username' size='30' maxlength='".$ya_config['nick_max']."'> 
                      <span class=\"tiny\">
                          " . _REQUIRED . "
                      </span>
                  </td>
              </tr>
              <tr>
                  <td>
                      "._UREALNAME.":
                  </td>
                  <td>
                      <input type='text' name='ya_realname' size='30' maxlength='60'> 
                      <span class=\"tiny\">
                          " . _REQUIRED . "
                      </span>
                  </td>
              </tr>
              <tr>
                  <td>
                      "._EMAIL.":
                  </td>
                  <td>
                      <input type='text' name='ya_user_email' size='30' maxlength='255'> 
                      <span class=\"tiny\">
                          " . _REQUIRED . "
                      </span>
                  </td>
              </tr>
     ";
if ($ya_config['doublecheckemail']==1) {
    echo "
          <tr>
              <td>
                  "._RETYPEEMAIL.":
              </td>
              <td>
                  <input type='text' name='ya_user_email2' size='30' maxlength='255'>
              </td>
          </tr>
         ";
} else {
    echo "
          <input type='hidden' name='ya_user_email2' value='ya_user_email'>
         ";
}

echo "
      <tr>
          <td valign='top'>
              "._UFAKEMAIL.":
          </td>
          <td>
              <input type='text' name='femail' size='40' maxlength='255'>
              <br />
              "._EMAILPUBLIC."
          </td>
      </tr>
     ";
$xd_meta = get_xd_metadata();
while ( list($code_name, $info) = each($xd_meta) ) {
    if ($info['display_register'] == XD_DISPLAY_NORMAL && $info['signup']) {
        $value = isset($xdata[$code_name]) ? str_replace('"', '&quot;', $xdata[$code_name]) : '';
        $length = ( $info['field_length'] > 0) ? ( $info['field_length'] ) : '';
        switch ($info['field_type']) {
            case 'text':
                $value = isset($xdata[$code_name]) ? str_replace('"', '&quot;', $xdata[$code_name]) : '';
                $length = ( $info['field_length'] > 0) ? ( $info['field_length'] ) : '';
                echo '
                      <tr>
                          <td valign="top">
                              '.$info['field_name'].':
                          </td>
                          <td>
                              <input type="text" class="post"style="width: 200px" name="'.$code_name.'" size="35" maxlength="'.$length .'" value="'.$value.'" />
                              <br />
                              ('.$info['field_desc'].')
                          </td>
                      </tr>
                     ';
            break;

            case 'textarea':
                echo '
                      <tr>
                          <td>
                              '.$info['field_name'].':
                          </td>
                          <td>
                              <textarea name="'.$code_name.'"style="width: 300px"  rows="6" cols="30" class="post">
                                  '.$value.'
                              </textarea>
                              <br />
                              ('.$info['field_desc'].')
                          </td>
                      </tr>
                     ';
            break;

            case 'radio':
                echo '
                      <tr>
                          <td valign="top">
                              '.$info['field_name'].':
                          </td>
                          <td>
                     ';
                while ( list( , $option) = each($info['values_array']) ) {
                    $select = ($xdata[$code_name] == $option) ? 'selected="selected"' : '';
                    echo '
                          <input type="radio" name="'.$code_name.'" value="'.$option.'" '.$select.' /> 
                          <span class="gen">
                              '.$option.'
                          </span>
                         ';
                }
                echo '
                              <br />    
                              ('.$info['field_desc'].')
                          </td>
                      </tr>
                     ';
            break;

            case 'select':
                echo '
                      <tr>
                          <td>
                              '.$info['field_name'].':
                          </td>
                          <td>
                              <select name="'.$code_name.'">';
                while ( list( , $option) = each($info['values_array']) ) {
                    $select = ($xdata[$code_name] == $option) ? 'selected="selected"' : '';
                    echo '
                          <option value="'.$option.'" '.$select.'>
                              '.$option.'
                          </option>
                         ';
                }
                echo '
                              </select>
                              ('.$info['field_desc'].')
                          </td>
                      </tr>
                     ';
            break;
        }
    } elseif ($info['display_register'] == XD_DISPLAY_ROOT) {
        switch ($code_name) {
            case "icq":
                echo "
                      <tr>
                          <td>
                              "._YICQ.":
                          </td>
                          <td>
                              <input type='text' name='user_icq' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "aim":
                echo "
                      <tr>
                          <td>
                              "._YAIM.":
                          </td>
                          <td>
                              <input type='text' name='user_aim' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "msn":
                echo "
                      <tr>
                          <td>
                              "._YMSNM.":
                          </td>
                          <td>
                              <input type='text' name='user_msnm' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "yim":
                echo "
                      <tr>
                          <td>
                              "._YYIM.":
                          </td>
                          <td>
                              <input type='text' name='user_yim' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "website":
                echo "
                      <tr>
                          <td>
                              "._YOURHOMEPAGE.":
                          </td>
                          <td>
                              <input type='text' name='user_website' size='40' maxlength='255'>
                          </td>
                      </tr>
                     ";
            break;

            case "location":
                echo "
                      <tr>
                          <td>
                              "._YLOCATION.":
                          </td>
                          <td>
                              <input type='text' name='user_from' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "occupation":
                echo "
                      <tr>
                          <td>
                              "._YOCCUPATION.":
                          </td>
                          <td>
                              <input type='text' name='user_occ' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "interests":
                echo "
                      <tr>
                          <td>
                              "._YINTERESTS.":
                          </td>
                          <td>
                              <input type='text' name='user_interests' size='30' maxlength='100'>
                          </td>
                      </tr>
                     ";
            break;

            case "signature":
                echo "
                      <tr>
                          <td>
                              "._SIGNATURE.":
                              <br />
                              "._NOHTML."
                          </td>
                          <td>
                              <textarea cols='50' rows='5' name='user_sig'>
                              </textarea>
                              <br />
                              "._255CHARMAX."
                          </td>
                      </tr>
                     ";
            break;
        }
    }
}
echo "
      <tr>
          <td>
              "._RECEIVENEWSLETTER."
          </td>
          <td>
              <select name='newsletter'>
                  <option value='1' selected>
                      "._YES."
                  </option>
                  <option value='0'>
                      "._NO."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._ALWAYSSHOWEMAIL.":
          </td>
          <td>
              <select name='user_viewemail'>
                  <option value='1' selected>
                      "._YES."
                  </option>
                  <option value='0'>
                      "._NO."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._HIDEONLINE.":
          </td>
          <td>
              <select name='user_allow_viewonline'>
                  <option value='0'>
                      "._YES."
                  </option>
                  <option value='1' selected>
                      "._NO."
                  </option>
              </select>
          </td>
      </tr>
      <tr>
          <td>
              "._FORUMSTIME.":
          </td>
          <td>";
global $board_config;
echo tz_select($board_config['board_timezone'], 'timezone');
echo "
          </td>
      </tr>
      <tr>
          <td>
              "._FORUMSDATE.":
          </td>
          <td>
              <input type='text' name='user_dateformat' value='D M d, Y g:i a' size='15' maxlength='14'>
          </td>
      </tr>
      <tr>
          <td>
              "._EXTRAINFO.":
              <br />
              "._NOHTML."
          </td>
          <td>
              <textarea cols='50' rows='5' name='bio'></textarea>
              <br />
              "._CANKNOWABOUT."
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._PASSWORD.":
          </td>
          <td>
              <input type='password' name='user_password' size='11' maxlength='".$ya_config['pass_max']."' onkeyup='chkpwd(newuser.user_password.value)' onblur='chkpwd(newuser.user_password.value)' onmouseout='chkpwd(newuser.user_password.value)'>
              <br>
              (Password must be ".$ya_config['pass_min']." to ".$ya_config['pass_max']." characters long!)
              <br>
              <br />
              <table width='300' cellpadding='2' cellspacing='0' border='1' bgcolor='#EBEBEB' style='border-collapse: collapse;'>
                  <tr>
                      <td id='td1' width='100' align='center'>
                          <div ID='div1'>
                          </div>
                      </td>
                      <td id='td2' width='100' align='center'>
                          <div ID='div2'>
                          </div>
                      </td>
                      <td id='td3' width='100' align='center'>
                          <div ID='div3'>
                              "._PSM_NOTRATED."
                          </div>
                      </td>
                      <td id='td4' width='100' align='center'>
                          <div ID='div4'>
                          </div>
                      </td>
                      <td id='td5' width='100' align='center'>
                          <div ID='div5'>
                          </div>
                      </td>
                  </tr>
              </table>
              <div ID='divTEMP'>
              </div>
              "._PSM_CLICK." 
              <a href=\"includes/help/passhelp.php\" rel='4' class='newWindow'>
                  "._PSM_HERE."
              </a> 
              "._PSM_HELP."
              <br />
          </td>
      </tr>
      <tr>
          <td valign='top'>
              "._RETYPEPASSWORD.":
          </td>
          <td>
              <input type='password' name='user_password2' size='11' maxlength='".$ya_config['pass_max']."'>
          </td>
      </tr>
     ";
$gfxchk = array(3,4,6);
$gfx = security_code($gfxchk, 'stacked');
if(!empty($gfx)) {
    echo security_code(array(7), 'normal', 1);
}
echo "
              <input type='hidden' name='op' value='new_confirm'>
              <tr>
                  <td colspan='2'>
                      <input type='submit' value='"._YA_CONTINUE."'>
                  </td>
              </tr>
          </table>
      </form>
      <br>
      "._COOKIEWARNING."
      <br />
      "._ASREGUSER."
      <br />
      <ul>
          <li>
              "._ASREG1."
          <li>
              "._ASREG2."
          <li>
              "._ASREG4."
     ";
list($show_download) = $db->sql_fetchrow($db->sql_query("SELECT config_value FROM ".$prefix."_downloads_config WHERE config_name = 'show_download'"));
if ($show_download== 0) { echo "<li>"._SHOWDOWNLOAD.""; }
if ($ya_config['allowusertheme']==0) { echo "<li>"._CHOOSETHEME.""; }
if ($links_anonaddlinklock == 0) { echo "<li>"._ASREG5.""; }
if (is_active('Surveys')) { echo "<li>"._ASREG3.""; }

$sql = "SELECT title, custom_title FROM ".$prefix."_modules WHERE active='1' AND view='3' AND inmenu='1'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $title = $row[title];
    $custom_title = $row[custom_title];
    if (!empty($custom_title)) {
        if(substr($row[title],0,3) == '~l~') {
            echo "<li>"._ACCESSTO." ".substr($row[title],3)."";
        } else {
            echo "<li>"._ACCESSTO." $custom_title";
        }
    }
}

$sql = "SELECT title FROM ".$prefix."_blocks WHERE active='1' AND view='3'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $b_title = $row[title];
    if (!empty($b_title)) { echo "<li>"._ACCESSTO." $b_title"; }
}
if (is_active("Journal")) { echo "<li>"._CREATEJOURNAL.""; }
echo "
          <li>
              "._ASREG7."
      </ul>
      "._REGISTERNOW."
      <br />
      "._WEDONTGIVE."
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
include_once(NUKE_BASE_DIR.'footer.php');

?>