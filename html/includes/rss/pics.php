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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$sitename = entity_to_hex_value($sitename);
$nukeurl = htmlspecialchars($nukeurl);
$backend_title = entity_to_hex_value($backend_title);
$slogan = entity_to_hex_value($slogan);

$gmtdiff = date("O", time());
$gmtstr = substr($gmtdiff, 0, 3) . ":" . substr($gmtdiff, 3, 9);
// Format: 2004-08-02T12:15:23-06:00 (W3C Compliant)
$now = date("Y-m-d\TH:i:s", time());
$now = $now . $gmtstr;

$num = (isset($num) && is_integer(intval($num)) && intval($num) > 0) ? 'LIMIT '.$num : 'LIMIT 10';

$result = $db->sql_query("SELECT t.pic_id, t.pic_title, t.pic_cat_id FROM ".$prefix."_bbalbum t, ".$prefix."_bbalbum_cat f WHERE t.pic_cat_id=f.cat_id AND ( pic_approval = 1 ) AND f.cat_view_level=-1 ORDER BY t.pic_cat_id DESC ".$num);

header("Content-Type: text/xml");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
echo "<?xml-stylesheet title=\"XSL_formatting\" type=\"text/xsl\" href=\"includes/rss/rss_20.xsl\" ?>\n\n";
echo "<rss version=\"2.0\" \n";
echo " xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n";
echo " xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"\n";
echo " xmlns:admin=\"http://webns.net/mvcb/\"\n";
echo " xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">\n\n";
echo "<channel>\n";
echo "<title>".$sitename."</title>\n";
echo "<link>".$nukeurl."</link>\n";
echo "<description>".$slogan."</description>\n";
echo "<copyright>".$sitename."</copyright>\n";
echo "<generator>".$sitename."RSS Parser</generator>\n";
echo "<ttl>60</ttl>\n\n";
echo "<image>\n";
echo "<title>".$sitename."</title>\n";
echo "<url>".$nukeurl."/images/powered/minilogo.gif</url>\n";
echo "<link>".$nukeurl."</link>\n";
echo "<width>94</width>\n";
echo "<height>15</height>\n";
echo "<description>".$backend_title."</description>\n";
echo "</image>\n";
echo "<dc:language>".$backend_language."</dc:language>\n";
echo "<dc:creator>".$adminmail."</dc:creator>\n";
echo "<dc:date>".$now."</dc:date>\n\n";
echo "<sy:updatePeriod>hourly</sy:updatePeriod>\n";
echo "<sy:updateFrequency>1</sy:updateFrequency>\n";
echo "<sy:updateBase>".$now."</sy:updateBase>\n\n";

while(list($pic_id, $pic_title, $pic_cat_id) = $db->sql_fetchrow($result)) {
    $pic_title = entity_to_hex_value($pic_title);
    $result2 = $db->sql_query("SELECT pic_desc FROM ".$prefix."_bbalbum WHERE pic_id='$pic_id'");
    list($desc) = $db->sql_fetchrow($result2);
    $desc = entity_to_hex_value($desc);
    //$desc = ereg_replace('\x99', '', $desc); // Needs improvement
    $desc = decode_bb_all($desc);
    $desc = decode_rss_rest($desc);
    $result3 = $db->sql_query("SELECT pic_time AS pic_time FROM ".$prefix."_bbalbum WHERE pic_id='$pic_id'");
    list($pic_time) = $db->sql_fetchrow($result3);

    // Format: 2004-08-02T12:15:23-06:00 (W3C Compliant)
    //$date = date("Y-m-d\TH:i:s", strtotime($post_time));
    $date = date('Y-m-d\TH:i:s', $pic_time);
    $date = $date . $gmtstr;
    echo "<item>\n";
    echo "<title>".$pic_title."</title>\n";
    echo "<link>".$nukeurl."/modules.php?name=Forums&amp;file=album_pic&amp;pic_id=$pic_id#$pic_cat_id</link>\n";
    echo "<description><![CDATA[".$desc."]]></description>\n";
    echo "<guid isPermaLink=\"false\">".$pic_cat_id."@".$nukeurl."</guid>\n";
    echo "<dc:subject>".$pic_title."</dc:subject>\n";
    echo "<dc:date>".$date."</dc:date>\n";
    //echo "<dc:creator>Posted by ".$username."</dc:creator>\n";
    echo "</item>\n\n";
}
echo "</channel>\n\n";
echo "</rss>\n";

?>