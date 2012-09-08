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
$result = $db->sql_query("SELECT s.sid, t.topicname, s.informant, s.title, s.time, s.hometext FROM ".$prefix."_stories s, ".$prefix."_topics t WHERE s.topic = t.topicid ORDER BY sid DESC ".$num);
while ($row = $db->sql_fetchrow($result)) {
    $rsid = intval($row['sid']);
    $informant = $row['informant'];
    $title = $row['title'];
    $time = $row['time'];
    $hometext = $row['hometext'];
    $hometext = BBCode2Html($hometext);
    $hometext = scaleImages($hometext, '130px');
    $gmtdiff = date("O", time());
    $gmtstr = substr($gmtdiff, 0, 3) . ":" . substr($gmtdiff, 3, 9);
    $date = date("Y-m-d\TH:i:s", strtotime($time));
    $date = $date . $gmtstr;

    //add item elements to the feed eg elements inside <item> -Elementshere- </item>
    $Feed->feeditem->setItemTitle($title);
    $Feed->feeditem->setItemLink("".$nukeurl."/modules.php?name=News&file=article&sid=".$rsid."");

    $Feed->feeditem->setDate($date);
    $Feed->feeditem->setItemDescription($hometext);

    $Feed->feeditem->addElement('author', "Posted by ".$informant."");
    $Feed->feeditem->addElement('guid', "".$rsid."@".$nukeurl."",array('isPermaLink'=>'true'));

    $Feed->feeditem=$Feed->insertItem($Feed->feeditem);//<--important! use as it is
}  

$Feed->burnFeed();

?>