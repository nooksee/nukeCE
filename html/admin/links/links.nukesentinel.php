<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(!isset($admin_file)) { $admin_file = 'admin'; }
if(!defined('ADMIN_FILE')) { die('Illegal Access Detected!!'); }

global $admin_file;

if ($radminsuper==1 AND defined('NUKESENTINEL_IS_LOADED')) {
    adminmenu($admin_file.'.php?op=ABMain', _AB_NUKESENTINELICON, 'nukesentinel.png');
}

?>