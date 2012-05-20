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
include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

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
// Check feature enabled
// ------------------------------------

if( $album_config['comment'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}


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
		message_die(GENERAL_ERROR, 'Bad request');
	}
}


// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------

if( isset($comment_id) )
{
	$sql = "SELECT comment_id, comment_pic_id
			FROM ". ALBUM_COMMENT_TABLE ."
			WHERE comment_id = '$comment_id'";

	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query comment and pic information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	if( empty($row) )
	{
		message_die(GENERAL_ERROR, 'This comment does not exist');
	}

	$pic_id = $row['comment_pic_id'];
}


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

if( empty($thispic) )
{
	message_die(GENERAL_ERROR, $lang['Pic_not_exist'] . ' -> ' . $pic_id);
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

$auth_data = album_user_access($cat_id, $thiscat, 1, 0, 0, 1, 1, 1);

if ($auth_data['view'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("modules.php?name=Your_Account&redirect=album_comment&pic_id=$pic_id", true));
		exit;
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
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


	// ------------------------------------
	// Get the comments thread
	// Beware: when this script was called with comment_id (without start)
	// ------------------------------------

	if( !isset($comment_id) )
	{
		if( isset($HTTP_GET_VARS['start']) )
		{
			$start = intval($HTTP_GET_VARS['start']);
		}
		else if( isset($HTTP_POST_VARS['start']) )
		{
			$start = intval($HTTP_POST_VARS['start']);
		}
		else
		{
			$start = 0;
		}
	}
	else
	{
		// We must do a query to co-ordinate this comment
		$sql = "SELECT COUNT(comment_id) AS count
				FROM ". ALBUM_COMMENT_TABLE ."
				WHERE comment_pic_id = $pic_id
					AND comment_id < $comment_id";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain comments information from the database', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		if( !empty($row) )
		{
			$start = floor( $row['count'] / $comments_per_page ) * $comments_per_page;
		}
		else
		{
			$start = 0;
		}
	}

	if( isset($HTTP_GET_VARS['sort_order']) )
	{
		switch ($HTTP_GET_VARS['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
		}
	}
	else if( isset($HTTP_POST_VARS['sort_order']) )
	{
		switch ($HTTP_POST_VARS['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
		}
	}
	else
	{
		$sort_order = 'ASC';
	}

	if ($total_comments > 0)
	{
		$limit_sql = ($start == 0) ? $comments_per_page : $start .','. $comments_per_page;

		$sql = "SELECT c.*, u.user_id, u.username
				FROM ". ALBUM_COMMENT_TABLE ." AS c
					LEFT JOIN ". USERS_TABLE ." AS u ON c.comment_user_id = u.user_id
				WHERE c.comment_pic_id = '$pic_id'
				ORDER BY c.comment_id $sort_order
				LIMIT $limit_sql";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain comments information from the database', '', __LINE__, __FILE__, $sql);
		}

		$commentrow = array();

		while( $row = $db->sql_fetchrow($result) )
		{
			$commentrow[] = $row;
		}

		for ($i = 0; $i < count($commentrow); $i++)
		{
			if( ($commentrow[$i]['user_id'] == ALBUM_GUEST) or ($commentrow[$i]['username'] == '') )
			{
				$poster = ($commentrow[$i]['comment_username'] == '') ? $lang['Guest'] : $commentrow[$i]['comment_username'];
			}
			else
			{
				$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $commentrow[$i]['user_id']) .'">'. UsernameColor($commentrow[$i]['username']) .'</a>';
			}

			if ($commentrow[$i]['comment_edit_count'] > 0)
			{
				$sql = "SELECT c.comment_id, c.comment_edit_user_id, u.user_id, u.username
						FROM ". ALBUM_COMMENT_TABLE ." AS c
							LEFT JOIN ". USERS_TABLE ." AS u ON c.comment_edit_user_id = u.user_id
						WHERE c.comment_id = '".$commentrow[$i]['comment_id']."'
						LIMIT 1";

				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain last edit information from the database', '', __LINE__, __FILE__, $sql);
				}

				$lastedit_row = $db->sql_fetchrow($result);

				$edit_info = ($commentrow[$i]['comment_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];

				$edit_info = '<br /><br />&raquo;&nbsp;'. sprintf($edit_info, $lastedit_row['username'], create_date($board_config['default_dateformat'], $commentrow[$i]['comment_edit_time'], $board_config['board_timezone']), $commentrow[$i]['comment_edit_count']) .'<br />';
			}
			else
			{
				$edit_info = '';
			}
			$commentrow[$i]['comment_text'] = smilies_pass($commentrow[$i]['comment_text']);

			$template->assign_block_vars('commentrow', array(
				'ID' => $commentrow[$i]['comment_id'],
				'POSTER' => $poster,
				'TIME' => create_date($board_config['default_dateformat'], $commentrow[$i]['comment_time'], $board_config['board_timezone']),
				'IP' => ($userdata['user_level'] == ADMIN) ? '-----------------------------------<br />' . $lang['IP_Address'] . ': <a href="http://www.dnsstuff.com/tools/whois/?ip=' . decode_ip($commentrow[$i]['comment_user_ip']) . '" target="_blank">' . decode_ip($commentrow[$i]['comment_user_ip']) .'</a><br />' : '',

				'TEXT' => nl2br($commentrow[$i]['comment_text']),
				'EDIT_INFO' => $edit_info,

				'EDIT' => ( ( $auth_data['edit'] and ($commentrow[$i]['comment_user_id'] == $userdata['user_id']) ) or ($auth_data['moderator'] and ($thiscat['cat_edit_level'] != ALBUM_ADMIN) ) or ($userdata['user_level'] == ADMIN) ) ? '<a href="'. append_sid("album_comment_edit.$phpEx?comment_id=". $commentrow[$i]['comment_id']) .'">'. $lang['Edit_pic'] .'</a>' : '',

				'DELETE' => ( ( $auth_data['delete'] and ($commentrow[$i]['comment_user_id'] == $userdata['user_id']) ) or ($auth_data['moderator'] and ($thiscat['cat_delete_level'] != ALBUM_ADMIN) ) or ($userdata['user_level'] == ADMIN) ) ? '<a href="'. append_sid("album_comment_delete.$phpEx?comment_id=". $commentrow[$i]['comment_id']) .'">'. $lang['Delete_pic'] .'</a>' : ''
				)
			);
		}

		$template->assign_block_vars('switch_comment', array());

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(append_sid("album_comment.$phpEx?pic_id=$pic_id&amp;sort_order=$sort_order"), $total_comments, $comments_per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $comments_per_page ) + 1 ), ceil( $total_comments / $comments_per_page ))
			)
		);
	}

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_comment_body.tpl')
	);

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) or ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $thispic['user_id']) .'">'. UsernameColor($thispic['username']) .'</a>';
	}

	//---------------------------------
	// Comment Posting Form
	//---------------------------------
	if ($auth_data['comment'] == 1)
	{
		$template->assign_block_vars('switch_comment_post', array());

		if( !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('switch_comment_post.logout', array());
		}
	}

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

		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],

		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',

		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid("album_comment.$phpEx?pic_id=$pic_id")
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

	// ------------------------------------
	// Check the permissions: COMMENT
	// ------------------------------------

	if ($auth_data['comment'] == 0)
	{
		if (!$userdata['session_logged_in'])
		{
			redirect(append_sid("modules.php?name=Your_Account&redirect=album_comment&pic_id=$pic_id", true));
		}
		else
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']);
		}
	}

	$comment_text = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['comment']), 0, $album_config['desc_length'])));

	$comment_username = (!$userdata['session_logged_in']) ? str_replace("\'", "''", substr(htmlspecialchars(trim($HTTP_POST_VARS['comment_username'])), 0, 32)) : str_replace("'", "''", htmlspecialchars(trim($userdata['username'])));

	if( empty($comment_text) )
	{
		message_die(GENERAL_ERROR, $lang['Comment_no_text']);
	}


	// --------------------------------
	// Check Pic Locked
	// --------------------------------

	if( ($thispic['pic_lock'] == 1) and (!$auth_data['moderator']) )
	{
		message_die(GENERAL_ERROR, $lang['Pic_Locked']);
	}


	// --------------------------------
	// Check username for guest posting
	// --------------------------------

	if (!$userdata['session_logged_in'])
	{
		if ($comment_username != '')
		{
			$result = validate_username($comment_username);
			if ( $result['error'] )
			{
				message_die(GENERAL_MESSAGE, $result['error_msg']);
			}
		}
	}


	// --------------------------------
	// Prepare variables
	// --------------------------------

	$comment_time = time();
	$comment_user_id = $userdata['user_id'];
	$comment_user_ip = $userdata['session_ip'];


	// --------------------------------
	// Get $comment_id
	// --------------------------------
	$sql = "SELECT MAX(comment_id) AS max
			FROM ". ALBUM_COMMENT_TABLE;

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not found comment_id', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$comment_id = $row['max'] + 1;


	// --------------------------------
	// Insert into DB
	// --------------------------------

	$sql = "INSERT INTO ". ALBUM_COMMENT_TABLE ." (comment_id, comment_pic_id, comment_user_id, comment_username, comment_user_ip, comment_time, comment_text)
			VALUES ('$comment_id', '$pic_id', '$comment_user_id', '$comment_username', '$comment_user_ip', '$comment_time', '$comment_text')";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not insert new entry', '', __LINE__, __FILE__, $sql);
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