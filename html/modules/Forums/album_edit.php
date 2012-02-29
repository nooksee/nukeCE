<?php
/***************************************************************************
 *                              album_edit.php
 *                            -------------------
 *   begin                : Wednesday, February 05, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: album_edit.php,v 2.0.5 2003/04/03 21:13:39 ngoctu Exp $
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

if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

if ($popup != "1") {
        $module_name = basename(dirname(__FILE__));
        require("modules/".$module_name."/nukebb.php");
} else {
        $phpbb_root_path = NUKE_FORUMS_DIR;
}

define('IN_PHPBB', true);
$album_root_path = $phpbb_root_path . 'album/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Check and set various parameters
//
$pic_id	= request_var('pic_id', 0);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
//
// End session management
//

//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);


// ------------------------------------
// Check the request
// ------------------------------------

if( isset($HTTP_GET_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_GET_VARS['pic_id']);
}
else if( isset($HTTP_POST_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_POST_VARS['pic_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No pics specified');
}


// ------------------------------------
// Get this pic info
// ------------------------------------

$sql = "SELECT *
		FROM ". ALBUM_TABLE ."
		WHERE pic_id = '$pic_id'";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if( empty($thispic) )
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist']);
}


// ------------------------------------
// Get the current Category Info
// ------------------------------------

if ($cat_id != PERSONAL_GALLERY)
{
	$sql = "SELECT *
			FROM ". ALBUM_CAT_TABLE ."
			WHERE cat_id = '$cat_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
	}

	$thiscat = $db->sql_fetchrow($result);
}
else
{
	$thiscat = init_personal_gallery_cat($user_id);
}

if (empty($thiscat))
{
	message_die(GENERAL_ERROR, $lang['Category_not_exist']);
}


// ------------------------------------
// Check the permissions
// ------------------------------------

$album_user_access = album_user_access($cat_id, $thiscat, 0, 0, 0, 0, 1, 0); // EDIT

if ($album_user_access['edit'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("modules.php?name=Your_Account&redirect=album_edit&pic_id=$pic_id", true));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
else
{	
	if( (!$album_user_access['moderator']) and ($userdata['user_level'] != ADMIN) )
	{
		if ($thispic['pic_user_id'] != $userdata['user_id'])
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']);
		}
	}
}


/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if( !isset($HTTP_POST_VARS['pic_title']) )
{
	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_edit_body.tpl')
	);

	$template->assign_vars(array(
		'L_EDIT_PIC_INFO' => $lang['Edit_Pic_Info'],

		'CAT_TITLE' => $thiscat['cat_title'],
		'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx?user_id=$user_id"),

		'L_PIC_TITLE' => $lang['Pic_Title'],
		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => $thispic['pic_desc'],

		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_PLAIN_TEXT_ONLY' => $lang['Plain_text_only'],
		'L_MAX_LENGTH' => $lang['Max_length'],

		'L_UPLOAD_NO_TITLE' => $lang['Upload_no_title'],
		'L_DESC_TOO_LONG' => $lang['Desc_too_long'],
		'S_PIC_DESC_MAX_LENGTH' => $album_config['desc_length'],

		'L_RESET' => $lang['Reset'],
		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid("album_edit.$phpEx?pic_id=$pic_id"),
		)
	);

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	// --------------------------------
	// Check posted info
	// --------------------------------

	$pic_title = str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['pic_title'])));

	$pic_desc = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['pic_desc']), 0, $album_config['desc_length'])));

	if( empty($pic_title) )
	{
		message_die(GENERAL_ERROR, $lang['Missed_pic_title']);
	}


	// --------------------------------
	// Update the DB
	// --------------------------------

	$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_title = '$pic_title', pic_desc= '$pic_desc'
			WHERE pic_id = '$pic_id'";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$message = $lang['Pics_updated_successfully'];

	if ($cat_id != PERSONAL_GALLERY)
	{
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_cat.$phpEx?cat_id=$cat_id") . '">')
		);

		$message .= "<br /><br />" . sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>");
	}
	else
	{
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_personal.$phpEx?user_id=$user_id") . '">')
		);

		$message .= "<br /><br />" . sprintf($lang['Click_return_personal_gallery'], "<a href=\"" . append_sid("album_personal.$phpEx?user_id=$user_id") . "\">", "</a>");
	}

	$message .= "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);

}


// +------------------------------------------------------+
// |  Powered by Photo Album 2.x.x (c) 2002-2003 Smartor  |
// +------------------------------------------------------+

?>