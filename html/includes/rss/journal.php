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
$result = $db->sql_query("SELECT jid, aid, title, bodytext, pdate, ptime FROM ".$prefix."_journal ORDER BY jid DESC ".$num);
while ($row = $db->sql_fetchrow($result)) {
    $rjid = intval($row['jid']);
    $informant = $row['aid'];
    $title = $row['title'];
    $pdate = $row['pdate'];
    $ptime = $row['ptime'];
    $date_array = explode("-",$pdate); // split the array
    $var_day = $date_array[0]; //day seqment
    $var_month = $date_array[1]; //month segment
    $var_year = $date_array[2]; //year segment
    $ndate = "$var_year-$var_day-$var_month"; // join them together
    $ntime = DATE("H:i", STRTOTIME($ptime));
    $date = $ndate . ' ' . $ntime;    
    $bodytext = $row['bodytext'];
    $bodytext = BBCode2Html($bodytext);
    $bodytext = scaleImages($bodytext, '130px');

    //add item elements to the feed eg elements inside <item> -Elementshere- </item>
    $Feed->feeditem->setItemTitle($title);
    $Feed->feeditem->setItemLink("".$nukeurl."/modules.php?name=Journal&file=display&jid=".$rjid."");

    $Feed->feeditem->setDate($date);
    $Feed->feeditem->setItemDescription($bodytext);

    $Feed->feeditem->addElement('author', "Journal Entry by ".$informant."");
    $Feed->feeditem->addElement('guid', "".$rjid."@".$nukeurl."",array('isPermaLink'=>'true'));

    $Feed->feeditem=$Feed->insertItem($Feed->feeditem);//<--important! use as it is
}  

$Feed->burnFeed();

?>