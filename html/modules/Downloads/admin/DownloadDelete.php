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

list($sname) = $db->sql_fetchrow($db->sql_query("SELECT submitter FROM ".$prefix."_downloads_downloads WHERE lid='$lid'"));
$db->sql_query("UPDATE ".$prefix."_downloads_accesses SET uploads=uploads-1 WHERE username='$sname'");
$db->sql_query("DELETE FROM ".$prefix."_downloads_downloads WHERE lid='$lid'");
$db->sql_query("OPTIMIZE TABLE ".$prefix."_downloads_downloads");
redirect($admin_file.".php?op=Downloads&min=$min");

?>