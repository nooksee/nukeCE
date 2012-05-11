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

function tinymce_getInstance($field='editor', $width='100%', $height='300px', $value='') {
    return tinymce::getInstance($field, $width, $height, $value);
}

class tinymce {

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
                      <script type="text/javascript" src="includes/wysiwyg/TinyMCE/jscripts/tiny_mce/jquery.tinymce.js"></script>
                      <script type="text/javascript" src="includes/js/tinymce.js"></script>
                     ';
    }

    function getHTML($name){
        return '<textarea id="'.$name.'" name="'.$name.'" class="tinymce_editor" style="width: '.$this->fields[$name]['width'].'; height: '.$this->fields[$name]['height'].'">'
        .htmlspecialchars($this->fields[$name]['value'])."</textarea>\n";
    }

    function getInstance($field='editor', $width='100%', $height='300px', $value=''){
        static $tinymce;
        if (!isset($tinymce)) {
            $tinymce = new tinymce;
        }
        $tinymce->fields[$field] = array('width' => $width, 'height' => $height, 'value' => $value);
        return $tinymce;
    }
}

?>