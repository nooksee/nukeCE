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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$pagetitle = $sitename.': '._ADMINISTRATION;
        
function create_first($name, $url, $email, $pwd, $user_new, $cpwd) {
    global $prefix, $db, $user_prefix, $admin_file, $language, $cache, $Default_Theme;
    if($cpwd != $pwd) {
        DisplayErrorReturn(_ERROR.": "._PASS_NOT_MATCH);
    }
    
    Validate($email, 'email', 'Admin Setup', 0, 1, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
    Validate($name, 'username', 'Admin Setup', 0, 1, 0, 2, 'Nickname:', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
    Validate($url, 'url', 'Admin Setup', 0, 0, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
    log_write('admin', 'God Admin (' . $name . ') was created', 'General Information');
    
    list($first) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM `".$prefix."_authors`"));
    if ($first == 0) {
        $pwd = md5($pwd);
        $the_adm = 'God';
        $email = validate_mail($email);
        $db->sql_query("INSERT INTO `".$prefix."_authors` VALUES ('$name', '$the_adm', '$url', '$email', '$pwd', '0', '1', '')");
        $db->sql_query("INSERT INTO `".$prefix."_nsnst_admins` (`aid`, `login`, `protected`) VALUES ('$name', '$name', '1')");
        $cookiedata = base64_encode("$name:$pwd:english:1");
        if(defined('SSL_MODE')) {
            setcookie('admin',$cookiedata,time()+2592000, "", "", 1);
        } else {
            setcookie('admin',$cookiedata,time()+2592000);
        }
        if ($user_new == 1) {
            $uid = 2;
            $cookiedata = base64_encode("$uid:$name:$pwd");
            setcookie('user',$cookiedata,time()+2592000);
            $user_regdate = date('M d, Y');
            $user_avatar = 'blank.png';
            $commentlimit = 4096;
            if ($url == 'http://') { $url = ''; }
            $boardconfig = load_board_config();
            $defaultlang = (!($boardconfig['default_lang'])) ? 'english' : $boardconfig['default_lang'];
            $defaultdateformat = (!($boardconfig['default_dateformat'])) ? 'D M d, Y g:i a' : $boardconfig['default_dateformat'];
            $db->sql_query("INSERT INTO `".$user_prefix."_users` (`user_id`, `username`, `user_email`, `user_website`, `user_avatar`, `user_regdate`, `user_password`, `theme`, `commentmax`, `user_level`, `user_lang`, `user_dateformat`, `user_color_gc`, `user_color_gi`, `user_posts`) VALUES ('','".$name."','".$email."','".$url."','".$user_avatar."','".$user_regdate."','".$pwd."','".$Default_Theme."','".$commentlimit."', '2', '".$defaultlang."','".$defaultdateformat."','FFA34F','--1--', '1')");
            $cache->delete('UserColors', 'config');
        }
        redirect($admin_file.".php");
    }
}

function login() {
    global $admin_file, $db, $prefix;
    include(NUKE_BASE_DIR.'header.php');
    OpenTable();
    echo "
          <div align=\"center\">
              <font class=\"title\">"._ADMINLOGIN."</font>
          </div>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "
          <table border=\"0\">
          <form method=\"post\" action=\"".$admin_file.".php\">
              <tr>
                  <td>"._ADMINID."</td>
                  <td><input type=\"text\" NAME=\"aid\" SIZE=\"20\" MAXLENGTH=\"25\"></td>
              </tr>
              <tr>
                  <td>"._PASSWORD."</td>
                  <td><input type=\"password\" NAME=\"pwd\" SIZE=\"20\" MAXLENGTH=\"40\"></td>
              </tr>
         ";
    $gfxchk = array(1,5,6,7);
    echo security_code($gfxchk, 'large');
    echo "
          </table>
          <table border=\"0\">
              <tr>
                  <td align=\"right\" colspan=\"2\">"._PERSISTENT."</td>
                  <td><input type=\"checkbox\" name=\"persistent\" value=\"1\" checked=\"checked\"></td>
              </tr>
              <tr>
                  <td colspan=\"2\"></td>
              </tr>
              <tr>
                  <td>
                      <input type=\"hidden\" NAME=\"op\" value=\"login\">
                      <input type=\"submit\" VALUE=\""._LOGIN."\">
                  </td>
              </tr>
          </form>
          </table>
         ";
    CloseTable();
    include(NUKE_BASE_DIR.'footer.php');
}

function deleteNotice($id) {
    global $prefix, $db, $admin_file, $cache;
    $id = intval($id);
    $db->sql_query("DELETE FROM `".$prefix."_reviews_add` WHERE `id` = '$id'");
    $cache->delete('numwaitreviews', 'submissions');
    redirect($admin_file.".php?op=reviews");
}

function adminmenu($url, $title, $image) {
    global $counter, $admingraphic, $Default_Theme;
    $ThemeSel = get_theme();
    if (file_exists("themes/$ThemeSel/images/admin/$image")) {
        $image = "themes/$ThemeSel/images/admin/$image";
    } else {
        $image = "images/admin/$image";
    }
    $img = ($admingraphic) ? "<img src=\"$image\" border=\"0\" alt=\"$title\" title=\"$title\" /></a><br />" : '';
    $close = ($admingraphic) ? '' : '</a>';
    echo "
          <td align=\"center\" width=\"10%\">
              <span class=\"content\">
                  <a href=\"$url\">$img<strong>$title</strong>$close<br /><br />
              </span>
          </td>
         ";
    if ($counter == 6) echo '</tr><tr>';
    $counter = ($counter == 6) ? 0 : $counter + 1;
}

function GraphicAdmin($pos=1) {
    global $aid, $admingraphic, $language, $admin, $prefix, $db, $counter, $admin_file, $admin_pos, $admdata, $radminsuper;
    if($pos != $admin_pos) {
        return;
    }
    $radminsuper = is_mod_admin();
    if (is_mod_admin('super')) {
        OpenTable();
        echo "
              <div align=\"center\">
                  <a href=\"".$admin_file.".php\"><font size='3' class='title'><strong>"._ADMINMENU."</strong></font></a>
                  <br /><br /><br />
                  <table border=\"0\" width=\"100%\" cellspacing=\"1\">
                      <tr>
             ";
        $linksdir = opendir(NUKE_ADMIN_DIR.'links');
        $menulist = "";
        while(false !== ($func = readdir($linksdir))) {
            if(substr($func, 0, 6) == 'links.') {
                $menulist .= $func.' ';
            }
        }
        closedir($linksdir);
        $menulist = explode(' ', $menulist);
        sort($menulist);
        for ($i=0, $maxi = count($menulist); $i < $maxi; $i++) {
            if(!empty($menulist[$i])) {
                include(NUKE_ADMIN_DIR.'links/'.$menulist[$i]);
            }
        }
        adminmenu($admin_file.'.php?op=logout', _ADMINLOGOUT, 'logout.gif');
        echo "
                      </tr>
                  </table>
              </div>
             ";
        $counter = "";
        CloseTable();
        echo "<br />";
    }
    OpenTable();
    echo "
          <div align=\"center\">
              <a href=\"".$admin_file.".php\"><font size='3' class='title'><strong>"._MODULESADMIN."</strong></font></a>
              <br /><br /><br />
              <table border=\"0\" width=\"100%\" cellspacing=\"1\">
                  <tr>
         ";
    update_modules();
    $result = $db->sql_query("SELECT title FROM ".$prefix."_modules ORDER BY title ASC");
    while($row = $db->sql_fetchrow($result)) {
        if (is_mod_admin($row['title'])) {
            if (file_exists(NUKE_MODULES_DIR.$row['title']."/admin/index.php") AND file_exists(NUKE_MODULES_DIR.$row['title']."/admin/links.php") AND file_exists(NUKE_MODULES_DIR.$row['title']."/admin/case.php")) {
                include(NUKE_MODULES_DIR.$row['title']."/admin/links.php");
            }
        }
    }
    echo "
                  </tr>
              </table>
          </div>
         ";
    CloseTable();
    echo '<br />';
}

?>