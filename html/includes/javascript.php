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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

echo '
      <!-- jQuery -->
      <script src="includes/libraries/jquery.js"></script>
      <!-- Sexy -->
      <script src="includes/libraries/jquery.sexy.js"></script>
      <!-- Colorbox -->
      <script src="includes/libraries/jquery.colorbox.js"></script>
     ';
########################################################
# Include for some common javascript/jquery functions  #
########################################################
echo '
      <script type="text/javascript">
          Sexy.css("includes/css/styles.css");
          Sexy.css("includes/css/colorbox.css");
          Sexy.bundle(
              "includes/js/onload.js",
              "includes/js/popup.js",
              "includes/js/collapse.js"
          );
      </script>
     ';
/*****[BEGIN]******************************************
 [ Mod:     Anti-spam                         v.1.1.0 ]
 ******************************************************/
if (!defined('ADMIN_FILE')) {
    echo '
          <script type="text/javascript">
              Sexy.js("includes/js/anti-spam.js");
          </script>
         ';
}
/*****[END]********************************************
 [ Mod:     Anti-spam                         v.1.1.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Resize Posted Images               v2.4.5 ]
 ******************************************************/
global $img_resize;
if ($img_resize) {
    if((empty($name) || $name == 'News' || $name == 'Journal' || $name == 'Reviews' || $name == 'Stories Archive' || $name == 'Downloads' || $name == 'Web Links' || $name == 'Content') && !defined('IN_PHPBB')) {
        echo '
              <script src="includes/libraries/jquery.resize.js"></script>
              <script src="includes/js/resize.js"></script>
              <script src="includes/js/fullsize.js"></script>
             ';
    }
}
/*****[END]********************************************
 [ Mod:     Resize Posted Images               v2.4.5 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     IE PNG Fix                         v1.0.0 ]
 ******************************************************/
$arcade_on = (isset($_GET['file']) && $_GET['file'] == 'arcade_games') ? true : (isset($_POST['file']) && $_POST['file'] == 'arcade_games') ? true : false;
if (!$arcade_on) {
    $arcade_on = (isset($_GET['do']) && $_GET['do'] == 'newscore') ? true : (isset($_POST['do']) && $_POST['do'] == 'newscore') ? true : false;
}
if (!$arcade_on) {
    echo '
          <script src="includes/libraries/jquery.pngfix.js"></script>
          <script src="includes/js/pngfix.js"></script>
         ';
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
              Sexy.bundle(
                  "includes/js/chkpwd.js",
                  "includes/js/strength.js"
              );
          </script>
         ';
}
/*****[END]********************************************
 [ Mod:     Password Strength Meter            v1.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Admin Controls                    v.1.1.0 ]
 ******************************************************/
if (defined('ADMIN_FILE')) {
    echo '
          <script type="text/javascript">
              Sexy.css("includes/css/admin.css");
              Sexy.js("includes/js/select.js");
          </script>
         ';
}
/*****[BEGIN]******************************************
 [ Mod:     ToolManDHTML                       v1.0.2 ]
 ******************************************************/
if (defined('ADMIN_FILE') && defined('USE_DRAG_DROP')) {
    global $element_ids, $Sajax;
    if(isset($Sajax) && is_object($Sajax)) {
        echo '
              <script type="text/javascript">
                  Sexy.bundle(
                      "includes/libraries/sajax.js",
                      "includes/libraries/json2.stringify.js",
                      "includes/libraries/json_stringify.js",
                      "includes/libraries/json_parse_state.js"
                  );
              </script>
             ';
        ?>
        <script type="text/javascript">
        <?php echo $Sajax->sajax_show_javascript(); ?>
        </script>
        <?php
    }
    $i = 0;
    $list = '';
    if(!is_array($element_ids)) $element_ids = array();
    foreach ($element_ids as $id) {
        if(!$i) {
            $list .= 'var list = document.getElementById("'.$id.'");';
            $i++;
        } else {
            $list .= 'list = document.getElementById("'.$id.'");';
        }
        global $g2;
        $list .= (!$g2) ? "
                           DragDrop.makeListContainer( list, 'g1' );" : "DragDrop.makeListContainer( list, 'g2' );
                           list.onDragOver = function() { this.style[\"background\"] = \"#EEF\"; };
                           list.onDragOut = function() {this.style[\"background\"] = \"none\"; };
                           list.onDragDrop = function() {onDrop(); };
                          ";
    }
    echo '
          <script type="text/javascript">
              Sexy.bundle(
                  "includes/js/coordinates.js",
                  "includes/js/drag.js",
                  "includes/js/dragdrop.js"
              );

              function confirm(z) {
                  window.status = "Sajax version updated";
              }
              
              function create_drag_drop() {
         ';
          ?>
            <?php echo $list; ?>
          <?php
    echo '
              };
              
              if (window.addEventListener) window.addEventListener("load", create_drag_drop, false)
              else if (window.attachEvent) window.attachEvent("onload", create_drag_drop)
              else if (document.getElementById) womAdd("create_drag_drop()");
          </script>
         ';
}
/*****[END]********************************************
 [ Mod:     ToolManDHTML                       v0.0.2 ]
 ******************************************************/

/*****[END]********************************************
 [ Mod:     Admin Controls                    v.1.1.0 ]
 ******************************************************/

global $more_js;
if (!empty($more_js)) {
    echo $more_js;
}

//DO NOT PUT ANYTHING AFTER THIS LINE
echo "<!--[if IE]><script type=\"text/javascript\">womOn();</script><![endif]-->\n";
?>