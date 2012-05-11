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

function markitup_getInstance($field='editor', $width='100%', $height='300px', $value='') {
    return markitup::getInstance($field, $width, $height, $value);
}

class markitup {

    var $fields = array();
    var $first = true;

    function setHeader(){
        global $modheader;
        if ($this->first == false) {
            $modheader = '';
            return;
        }
        $this->first = false;

        $modheader = '
                      <link rel="stylesheet" type="text/css" href="includes/wysiwyg/markItUp/skins/simple/style.css" />
                      <link rel="stylesheet" type="text/css" href="includes/wysiwyg/markItUp/sets/bbcode/style.css" />
                      <script type="text/javascript" src="includes/wysiwyg/markItUp/jquery.markitup.js"></script>
                      <script type="text/javascript" src="includes/js/markitup.js"></script>
                      <script type="text/javascript" src="includes/wysiwyg/markItUp/sets/bbcode/set.js"></script>
                     ';
    }

    function getHTML($name){
        return '
                <div style=\"height: 5px; line-height: 5px;\">&nbsp;</div>
                <div id="emoticons">
                    <a href="#" title=":p"><img src="images/emoticons/emoticon-happy.png" /></a>
                    <a href="#" title=":("><img src="images/emoticons/emoticon-unhappy.png" /></a>
                    <a href="#" title=":o"><img src="images/emoticons/emoticon-surprised.png" /></a>
                    <a href="#" title=":p"><img src="images/emoticons/emoticon-tongue.png" /></a>
                    <a href="#" title=";)"><img src="images/emoticons/emoticon-wink.png" /></a>
                    <a href="#" title=":D"><img src="images/emoticons/emoticon-smile.png" /></a>
                </div>
                <textarea id="'.$name.'" name="'.$name.'" class="markitup_editor" style="width: '.$this->fields[$name]['width'].'; height: '.$this->fields[$name]['height'].'">
               '
        .htmlspecialchars($this->fields[$name]['value'])."</textarea>\n";
    }

    function getInstance($field='editor', $width='100%', $height='300px', $value=''){
        static $markitup;
        if (!isset($markitup)) {
            $markitup = new markitup;
        }
        $markitup->fields[$field] = array('width' => $width, 'height' => $height, 'value' => $value);
        return $markitup;
    }
}

?>