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

/********************************************************/
/* Based on NSN News                                    */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

global $cookie, $userinfo;

$optionbox = "";
$module_name = basename(dirname(__FILE__));
include_once(NUKE_INCLUDE_DIR.'functions_news.php');
get_lang($module_name);

// we only show the left blocks, else the page gets messed up
$showblocks = 1;

if (isset($sid)) { $sid = intval($sid); } else { $sid = ""; }

if (stristr($_SERVER['REQUEST_URI'],"mainfile")) {
    redirect("modules.php?name=$module_name&file=article&sid=$sid");
} elseif (empty($sid) && !isset($tid)) {
    redirect("index.php");
}

if(is_user()) {
    if(!isset($mode)) { $mode = $userinfo['umode']; }
    if(!isset($order)) { $order = $userinfo['uorder']; }
    if(!isset($thold)) { $thold = $userinfo['thold']; }
    $db->sql_query("UPDATE ".$user_prefix."_users SET umode='$mode', uorder='$order', thold='$thold' where user_id=".intval($cookie[0]));
}

if ($op == "Reply") {
    $display = "";
    if(isset($mode)) { $display .= "&mode=".$mode; }
    if(isset($order)) { $display .= "&order=".$order; }
    if(isset($thold)) { $display .= "&thold=".$thold; }
    redirect("modules.php?name=$module_name&file=comments&op=Reply&pid=0&sid=".$sid.$display);
}

$result = $db->sql_query("select catid, aid, time, title, hometext, bodytext, topic, informant, notes, acomm, haspoll, pollID, score, ratings, ticon FROM ".$prefix."_stories where sid='$sid'");
//Causes trouble, has to be fixed
$numrows = $db->sql_numrows($result);
if (!empty($sid) && $numrows != 1) {
    redirect("index.php");
}
$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);
$aaid = stripslashes($row['aid']);
$catid = intval($row["catid"]);
$time = $row["time"];
$title = stripslashes(check_html($row["title"], "nohtml"));
/*****[BEGIN]******************************************
[ Mod:     News BBCodes                       v1.0.0 ]
******************************************************/
$hometext = BBCode2Html(stripslashes($row["hometext"]));
$bodytext = BBCode2Html(stripslashes($row["bodytext"]));
$hometext = nuke_img_tag_to_resize($hometext);
$bodytext = nuke_img_tag_to_resize($bodytext);
/*****[END]********************************************
[ Mod:     News BBCodes                       v1.0.0 ]
******************************************************/
$topic = intval($row["topic"]);
$informant = stripslashes($row["informant"]);
$notes = stripslashes($row["notes"]);
$acomm = intval($row["acomm"]);
$haspoll = intval($row["haspoll"]);
$pollID = intval($row["pollID"]);
$score = intval($row["score"]);
$ratings = intval($row["ratings"]);
/*****[BEGIN]******************************************
[ Mod:    Display Topic Icon                  v1.0.0 ]
******************************************************/
$topic_icon = intval($row["ticon"]);
/*****[END]********************************************
[ Mod:    Display Topic Icon                  v1.0.0 ]
******************************************************/

if (empty($aaid)) {
    redirect("modules.php?name=".$module_name);
}

$db->sql_query("UPDATE ".$prefix."_stories SET counter=counter+1 where sid='$sid'");

$artpage = 1;
$pagetitle = $title;
include(NUKE_BASE_DIR."header.php");
$artpage = 0;

formatTimestamp($time);
$title = stripslashes(check_html($title, "nohtml"));
$hometext = stripslashes($hometext);
$bodytext = stripslashes($bodytext);
$notes = stripslashes($notes);

if (!empty($notes)) {
    $notes = '<br /><br /><div><b>'._EDITORNOTE.'</b>&nbsp;<i>'.$notes.'</i></div>';
} else {
    $notes = "";
}

if(empty($bodytext)) {
    $bodytext = "$hometext$notes";
} else {
    $bodytext = "$hometext<br /><br />$bodytext$notes";
}

if(empty($informant)) {
    $informant = $anonymous;
}
/*****[END]********************************************
[ Mod:    Advanced Username Color             v1.0.5 ]
******************************************************/
getTopics($sid);

