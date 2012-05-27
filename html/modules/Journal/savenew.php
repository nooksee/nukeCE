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
    if (empty($title) OR empty($jbodytext) OR empty($status)) {
        OpenTable();
        echo "<div align=\"center\"><span class=\"option\"><b><em>"._YOUMUSTFILLFIELDS."</em></b></span><br /><br />"._GOBACK."</div>";
        CloseTable();
        include("footer.php");
        exit;
    } elseif (is_user()) {
        $cookie = cookiedecode();
        $username = $cookie[1];
        $htime = date("h");
        $mtime = date("i");
        $ntime = date("a");
        $mtime = "$htime:$mtime $ntime";
        $mdate = date("m");
        $ddate = date("d");
        $ydate = date("Y");
        $ndate = "$mdate-$ddate-$ydate";
        $pdate = $ndate;
        $ptime = $mtime;
        if ($debug == "true") :
        echo ("UserName:$username<br />SiteName: $sitename");
        endif;
        startjournal($sitename, $user);
        echo "<br />";
        OpenTable();
        echo ("<div align=center class=title>"._ENTRYADDED."</div><br /><br />");
        echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&amp;file=edit\">"._RETURNJOURNAL."</a> ]</div>");
        CloseTable();
        $username = $cookie[1];
        $user = check_html($user, "nohtml");
        $username = check_html($username, "nohtml");
        $sitename = check_html($sitename, "nohtml");
        $title = check_html($title, "nohtml");
        $title = addslashes($title);
        if (isset($mood)) { $mood = check_html($mood, "nohtml"); }
        else { $mood = ""; }
        $jbodytext =  BBCode2Html(stripslashes($jbodytext));
        $jbodytext = nuke_img_tag_to_resize($jbodytext);
        $jbodytext = addslashes($jbodytext);
        $sql = "INSERT INTO ".$prefix."_journal (jid, aid, title, bodytext, mood, pdate, ptime, status, mtime, mdate) VALUES (NULL,'$username','$title','$jbodytext','$mood','$pdate','$ptime','$status','$mtime','$ndate')";
        $db->sql_query($sql);
        $sql = "SELECT * FROM ".$prefix."_journal_stats WHERE joid = '$username'";
        $result = $db->sql_query($sql);
        $row_count = $db->sql_numrows($result);
        if ($row_count == 0):
        $query = "INSERT INTO ".$prefix."_journal_stats (id, joid, nop, ldp, ltp, micro) VALUES ('','$username','1',now(),'$mtime',now())";
        $db->sql_query($query);
        else :
        $row = $db->sql_fetchrow($result);
        $nnop = $row['nop'];
        $nnnop = ($nnop + 1);
        $micro = date("U");
        $nnnop = check_html($nnnop, "nohtml");
        $ndate = check_html($ndate, "nohtml");
        $mtime = check_html($mtime, "nohtml");
        $micro = check_html($micro, "nohtml");
        $query = "UPDATE ".$prefix."_journal_stats SET nop='$nnnop', ldp='$ndate', ltp='$mtime', micro='$micro' WHERE joid='$username'";
        $db->sql_query($query);
        endif;
        journalfoot();
    } else {
        $pagetitle = "- "._YOUMUSTBEMEMBER."";
        $pagetitle = check_html($pagetitle, "nohtml");
        OpenTable();
        echo "<center><strong>"._YOUMUSTBEMEMBER."</strong></center>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
        exit;
    }

?>