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

$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_extensions WHERE ext='$xext'"));
if ($numrows>0) {
  $pagetitle = _EXTENSIONSADMIN.": "._DL_ERROR;
  include_once(NUKE_BASE_DIR.'header.php');
  GraphicAdmin();
  title($pagetitle);
  DLadminmain();
  echo "<br />\n";
  OpenTable();
  echo "<center><strong>"._ERRORTHEEXTENSION." $xext "._ALREADYEXIST."</strong></center><br />\n";
  echo "<center>"._GOBACK."</center>\n";
  CloseTable();
  include_once(NUKE_BASE_DIR.'header.php');
  GraphicAdmin();
  } else {
  $db->sql_query("INSERT INTO ".$prefix."_downloads_extensions VALUES (NULL, '$xext', '$xfile', '$ximage')");
  redirect($admin_file.".php?op=Extensions");
}

?>