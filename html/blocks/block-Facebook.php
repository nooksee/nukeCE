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

global $nukeurl;

$message = _FB_BOOKMARK;
$action = '<iframe src="http://www.facebook.com/plugins/like.php?href='.$nukeurl.'&layout=box_count&show_faces=false&width=350&action=like&colorscheme=light&amp;font=verdana" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:55px; height:65px;"></iframe>';
$content = '
            <br />
            <div align="center">
            <img src="images/blocks/facebook.png" alt="'._FB_BOOKMARK.'" title="'._FB_BOOKMARK.'" />
                <br />
                <br />
                '.$message.'
                <br />
                <br />
                '.$action.'
            </div>
           ';

?>