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

//Note due to all the windows.onload use womAdd('function_name()'); instead

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

include_once(NUKE_INCLUDE_DIR.'styles.php');

##################################################
# Include for some common javascripts functions  #
##################################################

echo "<script type=\"text/javascript\" src=\"includes/js/onload.js\"></script>\n";

/*****[BEGIN]******************************************
 [ Mod:     IE Embed Fix                       v1.0.0 ]
 ******************************************************/
echo "<!--[if IE]><script defer=\"defer\" type=\"text/javascript\" src=\"includes/js/embed_fix.js\"></script>\n<![endif]-->";
/*****[END]********************************************
 [ Mod:     IE Embed Fix                       v1.0.0 ]
 ******************************************************/

if (isset($userpage)) {
    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function showimage() {\n";
    echo "if (!document.images)\n";
    echo "return\n";
    echo "document.images.avatar.src=\n";
    echo "'$nukeurl/modules/Forums/images/avatars/gallery/' + document.Register.user_avatar.options[document.Register.user_avatar.selectedIndex].value\n";
    echo "}\n";
    echo "//-->\n";
    echo "</script>\n\n";
}

global $name;

if (defined('MODULE_FILE') && !defined("HOME_FILE") AND file_exists("modules/".$name."/copyright.php")) {
    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function openwindow(){\n";
    echo "    window.open (\"modules/".$name."/copyright.php\",\"Copyright\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=400,height=200\");\n";
    echo "}\n";
    echo "//-->\n";
    echo "</script>\n\n";
}


/*****[BEGIN]******************************************
 [ Mod:     Anti-Spam                         v.1.1.0 ]
 ******************************************************/
if (!defined('ADMIN_FILE')) {
    echo "<script type=\"text/javascript\" src=\"includes/js/anti-spam.js\"></script>\n";
}
/*****[END]********************************************
 [ Mod:     Anti-Spam                         v.1.1.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     IE PNG Fix                         v1.0.0 ]
 ******************************************************/
$arcade_on = (isset($_GET['file']) && $_GET['file'] == 'arcade_games') ? true : (isset($_POST['file']) && $_POST['file'] == 'arcade_games') ? true : false;
if (!$arcade_on) {
    $arcade_on = (isset($_GET['do']) && $_GET['do'] == 'newscore') ? true : (isset($_POST['do']) && $_POST['do'] == 'newscore') ? true : false;
}
if (!$arcade_on) {
    echo "<!--[if lt IE 7]><script type=\"text/javascript\" src=\"includes/js/pngfix.js\"></script><![endif]-->\n";
}
/*****[END]********************************************
 [ Mod:     IE PNG Fix                         v1.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Password Strength Meter            v1.0.0 ]
 ******************************************************/
 global $admin_file;
 if(isset($name) && ($name == "Your Account" || $name == "Your_Account" || $name == "Profile" || defined('ADMIN_FILE'))) {
     echo '<script type="text/javascript">
        var pwd_strong = "'.PSM_STRONG.'";
        var pwd_stronger = "'.PSM_STRONGER.'";
        var pwd_strongest = "'.PSM_STRONGEST.'";
        var pwd_notrated = "'.PSM_NOTRATED.'";
        var pwd_med = "'.PSM_MED.'";
        var pwd_weak = "'.PSM_WEAK.'";
        var pwd_strength = "'.PSM_CURRENTSTRENGTH.'";
    </script>';
    echo "<script type=\"text/javascript\" src=\"includes/js/password_strength.js\"></script>\n";
 }
/*****[END]********************************************
 [ Mod:     Password Strength Meter            v1.0.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Base:    Theme Management                   v1.0.2 ]
 ******************************************************/
if (defined('ADMIN_FILE')) {
    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function themepreview(theme){\n";
    echo "window.open (\"index.php?tpreview=\" + theme + \"\",\"ThemePreview\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=1000,height=800\");\n";
    echo "}\n";
    echo "//-->\n";
    echo "</script>\n\n";
}
/*****[END]********************************************
 [ Base:    Theme Management                   v1.0.2 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     ToolManDHTML                       v0.0.2 ]
 ******************************************************/
if (defined('ADMIN_FILE') && defined('USE_DRAG_DROP')) {
    global $element_ids, $Sajax;
    if(isset($Sajax) && is_object($Sajax)) {
        echo "<script type=\"text/javascript\">\n<!--\n";
        echo $Sajax->sajax_show_javascript();
        echo "//-->\n";
        echo "</script>\n";
    }
    $i = 0;
    $script_out = '';
    if(!is_array($element_ids)) $element_ids = array();
    foreach ($element_ids as $id) {
        if(!$i) {
            $script_out .= "var list = document.getElementById(\"".$id."\");\n";
            $i++;
        } else {
            $script_out .= "list = document.getElementById(\"".$id."\");\n";
        }
        global $g2;
        $script_out .= (!$g2) ? "DragDrop.makeListContainer( list, 'g1' );\n" : "DragDrop.makeListContainer( list, 'g2' );\n";
        $script_out .= "list.onDragOver = function() { this.style[\"background\"] = \"#EEF\"; };\n";
        $script_out .= "list.onDragOut = function() {this.style[\"background\"] = \"none\"; };\n\n\n";
        $script_out .= "list.onDragDrop = function() {onDrop(); };\n";
    }

    echo "<script type=\"text/javascript\" src=\"includes/ajax/coordinates.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"includes/ajax/drag.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"includes/ajax/dragdrop.js\"></script>\n";
    echo "<script type=\"text/javascript\"><!--
    function confirm(z)
    {
      window.status = 'Sajax version updated';
    }

    function create_drag_drop() {";

        echo $script_out;

    echo "};

    if (window.addEventListener)
        window.addEventListener(\"load\", create_drag_drop, false)
    else if (window.attachEvent)
        window.attachEvent(\"onload\", create_drag_drop)
    else if (document.getElementById)
        womAdd('create_drag_drop()');
    //-->
</script>\n";
}
/*****[END]********************************************
 [ Mod:     ToolManDHTML                       v0.0.2 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Base:    Switch Content Script              v2.0.0 ]
 ******************************************************/
global $plus_minus_images, $collapse;
if ($collapse) {
echo "<script type=\"text/javascript\">
        var enablepersist=\"on\" //Enable saving state of content structure using session cookies? (on/off)
        var memoryduration=\"7\" //persistence in # of days
        var contractsymbol='".$plus_minus_images['minus']."' //Path to image to represent contract state.
        var expandsymbol='".$plus_minus_images['plus']."' //Path to image to represent expand state.
      </script>\n
      <script type=\"text/javascript\" src=\"includes/js/collapse_blocks.js\"></script>\n";
}
/*****[END]********************************************
 [ Base:    Switch Content Script              v2.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Resize Posted Images               v2.4.5 ]
 ******************************************************/
global $img_resize;
if ($img_resize) {
    if((empty($name) || $name == 'News' || $name == 'Reviews' || $name == 'Stories Archive' || $name == 'Downloads' || $name == 'Web Links' || $name == 'Content') && !defined('IN_PHPBB')) {
        global $img_width, $img_height;
        echo "<script defer=\"defer\" type=\"text/javascript\">
        //<![CDATA[
        <!--
        var rmw_max_width = ".$img_width."; // you can change this number, this is the max width in pixels for posted images
        var rmw_max_height = ".$img_height."; // you can change this number, this is the max hight in pixels for posted images
        var rmw_border_1 = '1px solid ';
        var rmw_border_2 = '2px dotted ';
        var rmw_image_title = '';
        //-->
        //]]>
        </script>
        <script defer=\"defer\" type=\"text/javascript\" src=\"includes/js/rmw_jslib.js\"></script>\n";
    }
}
/*****[END]********************************************
 [ Mod:     Resize Posted Images               v2.4.5 ]
 ******************************************************/

global $more_js;
if (!empty($more_js)) {
    echo $more_js;
}

//DO NOT PUT ANYTHING AFTER THIS LINE
echo "<!--[if IE]><script type=\"text/javascript\">womOn();</script><![endif]-->\n";
?>