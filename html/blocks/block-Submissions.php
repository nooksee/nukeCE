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

if(!defined('NUKE_CE')) exit;

$content = '';

if (is_admin()) {

      global $currentlang;
      if (file_exists(NUKE_LANGUAGE_DIR.'/lang-'.$currentlang.'.php')) {
          include_once(NUKE_LANGUAGE_DIR.'/lang-'.$currentlang.'.php');
      } else {
          include_once(NUKE_LANGUAGE_DIR.'/lang-english.php');
      }

      $handle = opendir(NUKE_MODULES_DIR);
      while(false !== ($module = readdir($handle))) {
          if (is_active($module) && file_exists("modules/$module/admin/wait.php")) {
              $submissions[$module] = "modules/$module/admin/wait.php";
          }
      }
      closedir($handle);
      if(is_array($submissions)) {
          ksort($submissions);
          foreach($submissions as $module => $file) {
              require_once($file);
          }
      }

} else {
      $content .= '
                   <div align="center">
                       <strong>
                           '._ADMIN_BLOCK_DENIED.'
                       </strong>
                   </div>
                  ';
}

?>