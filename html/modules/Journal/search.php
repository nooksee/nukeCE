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

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}
    $module_name = basename(dirname(__FILE__));
    get_lang($module_name);
    $pagetitle = "- "._USERSJOURNAL."";
    include_once(NUKE_BASE_DIR.'header.php');
    include(NUKE_MODULES_DIR.$module_name.'/functions.php');
    if (is_user()) {
        $cookie = cookiedecode();
        $username = $cookie[1];
    }
    if (!isset($bywhat)):
        $bywhat = "naddaanythang";
    else :
    $bywhat = check_html($bywhat, "nohtml");
    $bywhat = stripslashes($bywhat);
    endif;
    if (!isset($forwhat)):
        $forwhat = "naddaanythang";
    else :
    $forwhat = check_html($forwhat, "nohtml");
    $forwhat = stripslashes($forwhat);
    endif;
    startjournal($sitename, $user);

/*********************************************************/
/* Search Functions                                      */
/*********************************************************/

	function displaySearch($sitename, $username, $bgcolor2, $bgcolor3, $bgcolor1) {
    global $module_name;
    echo "<br />";
    OpenTable();
    echo ("<div align=center class=title>");
    echo ("<strong>"._JOURNALSEARCH."</strong></div><br /><br />");
    echo ("<div align=center>");
    echo ("<form action='modules.php?name=$module_name&amp;file=search' method='post'>");
    echo ("<input type='hidden' name='disp' value='search' />");
    echo ("<input type='text' name='forwhat' size='30' maxlength='150' /> "._IN." <select name='bywhat'>");
    echo ("<option value=\"aid\" SELECTED>"._MEMBER."</option>");
    echo ("<option value=\"title\">"._TITLE."</option>");
    echo ("<option value=\"bodytext\">"._BODYTEXT."</option>");
    echo ("<option value=\"comment\">"._UCOMMENTS."</option>");
    echo ("</select>&nbsp;&nbsp;<input type='submit' name='submit' value='"._SEARCH."' />");
    echo ("</form>");
    echo ("</div>");
    CloseTable();
	}

	function search($username, $bywhat, $forwhat, $sitename, $bgcolor2, $bgcolor3, $user) {
    global $prefix, $user_prefix, $db, $module_name, $exact, $bgcolor1;
    echo "<br />";
    OpenTable();
    echo ("<div align=center>");
    $exact = intval($exact);
    if ($exact == '1') {
        echo ("<strong>"._JOURNALFOR.": \"$forwhat\"</strong><br /><br />");
			} else {
        echo ("<strong>"._SEARCHRESULTS.": \"$forwhat\"</strong><br /><br />");
    }
			if ($forwhat == "naddaanythang") :
				displaySearch($sitename, $username, $bgcolor2, $bgcolor3, $bgcolor1);
        else :
        echo ("<table align=center width=\"90%\" border=2>");
        echo ("<tr>");
        echo ("<td align=center width=100><div align=\"center\"><strong>"._PROFILE."</strong></div></td>");
        echo ("<td align=center><strong>"._TITLE."</strong> "._CLICKTOVIEW."</td>");
        echo ("<td align=center width=\"5%\"><strong>"._VIEW."</strong></td>");
        echo ("<td align=center width=\"5%\"><strong>"._PROFILE."</strong></td>");
        echo ("</tr>");
        $forwhat = addslashes($forwhat);
        if ($bywhat == 'aid'):
            if ($exact == '1') {
            $sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.status, j.mdate, j.mtime, u.user_id, u.username FROM ".$prefix."_journal j, ".$user_prefix."_users u WHERE u.username=j.aid and j.aid='$forwhat' order by j.jid DESC";
        	} else {
            $sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.status, j.mdate, j.mtime, u.user_id, u.username FROM ".$prefix."_journal j, ".$user_prefix."_users u WHERE u.username=j.aid and j.aid like '%$forwhat%' order by j.jid DESC";
        	} elseif ($bywhat == 'title'):
            $sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.status, j.mdate, j.mtime, u.user_id, u.username FROM ".$prefix."_journal j, ".$user_prefix."_users u WHERE u.username=j.aid and j.title like '%$forwhat%' order by j.jid DESC";
            elseif ($bywhat == 'bodytext'):
            $sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.status, j.mdate, j.mtime, u.user_id, u.username FROM ".$prefix."_journal j, ".$user_prefix."_users u WHERE u.username=j.aid and j.bodytext LIKE '%$forwhat%' order by j.jid DESC";
            elseif ($bywhat == 'comment'):
            $sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.status, j.mdate, j.mtime, u.user_id, u.username FROM ".$prefix."_journal j, ".$user_prefix."_users u, ".$user_prefix."_journal_comments c WHERE u.username=j.aid and c.rid=j.jid and c.comment LIKE '%$forwhat%' order by j.jid DESC";
            endif;
            $result = $db->sql_query($sql);
            $dcount = 0;
            while ($row = $db->sql_fetchrow($result)) {
                $row['jid'] = intval($row['jid']);
                $row['aid'] = check_html($row['aid'], "nohtml");
                $row['title'] = check_html($row['title'], "nohtml");
                $row['pdate'] = check_html($row['pdate'], "nohtml");
                $row['ptime'] = check_html($row['ptime'], "nohtml");
                $row['status'] = check_html($row['status'], "nohtml");
                $row['mdate'] = check_html($row['mdate'], "nohtml");
                $row['mtime'] = check_html($row['mtime'], "nohtml");
                $row['user_id'] = check_html($row['user_id'], "nohtml");
                $row['username'] = check_html($row['username'], "nohtml");
                if ($row['status'] == "no") :
            $dcount = $dcount + 0;
            else :
            $dcount = $dcount + 1;
            print ("<tr>");
            printf ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=".$row['username']."\">" . UsernameColor("%s") . "</a></td>", $row['aid'], $row['aid']);
            printf ("<td align=left bgcolor=$bgcolor2>&nbsp;<a href=\"modules.php?name=$module_name&amp;file=display&amp;jid=%s\">%s</a> <span class=tiny>(%s @ %s)</span>", $row['jid'], $row['title'], $row['pdate'], $row['ptime']);
            $sqlscnd = "SELECT cid from ".$prefix."_journal_comments where rid=".$row['jid'];
            $rstscnd = $db->sql_query($sqlscnd);
            $scndcount = 0;
            while ($rowscnd = $db->sql_fetchrow($rstscnd)) {
                $scndcount = $scndcount + 1;
            }
            if ($scndcount > 0):
                printf (" &#151;&#151; $scndcount comments</td>");
            endif;
            printf ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=$module_name&amp;file=display&amp;jid=%s\"><img src=\"modules/$module_name/images/read.gif\" border=\"0\" alt=\""._READ."\" title=\""._READ."\" /></a></td>", $row['jid'], $row['title']);
            printf ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=".$row['username']."\"><img src=\"modules/$module_name/images/nuke.gif\" border=\"0\" alt=\""._USERPROFILE2."\" title=\""._USERPROFILE2."\" /></a></td>");
            endif;
        }
        echo ("</table>");
        if (empty($dcount)) {
            $dcount = 0;
        }
        echo ("<br /><div align=center>$dcount "._PUBLICFOR." \"$forwhat\"</div>");
        endif;
        echo ("</div>");
        CloseTable();
    }
    if (isset($disp)) { $disp = check_html($disp, "nohtml"); }
    else { $disp = ""; }
    switch($disp) {
        case "showsearch":
        displaySearch($sitename, $username, $bgcolor2, $bgcolor3, $bgcolor1, $forwhat, $user);
        break;
        case "search":
        search($username, $bywhat, $forwhat, $sitename, $bgcolor2, $bgcolor3, $user);
        break;
        default:
        search($username, $bywhat, $forwhat, $sitename, $bgcolor2, $bgcolor3, $user);
        break;
    }
    journalfoot();

?>