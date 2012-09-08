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

include(NUKE_CLASSES_DIR.'class.feed.php');
require_once(NUKE_CLASSES_DIR.'class.bbcode.php');

$sitename = entity_to_hex_value($sitename);
$nukeurl = htmlspecialchars($nukeurl);
$backend_title = entity_to_hex_value($backend_title);
$slogan = entity_to_hex_value($slogan);

$Feed = new FeedWriter(RSS2);

$Feed->setChannelElement('title',$sitename);
$Feed->setChannelElement('link',$nukeurl);
$Feed->setChannelElement('description',$slogan);
$Feed->setChannelElement('copyright',$sitename);
$Feed->setChannelElement('generator',"".$sitename." RSS Parser");
$Feed->setChannelElement('ttl',"60");

$Feed->setImage($sitename,$nukeurl,"".$nukeurl."/images/powered/minilogo.gif");

$Feed->setChannelElement('language',$backend_language);
$Feed->setChannelElement('creator',$adminmail);
$Feed->setChannelElement('pubDate', date(DATE_RSS, time()));

$num = (isset($num) && is_integer(intval($num)) && intval($num) > 0) ? 'LIMIT '.$num : 'LIMIT 10';
$result = $db->sql_query("SELECT t.topic_id, t.topic_title, t.topic_last_post_id FROM ".$prefix."_bbtopics t, ".$prefix."_bbforums f WHERE t.forum_id=f.forum_id AND f.auth_view=0 ORDER BY t.topic_last_post_id DESC ".$num);
while(list($topic_id, $topic_title, $topic_last_post_id) = $db->sql_fetchrow($result)) {
    $topic_title = entity_to_hex_value($topic_title);
    $result2 = $db->sql_query("SELECT post_text FROM ".$prefix."_bbposts_text WHERE post_id='$topic_last_post_id'");
    list($desc) = $db->sql_fetchrow($result2);
    $desc = entity_to_hex_value($desc);
    $desc = decode_bbcode(stripslashes($desc), 1, true);
    $desc = decode_rss_rest($desc);
    $desc = scaleImages($desc, '130px');
    $result3 = $db->sql_query("SELECT poster_id, post_time FROM ".$prefix."_bbposts WHERE post_id='$topic_last_post_id'");
    list($poster_id, $post_time) = $db->sql_fetchrow($result3);
    $result4 = $db->sql_query("SELECT username FROM ".$user_prefix."_users WHERE user_id='$poster_id'");
    list($username) = $db->sql_fetchrow($result4);
    $username = htmlspecialchars(stripslashes($username));
    $gmtdiff = date("O", time());
    $gmtstr = substr($gmtdiff, 0, 3) . ":" . substr($gmtdiff, 3, 9);
    $date = date('Y-m-d\TH:i:s', $post_time);
    $date = $date . $gmtstr;

    //add item elements to the feed eg elements inside <item> -Elementshere- </item>
    $Feed->feeditem->setItemTitle($topic_title);
    $Feed->feeditem->setItemLink("".$nukeurl."/modules.php?name=Forums&file=viewtopic&t=".$topic_id."#".$topic_last_post_id."");

    $Feed->feeditem->setDate($date);
    $Feed->feeditem->setItemDescription($desc);

    $Feed->feeditem->addElement('author', "Posted by ".$username."");
    $Feed->feeditem->addElement('guid', "".$topic_last_post_id."@".$nukeurl."",array('isPermaLink'=>'true'));

    $Feed->feeditem=$Feed->insertItem($Feed->feeditem);//<--important! use as it is
}  

$Feed->burnFeed();

?>