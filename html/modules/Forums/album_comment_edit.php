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
$comment_id	= request_var('comment_id', 0);

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
// Check feature enabled
// ------------------------------------

if( $album_config['comment'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}


// ------------------------------------
// Check the request
// ------------------------------------

if( isset($HTTP_GET_VARS['comment_id']) )
{
	$comment_id = intval($HTTP_GET_VARS['comment_id']);
}
else if( isset($HTTP_POST_VARS['comment_id']) )
{
	$comment_id = intval($HTTP_POST_VARS['comment_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No comment_id specified');
}


// ------------------------------------
// Get the comment info
// ------------------------------------
$sql = "SELECT *
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query this comment information', '', __LINE__, __FILE__, $sql);
}

$thiscomment = $db->sql_fetchrow($result);

if( empty($thiscomment) )
{
	message_die(GENERAL_ERROR, 'This comment does not exist');
}


// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------

$sql = "SELECT comment_id, comment_pic_id
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query comment and pic information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

$pic_id = $row['comment_pic_id'];


// ------------------------------------
// Get this pic info
// ------------------------------------

$sql = "SELECT p.*, u.user_id, u.username, COUNT(c.comment_id) as comments_count
		FROM ". ALBUM_TABLE ." AS p
			LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
			LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
		WHERE pic_id = '$pic_id'
		GROUP BY p.pic_id
		LIMIT 1";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$total_comments = $thispic['comments_count'];
$comments_per_page = $board_config['posts_per_page'];

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

$album_user_access = album_user_access($thispic['pic_cat_id'], $thiscat, 0, 0, 0, 1, 1, 0);

if( ($album_user_access['comment'] == 0) or ($album_user_access['edit'] == 0) )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("modules.php?name=Your_Account&redirect=album_comment_edit&comment_id=$comment_id", true));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
else
{	
	if( (!$album_user_access['moderator']) or ($userdata['user_level'] != ADMIN) )
	{
		if ($thiscomment['comment_user_id'] != $userdata['user_id'])
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


if( !isset($HTTP_POST_VARS['comment']) )
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               Comments Screen
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) or ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $thispic['user_id']) .'">'. UsernameColor($thispic['username']) .'</a>';
	}

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_comment_body.tpl')
	);

	$template->assign_block_vars('switch_comment_post', array());

	$template->assign_vars(array(
		'CAT_TITLE' => $thiscat['cat_title'],
		'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx?user_id=$user_id"),

		'U_THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=$pic_id"),
		'U_PIC' => append_sid("album_pic.$phpEx?pic_id=$pic_id"),

		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => nl2br($thispic['pic_desc']),
		'POSTER' => $poster,
		'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_COMMENTS' => $total_comments,
		'S_MESSAGE' => $thiscomment['comment_text'],

		'L_PIC_TITLE' => $lang['Pic_Title'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['View'],
		'L_COMMENTS' => $lang['Comments'],

		'L_POST_YOUR_COMMENT' => $lang['Post_your_comment'],
		'L_MESSAGE' => $lang['Message'],
		'L_USERNAME' => $lang['Username'],
		'L_COMMENT_NO_TEXT' => $lang['Comment_no_text'],
		'L_COMMENT_TOO_LONG' => $lang['Comment_too_long'],
		'L_MAX_LENGTH' => $lang['Max_length'],
		'S_MAX_LENGTH' => $album_config['desc_length'],

		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid("album_comment_edit.$phpEx?comment_id=$comment_id")
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
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              Comment Submited
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	$comment_text = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['comment']), 0, $album_config['desc_length'])));

	if( empty($comment_text) )
	{
		message_die(GENERAL_ERROR, $lang['Comment_no_text']);
	}


	// --------------------------------
	// Prepare variables
	// --------------------------------

	$comment_edit_time = time();
	$comment_edit_user_id = $userdata['user_id'];


	// --------------------------------
	// Update the DB
	// --------------------------------

	$sql = "UPDATE ". ALBUM_COMMENT_TABLE ."
			SET comment_text = '$comment_text', comment_edit_time = '$comment_edit_time', comment_edit_count = comment_edit_count + 1, comment_edit_user_id = '$comment_edit_user_id'
			WHERE comment_id = '$comment_id'";

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update comment data', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_comment.$phpEx?comment_id=$comment_id") . '#'.$comment_id.'">')
	);

	$message = $lang['Stored'] . "<br /><br />" . sprintf($lang['Click_view_message'], "<a href=\"" . append_sid("album_comment.$phpEx?comment_id=$comment_id") . "#$comment_id\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}


// +------------------------------------------------------+
// |  Powered by Photo Album 2.x.x (c) 2002-2003 Smartor  |
// +------------------------------------------------------+

?>