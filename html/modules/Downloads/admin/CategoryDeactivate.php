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

$crawled = array($cid);
CrawlLevel($cid);
$x=0;
while ($x <= (count($crawled)-1)) {
  $db->sql_query("UPDATE ".$prefix."_downloads_categories SET active='0' WHERE cid='$crawled[$x]'");
  $db->sql_query("UPDATE ".$prefix."_downloads_downloads SET active='0' WHERE cid='$crawled[$x]'");
  $x++;
}

redirect($admin_file.".php?op=Categories&min=$min");

?>