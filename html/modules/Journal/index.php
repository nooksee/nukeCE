<?php

/**************************************************************************/
/* PHP-EVOLVED: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.php-evolved.com                                             */
/*                                                                        */
/* All PHP-EVOLVED code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$pagetitle = _USERSJOURNAL;
include_once(NUKE_BASE_DIR.'header.php');
include(NUKE_MODULES_DIR.$module_name.'/functions.php');

if (is_user()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
    $username = check_html($username, "nohtml");
}
$user = check_html($user, "nohtml");
$sitename = check_html($sitename, "nohtml");
startjournal($sitename, $user);

/*********************************************************/
/* Blogs Functions                                       */
/*********************************************************/

	function last20($bgcolor1, $bgcolor2, $bgcolor3, $username) {
    global $prefix, $user_prefix, $db, $module_name;
    OpenTable();
    echo ("<div align=center class=title>"._20ACTIVE."</div><br />");
    echo ("<table align=center border=1 cellpadding=0 cellspacing=0>");
    echo ("<tr>");
    echo ("<td bgcolor=$bgcolor1 width=150>&nbsp;<strong>"._MEMBER."</strong> "._CLICKTOVIEW."</td>");
    echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._VIEWJOURNAL."</strong></td>");
    echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._MEMBERPROFILE."</strong></td>");
    if (empty($username)) {
        echo "<td bgcolor=$bgcolor1 width=70 align=center><strong>"._CREATEACCOUNT2."</strong></td>";
    } else {
        if (is_active("Private_Messages")) {
            echo "<td bgcolor=$bgcolor1 width=70 align=center><strong>"._PRIVMSGJ."</strong></td>";
        }
    }
    echo "</tr>";
    $sql = "SELECT j.id, j.joid, j.nop, j.ldp, j.ltp, j.micro, u.user_id, u.username FROM ".$prefix."_journal_stats j, ".$user_prefix."_users u where u.username=j.joid ORDER BY 'ldp' DESC";
    $result = $db->sql_query($sql);
    $dcount = 1;
    while ($row = $db->sql_fetchrow($result)) {
        $row['id'] = intval($row['id']);
        $row['joid'] = check_html($row['joid'], "nohtml");
        $row['nop'] = check_html($row['nop'], "nohtml");
        $row['ldp'] = check_html($row['ldp'], "nohtml");
        $row['ltp'] = check_html($row['ltp'], "nohtml");
        $row['micro'] = check_html($row['micro'], "nohtml");
        $row['user_id'] = check_html($row['user_id'], "nohtml");
        if ($dcount >= 21) {
            echo "</table>";
            CloseTable();
            journalfoot();
            exit;
        } else {
            $dcount = $dcount + 1;
            print ("<tr>");
            printf ("<td bgcolor=$bgcolor2>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&amp;file=search&amp;bywhat=aid&amp;exact=1&amp;forwhat=%s\">" . UsernameColor("%s") . "</a></td>", $row['joid'], $row['joid']);
            printf ("<td bgcolor=$bgcolor2 align=center><div class=title><a href=\"modules.php?name=$module_name&amp;file=search&amp;bywhat=aid&amp;exact=1&amp;forwhat=%s\"><img src=\"modules/$module_name/images/binocs.gif\" border=0 alt=\""._VIEWJOURNAL2."\" title=\""._VIEWJOURNAL2."\" /></a></td>", $row['joid'], $row['joid']);
            printf ("<td bgcolor=$bgcolor2 align=center><a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=%s\"><img src=\"modules/$module_name/images/nuke.gif\" alt=\""._USERPROFILE2."\" title=\""._USERPROFILE2."\" border=\"0\" /></a></td>", $row['joid'], $row['joid'], $row['joid']);
            if (empty($username)) {
                print ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Your_Account&amp;op=new_user\"><img src=\"modules/$module_name/images/folder.gif\" border=\"0\" alt=\""._CREATEACCOUNT."\" title=\""._CREATEACCOUNT."\" /></a></td>");
            } else {
                if (is_active("Private_Messages")) {
                    printf ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Private_Messages&amp;mode=post&amp;u=".$row['user_id']."\"><img src='modules/$module_name/images/chat.gif' border='0' alt='"._PRIVMSGJ2."' /></a></td>", $row['joid'], $row['joid']);
                }
            }
            echo "</tr>";
        }
    }
    $db->sql_freeresult($result);
    echo "</table>";
    CloseTable();
	}

	function all($bgcolor1, $bgcolor2, $bgcolor3, $sitename, $username) {
    global $prefix, $user_prefix, $db, $module_name;
    OpenTable();
    echo ("<div align=\"center\" class=title>"._ALPHABETICAL."</div><br />");
    echo ("<table align=center border=1 cellpadding=0 cellspacing=0>");
    echo ("<tr>");
    echo ("<td bgcolor=$bgcolor1 width=150>&nbsp;<strong>"._MEMBER."</strong> "._CLICKTOVIEW."</td>");
    echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._VIEWJOURNAL."</strong></td>");
    echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._MEMBERPROFILE."</strong></td>");
    if (empty($username)) {
        echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._CREATEACCOUNT2."</strong></td>");
    } else {
        echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._PRIVMSGJ."</strong></td>");
    }
    echo ("</tr>");
    $sql = "SELECT j.id, j.joid, j.nop, j.ldp, j.ltp, j.micro, u.user_id FROM ".$prefix."_journal_stats j, ".$user_prefix."_users u where u.username=j.joid ORDER BY 'joid'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        $row['id'] = intval($row['id']);
        $row['joid'] = check_html($row['joid'], "nohtml");
        $row['nop'] = check_html($row['nop'], "nohtml");
        $row['ldp'] = check_html($row['ldp'], "nohtml");
        $row['ltp'] = check_html($row['ltp'], "nohtml");
        $row['micro'] = check_html($row['micro'], "nohtml");
        $row['user_id'] = check_html($row['user_id'], "nohtml");
        print ("<tr>");
        printf ("<td bgcolor=$bgcolor2>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&amp;file=search&amp;bywhat=aid&amp;forwhat=%s\">" . UsernameColor("%s") . "</a></td>", $row['joid'], $row['joid']);
        printf ("<td bgcolor=$bgcolor2 align=center><div class=title><a href=\"modules.php?name=$module_name&amp;file=search&amp;bywhat=aid&amp;forwhat=%s\"><img src=\"modules/$module_name/images/binocs.gif\" border=\"0\" alt=\""._VIEWJOURNAL2."\" title=\""._VIEWJOURNAL2."\" /></a></td>", $row['joid'], $row['joid']);
        printf ("<td bgcolor=$bgcolor2 align=center><a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=%s\"><img src=\"modules/$module_name/images/nuke.gif\" alt=\""._USERPROFILE2."\" title=\""._USERPROFILE2."\" border=\"0\" /></a></td>", $row['joid'], $row['joid'], $row['joid']);
        if (empty($username)) {
            print ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Your_Account&amp;op=new_user\"><img src=\"modules/$module_name/images/folder.gif\" border=\"0\" alt=\""._CREATEACCOUNT."\" title=\""._CREATEACCOUNT."\" /></a></td>");
        } elseif (!empty($username) AND is_active("Private_Messages")) {
            print ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Private_Messages&amp;mode=post&amp;u=".$row['user_id']."\"><img src='modules/$module_name/images/chat.gif' border='0' alt='"._PRIVMSGJ2."' /></a></td>");
        }
        echo "</tr>";
    }
    $db->sql_freeresult($result);
    echo "</table>";
    CloseTable();
	}

echo "<br />";
OpenTable();
echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&amp;op=last\">"._20AUTHORS."</a> | <a href=\"modules.php?name=$module_name&amp;op=all\">"._LISTALLJOURNALS."</a> | <a href=\"modules.php?name=$module_name&amp;file=search&amp;disp=showsearch\">"._SEARCHMEMBER."</a> ]</div>");
CloseTable();
echo "<br />";
if (!isset($op)) $op = '';
switch($op) {
    case "last":
        last20($bgcolor1, $bgcolor2, $bgcolor3, $username);
    break;
    case "all":
        all($bgcolor1, $bgcolor2, $bgcolor3, $sitename, $username);
    break;
    default:
        last20($bgcolor1, $bgcolor2, $bgcolor3, $username);
    break;
}
journalfoot();

?>