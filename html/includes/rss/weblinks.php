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
$cat = intval($cat);
if (!empty($cat)) {
    $catid = $db->sql_fetchrow($db->sql_query("SELECT catid FROM ".$prefix."_links_categories WHERE title LIKE '%$cat%' LIMIT 1"));
    if (empty($catid)) {
        $result = $db->sql_query("SELECT lid, title, description, date, submitter FROM ".$prefix."_links_links ORDER BY lid DESC ".$num);
    } else {
        $catid = intval($catid);
        $result = $db->sql_query("SELECT lid, title, description, date, submitter FROM ".$prefix."_links_links WHERE catid='$catid' ORDER BY lid DESC ".$num);
    }
} else {
    $result = $db->sql_query("SELECT lid, title, description, date, submitter FROM ".$prefix."_links_links ORDER BY lid DESC ".$num);
}
while(list($lid, $title, $description, $date, $submitter) = $db->sql_fetchrow($result)) {
    $title = stripslashes($title);
    $title = entity_to_hex_value($title);
    $title2 = ereg_replace(" ", "_", $title);
    $description = stripslashes($description);
    $description = BBCode2Html($description);
    $description = scaleImages($description, '130px');
    if (empty($submitter)) {
        $submitter = $sitename;
    }
    $gmtdiff = date("O", time());
    $gmtstr = substr($gmtdiff, 0, 3) . ":" . substr($gmtdiff, 3, 9);
    $date = date("Y-m-d\TH:i:s", strtotime($date));
    $date = $date . $gmtstr;

    //add item elements to the feed eg elements inside <item> -Elementshere- </item>
    $Feed->feeditem->setItemTitle($title);
    $Feed->feeditem->setItemLink("".$nukeurl."/modules.php?name=Web_Links&l_op=viewlinkdetails&lid=".$lid."&ttitle=".$title2."");

    $Feed->feeditem->setDate($date);
    $Feed->feeditem->setItemDescription($description);

    $Feed->feeditem->addElement('author', "Submitted by ".$submitter."");
    $Feed->feeditem->addElement('guid', "".$lid."@".$nukeurl."",array('isPermaLink'=>'true'));

    $Feed->feeditem=$Feed->insertItem($Feed->feeditem);//<--important! use as it is
}  

$Feed->burnFeed();

?>