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
$user_id	= request_var('user_id', 0);

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
if( isset($HTTP_POST_VARS['user_id']) )
{
	$user_id = intval($HTTP_POST_VARS['user_id']);
}
else if( isset($HTTP_GET_VARS['user_id']) )
{
	$user_id = intval($HTTP_GET_VARS['user_id']);
}
else
{
	$user_id = $userdata['user_id'];
}
//
// END check request
//


// ------------------------------------
// Check $user_id
// ------------------------------------

if( ($user_id < 1) and (!$userdata['session_logged_in']) )
{
	redirect(append_sid("modules.php?name=Your_Account&redirect=album_personal", true));
}


// ------------------------------------
// Get the username of this gallery's owner
// ------------------------------------

$sql = "SELECT username
		FROM ". USERS_TABLE ."
		WHERE user_id = $user_id";

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get the username of this category owner', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

$username = $row['username'];

if( empty($username) )
{
	message_die(GENERAL_ERROR, 'Sorry, this user does not exist');
}


// ------------------------------------
// Check Permissions
// ------------------------------------
$personal_gallery_access = personal_gallery_access(1,1);

if( $personal_gallery_access['view'] == 0 )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("modules.php?name=Your_Account&redirect=album_personal&user_id=$user_id", true));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
//
// END check permissions
//


// ------------------------------------
// Check own gallery
// ------------------------------------

if ($user_id == $userdata['user_id'])
{
	if( $personal_gallery_access['upload'] == 0 )
	{
		message_die(GENERAL_MESSAGE, $lang['Not_allowed_to_create_personal_gallery']);
	}
}

//
// End check own gallery
//


// ------------------------------------
// Build the thumbnail page
// ------------------------------------

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

if( isset($HTTP_GET_VARS['sort_method']) )
{
	switch ($HTTP_GET_VARS['sort_method'])
	{
		case 'pic_title':
			$sort_method = 'pic_title';
			break;
		case 'pic_view_count':
			$sort_method = 'pic_view_count';
			break;
		case 'rating':
			$sort_method = 'rating';
			break;
		case 'comments':
			$sort_method = 'comments';
			break;
		case 'new_comment':
			$sort_method = 'new_comment';
			break;
		default:
			$sort_method = $album_config['sort_method'];
	}
}
else if( isset($HTTP_POST_VARS['sort_method']) )
{
	switch ($HTTP_POST_VARS['sort_method'])
	{
		case 'pic_title':
			$sort_method = 'pic_title';
			break;
		case 'pic_view_count':
			$sort_method = 'pic_view_count';
			break;
		case 'rating':
			$sort_method = 'rating';
			break;
		case 'comments':
			$sort_method = 'comments';
			break;
		case 'new_comment':
			$sort_method = 'new_comment';
			break;
		default:
			$sort_method = $album_config['sort_method'];
	}
}
else
{
	$sort_method = $album_config['sort_method'];
}

if( isset($HTTP_GET_VARS['sort_order']) )
{
	switch ($HTTP_GET_VARS['sort_order'])
	{
		case 'ASC':
			$sort_order = 'ASC';
			break;
		case 'DESC':
			$sort_order = 'DESC';
			break;
		default:
			$sort_order = $album_config['sort_order'];
	}
}
else if( isset($HTTP_POST_VARS['sort_order']) )
{
	switch ($HTTP_POST_VARS['sort_order'])
	{
		case 'ASC':
			$sort_order = 'ASC';
			break;
		case 'DESC':
			$sort_order = 'DESC';
			break;
		default:
			$sort_order = $album_config['sort_order'];
	}
}
else
{
	$sort_order = $album_config['sort_order'];
}

$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];


// ------------------------------------
// Count Pics
// ------------------------------------

$sql = "SELECT COUNT(pic_id) AS count
		FROM ". ALBUM_TABLE ."
		WHERE pic_cat_id = ". PERSONAL_GALLERY ."
			AND pic_user_id = $user_id";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not count pics', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

$total_pics = $row['count'];


// ------------------------------------
// Build up
// ------------------------------------