if ($catid != 0) {
    $row2 = $db->sql_fetchrow($db->sql_query("select title from ".$prefix."_stories_cat where catid='$catid'"));
    $title1 = stripslashes(check_html($row2["title"], "nohtml"));
    $title = "<a href=\"modules.php?name=$module_name&amp;file=categories&amp;op=newindex&amp;catid=$catid\"><font class=\"storytitle\">$title1</font></a>: $title";
}

/*****[BEGIN]******************************************
[ Mod:    Display Topic Icon                  v1.0.0 ]
******************************************************/
if($topic_icon == 1) {
    $topicimage = '';
}
/*****[END]********************************************
[ Mod:    Display Topic Icon                  v1.0.0 ]
******************************************************/

echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\" width=\"100%\">\n";
themearticle($aaid, $informant, $datetime, $title, $bodytext, $topic, $topicname, $topicimage, $topictext);

include_once("modules/$module_name/associates.php");

if (((empty($mode) OR ($mode != "nocomments")) OR ($acomm == 0)) OR ($articlecomm == 1)) {
    @include_once("modules/$module_name/comments.php");
}

echo "</td><td>&nbsp;</td><td>&nbsp;</td><td></td><td></td><td valign=\"top\">\n";

if ($multilingual == 1) {
    $querylang = "AND (blanguage='$currentlang' OR blanguage='')";
} else {
    $querylang = "";
}

/* Determine if the article has attached a poll */
if ($haspoll == 1) {
    $url = sprintf("modules.php?name=Surveys&amp;op=results&amp;pollID=%d", $pollID);
    $boxContent = "<form action=\"modules.php?name=Surveys\" method=\"post\">";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $row3 = $db->sql_fetchrow($db->sql_query("SELECT pollTitle, voters FROM ".$prefix."_poll_desc WHERE pollID='$pollID'"));
    $pollTitle = stripslashes(check_html($row3["pollTitle"], "nohtml"));
    $voters = $row3["voters"];
    $boxTitle = _ARTICLEPOLL;
    $boxContent .= "<span class=\"content\"><strong>$pollTitle</strong></span><br /><br />\n";
    $boxContent .= "<table border=\"0\" width=\"100%\">";
    for($i = 1; $i <= 12; $i++) {
        $result4 = $db->sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE (pollID='$pollID') AND (voteID='$i')");
        $row4 = $db->sql_fetchrow($result4);
        $numrows = $db->sql_numrows($result4);
        $db->sql_freeresult($result4);
        if($numrows != 0) {
            $optionText = $row4["optionText"];
            if(!empty($optionText)) {
                $boxContent .= "<tr><td valign=\"top\"><input type=\"radio\" name=\"voteID\" value=\"".$i."\"></td><td width=\"100%\"><span class=\"content\">$optionText</span></td></tr>\n";
            }
        }
    }
    $boxContent .= "</table><br /><center><span class=\"content\"><input type=\"submit\" value=\""._VOTE."\"></span><br />";
    for($i = 0; $i < 12; $i++) {
        $row5 = $db->sql_fetchrow($db->sql_query("SELECT optionCount FROM ".$prefix."_poll_data WHERE (pollID='$pollID') AND (voteID='$i')"));
        $optionCount = $row5["optionCount"];
        $sum = (int)$sum+$optionCount;
    }
    $boxContent .= "
                    <div style=\"height: 12px; line-height: 12px;\">&nbsp;</div>
                    <div class=\"content\">
                        &nbsp;<a href=\"modules.php?name=Surveys&amp;op=results&amp;pollID=$pollID&amp;mode=".$userinfo['umode']."&amp;order=".$userinfo['uorder']."&amp;thold=".$userinfo['thold']."\"><strong>"._RESULTS."</strong></a>&nbsp;<br />
                        &nbsp;<a href=\"modules.php?name=Surveys\"><strong>"._POLLS."</strong></a>&nbsp;
                    </div>
                    <div style=\"height: 1px; line-height: 1px;\">&nbsp;</div>
                   ";

    if ($pollcomm) {
        $result6 = $db->sql_query("select * from ".$prefix."_pollcomments where pollID='$pollID'");
        $numcom = $db->sql_numrows($result6);
        $db->sql_freeresult($result6);
        $boxContent .= "<br />"._VOTES.": <strong>$sum</strong><br />"._PCOMMENTS." <strong>$numcom</strong>\n\n";
    } else {
        $boxContent .= "<br />"._VOTES." <strong>$sum</strong>\n\n";
    }
    $boxContent .= "</span></center></form>\n\n";
    themesidebox($boxTitle, $boxContent, "poll1");
}

