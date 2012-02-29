<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$display_page = abview_template($template);
$display_page = eregi_replace("</body>", "<hr noshade='noshade' />\n<div align='right'>"._AB_SENTINEL."</div>\n</body>", $display_page);
die($display_page);

?>