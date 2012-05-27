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

if (!isset($jid) OR !is_numeric($jid)) { die("No journal specified."); }
$pagetitle = '- '._USERSJOURNAL;
include_once(NUKE_BASE_DIR.'header.php');
include(NUKE_MODULES_DIR.$module_name.'/functions.php');

if (is_user()) {
    $cookie = cookiedecode();
    $username = $cookie[1];
}
$username = check_html($username, "nohtml");
$sitename = check_html($sitename, "nohtml");
$debug = check_html($debug, "nohtml");
if ($debug == "true") {
    echo ("UserName:$username<br />SiteName: $sitename");
}

startjournal($sitename,$user);
$jid = intval($jid);
if (empty($jid)) {
    OpenTable();
    echo ("<div align=\"center\">"._ANERROR."</div>");
    CloseTable();
    echo ("<br /><br />");
    journalfoot();
}

$sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.mdate, j.mtime, j.bodytext, j.status, j.mood, u.user_id, u.username FROM ".$user_prefix."_journal j, ".$user_prefix."_users u WHERE u.username=j.aid and j.jid = '$jid'";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) {
    $owner = $row['aid'];
    $owner = check_html($owner, "nohtml");
    $status = $row['status'];
    $status = check_html($status, "nohtml");
    $jaid = check_html($row['aid'], "nohtml");
    if (($status == 'no') && ($jaid != $username)) {
        OpenTable();
        echo "<center><br />"._ISPRIVATE."<br /></center>";
        CloseTable();
        journalfoot();
    }
    echo "<br />";
    OpenTable();
    $row['title'] = check_html($row['title'], "nohtml");
    $jmood = check_html($row['mood'], "nohtml");
    if (!empty($jmood)) {
        printf ("<br /><div align=center><img src=\"$jsmiles/%s\" alt=\"%s\" title=\"%s\" /></div>", $jmood, $jmood, $jmood);
    }
    $title = check_html($row['title'], "nohtml");
    printf ("<div class=title align=center>%s</div>", $title);
    $username = check_html($row['username'], "nohtml");
    $jid = intval($row['jid']);
    $pdate = check_html($row['pdate'], "nohtml");
    $ptime = check_html($row['ptime'], "nohtml");
    printf ("<div align=center>"._BY.": <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$jaid\">%s</a></div>", $jaid, $jaid);
    printf ("<div align=center class=tiny>"._POSTEDON.": %s @ %s</div>", $pdate, $ptime);
    CloseTable();
    echo "<br />";
    OpenTable();
    $jbodytext = $row['bodytext'];
    $jbodytext =  BBCode2Html(stripslashes($jbodytext));
    $jbodytext = nuke_img_tag_to_resize($jbodytext);
    printf ("%s", $jbodytext);
    CloseTable();
    printf ("<br /><br /><div class=tiny align=center>"._LASTUPDATED." %s @ %s</div><br />", $row['mdate'], $row['mtime']);
    printf ("<div class=tiny align=center>[ <a href=\"modules.php?name=$module_name&amp;file=friend&amp;jid=%s\">"._SENDJFRIEND."</a> ]</div>", $row['jid']);
    print ("<br />");
    OpenTable();
    print ("<table width=\"100%\" align=\"center\"><tr>");
    if (is_user()) {
        $cookie = cookiedecode();
        $username = $cookie[1];
        $username = check_html($username, "nohtml");
    }
    if (is_user() && $owner == $username) {
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&amp;file=modify&amp;jid=$jid\"><img src=\"modules/$module_name/images/edit.gif\" border=0 alt=\""._EDIT."\" title=\""._EDIT."\" /><br />"._EDIT."</a></td>";
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&amp;file=delete&amp;jid=$jid&amp;forwhat=$jid\"><img src=\"modules/$module_name/images/trash.gif\" border=0 alt=\""._DELETE."\" title=\""._DELETE."\" /><br />"._DELETE."</a></td>";
    } elseif (is_admin()) {
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&amp;file=modify&amp;jid=$jid\"><img src=\"modules/$module_name/images/edit.gif\" border=0 alt=\""._EDIT."\" title=\""._EDIT."\" /><br />"._EDIT."</a></td>";
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&amp;file=delete&amp;jid=$jid&amp;forwhat=$jid\"><img src=\"modules/$module_name/images/trash.gif\" border=0 alt=\""._DELETE."\" title=\""._DELETE."\" /><br />"._DELETE."</a></td>";
    }
    if (!empty($username)) {
            echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&amp;file=comment&amp;onwhat=$jid\"><img src=\"modules/$module_name/images/write.gif\" border=0 alt=\""._WRITECOMMENT."\" title=\""._WRITECOMMENT."\" /><br />"._WRITECOMMENT."</a></td>";
    }
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&amp;file=search&amp;bywhat=aid&amp;forwhat=".$row['aid']."\"><img src=\"modules/$module_name/images/binocs.gif\" border=0 alt=\""._VIEWMORE."\" title=\""._VIEWMORE."\" /><br />"._VIEWMORE."</a></td>";
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$username\"><img src=\"modules/$module_name/images/nuke.gif\" border=0 alt=\""._USERPROFILE."\" title=\""._USERPROFILE."\" /><br />"._USERPROFILE."</a></td>";
    if ($username != "" AND is_active("Private_Messages")) {
        echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=Private_Messages&amp;mode=post&amp;u=".$row['user_id']."\"><img src=\"modules/$module_name/images/chat.gif\" border=0 alt=\""._SENDMESSAGE."\" title=\""._SENDMESSAGE."\" /><br />"._SENDMESSAGE."</a></td>";
    }
    if (empty($username)) {
            echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=Your_Account\"><img src=\"modules/$module_name/images/folder.gif\" border=0 alt=\"Create an account\" title=\"Create an account\" /><br />"._CREATEACCOUNT."</a></td>";
    }
    print ("</tr></table>");
    CloseTable();
}
$db->sql_freeresult($result);
$commentheader = "no";
$sql = "SELECT j.cid, j.rid, j.aid, j.comment, j.pdate, j.ptime, u.user_id FROM ".$user_prefix."_journal_comments j, ".$user_prefix."_users u WHERE j.aid=u.username and j.rid = '$jid'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
        $row['cid'] = intval($row['cid']);
        $row['rid'] = check_html($row['rid'], "nohtml");
        $row['aid'] = check_html($row['aid'], "nohtml");
        $row['comment'] = check_html($row['comment'], $AllowableHTML);
        $pdate = check_html($row['pdate'], "nohtml");
        $ptime = check_html($row['ptime'], "nohtml");
        $row['user_id'] = check_html($row['user_id'], "nohtml");
        if ($row == 0) {
            $commentheader = 'yes';
        }
        if ($commentheader == 'no') {
            echo "<br />";
            if (empty($username) OR $username == $anonymous) {
                $ann_co = "<br /><div align=center class=tiny>"._REGUSERSCOMM."</div>";
            } else {
                $ann_co = "";
            }
            title(""._POSTEDCOMMENTS." ".$ann_co."");
            $commentheader = "yes";
        } elseif ($commentheader = "yes") {
        }
        OpenTable();
        printf (_COMMENTBY.": <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$username\">%s</a> <div class=tiny>("._POSTEDON." $pdate @ $ptime)</div><br />", $row['aid'], $row['aid'], $pdate, $ptime);
        $row['comment'] = check_html($row['comment'], $AllowableHTML);
        printf ("<strong>"._UCOMMENTS."</strong> %s", $row['comment']);
        if (is_user() && ($owner == $username)) {
            printf ("<br /><div align=center>[ <a href=\"modules.php?name=$module_name&amp;file=commentkill&amp;onwhat=%s&amp;ref=$jid\">"._DELCOMMENT."</a> ]</div>", $row['cid'], $jid);
        } else if (is_admin()) {
            printf ("<br /><div align=center>[ <a href=\"modules.php?name=$module_name&amp;file=commentkill&amp;onwhat=%s&amp;ref=$jid\">"._DELCOMMENT."</a> ]</div>", $row['cid'], $jid);
        }
        CloseTable();
        print ("<br /><br />");
}
$db->sql_freeresult($result);
journalfoot();

?>