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

/****************************************************
CPG Dragonfly CMS
*****************************************************
Copyright (c) 2004 - 2005 by CPG-Nuke Dev Team
http://dragonflycms.org

Dragonfly is released under the terms and conditions
of the GNU GPL version 2 or any later version

$Source: /cvs/html/includes/wysiwyg/wysiwyg.inc,v $
$Revision: 1.9 $
$Author: djmaze $
$Date: 2006/01/13 12:22:48 $

Modifications made by the Nuke-Evolution Team/nukeCE
****************************************************/

class Wysiwyg {
    var $editor;
    var $type;
    var $form;
    var $field;
    var $width;
    var $height;
    var $value;
    var $pass;

    function Wysiwyg($form, $field, $width='100%', $height='300px', $value='') {
        global $wysiwyg;
        if (!empty($wysiwyg) && $wysiwyg != 'none') {
            if (file_exists(NUKE_INCLUDE_DIR."wysiwyg/$wysiwyg/$wysiwyg.php")) {
                include_once(NUKE_INCLUDE_DIR."wysiwyg/$wysiwyg/$wysiwyg.php");
                $func = $wysiwyg.'_getInstance';
                if (function_exists($func)) {
                    $this->pass = 1;
                    $this->editor = $func($field, $width, $height, $value);
                } else {
                    $this->pass = 0;
                    $this->editor = new $wysiwyg($field, $width, $height, $value);
                }
            } else {
                echo '<div align=\"center\">
                          <span class=\"option\"><b><em>The chosen WYSIWYG editor "'.$wysiwyg.'" is not available!</em></b></span>
                      </div>
                     ';
            }
        }
        
        $this->type   = $wysiwyg;
        $this->form   = $form;
        $this->field  = $field;
        $this->width  = $width;
        $this->height = $height;
        $this->value  = $value;
    }

    function setHeader() {
        if (!empty($this->editor) && method_exists($this->editor, 'setHeader')) {
            $this->editor->setHeader();
        }
    }

    function getSelect() {
        global $wysiwyg;
        return select_box('xtextarea', $wysiwyg, $this->getEditors()). '</td></tr>';
    }

    function getEditors() {
        $editors = array('' => _NONE);
        if (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
            $wysiwygs = dir(NUKE_INCLUDE_DIR.'wysiwyg');
            while ($dir = $wysiwygs->read()) {
                if ($dir[0] != '.' && file_exists(NUKE_INCLUDE_DIR."wysiwyg/SimpleMCE/$dir.php")) {
                    $editors[$dir] = $dir;
                }
            }
            $wysiwygs->close();
            return $editors;
        } else
            $wysiwygs = dir(NUKE_INCLUDE_DIR.'wysiwyg');
            while ($dir = $wysiwygs->read()) {
                if ($dir[0] != '.' && file_exists(NUKE_INCLUDE_DIR."wysiwyg/$dir/$dir.php")) {
                    $editors[$dir] = $dir;
                }
            }
            $wysiwygs->close();
            return $editors;
    }

    function getHTML() {
        if (!empty($this->editor)) {
            if ($this->pass) {
                $this->setHeader();
                global $modheader;
                echo $modheader;
                return $this->editor->getHtml($this->field);	
            }
            return $this->editor->getHtml();
        } else {
            return '<textarea id="'.$this->field.'" name="'.$this->field.'" style="wrap:virtual; width: '.$this->width.'; height: '.$this->height.'">'.htmlspecialchars($this->value).'</textarea><br />';
        }
    }

    function Show() { echo $this->getHTML(); echo '<br />'; }
    function Ret() { return $this->getHTML(); }

}

?>