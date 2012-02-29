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
   die ("Illegal File Access");
}

global $prefix, $db, $admdata;

if ($admdata['radminsuper'] == 1) {

    function MsgDeactive($mid) {
        global $prefix, $db, $admin_file;
        $mid = intval($mid);
        $db->sql_query("update " . $prefix . "_message set active='0' WHERE mid='$mid'");
        Header("Location: ".$admin_file.".php?op=messages");
    }

    function messages() {
        global $admin, $admlanguage, $language, $prefix, $db, $multilingual, $admin_file;
        include(NUKE_BASE_DIR.'header.php');
        if (empty($admlanguage)) {
            $admlanguage = $language; /* This to make sure some language is pre-selected */
        }
        GraphicAdmin();
        OpenTable();
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php?op=messages\">
                          "._MESSAGES."
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
                  <tr>
                      <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                          <strong>
                              "._ID."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"left\" class=\"thTop\" nowrap=\"nowrap\">
                          <strong>
                              "._TITLE."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                          <strong>
                              "._LANGUAGE."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                          <strong>
                              "._VIEW."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                          <strong>
                              "._STATUS."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                          <strong>
                              "._FUNCTIONS."
                          </strong>
                      </th>
                  </tr>
             ";
        $result = $db->sql_query("SELECT * from " . $prefix . "_message");
        while ($row = $db->sql_fetchrow($result)) {
            $row_class = ($c++%2==1) ? 'row2' : 'row1';
            $groups = $row['groups'];
            $mid = intval($row['mid']);
            $title = $row['title'];
            $content = $row['content'];
            $mdate = $row['date'];
            $expire = intval($row['expire']);
            $active = intval($row['active']);
            $view = intval($row['view']);
            $mlanguage = $row['mlanguage'];
            if ($active == 1) {
                $mactive = "<img src=\"images/active.gif\" alt=\""._ACTIVE."\" title=\""._ACTIVE."\" border=\"0\" width=\"16\" height=\"16\">";
            } elseif ($active == 0) {
                $mactive = "<img src=\"images/inactive.gif\" alt=\""._INACTIVE."\" title=\""._INACTIVE."\" border=\"0\" width=\"16\" height=\"16\">";
            }
            if ($view == 1) {
                $mview = _MVALL;
            } elseif ($view == 2) {
                $mview = _MVANON;
            } elseif ($view == 3) {
                $mview = _MVUSERS;
            } elseif ($view == 4) {
                $mview = _MVADMIN;
            } elseif ($view > 5) {
                $mview = _MVGROUPS;
            }
            if (empty($mlanguage)) {
                $mlanguage = _ALL;
            }
            echo "
                  <tr>
                      <td class=".$row_class." align=\"center\">
                          $mid
                      </td>
                      <td class=".$row_class." align=\"left\">
                          $title
                      </td>
                      <td class=".$row_class." align=\"center\">
                          $mlanguage
                      </td>
                      <td class=".$row_class." align=\"center\">
                          $mview
                      </td>
                      <td class=".$row_class." align=\"center\">
                          $mactive
                      </td>
                      <td class=".$row_class." align=\"center\" nowrap>
                          <a href=\"".$admin_file.".php?op=editmsg&amp;mid=$mid\">
                              <img src=\"images/edit.gif\" alt=\""._EDIT."\" title=\""._EDIT."\" border=\"0\" width=\"17\" height=\"17\">
                          </a>
                          <a href=\"".$admin_file.".php?op=deletemsg&amp;mid=$mid\">
                              <img src=\"images/delete.gif\" alt=\""._DELETE."\" title=\""._DELETE."\" border=\"0\" width=\"17\" height=\"17\">
                          </a>
                      </td>
                  </tr>
                 ";

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
                          " . _ADDMSG . "
                          &nbsp;
                      </span>
                  </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <form action=\"".$admin_file.".php\" method=\"post\" name=\"message\">
                      <tr>
                          <td>
                              " . _MESSAGETITLE . ":
                          </td>
                          <td colspan=\"3\">
                              <input type=\"text\" name=\"add_title\" value=\"\" size=\"40\" maxlength=\"60\">
                          </td>
                      </tr>
                      <tr>
                          <td nowrap>
                              " . _MESSAGECONTENT . ":
                          </td>
                          <td width=\"100%\">
             ";
        Make_TextArea('add_content', '', 'message');
        if ($multilingual == 1) {
            echo "
                  <tr>
                      <td>
                          " . _LANGUAGE . ":
                      </td>
                      <td colspan=\"3\">
                          <select name=\"add_mlanguage\">
                 ";
            $handle=opendir('language');
            while ($file = readdir($handle)) {
                if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
                    $langFound = $matches[1];
                    $languageslist .= $langFound.' ';
                }
            }
            closedir($handle);
            $languageslist = explode(" ", $languageslist);
            sort($languageslist);
            for ($i=0; $i < count($languageslist); $i++) {
                if($languageslist[$i]!="") {
                    echo "<option value=\"$languageslist[$i]\" ";
                    if($languageslist[$i]==$language) echo "selected";
                    echo ">
                              ".ucfirst($languageslist[$i])."
                          </option>
                         ";
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
                  <input type=\"hidden\" name=\"add_mlanguage\" value=\"\">
                 ";
        }
        $now = time();
        echo "
              <tr>
                  <td>
                      " . _EXPIRATION . ":
                  </td>
                  <td colspan=\"3\"> 
                      <select name=\"add_expire\">
                          <option value=\"86400\" >
                              1 " . _DAY . "
                          </option>
                          <option value=\"172800\" >
                              2 " . _DAYS . "
                          </option>
                          <option value=\"432000\" >
                              5 " . _DAYS . "
                          </option>
                          <option value=\"1296000\" >
                              15 " . _DAYS . "
                          </option>
                          <option value=\"2592000\" >
                              30 " . _DAYS . "
                          </option>
                          <option value=\"0\" >
                              " . _UNLIMITED . "
                          </option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ACTIVE . "?
                  </td>
                  <td colspan=\"3\">
                      ".yesno_option("add_active", $value)."
                  </td>
              </tr>
              <tr>
                  <td nowrap>
                      "._VIEWPRIV."
                  </td>
                  <td>
             ";
        switch ($value) {
            case '0':
            case '1':
                $o1 = 'SELECTED';  //All
            break;
            case '2':
                $o2 = 'SELECTED'; //Anon
            break;
            case '3':
                $o3 = 'SELECTED'; //Users
            break;
            case '4':
                $o4 = 'SELECTED';  //Admin
            break;
            default:
                $o6 = 'SELECTED';  //Groups
                $ingroups = explode('-', $value);
            break;
        }
        echo "
                      <select name=\"view\">
                          <option value=\"1\" $o1>
                              " . _MVALL . "
                          </option>
                          <option value=\"2\" $o2>
                              " . _MVANON . "
                          </option>
                          <option value=\"3\" $o3>
                              " . _MVUSERS . "
                          </option>
                          <option value=\"4\" $o4>
                              " . _MVADMIN . "
                          </option>
                          <option value=\"6\" $o6>
                              "._MVGROUPS."
                          </option>
                      </select>
                      <br />
                  </td>
              </tr>
              <tr>
                  <td nowrap valign='middle'>
                      <div>
                          "._WHATGROUPS.":
                      </div>
                  </td>
                  <td colspan=\"3\">
                      <select name='add_groups[]' multiple size='5'>
             ";
        $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups where group_description <> 'Personal User'");
        while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
            if(@in_array($gid,$ingroups) AND $o6 == 'SELECTED') { $sel = "selected"; } else { $sel = ""; }
            echo "
                  <OPTION VALUE='$gid'$sel>
                      $gname
                  </option>
                 ";
        }
        echo "
                              </select>
                              <br />
                              <span class='tiny'>
                                  ("._WHATGRDESC.")
                              </span>
                          </td>
                      </tr>
                  </table>
              </fieldset>
              <br />
                  <div align=\"center\">
                      <input type=\"hidden\" name=\"op\" value=\"addmsg\">
                      <input type=\"hidden\" name=\"add_mdate\" value=\"$now\">
                      <input type=\"submit\" value=\"" . _ADDMSG . "\">
                  </div>
                  </form>
              </td>
             ";
        CloseTable();
        include(NUKE_BASE_DIR.'footer.php');
    }

    function editmsg($mid) {
        global $admin, $prefix, $db, $multilingual, $admin_file;
        include(NUKE_BASE_DIR.'header.php');
        $mid = intval($mid);
        GraphicAdmin();
        OpenTable();
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php?op=messages\">
                          "._MESSAGES."
                      </a>
                  </font>
              </div>
             ";
        CloseTable();
        echo "<br />";
        $row = $db->sql_fetchrow($db->sql_query("SELECT * from " . $prefix . "_message WHERE mid='$mid'"));
        $groups = $row['groups'];
        $title = $row['title'];
        $content = $row['content'];
        $mdate = $row['date'];
        $expire = intval($row['expire']);
        $active = intval($row['active']);
        $view = intval($row['view']);
        $mlanguage = $row['mlanguage'];
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>
                          " . _EDITMSG . "
                          &nbsp;
                      </span>
                  </legend>";
        if ($active == 1) {
            $asel1 = "checked";
            $asel2 = "";
        } elseif ($active == 0) {
            $asel1 = "";
            $asel2 = "checked";
        }
        $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = "";
        if ($view == 1) {
            $sel1 = 'selected';
        } elseif ($view == 2) {
            $sel2 = 'selected';
        } elseif ($view == 3) {
            $sel3 = 'selected';
        } elseif ($view == 4) {
            $sel4 = 'selected';
        } elseif ($view == 5) {
            $sel5 = 'selected';
        } elseif ($view > 5) {
            $sel6 = 'selected';
        }
        $esel1 = $esel2 = $esel3 = $esel4 = $esel5 = $esel6 = "";
        if ($expire == 86400) {
            $esel1 = 'selected';
        } elseif ($expire == 172800) {
            $esel2 = 'selected';
        } elseif ($expire == 432000) {
            $esel3 = 'selected';
        } elseif ($expire == 1296000) {
            $esel4 = 'selected';
        } elseif ($expire == 2592000) {
            $esel5 = 'selected';
        } elseif ($expire == 0) {
            $esel6 = 'selected';
        }
        echo "
              <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <form action=\"".$admin_file.".php\" method=\"post\" name=\"message\">
                  <tr>
                      <td>
                          " . _MESSAGETITLE . ":
                      </td>
                      <td colspan=\"3\">
                          <input type=\"text\" name=\"title\" value=\"$title\" size=\"40\" maxlength=\"60\">
                      </td>
                  </tr>
                  <tr>
                      <td nowrap>
                          " . _MESSAGECONTENT . ":
                      </td>
                      <td width=\"100%\">
             ";
        Make_TextArea('content', $content, 'message');
        if ($multilingual == 1) {
            echo "
                  <tr>
                      <td>
                          " . _LANGUAGE . ": 
                      </td>
                      <td colspan=\"3\">
                          <select name=\"mlanguage\">
                 ";
            $handle=opendir('language');
            while ($file = readdir($handle)) {
                if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
                    $langFound = $matches[1];
                    $languageslist .= "$langFound ";
                }
            }
            closedir($handle);
            $languageslist = explode(" ", $languageslist);
            sort($languageslist);
            for ($i=0; $i < count($languageslist); $i++) {
                if(!empty($languageslist[$i])) {
                echo "<option value=\"$languageslist[$i]\" ";
                if($languageslist[$i]==$mlanguage) echo "selected";
                echo ">
                          ".ucfirst($languageslist[$i])."
                      </option>
                     ";
                }
            }
            if (empty($mlanguage)) {
                $sellang = 'selected';
            } else {
                    $sellang = '';
            }
            echo "
                              <option value=\"\" $sellang>
                                  " . _ALL . "
                              </option>
                          </select>
                      </td>
                  </tr>
                 ";
        } else {
        echo "
              <input type=\"hidden\" name=\"mlanguage\" value=\"\">
             ";
        }
        echo "
              <tr>
                  <td>
                      " . _EXPIRATION . ":
                  </td>
                  <td colspan=\"3\"> 
                      <select name=\"expire\">
                          <option name=\"expire\" value=\"86400\" $esel1>
                              1 " . _DAY . "
                          </option>
                          <option name=\"expire\" value=\"172800\" $esel2>
                              2 " . _DAYS . "
                          </option>
                          <option name=\"expire\" value=\"432000\" $esel3>
                              5 " . _DAYS . "
                          </option>
                          <option name=\"expire\" value=\"1296000\" $esel4>
                              15 " . _DAYS . "
                          </option>
                          <option name=\"expire\" value=\"2592000\" $esel5>
                              30 " . _DAYS . "
                          </option>
                          <option name=\"expire\" value=\"0\" $esel6>
                              " . _UNLIMITED . "
                          </option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ACTIVE . "?
                  </td>
                  <td colspan=\"3\"> 
                      <input type=\"radio\" name=\"active\" value=\"1\" $asel1>
                          " . _YES . " 
                      <input type=\"radio\" name=\"active\" value=\"0\" $asel2>
                          " . _NO . "
                  </td>
              </tr>
             ";
        if ($active == 1) {
            echo "
                  <tr>
                      <td nowrap>
                          " . _CHANGEDATE . "
                      </td>
                      <td colspan=\"3\">
                          <input type=\"radio\" name=\"chng_date\" value=\"1\">
                              " . _YES . " 
                          <input type=\"radio\" name=\"chng_date\" value=\"0\" checked>
                              " . _NO . "
                      </td>
                  </tr>
                 ";
        } elseif ($active == 0) {
            echo "
                  <input type=\"hidden\" name=\"chng_date\" value=\"1\">
                 ";
        }
        echo "
              <tr>
                  <td nowrap>
                      " . _VIEWPRIV . "
                  </td>
                  <td>
                      <select name=\"view\">
                          <option name=\"view\" value=\"1\" $sel1>
                              " . _MVALL . "
                          </option>
                          <option name=\"view\" value=\"2\" $sel2>
                              " . _MVANON . "
                          </option>
                          <option name=\"view\" value=\"3\" $sel3>
                              " . _MVUSERS . "
                          </option>
                          <option name=\"view\" value=\"4\" $sel4>
                              " . _MVADMIN . "
                          </option>
                          <option name=\"view\" value=\"6\" $sel6>
                              "._MVGROUPS."
                          </option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td nowrap valign='middle'>
                      <div>
                          "._WHATGROUPS.":
                      </div>
                  </td>
                  <td colspan=\"3\"> 
                      <select name='groups[]' multiple size='5'>
             ";
        $ingroups = explode("-",$groups);
        $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups where group_description <> 'Personal User'");
        while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
            if(in_array($gid,$ingroups) AND $view > 5) { $sel = " selected"; } else { $sel = ""; }
            echo "
                  <OPTION VALUE='$gid'$sel>
                      $gname
                  </option>
                 ";
        }
        echo "
                              </select>
                              <br />
                              <span class='tiny'>
                                  ("._WHATGRDESC.")
                              </span>
                          </td>
                      </tr>
                  </table>
              </fieldset>
              <br />
                  <div align=\"center\">
                      <input type=\"hidden\" name=\"mdate\" value=\"$mdate\">
                      <input type=\"hidden\" name=\"mid\" value=\"$mid\">
                      <input type=\"hidden\" name=\"op\" value=\"savemsg\">
                      <input type=\"submit\" value=\"" . _SAVECHANGES . "\">
                  </div>
                  </form>
              </td>
             ";
        CloseTable();
        include(NUKE_BASE_DIR.'footer.php');
    }

    function savemsg($mid, $title, $content, $mdate, $expire, $active, $view, $groups, $chng_date, $mlanguage) {
        global $prefix, $db, $admin_file;
        if($view == 6) { $ingroups = implode("-",$groups); }
        if($view < 6) { $ingroups = ""; }
        $mid = intval($mid);
        $title = Fix_Quotes(stripslashes($title));
        $content = Fix_Quotes(stripslashes($content));
        if ($chng_date == 1) {
            $newdate = time();
        } elseif ($chng_date == 0) {
            $newdate = $mdate;
        }
        $result = $db->sql_query("update " . $prefix . "_message set title='$title', content='$content', date='$newdate', expire='$expire', active='$active', view='$view', groups='$ingroups', mlanguage='$mlanguage' WHERE mid='$mid'");
        Header("Location: ".$admin_file.".php?op=messages");
    }

    function addmsg($add_title, $add_content, $add_mdate, $add_expire, $add_active, $add_view, $add_groups, $add_mlanguage) {
        global $prefix, $db, $admin_file;
        if($add_view == 6) { $ingroups = implode("-",$add_groups); }
        if($add_view < 6) { $ingroups = ""; }
        $title = Fix_Quotes(stripslashes($add_title));
        $content = Fix_Quotes(stripslashes($add_content));
        $result = $db->sql_query("insert into " . $prefix . "_message values (NULL, '$add_title', '$add_content', '$add_mdate', '$add_expire', '$add_active', '$add_view', '$ingroups', '$add_mlanguage')");
        if (!$result) {
            exit();
        }
        Header("Location: ".$admin_file.".php?op=messages");
    }

    function deletemsg($mid, $ok=0) {
        global $prefix, $db, $admin_file;
        if($ok) {
        $result = $db->sql_query("delete from " . $prefix . "_message where mid='$mid'");
            if (!$result) {
            return;
            }
            Header("Location: ".$admin_file.".php?op=messages");
        } else {
            confirm_msg(_REMOVEMSG, "".$admin_file.".php?op=deletemsg&amp;mid=$mid&amp;ok=1", "".$admin_file.".php?op=messages");
       }
    }

        if (!isset($title)) { $title = ''; }
        if (!isset($content)) { $content = ''; }
        if (!isset($mdate)) { $mdate = ''; }
        if (!isset($expire)) { $expire = ''; }
        if (!isset($active)) { $active = ''; }
        if (!isset($view)) { $view = ''; }
        if (!isset($chng_date)) { $chng_date = ''; }
        if (!isset($mlanguage)) { $mlanguage = ''; }
        if (!isset($ok)) { $ok = ''; }

    switch ($op){

        case "messages":
        messages();
        break;

        case "editmsg":
        editmsg($mid, $title, $content, $mdate, $expire, $active, $view, $chng_date, $mlanguage);
        break;

        case "addmsg":
        addmsg($add_title, $add_content, $add_mdate, $add_expire, $add_active, $add_view, $add_groups, $add_mlanguage);
        break;

        case "deletemsg":
        deletemsg($mid, $ok);
        break;

        case "savemsg":
        savemsg($mid, $title, $content, $mdate, $expire, $active, $view, $groups, $chng_date, $mlanguage);
        break;

    }

} else {
    echo "Access Denied";
}

?>