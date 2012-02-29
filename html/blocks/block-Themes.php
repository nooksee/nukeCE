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

if (is_user()) {
    $content  = "<div align=\"center\">
                     <form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">
                         <input type=\"hidden\" name=\"chngtheme\" value=\"1\" />
                         <br />"; 
    $content .=          GetThemeSelect('theme', 'user_themes', false, 'onChange=submit();');
    $content .= "
                         <br />
                     </form>
                 </div>";
} else {
    $content  = "<div align=\"center\">
                     <form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">
                         <br />"; 
    $content .=          GetThemeSelect('tpreview', 'user_themes', false, 'onChange=submit();', get_theme(), 0);
    $content .= "
                         <br />
                     </form>
                 </div>
                ";
}

?>