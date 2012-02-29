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
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if(!isset($sid)) {
    exit();
}

function PrintPage($sid) {
    global $site_logo, $nukeurl, $sitename, $datetime, $prefix, $dbi, $module_name ;
    $result = sql_query("select title, time, hometext, bodytext, topic, notes from ".$prefix."_stories where sid=$sid", $dbi);
    list($title, $time, $hometext, $bodytext, $topic, $notes) = sql_fetch_row($result, $dbi);
    $result2 = sql_query("select topictext from ".$prefix."_topics where topicid=$topic", $dbi);
    list($topictext) = sql_fetch_row($result2, $dbi);
    formatTimestamp($time);
    $datetime = formatTimestamp($datetime);
    $html = "<br>"._PDATE." ".$time."    "._PTOPIC." ".$topictext."<br ><br ><b>".$title."</b><br ><br >".$hometext."<br >";
    if ( $bodytext != "" ){
        $html .= $bodytext."<br ><br >";
    }
    $html .= "<br><br><br>"._COMESFROM." ".$sitename.":<br ><br ><a href=\"$nukeurl\">".$nukeurl."</a><br ><br>"._THEURL."<br /><br /><a href=\"$nukeurl/modules.php?name=News&file=article&sid=$sid\">$nukeurl/modules.php?name=News&file=article&sid=$sid</a>";
    
    $pdftitle = $title;
    $pdfauthor = $nukeurl;
    $pdfdescription = $hometext;

    define("FPDF_FONTPATH","includes/fonts/");
    require(NUKE_CLASSES_DIR.'class.fpdf.php');

    class PDF extends FPDF {
        var $B;
        var $I;
        var $U;
        var $HREF;

        function PDF($orientation='P',$unit='mm',$format='A4') {
            //Call parent constructor
            $this->FPDF($orientation,$unit,$format);
            //Initialization
            $this->B=0;
            $this->I=0;
            $this->U=0;
            $this->HREF='';
        }

        function WriteHTML($html) {
            //HTML parser
            $html=str_replace("\n",' ',$html);
            $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
            foreach($a as $i=>$e) {
                if($i%2==0) {
                    //Text
                    if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                    else
                    $this->Write(5,$e);
                } else {
                    //Tag
                    if($e{0}=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                    else {
                        //Extract properties
                        $a2=split(' ',$e);
                        $tag=strtoupper(array_shift($a2));
                        $prop=array();
                        foreach($a2 as $v)
                        if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
                        $prop[strtoupper($a3[1])]=$a3[2];
                        $this->OpenTag($tag,$prop);
                    }
                }
            }
        }

        function OpenTag($tag,$prop) {
            //Opening tag
            if($tag=='B' or $tag=='I' or $tag=='U')
            $this->SetStyle($tag,true);
            if($tag=='A')
            $this->HREF=$prop['HREF'];
            if($tag=='BR')
            $this->Ln(5);
        }

        function CloseTag($tag) {
            //Closing tag
            if($tag=='B' or $tag=='I' or $tag=='U')
            $this->SetStyle($tag,false);
            if($tag=='A')
            $this->HREF='';
        }

        function SetStyle($tag,$enable) {
            //Modify style and select corresponding font
            $this->$tag+=($enable ? 1 : -1);
            $style='';
            foreach(array('B','I','U') as $s)
            if($this->$s>0)
            $style.=$s;
            $this->SetFont('',$style);
        }

        function PutLink($URL,$txt) {
            //Put a hyperlink
            $this->SetTextColor(0,0,255);
            $this->SetStyle('U',true);
            $this->Write(5,$txt,$URL);
            $this->SetStyle('U',false);
            $this->SetTextColor(0);
        }

        function Footer() {
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            //Arial italic 8
            $this->SetFont('Arial','I',10);
            //Text color in gray
            $this->SetTextColor(128);
            //Page number
            $this->Cell(0,10,''._NE_PAGE.' '.$this->PageNo() .' '._NE_OF.' {nb}' ,0,0,'C');
        }
    }

    $pdf=new PDF();
    $pdf->Open();
    $pdf->SetTitle($pdftitle);          
    $pdf->SetAuthor($pdfauthor);        
    $pdf->SetMargins(25,40);
    //First page
    $pdf->AddPage();
    $pdf->SetFont('Arial','',20);
    // $pdf->SetFont('','U');
    $link=$pdf->AddLink();
    // $pdf->Write(5,'here',$link);
    // $pdf->SetFont('');
    //Second page
    // $pdf->AddPage();
    $pdf->SetLink($link);
    $pdf->Image('images/'.$site_logo,25,20,60,0,'', $nukeurl);
    //$pdf->SetTopMargin(400);
    //$pdf->SetLeftMargin(25);
    $pdf->SetFontSize(14);
    $pdf->WriteHTML($html);
    $pdf->AliasNbPages();
    $pdf->Output();
}
PrintPage($sid);

?>