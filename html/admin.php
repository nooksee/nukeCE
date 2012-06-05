<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

define('ADMIN_FILE', true);
define('VALIDATE', true);

if(isset($aid) && ($aid) && (!isset($admin) || empty($admin)) && $op!='login') {
    unset($aid, $admin);
    die('Access Denied');
}

// Include functions
require_once(dirname(__FILE__) . '/mainfile.php');
require_once(NUKE_ADMIN_DIR.'functions.php');
include(NUKE_BASE_DIR.'ips.php');

global $domain, $admin_file, $client;

if(isset($ips) && is_array($ips)) {
    $client = new Client();
    $ip_check = implode('|^',$ips);
    if (!preg_match("/^".$ip_check."/",$client->getIp())) {
        unset($aid);
        unset($admin);
        global $cookie;
        $name = (isset($cookie[1]) && !empty($cookie[1])) ? $cookie[1] : _ANONYMOUS;
        log_write('admin', $name.' used invalid IP address attempted to access the admin area', 'Security Breach');
        die('Invalid IP<br />Access denied');
    }
    define('ADMIN_IP_LOCK',true);
}

global $admin_file;
list($the_first )= $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$prefix."_authors", SQL_NUM);
if ($the_first == 0) {
    if (!$name) {
        include_once(NUKE_BASE_DIR.'header.php');
        title($sitename.': '._ADMINISTRATION);
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>" . _NOADMINYET . "&nbsp;</span>
                  </legend>
                  <form action=\"".$admin_file.".php\" method=\"post\" name=\"form1\">
                      <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                          <tr>
                              <td>"._NICKNAME.":&nbsp;</td>
                              <td colspan=\"3\"><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"25\"></td>
                          </tr>
                          <tr>
                              <td>"._HOMEPAGE.":&nbsp;</td>
                              <td colspan=\"3\"><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"255\" value=\"http://\"></td>
                          </tr>
                          <tr>
                              <td>"._EMAIL.":&nbsp;</td>
                              <td colspan=\"3\"><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"255\"></td>
                          </tr>
                          <tr>
                              <td valign='top'>"._PASSWORD.":&nbsp;</td>
                              <td>
                                  <input type='password' name='pwd' size='11' maxlength='40' onkeyup='chkpwd(form1.pwd.value)' onblur='chkpwd(form1.pwd.value)' onmouseout='chkpwd(form1.pwd.value)'>
                                  <br><br />
                                  <table width='300' cellpadding='2' cellspacing='0' border='1' bgcolor='#EBEBEB' style='border-collapse: collapse;'>
                                      <tr>
                                          <td id='td1' width='100' align='center'><div ID='div1'></div></td>
                                          <td id='td2' width='100' align='center'><div ID='div2'></div></td>
                                          <td id='td3' width='100' align='center'><div ID='div3'>"._PSM_NOTRATED."</div></td>
                                          <td id='td4' width='100' align='center'><div ID='div4'></div></td>
                                          <td id='td5' width='100' align='center'><div ID='div5'></div></td>
                                      </tr>
                                  </table>
                                  <div ID='divTEMP'></div>
                                  "._PSM_CLICK."
                                  <a href=\"includes/help/passhelp.php\" rel='4' class='newWindow'>"._PSM_HERE."</a>
                                  "._PSM_HELP."
                                  <br />
                              </td>
                          </tr>
                          <tr>
                              <td valign='top'>"._PASS_CONFIRM.":&nbsp;</td>
                              <td><input type='password' name='cpwd' size='11' maxlength='40'></td>
                          </tr>
                          <tr>
                              <td colspan=\"3\">&nbsp;</td>
                          </tr>
                          <tr>
                              <td colspan=\"3\">"._CREATEUSERDATA."&nbsp;
                                  <input type=\"radio\" name=\"user_new\" value=\"1\" checked>"._YES."&nbsp;
                                  <input type=\"radio\" name=\"user_new\" value=\"0\">"._NO."
                              </td>
                          </tr>
                      </table>
              </fieldset>
              <br />
                      <div align=\"center\">
                          <input type='hidden' name='fop' value='create_first'>
                          <input type=\"submit\" value=\"" . _SUBMIT . "\">
                      </div>
                  </form>
              </td>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }
    switch($fop) {
        case "create_first":
        create_first($name, $url, $email, $pwd, $user_new, $cpwd);
        break;
    }
    exit;
}

