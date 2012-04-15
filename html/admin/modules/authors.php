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

if (!defined('ADMIN_FILE')) {
   die ('Illegal File Access');
}

global $prefix, $db;
$pagetitle = _AUTHORSADMIN;

if (is_mod_admin()) {

    function displayadmins() {
        global $admin, $prefix, $db, $language, $multilingual, $admin_file;
        if (is_admin()) {
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            OpenTable();
            echo "
                  <div align=\"center\">
                      <font class=\"title\">
                          <a href=\"$admin_file.php?op=mod_authors\">
                              "._AUTHORSADMIN."
                          </a>
                      </font>
                  </div>
                 ";
            CloseTable();
            echo "
                  <br />
                 ";
            OpenTable();
            echo "
                  <span class=\"gen\">
                  <br />
                  </span>
                  <table align=\"center\" width=\"96%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                      <caption align=\"bottom\">
                          <br />
                          <span class=\"genmed\">
                              " . _GODNOTDEL . "
                          </span>
                      </caption>
                      <tr>
                          <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                              <strong>
                                  "._NAME."
                              </strong>
                          </th>
                          <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                              <strong>
                                  "._NICKNAME."
                              </strong>
                          </th>
                          <th colspan=\"1\" align=\"left\" class=\"thTop\" nowrap=\"nowrap\">
                              <strong>
                                  "._URL."
                              </strong>
                          </th>
                          <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                              <strong>
                                  "._EMAIL."
                              </strong>
                          </th>
                          <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                              <strong>
                                  "._LANGUAGE."
                              </strong>
                          </th>
                          <th colspan=\"1\" align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                              <strong>
                                  "._FUNCTIONS."
                              </strong>
                          </th>
                      </tr>
                 ";
            $result = $db->sql_query("SELECT aid, name, url, email, admlanguage from " . $prefix . "_authors");
            while ($row = $db->sql_fetchrow($result)) {
                $row_class = ($c++%2==1) ? 'row2' : 'row1';
                $a_aid = $row['aid'];
                $name = $row['name'];
                $url = $row['url'];
                $email = $row['email'];
                $admlanguage = $row['admlanguage'];
                $a_aid = substr($a_aid, 0,25);
                $name = substr($name, 0,50);
                if ($name == "God") {
                    echo "
                          <tr>
                              <td class=".$row_class." align=\"center\">
                                  $a_aid
                                  <i>
                                      ("._MAINACCOUNT.")
                                  </i>
                              </td>
                         ";
                } else {
                    echo "
                          <tr>
                              <td class=".$row_class." align=\"center\">
                                  $a_aid
                              </td>
                         ";
                }
                echo "
                      <td class=".$row_class." align=\"center\">
                          $name
                      </td>
                      <td class=".$row_class." align=\"left\">
                          $url
                      </td>
                      <td class=".$row_class." align=\"center\">
                          $email
                      </td>
                     ";
                if (empty($admlanguage)) {
                    $admlanguage =  _ALL;
                }
                echo "
                      <td class=".$row_class." align=\"center\">
                          $admlanguage
                      </td>
                      <td class=".$row_class." align=\"center\">
                          <a href=\"".$admin_file.".php?op=modifyadmin&amp;chng_aid=$a_aid\">
                              <img src=\"images/edit.gif\" alt=\""._MODIFYINFO."\" title=\""._MODIFYINFO."\" border=\"0\" width=\"17\" height=\"17\">
                          </a>
                     ";
                if($name=="God") {
                    echo "
                                  <img src=\"images/delete_x.gif\" alt=\""._MAINACCOUNT."\" title=\""._MAINACCOUNT."\" border=\"0\" width=\"17\" height=\"17\">
                              </td>
                          </tr>
                         ";
                } else {
                    echo "
                                  <a href=\"".$admin_file.".php?op=deladmin&amp;del_aid=$a_aid\">
                                      <img src=\"images/delete.gif\" alt=\""._DELAUTHOR."\" title=\""._DELAUTHOR."\" border=\"0\" width=\"17\" height=\"17\">
                                  </a>
                              </td>
                          </tr>
                         ";
                }
            }
            echo "
                  </table>
                  <span class=\"gen\">
                  <br />
                  </span>
                 ";
            CloseTable();
            echo "
                  <br />
                 ";
            OpenTable();
            echo "
                  <fieldset>
                      <legend>
                          <span class='option'>
                              " . _ADDAUTHOR . "
                              &nbsp;
                          </span>
                      </legend>
                      <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <form action=\"".$admin_file.".php\" method=\"post\" name=\"newauthor\">
                          <tr>
                              <td>
                                  " . _NAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\">
                                  <span class=\"tiny\">
                                      " . _REQUIREDNOCHANGE . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _NICKNAME . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_aid\" size=\"30\" maxlength=\"25\">
                                  <span class=\"tiny\">
                                      " . _REQUIRED . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _EMAIL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\">
                                  <span class=\"tiny\">
                                      " . _REQUIRED . "
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  " . _URL . ":
                              </td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\">
                              </td>
                          </tr>
                 ";
            if ($multilingual == 1) {
                echo "
                      <tr>
                          <td>
                              " . _LANGUAGE . ":
                          </td>
                          <td colspan=\"3\">
                              <select name=\"add_admlanguage\">
                     ";
                $languageslist = lang_list();
                for ($i=0, $maxi = count($languagelist); $i < $maxi; $i++) {
                    if(!empty($languageslist[$i])) {
                        echo "<option name='xlanguage' value='".$languageslist[$i]."'";
                        if($languageslist[$i]==$language) echo "selected";
                        echo ">".ucwords($languageslist[$i])."";
                    }
                }
                echo "
                                  <option value=\"\">
                                      " . _ALL . "
                                  </option>
                              </select>
                          </td>
                      </tr>
                     ";
            } else {
                echo "
                      <input type=\"hidden\" name=\"add_admlanguage\" value=\"\">
                     ";
            }
            echo "
                  <tr>
                      <td>
                          " . _PERMISSIONS . ":
                      </td>
                 ";
            $result = $db->sql_query("SELECT mid, title FROM ".$prefix."_modules ORDER BY title ASC");
            $a = 0;
            while ($row = $db->sql_fetchrow($result)) {
                $title = str_replace("_", " ", $row['title']);
                if (file_exists("modules/".$row['title']."/admin/index.php") AND file_exists("modules/".$row['title']."/admin/links.php") AND file_exists("modules/".$row['title']."/admin/case.php")) {
                    echo "<td><input type=\"checkbox\" name=\"auth_modules[]\" value=\"".intval($row['mid'])."\"> $title</td>";
                    if ($a == 2) { echo "</tr><tr><td>&nbsp;</td>";
                        $a = 0;
                    } else {
                        $a++;
                    }
                }
            }
            $db->sql_freeresult($result);
            echo "
                          </tr>
                          <tr>
                              <td>
                                  &nbsp;
                              </td>
                              <td>
                                  <input type=\"checkbox\" name=\"add_radminsuper\" value=\"1\"> 
                                  <strong>
                                      " . _SUPERUSER . "
                                  </strong>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  &nbsp;
                              </td>
                              <td colspan=\"3\">
                                  <span class=\"tiny\">
                                      <i>
                                          " . _SUPERWARNING . "
                                      </i>
                                  </span>
                              </td>
                          </tr>
                         <tr>
                             <td valign='top'>
                                 " . _PASSWORD . ":
                             </td>
                             <td colspan=\"3\">
                                 <input type=\"password\" name=\"add_pwd\" size=\"12\" maxlength=\"40\" onkeyup='chkpwd(newauthor.add_pwd.value)' onblur='chkpwd(newauthor.add_pwd.value)' onmouseout='chkpwd(newauthor.add_pwd.value)'> 
                                 <span class=\"tiny\">
                                     " . _REQUIRED . "
                                 </span>
                                 </br>
                                 </br>
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
                     </table>
                 </fieldset>
                 <br />
                     <div align=\"center\">
                         <input type=\"hidden\" name=\"op\" value=\"AddAuthor\">
                         <input type=\"submit\" value=\"" . _ADDAUTHOR2 . "\">
                     </div>
                     </form>
                 </td>
                ";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        } else {
            DisplayError(_UNAUTHORIZEDAUTHORS, 1);
        }
    }

    function modifyadmin($chng_aid) {
        global $admin, $prefix, $db, $multilingual, $admin_file;
        if (is_admin()) {
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            OpenTable();
            echo "
                  <div align=\"center\">
                      <font class=\"title\"><a href=\"$admin_file.php?op=mod_authors\">"._AUTHORSADMIN."</a></font>
                  </div>
                 ";
            CloseTable();
            echo "<br />";
            OpenTable();

            $adm_aid = $chng_aid;
            $adm_aid = trim($adm_aid);
            $row = $db->sql_fetchrow($db->sql_query("SELECT aid, name, url, email, pwd, radminsuper, admlanguage from " . $prefix . "_authors where aid='$chng_aid'"));
            $chng_aid = $row['aid'];
            $chng_name = $row['name'];
            $chng_url = stripslashes($row['url']);
            $chng_email = stripslashes($row['email']);
            $chng_pwd = $row['pwd'];
            $chng_radminsuper = intval($row['radminsuper']);
            $chng_admlanguage = $row['admlanguage'];
            $chng_aid = substr($chng_aid, 0,25);
            $aid = $chng_aid;

            echo "
                  <fieldset>
                      <legend>
                          <span class='option'>" . _MODIFYINFO . "&nbsp;</span>
                      </legend>
                      <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <form action=\"".$admin_file.".php\" method=\"post\" name=\"newauthor\">
                          <tr>
                              <td>" . _NAME . ":&nbsp;</td>
                              <td colspan=\"3\">
                                  <input type=\"hidden\" name=\"chng_name\" value=\"$chng_name\">
                                  <input type=\"text\" value=\"$chng_name\" size=\"30\" disabled=\"disabled\">
                              </td>
                          </tr>
                          <tr>
                              <td>" . _NICKNAME . ":&nbsp;</td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_aid\" value=\"$chng_aid\" size=\"30\" maxlength=\"25\">
                                  <span class=\"tiny\">" . _REQUIRED . "</span>
                              </td>
                          </tr>
                          <tr>
                              <td>" . _EMAIL . ":&nbsp;</td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\">
                                  <span class=\"tiny\">" . _REQUIRED . "</span>
                              </td>
                          </tr>
                          <tr>
                              <td>" . _URL . ":&nbsp;</td>
                              <td colspan=\"3\">
                                  <input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\">
                              </td>
                          </tr>
                 ";
            if ($multilingual == 1) {
                echo "
                      <tr>
                          <td> " . _LANGUAGE . ":&nbsp;</td>
                          <td colspan=\"3\">
                              <select name=\"chng_admlanguage\">
                     ";
                $languageslist = lang_list();
                for ($i=0, $maxi = count($languageslist); $i < $maxi; $i++) {
                    if(!empty($languageslist[$i])) {
                        echo "<option name='xlanguage' value='".$languageslist[$i]."'";
                        if($languageslist[$i]==$language) echo "selected";
                        echo ">".ucwords($languageslist[$i])."";
                    }
                }
                if (empty($chng_admlanguage)) {
                    $allsel = 'selected';
                } else {
                    $allsel = '';
                }
                echo "
                                  <option value=\"\" $allsel>" . _ALL . "</option>
                              </select>
                          </td>
                      </tr>
                     ";
            } else {
                echo "<input type=\"hidden\" name=\"chng_admlanguage\" value=\"\">";
            }
            echo "
                  <tr>
                      <td>" . _PERMISSIONS . ":&nbsp;</td>
                 ";
            if ($row['name'] != 'God') {
                $result = $db->sql_query("SELECT mid, title, admins FROM ".$prefix."_modules ORDER BY title ASC");
                while ($row = $db->sql_fetchrow($result)) {
                    $title = str_replace("_", " ", $row['title']);
                    if (file_exists(NUKE_MODULES_DIR.$row['title'].'/admin/index.php') AND file_exists(NUKE_MODULES_DIR.$row['title'].'/admin/links.php') AND file_exists(NUKE_MODULES_DIR.$row['title'].'/admin/case.php')) {
                        if(!empty($row['admins'])) {
                            $admins = explode(",", $row['admins']);
                            $sel = '';
                            for ($i=0, $maxi=count($admins); $i < $maxi; $i++) {
                                if ($chng_name == $admins[$i]) {
                                    $sel = 'checked';
                                }
                            }
                        }
                        echo "<td><input type=\"checkbox\" name=\"auth_modules[]\" value=\"".intval($row['mid'])."\" $sel> $title</td>";
                        $sel = "";
                        if ($a == 2) {
                            echo "
                                  </tr>
                                  <tr>
                                      <td>&nbsp;</td>
                                 ";
                            $a = 0;
                        } else {
                            $a++;
                        }
                    }
                }
                $db->sql_freeresult($result);
                if ($chng_radminsuper == 1) {
                    $sel1 = 'checked';
                }
                echo "
                      </tr>
                      <tr>
                          <td>&nbsp;</td>
                     ";
            } else {
                echo "
                      <input type=\"hidden\" name=\"auth_modules[]\" value=\"\">
                     ";
                $sel1 = 'checked';
            }
            echo "
                              <td>
                                  <input type=\"checkbox\" name=\"chng_radminsuper\" value=\"1\" $sel1> 
                                  <strong>" . _SUPERUSER . "</strong>
                              </td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td colspan=\"3\">
                                  <span class=\"tiny\"><i>" . _SUPERWARNING . "</i></span>
                              </td>
                          </tr>
                          <tr>
                              <td valign='top'>" . _PASSWORD . ":&nbsp;</td>
                              <td colspan=\"3\">
                                  <input type=\"password\" name=\"chng_pwd\" size=\"12\" maxlength=\"40\" onkeyup='chkpwd(newauthor.chng_pwd.value)' onblur='chkpwd(newauthor.chng_pwd.value)' onmouseout='chkpwd(newauthor.chng_pwd.value)'>
                                  <span class=\"tiny\">" . _FORCHANGES . "</span>
                                  </br></br>
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
                          <tr>
                              <td>" . _RETYPEPASSWD . ":&nbsp;</td>
                              <td colspan=\"3\"><input type=\"password\" name=\"chng_pwd2\" size=\"12\" maxlength=\"40\"></td>
                          </tr>
                      </table>
                  </fieldset>
                  <br />
                      <div align=\"center\">
                          <input type=\"hidden\" name=\"adm_aid\" value=\"$adm_aid\">
                          <input type=\"hidden\" name=\"op\" value=\"UpdateAuthor\">
                          <input type=\"submit\" value=\"" . _SAVE . "\">
                      </div>
                      </form>
                  </td>
                 ";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        } else {
            DisplayError(_UNAUTHORIZEDAUTHORS, 1);
        }
    }

    function updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $chng_admlanguage, $adm_aid, $auth_modules) {
        global $admin, $prefix, $db, $admin_file;
        if (is_admin()) {
            Validate($chng_aid, 'username', 'Modify Authors', 0, 1, 0, 2, 'Nickname:', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            Validate($chng_url, 'url', 'Modify Authors', 0, 0, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            Validate($chng_email, 'email', 'Modify Authors', 0, 1, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            if (!empty($chng_pwd2)) {
                Validate($chng_pwd, '', 'Modify Authors', 0, 1, 0, 2, 'password', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
                if($chng_pwd != $chng_pwd2) {
                    DisplayErrorReturn(_PASSWDNOMATCH);
                }
                $chng_pwd = md5($chng_pwd);
                $chng_aid = substr($chng_aid, 0,25);
                if ($chng_radminsuper == 1) {
                    $result = $db->sql_query("SELECT mid, admins FROM ".$prefix."_modules");
                    while ($row = $db->sql_fetchrow($result)) {
                        $admins = explode(",", $row['admins']);
                        $adm = '';
                        for ($a=0, $maxi=count($admins); $a < $maxi; $a++) {
                            if ($admins[$a] != $chng_name && !empty($admins[$a])) {
                                $adm .= $admins[$a].',';
                            }
                        }
                        $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm' WHERE mid='".intval($row['mid'])."'");
                    }
                    $db->sql_query("update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', pwd='$chng_pwd', admlanguage='$chng_admlanguage' where name='$chng_name' AND aid='$adm_aid'");
                    if ($adm_aid == $chng_aid) {
                        redirect($admin_file.".php?op=logout");
                    } else {
                        redirect($admin_file.".php?op=mod_authors");
                    }
                } else {
                    if ($chng_name != 'God') {
                        $db->sql_query("update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='0', pwd='$chng_pwd', admlanguage='$chng_admlanguage' where name='$chng_name' AND aid='$adm_aid'");
                    }
                    $result = $db->sql_query("SELECT mid, admins FROM ".$prefix."_modules");
                    while ($row = $db->sql_fetchrow($result)) {
                        $admins = explode(",", $row['admins']);
                        $adm = '';
                        for ($a=0, $maxa = count($admins); $a < $maxa; $a++) {
                            if ($admins[$a] != $chng_name && !empty($admins[$a])) {
                                $adm .= $admins[$a].',';
                            }
                        }
                        $db->sql_query("UPDATE ".$prefix."_authors SET radminsuper='$chng_radminsuper' WHERE name='$chng_name' AND aid='$adm_aid'");
                        $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm' WHERE mid='".intval($row['mid'])."'");
                    }
                    for ($i=0, $maxi=count($auth_modules); $i < $maxi; $i++) {
                        $row = $db->sql_fetchrow($db->sql_query("SELECT admins FROM ".$prefix."_modules WHERE mid='".intval($auth_modules[$i])."'"));
                        if(!empty($row['admins'])) {
                            $admins = explode(",", $row['admins']);
                            for ($a=0, $maxa = count($admins); $a < $maxa; $a++) {
                                if ($admins[$a] == $chng_name) {
                                    $dummy = 1;
                                }
                            }
                        }
                        if ($dummy != 1) {
                            $adm = $row['admins'].$chng_name;
                            $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm,' WHERE mid='".intval($auth_modules[$i])."'");
                        }
                        $dummy = '';
                    }
                    redirect($admin_file.".php?op=mod_authors");
                }
            } else {
                if ($chng_radminsuper == 1) {
                    $result = $db->sql_query("SELECT mid, admins FROM ".$prefix."_modules");
                    while ($row = $db->sql_fetchrow($result)) {
                        $admins = explode(",", $row['admins']);
                        $adm = '';
                        for ($a=0, $maxa = count($admins); $a < $maxa; $a++) {
                            if ($admins[$a] != $chng_name && !empty($admins[$a])) {
                                $adm .= $admins[$a].',';
                            }
                        }
                        $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm' WHERE mid='".intval($row['mid'])."'");
                    }
                    $db->sql_query("update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', admlanguage='$chng_admlanguage' where name='$chng_name' AND aid='$adm_aid'");
                    redirect($admin_file.".php?op=mod_authors");
                } else {
                    if ($chng_name != 'God') {
                        $db->sql_query("update " . $prefix . "_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminsuper='0', admlanguage='$chng_admlanguage' where name='$chng_name' AND aid='$adm_aid'");
                    }
                    $result = $db->sql_query("SELECT mid, admins FROM ".$prefix."_modules");
                    while ($row = $db->sql_fetchrow($result)) {
                        $admins = explode(",", $row['admins']);
                        $adm = '';
                        for ($a=0, $maxa = count($admins); $a < $maxa; $a++) {
                            if ($admins[$a] != $chng_name && !empty($admins[$a])) {
                                $adm .= $admins[$a].',';
                            }
                        }
                        $db->sql_query("UPDATE ".$prefix."_authors SET radminsuper='$chng_radminsuper' WHERE name='$chng_name' AND aid='$adm_aid'");
                        $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm' WHERE mid='".intval($row['mid'])."'");
                    }
                    for ($i=0, $maxi=count($auth_modules); $i < $maxi; $i++) {
                        $row = $db->sql_fetchrow($db->sql_query("SELECT admins FROM ".$prefix."_modules WHERE mid='".intval($auth_modules[$i])."'"));
                        if(!empty($row['admins'])) {
                            $admins = explode(",", $row['admins']);
                            for ($a=0, $maxa=count($admins); $a < $maxa; $a++) {
                                if ($admins[$a] == $chng_name) {
                                    $dummy = 1;
                                }
                            }
                        }
                        if ($dummy != 1) {
                            $adm = $row['admins'].$chng_name;
                            $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm,' WHERE mid='".intval($auth_modules[$i])."'");
                        }
                        $dummy = '';
                    }
                    redirect($admin_file.'.php?op=mod_authors');
                }
            }
            if ($adm_aid != $chng_aid) {
                $result2 = $db->sql_query("SELECT sid, aid, informant from " . $prefix . "_stories where aid='$adm_aid'");
                while ($row2 = $db->sql_fetchrow($result2)) {
                    $sid = intval($row2['sid']);
                    $old_aid = $row2['aid'];
                    $old_aid = substr($old_aid, 0,25);
                    $informant = $row2['informant'];
                    $informant = substr($informant, 0,25);
                    if ($old_aid == $informant) {
                        $db->sql_query("update " . $prefix . "_stories set informant='$chng_aid' where sid='$sid'");
                    }
                    $db->sql_query("update " . $prefix . "_stories set aid='$chng_aid' WHERE sid='$sid'");
                }
            }
        } else {
            DisplayError(_UNAUTHORIZEDAUTHORS, 1);
        }
    }

    function deladmin2($del_aid) {
        global $admin, $prefix, $db, $admin_file;
        if (is_admin()) {
            $del_aid = substr($del_aid, 0,25);
            $result = $db->sql_query("SELECT admins FROM ".$prefix."_modules WHERE title='News'");
            $row2 = $db->sql_fetchrow($db->sql_query("SELECT name FROM ".$prefix."_authors WHERE aid='$del_aid'"));
            while ($row = $db->sql_fetchrow($result)) {
                $admins = explode(",", $row['admins']);
                $auth_user = 0;
                for ($i=0, $maxi=count($admins); $i < $maxi; $i++) {
                    if ($row2['name'] == $admins[$i]) {
                        $auth_user = 1;
                    }
                }
                if ($auth_user == 1) {
                    $radminarticle = 1;
                }
            }
            $db->sql_freeresult($result);
            if ($radminarticle == 1) {
                $row2 = $db->sql_fetchrow($db->sql_query("SELECT sid from " . $prefix . "_stories where aid='$del_aid'"));
                $sid = intval($row2['sid']);
                if (!empty($sid)) {
                    include_once(NUKE_BASE_DIR.'header.php');
                    GraphicAdmin();
                    OpenTable();
                    echo "
                          <div align=\"center\">
                              <font class=\"title\"><a href=\"$admin_file.php?op=mod_authors\">"._AUTHORSADMIN."</a></font>
                          </div>
                         ";
                    CloseTable();
                    echo "<br />";
                    OpenTable();
                    echo "
                          <div align=\"center\">
                              <span class=\"option\">" . _PUBLISHEDSTORIES . "</span>
                              <br /><br />
                              ". _SELECTNEWADMIN . ":
                              <br /><br />
                         ";
                    $result3 = $db->sql_query("SELECT aid from " . $prefix . "_authors where aid!='$del_aid'");
                    echo "
                          <form action=\"".$admin_file.".php\" method=\"post\">
                              <select name=\"newaid\">
                         ";
                    while ($row3 = $db->sql_fetchrow($result3)) {
                        $oaid = $row3['aid'];
                        $oaid = substr($oaid, 0,25);
                        echo "
                              <option name=\"newaid\" value=\"$oaid\">
                                  $oaid
                              </option>
                             ";
                    }
                    $db->sql_freeresult($result3);
                    echo "
                                  </select>
                                  <input type=\"hidden\" name=\"del_aid\" value=\"$del_aid\">
                                  <input type=\"hidden\" name=\"op\" value=\"assignstories\">
                                  <input type=\"submit\" value=\"" . _OK . "\">
                              </form>
                          </div>
                         ";
                    CloseTable();
                    include_once(NUKE_BASE_DIR.'footer.php');
                    return;
                }
            }
            redirect($admin_file.".php?op=deladminconf&del_aid=$del_aid");
        } else {
            DisplayError(_UNAUTHORIZEDAUTHORS, 1);
        }
    }

    if($add_aid != $_POST['add_aid']) {
        die('Illegal Variable');
    }
    if($add_name != $_POST['add_name']) {
        die('Illegal Variable');
    }

    switch ($op) {
        case "mod_authors":
            displayadmins();
        break;

        case "modifyadmin":
            modifyadmin($chng_aid);
        break;

        case "UpdateAuthor":
            echo $chng_aid;
            updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $chng_admlanguage, $adm_aid, $auth_modules);
        break;

        case "AddAuthor":
            global $admin_file;
            $add_aid = substr($add_aid, 0,25);
            $add_name = substr($add_name, 0,25);
            Validate($add_aid, 'username', 'Add Authors', 0, 1, 0, 2, 'Nickname:', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            Validate($add_name, 'username', 'Add Authors', 0, 1, 0, 2, 'Name:', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            Validate($add_url, 'url', 'Add Authors', 0, 0, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            Validate($add_email, 'email', 'Add Authors', 0, 1, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            Validate($add_pwd, '', 'Add Authors', 0, 1, 0, 2, 'password', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            $add_pwd = md5($add_pwd);
            for ($i=0,$maxi=count($auth_modules); $i < $maxi; $i++) {
                $row = $db->sql_fetchrow($db->sql_query("SELECT admins FROM ".$prefix."_modules WHERE mid='".intval($auth_modules[$i])."'"));
                $adm = $row['admins'] . $add_name;
                $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm,' WHERE mid='".intval($auth_modules[$i])."'");
            }
            $result = $db->sql_query("insert into " . $prefix . "_authors values ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwd', '0', '$add_radminsuper', '$add_admlanguage')");
            if (!$result) {
                redirect($admin_file.".php");
            }
            $db->sql_freeresult($result);
            redirect($admin_file.".php?op=mod_authors");
        break;

        case "deladmin":
            $del_aid = trim($del_aid);
            confirm_msg("" . _AUTHORDELSURE . " <i>$del_aid</i>?", "".$admin_file.".php?op=deladmin2&amp;del_aid=$del_aid", "".$admin_file.".php?op=mod_authors");
        break;

        case "deladmin2":
            deladmin2($del_aid);
        break;

        case "assignstories":
            $del_aid = trim($del_aid);
            $result = $db->sql_query("SELECT sid from " . $prefix . "_stories where aid='$del_aid'");
            while ($row = $db->sql_fetchrow($result)) {
                $sid = intval($row['sid']);
                $db->sql_query("update " . $prefix . "_stories set aid='$newaid', informant='$newaid' where aid='$del_aid'");
                $db->sql_query("update " . $prefix . "_authors set counter=counter+1 where aid='$newaid'");
            }
            $db->sql_freeresult($result);
            redirect($admin_file.".php?op=deladminconf&del_aid=$del_aid");
        break;

        case "deladminconf":
            $del_aid = trim($del_aid);
            $db->sql_query("delete from " . $prefix . "_authors where aid='$del_aid' AND name!='God'");
            $result = $db->sql_query("SELECT mid, admins FROM ".$prefix."_modules");
            while ($row = $db->sql_fetchrow($result)) {
                $admins = explode(",", $row['admins']);
                   $adm = "";
                   for ($a=0, $maxa=count($admins); $a < $maxa; $a++) {
                    if ($admins[$a] != $del_aid && !empty($admins[$a])) {
                        $adm .= $admins[$a].',';
                       }
                   }
                $db->sql_query("UPDATE ".$prefix."_modules SET admins='$adm' WHERE mid='".intval($row['mid'])."'");
            }
            $db->sql_freeresult($result);
            redirect($admin_file.".php?op=mod_authors");
        break;

    }

} else {
    echo "Access Denied";
}

?>