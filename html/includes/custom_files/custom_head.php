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

/* 
   This file is to customize whatever stuff you need to include in your site 
   when the header loads. This can be used for third party banners, custom
   javascript, popup windows, etc. With this file you don't need to edit 
   system code each time you upgrade to a new version. Just remember, in case
   you add code here to not overwrite this file when updating!
   Whatever you put here will be between <head> and </head> tags.
*/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

global $nukeurl;
echo "<link rel=\"image_src\" type=\"image/jpeg\" href=\"$nukeurl/images/powered/minilogo.jpg\"/>\n";

?>