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

$pagetitle = _CATEGORIESADMIN;
$categoryinfo = getcategoryinfo($cid);
include_once(NUKE_BASE_DIR.'header.php');
GraphicAdmin();
title(_CATEGORIESADMIN);
DLadminmain();
echo "<br />\n";
OpenTable();
echo "<center>\n";
echo "<strong>"._EZTHEREIS." ".$categoryinfo['categories']." "._EZSUBCAT." "._EZATTACHEDTOCAT."</strong><br />\n";
echo "<strong>"._EZTHEREIS." ".$categoryinfo['downloads']." "._DOWNLOADS." "._EZATTACHEDTOCAT."</strong><br />\n";
echo "<br /><strong>"._DELEZDOWNLOADSCATWARNING."</strong><br /><br />\n";
echo "[ <a href='".$admin_file.".php?op=CategoryDeleteSave&amp;cid=$cid'>"._YES."</a> | <a href='".$admin_file.".php?op=Categories'>"._NO."</a> ]\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>