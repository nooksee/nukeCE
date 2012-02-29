<?php
/***************************************************************************
 *                            admin_album_auth.php
 *                             -------------------
 *   begin                : Tuesday, February 04, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: admin_album_auth.php,v 1.0.2 2003/03/05, 19:45:51 ngoctu Exp $
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
	$module['Photo_Album']['Permissions'] = $filename;
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
	// Build the category selector
	$sql = "SELECT cat_id, cat_title, cat_order
			FROM ". ALBUM_CAT_TABLE ."
			ORDER BY cat_order ASC";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get Category list', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$catrows[] = $row;
	}

	for ($i = 0; $i < count($catrows); $i++)
	{
		$template->assign_block_vars('catrow', array(
			'CAT_ID' => $catrows[$i]['cat_id'],
			'CAT_TITLE' => $catrows[$i]['cat_title'])
		);
	}

	$template->set_filenames(array(
		'body' => 'admin/album_cat_select_body.tpl')
	);

	$template->assign_vars(array(
		'L_ALBUM_AUTH_TITLE' => $lang['Album_Auth_Title'],
		'L_ALBUM_AUTH_EXPLAIN' => $lang['Album_Auth_Explain'],
		'L_SELECT_CAT' => $lang['Select_a_Category'],
		'S_ALBUM_ACTION' => append_sid("admin_album_auth.$phpEx"),
		'L_LOOK_UP_CAT' => $lang['Look_up_Category'])
	);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}
else
{
	if( !isset($HTTP_GET_VARS['cat_id']) )
	{
		$cat_id = intval($HTTP_POST_VARS['cat_id']);

		$template->set_filenames(array(
			'body' => 'admin/album_auth_body.tpl')
		);

		$template->assign_vars(array(
			'L_ALBUM_AUTH_TITLE' => $lang['Album_Auth_Title'],
			'L_ALBUM_AUTH_EXPLAIN' => $lang['Album_Auth_Explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],

			'L_GROUPS' => $lang['Usergroups'],

			'L_VIEW' => $lang['View'],
			'L_UPLOAD' => $lang['Upload'],
			'L_RATE' => $lang['Rate'],
			'L_COMMENT' => $lang['Comment'],
			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => $lang['Delete'],

			'L_IS_MODERATOR' => $lang['Is_Moderator'],
			'S_ALBUM_ACTION' => append_sid("admin_album_auth.$phpEx?cat_id=$cat_id"),
			)
		);

		// Get the list of phpBB usergroups
		$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . TRUE ."
				ORDER BY group_name ASC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get group list', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$groupdata[] = $row;
		}

		// Get info of this cat
		$sql = "SELECT cat_id, cat_title, cat_view_groups, cat_upload_groups, cat_rate_groups, cat_comment_groups, cat_edit_groups, cat_delete_groups, cat_moderator_groups
				FROM ". ALBUM_CAT_TABLE ."
				WHERE cat_id = '$cat_id'";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get Category information', '', __LINE__, __FILE__, $sql);
		}

		$thiscat = $db->sql_fetchrow($result);

		$view_groups = @explode(',', $thiscat['cat_view_groups']);
		$upload_groups = @explode(',', $thiscat['cat_upload_groups']);
		$rate_groups = @explode(',', $thiscat['cat_rate_groups']);
		$comment_groups = @explode(',', $thiscat['cat_comment_groups']);
		$edit_groups = @explode(',', $thiscat['cat_edit_groups']);
		$delete_groups = @explode(',', $thiscat['cat_delete_groups']);

		$moderator_groups = @explode(',', $thiscat['cat_moderator_groups']);

		for ($i = 0; $i < count($groupdata); $i++)
		{
			$template->assign_block_vars('grouprow', array(
				'GROUP_ID' => $groupdata[$i]['group_id'],
				'GROUP_NAME' => $groupdata[$i]['group_name'],

				'VIEW_CHECKED' => (in_array($groupdata[$i]['group_id'], $view_groups)) ? 'checked="checked"' : '',

				'UPLOAD_CHECKED' => (in_array($groupdata[$i]['group_id'], $upload_groups)) ? 'checked="checked"' : '',

				'RATE_CHECKED' => (in_array($groupdata[$i]['group_id'], $rate_groups)) ? 'checked="checked"' : '',

				'COMMENT_CHECKED' => (in_array($groupdata[$i]['group_id'], $comment_groups)) ? 'checked="checked"' : '',

				'EDIT_CHECKED' => (in_array($groupdata[$i]['group_id'], $edit_groups)) ? 'checked="checked"' : '',

				'DELETE_CHECKED' => (in_array($groupdata[$i]['group_id'], $delete_groups)) ? 'checked="checked"' : '',

				'MODERATOR_CHECKED' => (in_array($groupdata[$i]['group_id'], $moderator_groups)) ? 'checked="checked"' : '')
			);
		}

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
		$cat_id = intval($HTTP_GET_VARS['cat_id']);

		$view_groups = @implode(',', $HTTP_POST_VARS['view']);
		$upload_groups = @implode(',', $HTTP_POST_VARS['upload']);
		$rate_groups = @implode(',', $HTTP_POST_VARS['rate']);
		$comment_groups = @implode(',', $HTTP_POST_VARS['comment']);
		$edit_groups = @implode(',', $HTTP_POST_VARS['edit']);
		$delete_groups = @implode(',', $HTTP_POST_VARS['delete']);

		$moderator_groups = @implode(',', $HTTP_POST_VARS['moderator']);

		$sql = "UPDATE ". ALBUM_CAT_TABLE ."
				SET cat_view_groups = '$view_groups', cat_upload_groups = '$upload_groups', cat_rate_groups = '$rate_groups', cat_comment_groups = '$comment_groups', cat_edit_groups = '$edit_groups', cat_delete_groups = '$delete_groups',	cat_moderator_groups = '$moderator_groups'
				WHERE cat_id = '$cat_id'";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update Album config table', '', __LINE__, __FILE__, $sql);
		}

		// okay, return a message... 
		$message = $lang['Album_Auth_successfully'] . '<br /><br />' . sprintf($lang['Click_return_album_auth'], '<a href="' . append_sid("admin_album_auth.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

/* Powered by Photo Album v2.x.x (c) 2002-2003 Smartor */

?>