if (isset($aid) && (ereg("[^a-zA-Z0-9_-]",trim($aid)))) {
    die('Begone');
}
if (isset($aid)) { $aid = substr($aid, 0,25);}
if (isset($pwd)) { $pwd = substr($pwd, 0,40);}
if ((isset($aid)) && (isset($pwd)) && (isset($op)) && ($op == "login")) {
    $gfxchk = array(1,5,6,7);
    if (!security_code_check($_POST['gfx_check'], $gfxchk)) {
        redirect($admin_file.".php");
    }
    if(!empty($aid) AND !empty($pwd)) {
        $txt_pwd = $pwd;
        $nuke_crypt = NukeCrypt($pwd);
        $pwd = md5($pwd);
        $admlanguage = addslashes(get_admin_field('admlanguage', $aid));
        $rpwd = get_admin_field('pwd', $aid);
        //Un-nukecrypt
        if ($nuke_crypt == $rpwd) {
            $db->sql_query("UPDATE `".$prefix."_authors` SET `pwd`='".$pwd."' WHERE `aid`='".$aid."'");
            $rpwd = get_admin_field('pwd', $aid);
        }
        if($rpwd == $pwd && !empty($rpwd)) {
            $persistent = intval($persistent);
            $admin = base64_encode("$aid:$pwd:$admlanguage:$persistent");
            $time = (intval($admin1[3])) ? 43200 : 60;
            setcookie('admin',$admin,time()+($time*60));
            unset($op);
            unset($txt_pwd);
            redirect($_SERVER['REQUEST_URI']);
        } else {
            log_write('admin', 'Attempted to login with "' . $aid . '"/"' . $txt_pwd . '" but failed', 'Security Breach');
            unset($txt_pwd);
        }
    } else {
        if(empty($aid) AND empty($pwd)) {
            log_write('admin', 'Attempted to login to the admin area with no username and password', 'Security Breach');
        } else if(empty($aid)) {
            log_write('admin', 'Attempted to login to the admin area with no username', 'Security Breach');
        } else if(empty($pwd)) {
            log_write('admin', 'Attempted to login to the admin area with no password', 'Security Breach');
        }
    }
}

$admintest = 0;

if(isset($admin) && !empty($admin) && (!isset($admin1) || !is_array($admin1))) {
    $admin1 = base64_decode($admin);
    $admin1 = explode(":", $admin1);
    $aid = addslashes($admin1[0]);
    $pwd = $admin1[1];
    $admlanguage = (isset($admin1[2])) ? $admin1[2] : 'english';
    if (empty($aid) OR empty($pwd)) {
        $admintest=0;
        log_write('admin', 'Caused an Intruder Alert', 'Security Breach');
        die('Illegal Operation');
    }
    $aid = substr($aid, 0,25);
    if (!($admdata = get_admin_field('*', $aid))) {
        die('Selection from database failed!');
    } else {
        if ($admdata['pwd'] == $pwd && !empty($admdata['pwd'])) {
            $admintest = 1;
            $time = (intval($admin1[3])) ? 43200 : 60;
            if (!isset($op) || $op != 'logout') {
                setcookie('admin',$admin,time()+($time*60));
            }
        } else {
            $admdata = array();
            log_write('admin', 'Attempted to login with "' . $aid . '" but failed', 'Security Breach');
        }
    }
    unset($admin1);
}

if(!isset($op)) {
    $op = 'adminMain';
} elseif(($op=='mod_authors' OR $op=='modifyadmin' OR $op=='UpdateAuthor' OR $op=='AddAuthor' OR $op=='deladmin2' OR $op=='deladmin' OR $op=='assignstories' OR $op=='deladminconf') AND ($admdata['name'] != 'God')) {
    die('Illegal Operation');
}

if($admintest) {
    if(!$admin) exit('Illegal Operation');

    switch($op) {
        case "do_gfx":
        do_gfx();
        break;

        case "deleteNotice":
        deleteNotice($id);
        break;

        case "GraphicAdmin":
        GraphicAdmin();
        break;

        case "adminMain":
        include_once(NUKE_ADMIN_MODULE_DIR.'index.php');
        adminMain();
        break;

        case "logout":
        setcookie("admin", false);
        unset($admin);
        header("Refresh: 3; url=".$admin_file.".php");
        DisplayError(_YOUARELOGGEDOUT, 1);
        break;

        case "login";
        unset($op);

        default:
        if (!is_admin()) {
            login();
        }
        define('ADMIN_POS', true);
        define('ADMIN_PROTECTION', true);
        $casedir = opendir(NUKE_ADMIN_DIR.'case');
        while(false !== ($func = readdir($casedir))) {
            if(substr($func, 0, 5) == "case.") {
                include(NUKE_ADMIN_DIR.'case/'.$func);
            }
        }
        closedir($casedir);
        $result = $db->sql_query("SELECT title FROM ".$prefix."_modules ORDER BY title ASC");
        while (list($mod_title) = $db->sql_fetchrow($result,SQL_BOTH)) {
            if (is_mod_admin($mod_title) && file_exists(NUKE_MODULES_DIR.$mod_title.'/admin/index.php') AND file_exists(NUKE_MODULES_DIR.$mod_title.'/admin/links.php') AND file_exists(NUKE_MODULES_DIR.$mod_title.'/admin/case.php')) {
                include(NUKE_MODULES_DIR.$mod_title.'/admin/case.php');
            }
        }
        $db->sql_freeresult($result);
        break;

    }
} else {
    switch($op) {
        default:
        if (!stristr($_SERVER['HTTP_USER_AGENT'], 'WebTV')) {
            header('HTTP/1.0 403 Forbidden');
        }
        login();
        break;
    }
}

?>