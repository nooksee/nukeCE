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
global $prefix, $startdate, $db;

$row = $db->sql_fetchrow($db->sql_query("SELECT count FROM ".$prefix."_counter WHERE type='total' AND var='hits'"));
$content = "
            <font class=\"tiny\">
                <div align=\"center\">
                    "._WERECEIVED."
                    <br>
                    <b>
                        <a href=\"modules.php?name=Statistics\">
                            $row[0]
                        </a>
                    </b>
                    <br>
                    "._PAGESVIEWS." $startdate
                </div>
            </font>
           ";

?>