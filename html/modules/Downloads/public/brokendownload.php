<?php

/**************************************************************************/
/* PHP-EVOLVED: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.php-evolved.com                                             */
/*                                                                        */
/* All PHP-EVOLVED code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if(!defined('IN_DOWNLOADS')) {
  exit('Access Denied');
}

$lid = intval($lid);
$pagetitle = "- "._REPORTBROKEN;
include_once(NUKE_BASE_DIR.'header.php');
menu(1);
echo "<br />\n";
OpenTable();
echo "<center><span class='option'><strong>"._REPORTBROKEN."</strong></span><br /><br /><br /><span class='content'>\n";
echo "<form action='modules.php?name=$module_name' method='post'>\n";
echo "<input type='hidden' name='lid' value='$lid'>\n";
echo ""._THANKSBROKEN."<br />"._SECURITYBROKEN."<br /><br />\n";
echo "<input type='hidden' name='op' value='brokendownloadS'><input type='submit' value='"._REPORTBROKEN."'></center></form>\n";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>