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

$eid = intval($eid);
$db->sql_query("DELETE FROM ".$prefix."_downloads_extensions WHERE eid='$eid'");
$db->sql_query("OPTIMIZE TABLE ".$prefix."_downloads_extensions");
redirect($admin_file.".php?op=Extensions&min=$min");

?>