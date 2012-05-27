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

define('RSS_FEED', true);
define('NO_SENTINEL', true);
define('NO_SECURITY', true);

require_once(dirname(__FILE__) . '/mainfile.php');
include_once(NUKE_INCLUDE_DIR.'counter.php');

if(isset($feed) && !preg_match("/[\W]/i", $feed)) {
    $feed = htmlentities(addslashes($feed));
    if(file_exists(NUKE_RSS_DIR.$feed.'.php')) {
        include_once(NUKE_RSS_DIR.$feed.'.php');
    } else {
        exit(_NORSS);
    }
} else {
    include_once(NUKE_RSS_DIR.'news.php');
}

?>