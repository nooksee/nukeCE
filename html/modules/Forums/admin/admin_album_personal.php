<?php
/***************************************************************************
 *                            admin_album_personal.php
 *                             -------------------
 *   begin                : Sunday, February 02, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: admin_album_personal.php,v 1.0.2 2003/03/05, 19:44:38 ngoctu Exp $
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
	$module['Photo_Album']['Personal_Galleries'] = $filename;
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

if( !isset($HTTP_POST_VARS['submit']) )
{
	$template->set_filenames(array(
		'body' => 'admin/album_personal_body.tpl')
	);

	// Get the list of phpBB usergroups
	$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE ."
			ORDER BY group_name ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get group list", "", __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$groupdata[] = $row;
	}

	// Get the current album settings
	$sql = "SELECT *
			FROM ". ALBUM_CONFIG_TABLE ."
			WHERE config_name = 'personal_gallery_private'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Album info", "", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$private_groups = explode(',', $row['config_value']);

	for($i = 0; $i < count($groupdata); $i++)
	{
		$template->assign_block_vars('grouprow', array(
			'GROUP_ID' => $groupdata[$i]['group_id'],
			'GROUP_NAME' => $groupdata[$i]['group_name'],
			'PRIVATE_CHECKED' => (in_array($groupdata[$i]['group_id'], $private_groups)) ? 'checked="checked"' : ''
			) //end array
		);
	}

	$template->assign_vars(array(
		'L_ALBUM_PERSONAL_TITLE' => $lang['Album_personal_gallery_title'],
		'L_ALBUM_PERSONAL_EXPLAIN' => $lang['Album_personal_gallery_explain'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_GROUP_CONTROL' => $lang['Auth_Control_Group'],
		'L_GROUPS' => $lang['Usergroups'],
		'L_PRIVATE_ACCESS' => $lang['Private_access'],
		'S_ALBUM_ACTION' => append_sid('admin_album_personal.'.$phpEx)
		)
	);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}
else
{
	// Now we update the datatabase
	$private_groups = @implode(',', $HTTP_POST_VARS['private']);

	$sql = "UPDATE ". ALBUM_CONFIG_TABLE ."
			SET config_value = '$private_groups'
			WHERE config_name = 'personal_gallery_private'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update Album config table', '', __LINE__, __FILE__, $sql);
	}

	// okay, return a message... 
	$message = $lang['Album_personal_successfully'] . '<br /><br />' . sprintf($lang['Click_return_album_personal'], '<a href="' . append_sid("admin_album_personal.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

/* Powered by Photo Album v2.x.x (c) 2002-2003 Smartor */

?>