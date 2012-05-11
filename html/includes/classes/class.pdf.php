<?php
/*******************************************************************************
* FPDF                                                                         *
*                                                                              *
* Version: 1.7                                                                 *
* Date:    2011-06-18                                                          *
* Author:  Olivier PLATHEY                                                     *
*******************************************************************************/

define('FPDF_VERSION','1.7');
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
        $html=str_replace("&nbsp;",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e) {
            if($i%2==0) {
                //Text
                if($this->HREF)
                $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                $this->Cell(0,5,$e,0,1,'C');
                else
                $this->Write(5,$e);
            } else {
                //Tag
                if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
                else {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v) {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $prop[strtoupper($a3[1])]=$a3[2];
                    }
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