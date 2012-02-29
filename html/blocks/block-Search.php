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

$content = "
            <form onsubmit=\"this.submit.disabled='true'\" action=\"modules.php?name=Search\" method=\"post\">
                <table align=\"center\" border=\"0\">
                    <tr>
                        <td align=\"center\">
                            <input type=\"text\" name=\"query\" size=\"15\">
                        </td>
                    </tr>
                    <tr>
                        <td align=\"center\">
                            <input type=\"submit\" value=\""._SEARCH."\">
                        </td>
                    </tr>
                </table>
            </form>
           ";

?>