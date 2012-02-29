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

define('CP_INCLUDE_DIR', dirname(dirname(dirname(__FILE__))));
require_once(CP_INCLUDE_DIR.'/includes/showcp.php');

// To have the Copyright window work in your module just fill the following
// required information and then copy the file "copyright.php" into your
// module's directory. It's all, as easy as it sounds ;)
// NOTE: in $download_location PLEASE give the direct download link to the file!!!

$author_name = "Enhanced by sixonetonoffun";
$author_email = "";
$author_homepage = "http://www.netflake.com";
$license = "GNU/GPL";
$download_location = "http://www.netflake.com";
$module_version = "2.0";
$module_description = "<p>This is an updated version of the <a href='http://comuptercops.biz' target='_blank'>Paul Laudanski's </a> V1.51 version and <a href='http://www.nukeresources.com' target='_blank'>Chatserv's Patched Series 2.5</a> incremented to V2.0 to prevent confusion.</p> <p>V2.0 Image Pack Created by the talented <a href='http://www.GanjaUK.com' target='_blank'>Ganja</a>!</p><br />Let have a public Journal to your users. This is a modified version of <a href=\"http://viadome.net\">Joseph Howard's</a> Member's Journal which was based on the original Atomic Journal by <a href=\"http://www.trevor.net\">Trevor Scott</a>. Translation system implementation, SQL abstraction layer and HTML cleanup by <a href=\"http://phpnuke.org\" target=\"new\">Francisco Burzi</a>.";

// DO NOT TOUCH THE FOLLOWING COPYRIGHT CODE. YOU'RE JUST ALLOWED TO CHANGE YOUR "OWN"
// MODULE'S DATA (SEE ABOVE) SO THE SYSTEM CAN BE ABLE TO SHOW THE COPYRIGHT NOTICE
// FOR YOUR MODULE/ADDON. PLAY FAIR WITH THE PEOPLE THAT WORKED CODING WHAT YOU USE!!
// YOU ARE NOT ALLOWED TO MODIFY ANYTHING ELSE THAN THE ABOVE REQUIRED INFORMATION.
// AND YOU ARE NOT ALLOWED TO DELETE THIS FILE NOR TO CHANGE ANYTHING FROM THIS FILE IF
// YOU'RE NOT THIS MODULE'S AUTHOR.

show_copyright($author_name, $author_email, $author_homepage, $license, $download_location, $module_version, $module_description);

?>