$ThemeSel = get_theme();
if(!empty($topicimage)) {
    if (file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
        $t_image = "themes/$ThemeSel/images/topics/$topicimage";
    } else {
        $t_image = "$tipath$topicimage";
    }
    $topic_img = "$t_image";
} else {
    $topic_img = "";
}

$result = $db->sql_query("select title FROM ".$prefix."_stories where sid='$sid'");
list($title) = $db->sql_fetchrow($result);
$title = stripslashes(check_html($title, "nohtml"));

$pagetitle = $title;
$pagedesc = html2text($bodytext);
$pageimg = $topic_img;

$page_title = urlencode($pagetitle);
$page_desc = urlencode($pagedesc);
$page_img = urlencode($pageimg);

$optiontitle = _OPTIONS;
$optionbox = "
              <br />
              &nbsp;<img src='images/print.gif' border='0' alt='"._PRINTER."' title='"._PRINTER."'> <a href=\"modules.php?name=$module_name&amp;file=print&amp;sid=$sid\" target=\"_blank\">"._PRINTER."</a><br /><br />
              &nbsp;<img src='images/pdf.gif' border='0' alt='"._PDF."' title='"._PDF."'> <a href=\"modules.php?name=$module_name&amp;file=print_pdf&amp;sid=$sid\" rel='5' class='newWindow'>"._PDF."</a><br /><br />
              &nbsp;<img src='images/friend.gif' border='0' alt='"._FRIEND."' title='"._FRIEND."'> <a href=\"modules.php?name=$module_name&amp;file=friend&amp;op=FriendSend&amp;sid=$sid\">"._FRIEND."</a><br /><br />
              &nbsp;<img src='images/share.gif' border='0' alt='"._FACEBOOK."' title='"._FACEBOOK."'> <a href=\"http://www.facebook.com/sharer.php?s=100&p[title]=$page_title&p[summary]=$page_desc&p[url]=$current_url&p[images][0]=$nukeurl/$page_img\" rel='7' class='newWindow'\">"._FACEBOOK."</a><br /><br />
              &nbsp;<img src='images/tweet.png' border='0' alt='"._TWITTER."' title='"._TWITTER."'> <a href=\"http://twitter.com/share?url=$current_url&amp;text=$title\" rel='7' class='newWindow'\">"._TWITTER."</a><br /><br />
              &nbsp;<img src='images/feed.png' border='0' alt='"._SUBSCRIBERSS."' title='"._SUBSCRIBERSS."'> <a href=\"rss.php?feed=news\" target=\"_blank\">"._SUBSCRIBERSS."</a><br /><br />
             ";
themesidebox($optiontitle, $optionbox, "newsopt");

if (is_mod_admin($module_name)) {
    $admintitle = _ADMINOPT;
    $adminbox = "
                 <div align=\"center\">
                     &nbsp;<a href=\"".$admin_file.".php?op=adminStory\"><img src=\"images/add.gif\" alt=\""._ADD."\" title=\""._ADD."\" border=\"0\" width=\"17\" height=\"17\"></a>
                     &nbsp;<a href=\"".$admin_file.".php?op=EditStory&amp;sid=$sid\"><img src=\"images/edit.gif\" alt=\""._EDIT."\" title=\""._EDIT."\" border=\"0\" width=\"17\" height=\"17\"></a>
                     &nbsp;<a href=\"".$admin_file.".php?op=RemoveStory&amp;sid=$sid\"><img src=\"images/delete.gif\" alt=\""._DELETE."\" title=\""._DELETE."\" border=\"0\" width=\"17\" height=\"17\"></a>&nbsp;
                 </div>
                ";
    themesidebox($admintitle, $adminbox, "adminopt");
}

