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
$result = $db->sql_query("SELECT t.pic_id, t.pic_title, t.pic_cat_id FROM ".$prefix."_bbalbum t, ".$prefix."_bbalbum_cat f WHERE t.pic_cat_id=f.cat_id AND ( pic_approval = 1 ) AND f.cat_view_level=-1 ORDER BY t.pic_cat_id DESC ".$num);
while(list($pic_id, $pic_title, $pic_cat_id) = $db->sql_fetchrow($result)) {
    $pic_title = entity_to_hex_value($pic_title);
    $result2 = $db->sql_query("SELECT pic_user_id, pic_time FROM ".$prefix."_bbalbum WHERE pic_id='$pic_id'");
    list($pic_user_id, $pic_time) = $db->sql_fetchrow($result2);
    $result3 = $db->sql_query("SELECT username FROM ".$user_prefix."_users WHERE user_id='$pic_user_id'");
    list($username) = $db->sql_fetchrow($result3);
    $username = htmlspecialchars(stripslashes($username));
    $gmtdiff = date("O", time());
    $gmtstr = substr($gmtdiff, 0, 3) . ":" . substr($gmtdiff, 3, 9);
    $date = date('Y-m-d\TH:i:s', $pic_time);
    $date = $date . $gmtstr;
    $url = "".$nukeurl."/modules.php?name=Forums&file=album_page&pic_id=".$pic_id."";
    $thumb = "".$nukeurl."/modules.php?name=Forums&amp;file=album_thumbnail&amp;pic_id=".$pic_id."";
    $desc = "<a href=".$url."><img src=".$thumb." border=\"1\" vspace=\"2\" hspace=\"2\" align=\"left\" ></a>";

    //add item elements to the feed eg elements inside <item> -Elementshere- </item>
    $Feed->feeditem->setItemTitle($pic_title);
    $Feed->feeditem->setItemLink($url);

    $Feed->feeditem->setDate($date);
    $Feed->feeditem->setItemDescription($desc);
    $Feed->feeditem->addElement('author', "Uploaded by ".$username."");
    
    $Feed->feeditem->addElement('guid', "".$pic_cat_id."@".$nukeurl."",array('isPermaLink'=>'true'));

    $Feed->feeditem=$Feed->insertItem($Feed->feeditem);//<--important! use as it is
}  

$Feed->burnFeed();

?>