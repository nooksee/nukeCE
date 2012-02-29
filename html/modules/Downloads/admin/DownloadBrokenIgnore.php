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

$db->sql_query("DELETE FROM ".$prefix."_downloads_mods WHERE lid='$lid' and brokendownload='1'");
$db->sql_query("OPTIMIZE TABLE ".$prefix."_downloads_mods");
redirect($admin_file.".php?op=DownloadBroken");

?>