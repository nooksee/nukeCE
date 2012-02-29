<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_BLOCKEDIPMENU;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
$ip_sets = abget_configs();
OpenTable();
OpenMenu(_AB_BLOCKEDIPMENU);
abmenu();
CarryMenu();
blockedipmenu();
CloseMenu();
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>