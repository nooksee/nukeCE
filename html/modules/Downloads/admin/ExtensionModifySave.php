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

$db->sql_query("UPDATE ".$prefix."_downloads_extensions SET ext='$xext', file='$xfile', image='$ximage' WHERE eid='$eid'");
redirect($admin_file.".php?op=Extensions&min=$min");

?>