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

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

require_once("mainfile.php");
require(NUKE_CLASSES_DIR.'class.pdf.php');

$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if(!isset($sid)) {
    exit();
}

function PrintPDF($sid) {
    global $site_logo, $nukeurl, $module_name, $sitename, $prefix, $db;
    $sid = intval($sid);
    $num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_stories WHERE sid='$sid'"));
    if ($num == 0) {
        Header("Location: modules.php?name=$module_name");
        die();
    }
    $sid = intval(trim($sid));
    $row = $db->sql_fetchrow($db->sql_query("SELECT title, time, hometext, bodytext, topic FROM ".$prefix."_stories WHERE sid='$sid'"));
    $title = stripslashes($row['title']);
    $time = $row['time'];
/*****[BEGIN]******************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
    $hometext = BBCode2Html(stripslashes($row["hometext"]));
    $bodytext = BBCode2Html(stripslashes($row["bodytext"]));
/*****[END]********************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
    $topic = intval($row['topic']);
    $row2 = $db->sql_fetchrow($db->sql_query("SELECT topictext FROM ".$prefix."_topics WHERE topicid='$topic'"));
    $topictext = stripslashes($row2['topictext']);
    formatTimestamp($time);
    $html = _PDATE." ".$time."    "._PTOPIC." ".$topictext."<br ><br ><b>".$title."</b><br ><br >".$hometext."<br ><br >".$bodytext."<br ><br ><br><br><br>"._NEWSCOMESFROM." ".$sitename.":<br ><br ><a href=\"$nukeurl\">".$nukeurl."</a><br ><br>"._THEAURL."<br /><br /><a href=\"$nukeurl/modules.php?name=News&file=article&sid=$sid\">$nukeurl/modules.php?name=News&file=article&sid=$sid</a>";
    $pdftitle = $title;
    $pdfauthor = $nukeurl;

    $pdf=new PDF();
    $pdf->Open();
    $pdf->SetTitle($pdftitle);          
    $pdf->SetAuthor($pdfauthor);        
    $pdf->SetMargins(25,40);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',20);
    $link=$pdf->AddLink();
    $pdf->SetLink($link);
    $pdf->Image('images/'.$site_logo,25,20,0,0,'', $nukeurl);
    $pdf->SetFontSize(14);
    $pdf->WriteHTML($html);
    $pdf->AliasNbPages();
    $pdf->Output($pdftitle, 'I');
}

PrintPDF($sid);

?>