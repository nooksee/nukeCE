<?php
/***************************************************************************
 *							   album_common.php
 *                            -------------------
 *   begin                : Saturday, February 01, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: album_common.php,v 2.0.2 2003/03/03 22:38:24 ngoctu Exp $
 *
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

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}


//
// Include Language
//
$language = $board_config['default_lang'];

if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_main_album.'.$phpEx) )
{
	$language = 'english';
}

include($phpbb_root_path . 'language/lang_' . $language . '/lang_main_album.' . $phpEx);


//
// Get Album Config
//
$sql = "SELECT *
		FROM ". ALBUM_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query Album config information", "", __LINE__, __FILE__, $sql);
}
while( $row = $db->sql_fetchrow($result) )
{
	$album_config_name = $row['config_name'];
	$album_config_value = $row['config_value'];
	$album_config[$album_config_name] = $album_config_value;
}

//
// Set ALBUM Version
//
$template->assign_vars(array(
	'ALBUM_VERSION' => '2' . $album_config['album_version']
	)
);

include($album_root_path . 'album_functions.' . $phpEx);

?>