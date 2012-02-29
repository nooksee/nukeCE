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
   die ('Access Denied');
}

global $prefix, $db, $admdata;
$module_name = basename(dirname(dirname(__FILE__)));
$aid = substr($aid, 0,25);
$row = $db->sql_fetchrow($db->sql_query("SELECT title, admins FROM ".$prefix."_modules WHERE title='$module_name'"));
$row2 = $db->sql_fetchrow($db->sql_query("SELECT name, radminsuper FROM ".$prefix."_authors WHERE aid='$aid'"));
$admins = explode(",", $row['admins']);
$auth_user = 0;
for ($i=0; $i < count($admins); $i++) {
    if ($admdata['name'] == $admins[$i] && !empty($row['admins'])) {
        $auth_user = 1;
    }
}

if ($admdata['radminsuper'] == 1 || $auth_user == 1) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function alpha($eid) {
    global $module_name, $prefix, $db, $admin_file;
    $alphabet = array ("A","B","C","D","E","F","G","H","I","J","K","L","M",
                       "N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $num = count($alphabet) - 1;
    echo "<center>[ ";
    $counter = 0;
    while (list(, $ltr) = each($alphabet)) {
        $result = $db->sql_query("SELECT * FROM ".$prefix."_encyclopedia_text WHERE eid='$eid' AND UPPER(title) LIKE '$ltr%'");
        if ($db->sql_numrows($result) > 0) {
            echo "<a href=\"".$admin_file.".php?op=encyclopedia_terms&amp;eid=$eid&amp;ltr=$ltr\">$ltr</a>";
        } else {
            echo "$ltr";
        }
        if ( $counter == round($num/2) ) {
            echo " ]\n<br />\n[ ";
        } elseif ( $counter != $num ) {
            echo "&nbsp;|&nbsp;\n";
        }
        $counter++;
    }
    echo " ]</center><br /><br />\n\n\n";
}

function encyclopedia() {
    global $prefix, $db, $language, $multilingual, $bgcolor2, $admin_file;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    title(""._ENCYCLOPEDIAMANAGER."");
    OpenTable();
    echo "<table border=\"0\" width=\"100%\"><tr>"
        ."<td bgcolor=\"$bgcolor2\"><strong>"._TITLE."</strong></td><td align=\"center\" bgcolor=\"$bgcolor2\"><strong>"._TERMS."</strong></td><td align=\"center\" bgcolor=\"$bgcolor2\"><strong>"._CURRENTSTATUS."</strong></td><td align=\"center\" bgcolor=\"$bgcolor2\"><strong>"._FUNCTIONS."</strong></td></tr>";
    $result = $db->sql_query("SELECT * FROM ".$prefix."_encyclopedia ORDER BY eid");
    while($ency = $db->sql_fetchrow($result)) {
        $num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_encyclopedia_text WHERE eid='".intval($ency['eid'])."'"));
        if ($ency['active'] == 1) {
            $status = _ACTIVE;
            $status_chng = _DEACTIVATE;
            $active = 1;
        } else {
            $status = "<i>"._INACTIVE."</i>";
            $status_chng = _ACTIVATE;
            $active = 0;
        }
        echo "<tr><td><a href=\"modules.php?name=$module_name&amp;op=list_content&amp;eid=".intval($ency['eid'])."\">".$ency['title']."</a></td><td align=\"center\">$num</td><td align=\"center\">$status</td><td align=\"center\">[ <a href=\"".$admin_file.".php?op=encyclopedia_edit&amp;eid=".intval($ency['eid'])."\">"._EDIT."</a> | <a href=\"".$admin_file.".php?op=encyclopedia_change_status&amp;eid=".intval($ency['eid'])."&amp;active=$active\">$status_chng</a> | <a href=\"".$admin_file.".php?op=encyclopedia_delete&amp;eid=".intval($ency['eid'])."\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table>";
    CloseTable();
    echo "<br />";
    OpenTable();
    echo "<center><strong>"._ADDNEWENCYCLOPEDIA."</strong></center><br /><br />"
        ."<form action=\"".$admin_file.".php\" method=\"post\">"
        ."<strong>"._TITLE.":</strong><br />"
        ."<input type=\"text\" name=\"title\" size=\"50\"><br /><br />"
        ."<strong>"._DESCRIPTION.":</strong><br />"
        ."<textarea name=\"description\" cols=\"60\" rows=\"10\"></textarea><br /><br />";
    if ($multilingual == 1) {
        echo "<br /><strong>"._LANGUAGE.": </strong>"
            ."<select name=\"elanguage\">";
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
        for ($i=0; $i < sizeof($languageslist); $i++) {
            if(!empty($languageslist[$i])) {
                echo "<option value=\"$languageslist[$i]\" ";
                if($languageslist[$i]==$language) echo "selected";
                echo ">".ucfirst($languageslist[$i])."</option>\n";
            }
        }
        echo "</select><br /><br />";
    } else {
        echo "<input type=\"hidden\" name=\"elanguage\" value=\"$language\">";
    }
    echo "<strong>"._ACTIVATEPAGE."</strong><br />"
        ."<input type=\"radio\" name=\"active\" value=\"1\" checked>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\">&nbsp;"._NO."<br /><br />"
        ."<input type=\"hidden\" name=\"op\" value=\"encyclopedia_save\">"
        ."<input type=\"submit\" value=\""._SEND."\">"
        ."</form>";
    CloseTable();
    $result = $db->sql_query("SELECT eid, title FROM ".$prefix."_encyclopedia");
    if ($db->sql_numrows($result) > 0) {
        echo "<br />";
        OpenTable();
        echo "<center><strong>"._ADDNEWENCYTERM."</strong></center><br /><br />"
            ."<form action=\"".$admin_file.".php\" method=\"post\">"
            ."<strong>"._TITLE.":</strong><br />"
            ."<input type=\"text\" name=\"title\" size=\"50\"><br /><br />"
            ."<strong>"._TERMTEXT.":</strong><br />"._PAGEBREAK."<br />"
            ."<textarea name=\"text\" cols=\"60\" rows=\"20\"></textarea><br /><br />";
        if ($multilingual == 1) {
            $languageslist = "";
            echo "<br /><strong>"._LANGUAGE.": </strong>"
                ."<select name=\"elanguage\">";
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
            for ($i=0; $i < sizeof($languageslist); $i++) {
                if(!empty($languageslist[$i])) {
                        echo "<option value=\"$languageslist[$i]\" ";
                        if($languageslist[$i]==$language) echo "selected";
                    echo ">".ucfirst($languageslist[$i])."</option>\n";
                }
            }
            echo "</select><br /><br />";
        } else {
            echo "<input type=\"hidden\" name=\"elanguage\" value=\"$language\">";
        }
        echo "<strong>"._ENCYCLOPEDIA.":</strong><br /><select name=\"eid\">";
        while(list($eid, $title) = $db->sql_fetchrow($result)) {
        $eid = intval($eid);
            echo "<option value=\"$eid\" name=\"eid\">$title</option>";
        }
        echo "</select><br /><br />"
            ."<input type=\"hidden\" name=\"op\" value=\"encyclopedia_text_save\">"
            ."<input type=\"submit\" value=\""._ADD."\">"
            ."</form>";
        CloseTable();

    }
    $result = $db->sql_query("SELECT eid, title FROM ".$prefix."_encyclopedia");
    $result2 = $db->sql_query("SELECT eid, title FROM ".$prefix."_encyclopedia");
    if ($db->sql_numrows($result) > 1) {
        echo "<br />";
        OpenTable();
        echo "<center><strong>"._MOVETERMS."</strong><br /><br />"
            ."<form action=\"".$admin_file.".php\" method=\"post\">"
            .""._MOVEALLTERMSFROM.": <select name=\"eid\">";
        while(list($eid, $title) = $db->sql_fetchrow($result)) {
        $eid = intval($eid);
            echo "<option name=\"eid\" value=\"$eid\">$title";
        }
        echo "</select> "._TO.": <select name=\"new_eid\">";
        while(list($eid, $title) = $db->sql_fetchrow($result2)) {
        $eid = intval($eid);
            echo "<option name=\"new_eid\" value=\"$eid\">$title";
        }
        echo "</select>&nbsp;&nbsp;"
            ."<input type=\"hidden\" name=\"op\" value=\"move_terms\">"
            ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
            ."</form></center>";
        CloseTable();
    }
    include_once(NUKE_BASE_DIR.'footer.php');
}

function encyclopedia_edit($eid) {
    global $prefix, $db, $language, $multilingual, $bgcolor2, $admin_file;
    include_once(NUKE_BASE_DIR.'header.php');
    OpenTable();
	echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=encyclopedia\">" . _ENCYCLOPEDIA_ADMIN_HEADER . "</a></div>\n";
    echo "<br /><br />";
	echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _ENCYCLOPEDIA_RETURNMAIN . "</a> ]</div>\n";
	CloseTable();
	echo "<br />";
    title(""._ENCYCLOPEDIAMANAGER."");
    $result = $db->sql_query("SELECT * FROM ".$prefix."_encyclopedia WHERE eid='$eid'");
    $ency = $db->sql_fetchrow($result);
        if ($ency[active] == 1) {
            $sel1 = "checked";
            $sel2 = "";
        } else {
            $sel1 = "";
            $sel2 = "checked";
        }
    OpenTable();
    echo "<center><strong>"._EDITENCYCLOPEDIA."</strong></center><br /><br />"
        ."<form action=\"".$admin_file.".php\" method=\"post\">"
        ."<strong>"._TITLE.":</strong><br />"
        ."<input type=\"text\" name=\"title\" size=\"50\" value=\"$ency[title]\"><br /><br />"
        ."<strong>"._DESCRIPTION.":</strong><br />"
        ."<textarea name=\"description\" cols=\"60\" rows=\"10\">$ency[description]</textarea><br /><br />";
    if ($multilingual == 1) {
        echo "<br /><strong>"._LANGUAGE.": </strong>"
            ."<select name=\"elanguage\">";
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
        for ($i=0; $i < sizeof($languageslist); $i++) {
                if(!empty($languageslist[$i])) {
                echo "<option value=\"$languageslist[$i]\" ";
                if($languageslist[$i]==$language) echo "selected";
                echo ">".ucfirst($languageslist[$i])."</option>\n";
            }
        }
        echo "</select><br /><br />";
    } else {
        echo "<input type=\"hidden\" name=\"elanguage\" value=\"$ency[elanguage]\">";
    }
    echo "<strong>"._ACTIVATEPAGE."</strong><br />"
        ."<input type=\"radio\" name=\"active\" value=\"1\" $sel1>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\" $sel2>&nbsp;"._NO."<br /><br />"
        ."<input type=\"hidden\" name=\"eid\" value=\"$ency[eid]\">"
        ."<input type=\"hidden\" name=\"op\" value=\"encyclopedia_save_edit\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
        ."</form>";
    CloseTable();
    echo "<br />";
    OpenTable();
    echo "<center><strong>"._ENCYTERMSEDIT."</strong></center><br /><br />";
    alpha($eid);
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
}

function encyclopedia_terms($eid, $ltr) {
    global $prefix, $db, $admin_file;
    include_once(NUKE_BASE_DIR.'header.php');
    $eid = intval($eid);
    OpenTable();
	echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=encyclopedia\">" . _ENCYCLOPEDIA_ADMIN_HEADER . "</a></div>\n";
    echo "<br /><br />";
	echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _ENCYCLOPEDIA_RETURNMAIN . "</a> ]</div>\n";
	CloseTable();
	echo "<br />";
    title(""._ENCYCLOPEDIAMANAGER."");
    $result = $db->sql_query("SELECT title FROM ".$prefix."_encyclopedia WHERE eid='$eid' AND UPPER(title) LIKE '%$ltr%'");
    list($title) = $db->sql_fetchrow($result);
    title("$title");
    OpenTable();
        echo "<center>"._SELECTONETERM."</center><br /><br />"
            ."<table border=\"0\" align=\"center\">";
        $result = $db->sql_query("SELECT tid, title FROM ".$prefix."_encyclopedia_text WHERE UPPER(title) LIKE '$ltr%' AND eid='$eid'");
        if ($db->sql_numrows($result) == 0) {
            echo "<center><i>"._NOCONTENTFORLETTER." $ltr.</i></center>";
        }
        while(list($tid, $title) = $db->sql_fetchrow($result)) {
        $tid = intval($tid);
            echo "<tr><td><a href=\"".$admin_file.".php?op=encyclopedia_text_edit&amp;tid=$tid\">$title</a></td></tr>";
        }
        echo "</table><br /><br />";
        alpha($eid);
        echo "<center>"._GOBACK."</center>";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
}

function encyclopedia_text_edit($tid) {
    global $prefix, $db, $language, $multilingual, $bgcolor2, $admin_file;
    include_once(NUKE_BASE_DIR.'header.php');
    OpenTable();
	echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=encyclopedia\">" . _ENCYCLOPEDIA_ADMIN_HEADER . "</a></div>\n";
    echo "<br /><br />";
	echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _ENCYCLOPEDIA_RETURNMAIN . "</a> ]</div>\n";
	CloseTable();
	echo "<br />";
    title(""._ENCYCLOPEDIAMANAGER."");
    $tid = intval($tid);
    $result = $db->sql_query("SELECT * FROM ".$prefix."_encyclopedia_text WHERE tid='$tid'");
    $ency = $db->sql_fetchrow($result);
    OpenTable();
    echo "<center><strong>"._ENCYTERMSEDIT."</strong></center><br /><br />"
        ."<form action=\"".$admin_file.".php\" method=\"post\">"
        ."<strong>"._TITLE.":</strong><br />"
        ."<input type=\"text\" name=\"title\" size=\"50\" value=\"$ency[title]\"><br /><br />"
        ."<strong>"._TERMTEXT.":</strong><br />"._PAGEBREAK."<br />"
        ."<textarea name=\"text\" cols=\"60\" rows=\"20\">$ency[text]</textarea><br /><br />"
        ."<strong>"._CHANGETOENCY.":</strong><br />"
        ."<select name=\"eid\">";
    $result = $db->sql_query("SELECT eid, title FROM ".$prefix."_encyclopedia");
    while(list($eid, $title) = $db->sql_fetchrow($result)) {
        $eid = intval($eid);
        if ($eid == $ency[eid]) {
            $sel = "selected";
        } else {
            $sel = "";
        }
        echo "<option value=\"$eid\" name=\"eid\" $sel>$title</option>";
    }
    echo "</select><br /><br />"
        ."<input type=\"hidden\" name=\"op\" value=\"encyclopedia_text_save_edit\">"
        ."<input type=\"hidden\" name=\"tid\" value=\"$tid\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\"> &nbsp;&nbsp; [ <a href=\"".$admin_file.".php?op=encyclopedia_text_delete&amp;tid=$tid&amp;ok=0\">"._DELETE."</a> ]"
        ."</form>";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
}

function encyclopedia_save($title, $description, $elanguage, $active) {
    global $prefix, $db, $admin_file;
    $title = stripslashes(Fix_Quotes($title));
    $description = stripslashes(Fix_Quotes($description));
    $db->sql_query("INSERT INTO ".$prefix."_encyclopedia VALUES (NULL, '$title', '$description', '$elanguage', '$active')");
    header("Location: ".$admin_file.".php?op=encyclopedia");
}

function encyclopedia_text_save($eid, $title, $text) {
    global $prefix, $db, $admin_file;
    $eid = intval($eid);
    $text = stripslashes(Fix_Quotes($text));
    $title = stripslashes(Fix_Quotes($title));
    $db->sql_query("INSERT INTO ".$prefix."_encyclopedia_text VALUES (NULL, '$eid', '$title', '$text', '0')");
    header("Location: ".$admin_file.".php?op=encyclopedia");
}

function encyclopedia_save_edit($eid, $title, $description, $elanguage, $active) {
    global $prefix, $db, $admin_file;
    $eid = intval($eid);
    $title = stripslashes(Fix_Quotes($title));
    $description = stripslashes(Fix_Quotes($description));
    $db->sql_query("UPDATE ".$prefix."_encyclopedia SET title='$title', description='$description', elanguage='$elanguage', active='$active' WHERE eid='$eid'");
    header("Location: ".$admin_file.".php?op=encyclopedia");
}

function encyclopedia_text_save_edit($tid, $eid, $title, $text) {
    global $prefix, $db, $admin_file;
    $tid = intval($tid);
    $eid = intval($eid);
    $text = stripslashes(Fix_Quotes($text));
    $title = stripslashes(Fix_Quotes($title));
    $db->sql_query("UPDATE ".$prefix."_encyclopedia_text SET eid='$eid', title='$title', text='$text' WHERE tid='$tid'");
    header("Location: ".$admin_file.".php?op=encyclopedia");
}

function encyclopedia_change_status($eid, $active) {
    global $prefix, $db, $admin_file;
    $eid = intval($eid);
    if ($active == 1) {
        $new_active = 0;
    } elseif ($active == 0) {
        $new_active = 1;
    }
    $db->sql_query("UPDATE ".$prefix."_encyclopedia SET active='$new_active' WHERE eid='$eid'");
    header("Location: ".$admin_file.".php?op=encyclopedia");
}

function encyclopedia_delete($eid, $ok=0) {
    global $prefix, $db, $admin_file;
    $eid = intval($eid);
    if ($ok==1) {
        $db->sql_query("DELETE FROM ".$prefix."_encyclopedia WHERE eid='$eid'");
        $db->sql_query("DELETE FROM ".$prefix."_encyclopedia_text WHERE eid='$eid'");
        header("Location: ".$admin_file.".php?op=encyclopedia");
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
	    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=encyclopedia\">" . _ENCYCLOPEDIA_ADMIN_HEADER . "</a></div>\n";
        echo "<br /><br />";
	    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _ENCYCLOPEDIA_RETURNMAIN . "</a> ]</div>\n";
	    CloseTable();
	    echo "<br />";
        title(""._ENCYCLOPEDIAMANAGER."");
        $result = $db->sql_query("SELECT title FROM ".$prefix."_encyclopedia WHERE eid='$eid'");
        list($title) = $db->sql_fetchrow($result);
        OpenTable();
        echo "<center><strong>"._DELENCYCLOPEDIA.": $title</strong><br /><br />"
            .""._DELENCYCONTWARNING."<br /><br />"
            ."[ <a href=\"".$admin_file.".php?op=encyclopedia\">"._NO."</a> | <a href=\"".$admin_file.".php?op=encyclopedia_delete&amp;eid=$eid&amp;ok=1\">"._YES."</a> ]</center>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }
}

function encyclopedia_text_delete($tid, $ok=0) {
    global $prefix, $db, $admin_file;
    $tid = intval($tid);
    if ($ok==1) {
        $db->sql_query("DELETE FROM ".$prefix."_encyclopedia_text WHERE tid='$tid'");
        header("Location: ".$admin_file.".php?op=encyclopedia");
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
	    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=encyclopedia\">" . _ENCYCLOPEDIA_ADMIN_HEADER . "</a></div>\n";
        echo "<br /><br />";
	    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _ENCYCLOPEDIA_RETURNMAIN . "</a> ]</div>\n";
	    CloseTable();
	    echo "<br />";
        title(""._ENCYCLOPEDIAMANAGER."");
        $result = $db->sql_query("SELECT title FROM ".$prefix."_encyclopedia_text WHERE tid='$tid'");
        list($title) = $db->sql_fetchrow($result);
        OpenTable();
        echo "<center><strong>"._DELENCYCLOPEDIATEXT.": $title</strong><br /><br />"
            .""._DELENCYTEXTWARNING."<br /><br />"
            ."[ <a href=\"".$admin_file.".php?op=encyclopedia\">"._NO."</a> | <a href=\"".$admin_file.".php?op=encyclopedia_text_delete&amp;tid=$tid&amp;ok=1\">"._YES."</a> ]</center>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }
}

function move_terms($eid, $new_eid) {
    global $prefix, $db, $admin_file;
    $eid = intval($eid);
    $result = $db->sql_query("SELECT tid FROM ".$prefix."_encyclopedia_text WHERE eid='$eid'");
    while(list($tid) = $db->sql_fetchrow($result)) {
        $db->sql_query("UPDATE ".$prefix."_encyclopedia_text SET eid='$new_eid' WHERE tid='".intval($tid)."'");
    }
    header("Location: ".$admin_file.".php?op=encyclopedia");
}

switch ($op) {

    case "encyclopedia":
    encyclopedia();
    break;

    case "move_terms":
    move_terms($eid, $new_eid);
    break;

    case "encyclopedia_terms":
    encyclopedia_terms($eid, $ltr);
    break;

    case "encyclopedia_edit":
    encyclopedia_edit($eid);
    break;

    case "encyclopedia_text_edit":
    encyclopedia_text_edit($tid);
    break;

    case "encyclopedia_delete":
    encyclopedia_delete($eid, $ok);
    break;

    case "encyclopedia_text_delete":
    encyclopedia_text_delete($tid, $ok);
    break;

    case "encyclopedia_save":
    encyclopedia_save($title, $description, $elanguage, $active);
    break;

    case "encyclopedia_text_save":
    encyclopedia_text_save($eid, $title, $text);
    break;

    case "encyclopedia_save_edit":
    encyclopedia_save_edit($eid, $title, $description, $elanguage, $active);
    break;

    case "encyclopedia_text_save_edit":
    encyclopedia_text_save_edit($tid, $eid, $title, $text);
    break;

    case "encyclopedia_change_status":
    encyclopedia_change_status($eid, $active);
    break;

}

} else {
    include(NUKE_BASE_DIR.'header.php');
    OpenTable();
	echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=encyclopedia\">" . _ENCYCLOPEDIA_ADMIN_HEADER . "</a></div>\n";
    echo "<br /><br />";
	echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _ENCYCLOPEDIA_RETURNMAIN . "</a> ]</div>\n";
	CloseTable();
	echo "<br />";
    OpenTable();
    echo "<div align=\"center\"><span class=\"option\">"._ERROR.": You do not have administration permission for module \"$module_name\"</em></b><br /><br />"._GOBACK."</span></div>";
    CloseTable();
    include(NUKE_BASE_DIR.'footer.php');
}

?>