if ($ratings != 0) {
    $rate = substr($score / $ratings, 0, 4);
    $r_image = round($rate);
    if ($r_image == 1) {
        $the_image = "<br /><br /><img src=\"images/articles/stars-1.gif\" border=\"1\"></center><br />";
    } elseif ($r_image == 2) {
        $the_image = "<br /><br /><img src=\"images/articles/stars-2.gif\" border=\"1\"></center><br />";
    } elseif ($r_image == 3) {
        $the_image = "<br /><br /><img src=\"images/articles/stars-3.gif\" border=\"1\"></center><br />";
    } elseif ($r_image == 4) {
        $the_image = "<br /><br /><img src=\"images/articles/stars-4.gif\" border=\"1\"></center><br />";
    } elseif ($r_image == 5) {
        $the_image = "<br /><br /><img src=\"images/articles/stars-5.gif\" border=\"1\"></center><br />";
    }
} else {
    $rate = 0;
    $the_image = "</center><br />";
}

$ratetitle = _RATEARTICLE;
$ratecontent = "
                <form action=\"modules.php?name=$module_name\" method=\"post\">
                    <div align=\"center\">
                        "._AVERAGESCORE.": <strong>$rate</strong><br />
                        "._VOTES.": <strong>$ratings</strong>$the_image
                        <div style=\"height: 5px; line-height: 5px;\">&nbsp;</div>
                        "._RATETHISARTICLE."
                    </div>
                    <div style=\"height: 5px; line-height: 5px;\">&nbsp;</div>
                    <input type=\"radio\" name=\"score\" value=\"5\"> <img src=\"images/articles/stars-5.gif\" border=\"0\" alt=\""._EXCELLENT."\" title=\""._EXCELLENT."\"><br />
                    <input type=\"radio\" name=\"score\" value=\"4\"> <img src=\"images/articles/stars-4.gif\" border=\"0\" alt=\""._VERYGOOD."\" title=\""._VERYGOOD."\"><br />
                    <input type=\"radio\" name=\"score\" value=\"3\"> <img src=\"images/articles/stars-3.gif\" border=\"0\" alt=\""._GOOD."\" title=\""._GOOD."\"><br />
                    <input type=\"radio\" name=\"score\" value=\"2\"> <img src=\"images/articles/stars-2.gif\" border=\"0\" alt=\""._REGULAR."\" title=\""._REGULAR."\"><br />
                    <input type=\"radio\" name=\"score\" value=\"1\"> <img src=\"images/articles/stars-1.gif\" border=\"0\" alt=\""._BAD."\" title=\""._BAD."\"><br /><br />
                    <center>
                        <input type=\"hidden\" name=\"sid\" value=\"$sid\">
                        <input type=\"hidden\" name=\"op\" value=\"rate_article\">
                        <input type=\"submit\" value=\""._CASTMYVOTE."\">
                    </center>
                </form>
               ";
themesidebox($ratetitle, $ratecontent, "newsvote");

$boxtitle = _RELATED;
$boxstuff = "<span class=\"content\">";
$result8 = $db->sql_query("select name, url from ".$prefix."_related where tid='$topic'");
while ($row8 = $db->sql_fetchrow($result8)) {
    $name = stripslashes($row8["name"]);
    $url = stripslashes($row8["url"]);
    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$url\" target=\"new\">$name</a><br />\n";
}
$db->sql_freeresult($result8);

$boxstuff .= "
                <strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Search&amp;topic=$topic\">"._MOREABOUT." $topictext</a><br />
                <strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Search&amp;author=$aaid\">"._NEWSBY." $aaid</a>
              </span><br />
              <hr noshade width=\"95%\" size=\"1\">
              <center>
                  <span class=\"content\"><strong>"._MOSTREAD." $topictext:</strong><br />
             ";

global $multilingual, $currentlang;
if ($multilingual == 1) {
    $querylang = "AND (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
} else {
    $querylang = "";
}
$row9 = $db->sql_fetchrow($db->sql_query("select sid, title from ".$prefix."_stories where topic='$topic' $querylang order by counter desc limit 0,1"));
$topstory = intval($row9["sid"]);
$ttitle = stripslashes(check_html($row9["title"], "nohtml"));

$boxstuff .= "
                        <a href=\"modules.php?name=$module_name&amp;file=article&amp;sid=$topstory\">$ttitle</a>
                    </span>
                </center>
              <div style=\"height: 5px; line-height: 5px;\">&nbsp;</div>
             ";
themesidebox($boxtitle, $boxstuff, "newstopic");

echo "</td></tr></table>";

include_once(NUKE_BASE_DIR.'footer.php');

?>