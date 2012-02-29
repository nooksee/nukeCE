<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
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
        
		$modheader = '<script type="text/javascript" src="includes/wysiwyg/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript">
			tinyMCE.init({
				// General options
				mode : "textareas",
				editor_selector : "tinymce_editor",
				theme : "advanced",
				plugins : "media,safari,advhr,advlink,emotions,iespell,preview,contextmenu,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
		
				// Theme options
				theme_advanced_buttons1 : "undo,redo,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,media,|,charmap,iespell,advhr,|,cleanup,code,preview",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "bottom",
				theme_advanced_toolbar_align : "center",
				theme_advanced_statusbar_location : "top",
				theme_advanced_resizing : true,
		
				// Example word content CSS (should be your site CSS) this one removes paragraph margins
				content_css : "css/word.css",
		
				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "lists/template_list.js",
				external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js"
				});
		</script>';
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