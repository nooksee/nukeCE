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

if (is_admin()) {
    global $prefix, $db, $admin_file, $currentlang;

    if (file_exists('language/lang-'.$currentlang.'.php')) {
        include_once('language/lang-'.$currentlang.'.php');
    } else {
        include_once('language/lang-english.php');
    }

    $links = array(
        #Site Admin
        _ADMIN_BLOCK_NUKE => $admin_file.".php",
        #Forums Admin
        _ADMIN_BLOCK_FORUMS => $admin_file.".php?op=forums",
        #Settings
        _ADMIN_BLOCK_SETTINGS => $admin_file.".php?op=Configure",
        #Themes
        _THEMES => $admin_file.".php?op=themes",
        #Cache
        _CACHE_ADMIN => $admin_file.".php?op=cache",
        #Downloads
        _ADMIN_BLOCK_DOWNLOADS => $admin_file.".php?op=DLMain",
        #Sentinel
        _AB_SENTINEL => $admin_file.".php?op=ABMain",
        #News
        _ADMIN_BLOCK_NEWS => $admin_file.".php?op=adminStory",
        #Blocks
        _ADMIN_BLOCK_BLOCKS => $admin_file.".php?op=blocks",
        #Modules
        _ADMIN_BLOCK_MODULES => $admin_file.".php?op=modules",
        #YA
        _ADMIN_BLOCK_YA => $admin_file.".php?op=YAMain",
        #Messages
        _ADMIN_BLOCK_MSGS => $admin_file.".php?op=messages",
        #Database Manager
        _ADMIN_BLOCK_OPTIMIZE_DB => $admin_file.".php?op=database",
        #Clear Cache
        _CACHE_CLEAR => $admin_file.".php?op=cache_clear",
        #Logout
        _ADMIN_BLOCK_LOGOUT => $admin_file.".php?op=logout",
    );
    
    if (is_array($links)) {
        foreach($links as $text => $link) {
            $content .= "
                         &nbsp;
                         <strong>
                             <big>
                                 &middot;
                             </big>
                         </strong>
                         <a href='" . $link . "'>
                             ".$text."
                         </a>
                         <br />
                        ";
        }
    }

    } else {
        global $admin_file;
        $content .= "
                     <div align=\"center\">
                         <strong>
                             "._ADMIN_BLOCK_LOGIN."
                         </strong>
                     </div>
                     <br />
                     <form action=\"".$admin_file.".php\" method=\"post\">
                     <table align=\"center\" border=\"0\">
                         <tr>
                             <td align=\"center\">
                                 "._ADMIN_ID."
                              </td>
                         </tr>
                         <tr>
                              <td align=\"center\">
                                  <input type=\"text\" name=\"aid\" size=\"10\" maxlength=\"25\" />
                              </td>
                          </tr>
                          <tr>
                              <td align=\"center\">
                                  "._ADMIN_PASS."
                              </td>
                          </tr>
                          <tr>
                              <td align=\"center\">
                                  <input type=\"password\" name=\"pwd\" size=\"10\" maxlength=\"40\" />
                              </td>
                          </tr>
                    ";
        $gfxchk = array(1,5,6,7);
        $content .= security_code($gfxchk, 'block');
        $content .= "
                         <tr>
                             <td align=\"center\">
                                 <input type=\"hidden\" name=\"op\" value=\"login\" />
                                 <input type=\"submit\" value=\""._LOGIN."\" />
                             </td>
                         </tr>
                     </table>
                     </form>
                    ";
    }

?>