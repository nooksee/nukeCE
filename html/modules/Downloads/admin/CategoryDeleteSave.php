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
  $db->sql_query("DELETE FROM ".$prefix."_downloads_categories WHERE cid='$crawled[$x]'");
  $db->sql_query("DELETE FROM ".$prefix."_downloads_downloads WHERE cid='$crawled[$x]'");
  $x++;
}
$db->sql_query("OPTIMIZE TABLE ".$prefix."_downloads_categories");
$db->sql_query("OPTIMIZE TABLE ".$prefix."_downloads_downloads");
redirect($admin_file.".php?op=Categories");

?>