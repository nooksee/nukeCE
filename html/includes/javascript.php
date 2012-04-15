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

########################################################
# Include for some common javascript/jquery functions  #
########################################################

echo "<script type=\"text/javascript\" src=\"includes/js/onload.js\"></script>\n";

echo "<script type=\"text/javascript\" src=\"includes/js/jquery.js\"></script>\n";

/*****[BEGIN]******************************************
 [ Mod:     Anti-spam                         v.1.1.0 ]
 ******************************************************/
if (!defined('ADMIN_FILE')) {
    echo "<script type=\"text/javascript\" src=\"includes/js/anti-spam.js\"></script>\n";
}
/*****[END]********************************************
 [ Mod:     Anti-spam                         v.1.1.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Open Pop-up Window                v.1.1.0 ]
 ******************************************************/
echo "
      <script type=\"text/javascript\">
          var windowSizeArray = [ 
                                 \"width=200,height=200\",
                                 \"width=350,height=150,scrollbars=yes\",
                                 \"width=300,height=400,scrollbars=yes\",
                                 \"width=300,height=300,scrollbars=yes\",
                                 \"width=400,height=200,scrollbars=yes\",
                                 \"width=1024,height=768,scrollbars=yes\",
                                 \"width=500,height=300\",
                                 \"width=700,height=450\",
                                 \"width=600,height=400,scrollbars=yes\",
                                 \"width=280,height=200,scrollbars=yes\"
                                ];
          $(document).ready(function(){
              $('.newWindow').click(function (event){
                  var url = $(this).attr(\"href\");
                  var windowName = \"popUp\";//$(this).attr(\"name\");
                  var windowSize = windowSizeArray[  $(this).attr(\"rel\")  ];
                  window.open(url, windowName, windowSize);
                  event.preventDefault();
              });
          });
      </script>
     ";
/*****[END]********************************************
 [ Mod:     Open Pop-up Window                v.1.1.0 ]
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
     echo '
           <script type="text/javascript">
               var pwd_strong = "'.PSM_STRONG.'";
               var pwd_stronger = "'.PSM_STRONGER.'";
               var pwd_strongest = "'.PSM_STRONGEST.'";
               var pwd_notrated = "'.PSM_NOTRATED.'";
               var pwd_med = "'.PSM_MED.'";
               var pwd_weak = "'.PSM_WEAK.'";
               var pwd_strength = "'.PSM_CURRENTSTRENGTH.'";
           </script>
          ';
     echo "<script type=\"text/javascript\" src=\"includes/js/chkpwd.js\"></script>";
 }
/*****[END]********************************************
 [ Mod:     Password Strength Meter            v1.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Admin Controls                    v.1.1.0 ]
 ******************************************************/
if (defined('ADMIN_FILE')) {
    echo "
          <script language=\"JavaScript\" type=\"text/javascript\">
              function setSelectOptions(the_form, the_select, do_check) {
                  var selectObject = document.forms[the_form].elements[the_select];
                  var selectCount  = selectObject.length;
                  for (var i = 0; i < selectCount; i++) {
                      selectObject.options[i].selected = do_check;
                  }
                  return true;
              }
          </script>
         ";
}

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

    echo "<script type=\"text/javascript\" src=\"includes/js/coordinates.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"includes/js/drag.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"includes/js/dragdrop.js\"></script>\n";
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

/*****[END]********************************************
 [ Mod:     Admin Controls                    v.1.1.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Base:    Switch Content Script              v3.0.0 ]
 ******************************************************/
global $collapse;
if ($collapse) {
    echo "              
          <script type=\"text/javascript\">
              $(function() {
                  $('tr.parent')
                  .css(\"cursor\",\"pointer\")
                  .attr(\"title\",\"Click to expand/collapse\")
                  .click(function(){
                      $(this).siblings('.child-'+this.id).toggle();
                  });
                  $('tr[@class^=child-]').hide().children('td');
              });
          </script>
         ";
}
/*****[END]********************************************
 [ Base:    Switch Content Script              v3.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Resize Posted Images               v2.4.5 ]
 ******************************************************/
global $img_resize;
if ($img_resize) {
    if((empty($name) || $name == 'News' || $name == 'Reviews' || $name == 'Stories Archive' || $name == 'Downloads' || $name == 'Web Links' || $name == 'Content') && !defined('IN_PHPBB')) {
        global $img_width, $img_height;
        echo "
              <script defer=\"defer\" type=\"text/javascript\">
                  var rmw_max_width = ".$img_width."; // you can change this number, this is the max width in pixels for posted images
                  var rmw_max_height = ".$img_height."; // you can change this number, this is the max hight in pixels for posted images
                  var rmw_border_1 = '1px solid ';
                  var rmw_border_2 = '2px dotted ';
                  var rmw_image_title = '';
              </script>
              <script defer=\"defer\" type=\"text/javascript\" src=\"includes/js/rmw_jslib.js\"></script>
             ";
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