if ($total_pics > 0)
{
	$limit_sql = ($start == 0) ? $pics_per_page : $start .','. $pics_per_page;

	$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_time, p.pic_view_count, p.pic_lock, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments, MAX(c.comment_id) as new_comment
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id = ". PERSONAL_GALLERY ."
				AND p.pic_user_id = $user_id
			GROUP BY p.pic_id
			ORDER BY $sort_method $sort_order
			LIMIT $limit_sql";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pics information', '', __LINE__, __FILE__, $sql);
	}

	$picrow = array();

	while( $row = $db->sql_fetchrow($result) )
	{
		$picrow[] = $row;
	}


	// --------------------------------
	// Thumbnails table
	// --------------------------------

	for ($i = 0; $i < count($picrow); $i += $album_config['cols_per_page'])
	{
		$template->assign_block_vars('picrow', array());

		for ($j = $i; $j < ($i + $album_config['cols_per_page']); $j++)
		{
			if( $j >= count($picrow) )
			{
				$template->assign_block_vars('picrow.nopiccol', array()); 
				$template->assign_block_vars('picrow.picnodetail', array()); 
				continue;
			}

			if(!$picrow[$j]['rating'])
			{
				$picrow[$j]['rating'] = $lang['Not_rated'];
			}
			else
			{
				$picrow[$j]['rating'] = round($picrow[$j]['rating'], 2);
			}

			$template->assign_block_vars('picrow.piccol', array(
				'U_PIC' => append_sid("album_pic.$phpEx?pic_id=". $picrow[$j]['pic_id']),
				'THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=". $picrow[$j]['pic_id']),
				'DESC' => $picrow[$j]['pic_desc']
				)
			);

			$template->assign_block_vars('picrow.pic_detail', array(
				'TITLE' => truncate($picrow[$j]['pic_title'], 12),
				'TIME' => create_date($board_config['default_dateformat'], $picrow[$j]['pic_time'], $board_config['board_timezone']),

				'VIEW' => $picrow[$j]['pic_view_count'],

				'RATING' => ($album_config['rate'] == 1) ? ( '<a href="'. append_sid("album_rate.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '" class="genmed">' . $lang['Rating'] . '</a>: ' . $picrow[$j]['rating'] . '<br />') : '',

				'COMMENTS' => ($album_config['comment'] == 1) ? ( '<a href="'. append_sid("album_comment.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '" class="genmed">' . $lang['Comments'] . '</a>: ' . $picrow[$j]['comments'] . '<br />') : '',

				'EDIT' => ( ($userdata['user_level'] == ADMIN) or ($userdata['user_id'] == $picrow[$j]['pic_user_id']) ) ? '<a href="'. append_sid("album_edit.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '">' . $lang['Edit_pic'] . '</a>' : '',

				'DELETE' => ( ($userdata['user_level'] == ADMIN) or ($userdata['user_id'] == $picrow[$j]['pic_user_id']) ) ? '<a href="'. append_sid("album_delete.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '">' . $lang['Delete_pic'] . '</a>' : '',

				'LOCK' => ($userdata['user_level'] == ADMIN) ? '<a href="'. append_sid("album_cp.$phpEx?mode=". (($picrow[$j]['pic_lock'] == 0) ? 'lock' : 'unlock') ."&amp;pic_id=". $picrow[$j]['pic_id']) .'">'. (($picrow[$j]['pic_lock'] == 0) ? $lang['Lock'] : $lang['Unlock']) .'</a>' : '',

				'IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://www.dnsstuff.com/tools/whois/?ip=' . decode_ip($picrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($picrow[$j]['pic_user_ip']) .'</a><br />' : ''
				)
			);
		}
	}


	// --------------------------------
	// Pagination
	// --------------------------------

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(append_sid("album_personal.$phpEx?user_id=$user_id&amp;sort_method=$sort_method&amp;sort_order=$sort_order"), $total_pics, $pics_per_page, $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pics_per_page ) + 1 ), ceil( $total_pics / $pics_per_page ))
		)
	);
}
else
{
	$template->assign_block_vars('no_pics', array());
}


/*
+----------------------------------------------------------
| Main page...
+----------------------------------------------------------
*/

// ------------------------------------
// additional sorting options
// ------------------------------------

$sort_rating_option = '';
$sort_comments_option = '';
if( $album_config['rate'] == 1 )
{
	$sort_rating_option = '<option value="rating" ';
	$sort_rating_option .= ($sort_method == 'rating') ? 'selected="selected"' : '';
	$sort_rating_option .= '>' . $lang['Rating'] .'</option>';
}
if( $album_config['comment'] == 1 )
{
	$sort_comments_option = '<option value="comments" ';
	$sort_comments_option .= ($sort_method == 'comments') ? 'selected="selected"' : '';
	$sort_comments_option .= '>' . $lang['Comments'] .'</option>';

	$sort_new_comment_option = '<option value="new_comment" ';
	$sort_new_comment_option .= ($sort_method == 'new_comment') ? 'selected="selected"' : '';
	$sort_new_comment_option .= '>' . $lang['New_Comment'] .'</option>';
}


//
// Start output of page
//
$page_title = $lang['Album'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'album_personal_body.tpl')
);

if( $user_id == $userdata['user_id'] )
{
	$template->assign_block_vars('your_personal_gallery', array());
}

$template->assign_vars(array(
	'U_UPLOAD_PIC' => append_sid("album_upload.$phpEx?cat_id=". PERSONAL_GALLERY),
	'UPLOAD_PIC_IMG' => $images['upload_pic'],
	'L_UPLOAD_PIC' => $lang['Upload_Pic'],

	'L_PERSONAL_GALLERY_NOT_CREATED' => sprintf($lang['Personal_gallery_not_created'], $username),

	'S_COLS' => $album_config['cols_per_page'],
	'S_COL_WIDTH' => (100/$album_config['cols_per_page']) . '%',

	'L_VIEW' => $lang['View'],

	'U_PERSONAL_GALLERY' => append_sid("album_personal.$phpEx?user_id=$user_id"),
	'L_YOUR_PERSONAL_GALLERY' => $lang['Your_Personal_Gallery'],
	'L_PERSONAL_GALLERY_EXPLAIN' => $lang['Personal_Gallery_Explain'],

	'L_PERSONAL_GALLERY_OF_USER' => sprintf($lang['Personal_Gallery_Of_User'], $username),

	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],

	'L_TIME' => $lang['Time'],
	'L_PIC_TITLE' => $lang['Pic_Title'],

	'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
	'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
	'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',

	'SORT_RATING_OPTION' => $sort_rating_option,
	'SORT_COMMENTS_OPTION' => $sort_comments_option,
	'SORT_NEW_COMMENT_OPTION' => $sort_new_comment_option,

	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],

	'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
	'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '')
);


//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);


// +------------------------------------------------------+
// |  Powered by Photo Album 2.x.x (c) 2002-2003 Smartor  |
// +------------------------------------------------------+

?>