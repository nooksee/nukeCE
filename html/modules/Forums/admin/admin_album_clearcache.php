<?php
/***************************************************************************
 *                          admin_album_clearcache.php
 *                             -------------------
 *   begin                : Thursday, February 06, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: admin_album_clearcache.php,v 1.0.0 2003/02/06, 21:16:46 ngoctu Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Photo_Album']['Clear_Cache'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main_album.' . $phpEx);
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_album.' . $phpEx);


$cache_dir = @opendir('../' . ALBUM_CACHE_PATH);

while( $cache_file = @readdir($cache_dir) )
{
        if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
        {
                @unlink('../' . ALBUM_CACHE_PATH . $cache_file);
        }
}

@closedir($cache_dir);

message_die(GENERAL_MESSAGE, $lang['Thumbnail_cache_cleared_successfully']);

/* Powered by Photo Album v2.x.x (c) 2002-2003 Smartor */

?>