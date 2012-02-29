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

if (!defined('ADMIN_FILE')) {
   die ("Illegal File Access");
}

global $prefix, $db;

if (is_mod_admin()) {

    if (isset($_GET['del']) && $_GET['del'] == 'all') {
        $db->sql_query('DELETE FROM `'.$prefix.'_referer`');
        $db->sql_query('OPTIMIZE TABLE `'.$prefix.'_referer`');
        redirect($admin_file.'.php?op=hreferer');
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        GraphicAdmin();
        OpenTable();
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php?op=hreferer\">
                          "._HTTPREF."
                      </a>
                  </font>
              </div>
             ";
        CloseTable();
        echo "
              <br />
             ";
        OpenTable();
        echo '
              <span class=\"gen\">
              <br />
              </span>
              <div align="center">
                  <span class="genmed">
                      <strong>
                          '._WHOLINKS.'
                      </strong>
                  </span>
              </div>
              <br />
             ';
        $result = $db->sql_query("SELECT `url`, `link` FROM ".$prefix."_referer ORDER by `url`");
        $bgcolor = '';
        if ($db->sql_numrows($result) > 0) {
            echo  "
                   <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"96%\">
                       <caption align=\"bottom\" style=\"padding: 10; font-size: 10pt\">
                           <span class=\"content\">
                               [
                               <a href=\"".$admin_file.".php?op=hreferer&amp;del=all\">
                                   " . _DELETEREFERERS . "
                               </a>
                               ]
                           </span>
                       </caption>
                  ";
            while (list($url, $link) = $db->sql_fetchrow($result)) {
                $bgcolor = ($bgcolor == '') ? ' style="background: '.$bgcolor3.'"' : '';
                echo  "
                       <tr>
                           <td style=\"width: auto; word-break: break-all;\" width=\"100%\" align=\"left\">
                      ";
                $link = (!empty($link) && $link != '/' && $link != '/GET/') ? "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---&gt;&nbsp;".$link : '';
                echo '
                               <div class="content"'.$bgcolor.'>
                                   <a href="'.$url.'" target="_blank">
                                       '.$url."
                                   </a>
                                   ".$link."
                               </div>
                           </td>
                       </tr>
                     ";
           }
            echo "
                  </table>
                  <span class=\"gen\">
                  <br />
                  </span>
                 ";
        } else {
            echo "
                  <div align=\"center\">
                      <span class=\"option\">
                         <b>
                             <em>
                                  ".sprintf(_ERROR_NONE_TO_DISPLAY, strtolower(_HTTPREFERERS))."
                             </em>
                         </b>
                      </span>        
                  </div>
                 ";
        }
        $db->sql_freeresult($result);
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

} else {
    echo 'Access Denied';
}

?>