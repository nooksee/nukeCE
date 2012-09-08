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
$cat_id	= request_var('cat_id', 0);

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
if( isset($HTTP_POST_VARS['cat_id']) )
{
	$cat_id = intval($HTTP_POST_VARS['cat_id']);
}
else if( isset($HTTP_GET_VARS['cat_id']) )
{
	$cat_id = intval($HTTP_GET_VARS['cat_id']);
}
else
{
	message_die(GENERAL_ERROR, 'No categories specified');
}
//
// END check request
//


if ($cat_id == PERSONAL_GALLERY)
{
	redirect(append_sid("album_personal.$phpEx"));
}


// ------------------------------------
// Get this cat info
// ------------------------------------
$sql = "SELECT c.*, COUNT(p.pic_id) AS count
		FROM ". ALBUM_CAT_TABLE ." AS c LEFT JOIN ". ALBUM_TABLE ." AS p ON c.cat_id = p.pic_cat_id
		WHERE c.cat_id <> 0
		GROUP BY c.cat_id
		ORDER BY cat_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
}

$thiscat = array(); // this category
$catrows = array(); // all categories for jumpbox

while( $row = $db->sql_fetchrow($result) )
{
	$album_user_access = album_user_access($row['cat_id'], $row, 1, 0, 0, 0, 0, 0); // VIEW
	if ($album_user_access['view'] == 1)
	{
		$catrows[] = $row;

		if( $row['cat_id'] == $cat_id )
		{
			$thiscat = $row;
			$auth_data = album_user_access($cat_id, $row, 1, 1, 1, 1, 1, 1); // ALL
			$total_pics = $thiscat['count'];
		}
	}
}

//
// END cat info
//


// ------------------------------------
// Check permissions
// ------------------------------------
if( !$auth_data['view'] )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("modules.php?name=Your_Account&redirect=album_cat&cat_id=$cat_id", true));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
//
// END check permissions
//


if (empty($thiscat))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist']);
}


// ------------------------------------
// Build Auth List
// ------------------------------------
$auth_key = array_keys($auth_data);

$auth_list = '';
for ($i = 0; $i < (count($auth_data) - 1); $i++) // ignore MODERATOR in this loop
{
	//
	// we should skip a loop if RATE and COMMENT is disabled
	//
	if( ( ($album_config['rate'] == 0) and ($auth_key[$i] == 'rate') ) or ( ($album_config['comment'] == 0) and ($auth_key[$i] == 'comment') ) )
	{
		continue;
	}

	$auth_list .= ($auth_data[$auth_key[$i]] == 1) ? $lang['Album_'. $auth_key[$i] .'_can'] : $lang['Album_'. $auth_key[$i] .'_cannot'];
	$auth_list .= '<br />';
}

// add Moderator Control Panel here
if( ($userdata['user_level'] == ADMIN) or ($auth_data['moderator'] == 1) )
{
	$auth_list .= sprintf($lang['Album_moderate_can'], '<a href="'. append_sid("album_cp.$phpEx?cat_id=$cat_id") .'">', '</a>');
}

//
// END Auth List
//


// ------------------------------------
// Build Moderators List
// ------------------------------------

$grouprows = array();
$moderators_list = '';

if ($thiscat['cat_moderator_groups'] != '')
{
	// Get the namelist of moderator usergroups
	$sql = "SELECT group_id, group_name, group_type, group_single_user
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> 1
				AND group_type <> ". GROUP_HIDDEN ."
				AND group_id IN (". $thiscat['cat_moderator_groups'] .")
			ORDER BY group_name ASC";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get group list', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$grouprows[] = $row;
	}

	if( count($grouprows) > 0 )
	{
		for ($j = 0; $j < count($grouprows); $j++)
		{
			$group_link = '<a href="'. append_sid("groupcp.$phpEx?". POST_GROUPS_URL .'='. $grouprows[$j]['group_id']) .'">'. GroupColor($grouprows[$j]['group_name']) .'</a>';

			$moderators_list .= ($moderators_list == '') ? $group_link : ', ' . $group_link;
		}
	}
}

if( empty($moderators_list) )
{
	$moderators_list = $lang['None'];
}
//
// END Moderator List
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
   case 'pic_time':
         $sort_method = 'pic_time';
         break;
      case 'pic_title':
         $sort_method = 'pic_title';
         break;
      case 'username':
         $sort_method = 'username';
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
      case 'pic_time':
         $sort_method = 'pic_time';
         break;
      case 'pic_title':
         $sort_method = 'pic_title';
         break;
      case 'username':
         $sort_method = 'username';
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

if ($total_pics > 0)
{
	$limit_sql = ($start == 0) ? $pics_per_page : $start .','. $pics_per_page;

	$pic_approval_sql = 'AND p.pic_approval = 1';
	if ($thiscat['cat_approval'] != ALBUM_USER)
	{
		if( ($userdata['user_level'] == ADMIN) or (($auth_data['moderator'] == 1) and ($thiscat['cat_approval'] == ALBUM)) )
		{
			$pic_approval_sql = '';
		}
	}

	$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, p.pic_lock, p.pic_approval, u.user_id , u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments, MAX(c.comment_id) as new_comment
            FROM ". ALBUM_TABLE ." AS p
                LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
                LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
                LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id 
            WHERE p.pic_cat_id = '$cat_id' 
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
    
    $tot_unapproved = 0;
    for ($i = 0 ; $i < count($picrow); $i++ )
    {
        if ($picrow[$i]['pic_approval'] == 0 ) $tot_unapproved++ ;
    }

	$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, p.pic_lock, p.pic_approval, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments, MAX(c.comment_id) as new_comment
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id = '$cat_id' $pic_approval_sql
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

			if ($thiscat['cat_approval'] != ALBUM_USER)
			{
				if( ($userdata['user_level'] == ADMIN) or (($auth_data['moderator'] == 1) and ($thiscat['cat_approval'] == ALBUM)) )
				{
					$approval_mode = ($picrow[$j]['pic_approval'] == 0) ? 'approval' : 'unapproval';

					$approval_link = '<a href="'. append_sid("album_cp.$phpEx?mode=$approval_mode&amp;pic_id=". $picrow[$j]['pic_id']) .'">';

					$approval_link .= ($picrow[$j]['pic_approval'] == 0) ? '<b>'. $lang['Approve'] .'</b>' : $lang['Unapprove'];

					$approval_link .= '</a>';
				}
			}

			$template->assign_block_vars('picrow.piccol', array(
				'U_PIC' => append_sid("album_pic.$phpEx?pic_id=". $picrow[$j]['pic_id']),
				'THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=". $picrow[$j]['pic_id']),
				'DESC' => $picrow[$j]['pic_desc'],
				'APPROVAL' => $approval_link,
				)
			);

			if( ($picrow[$j]['user_id'] == ALBUM_GUEST) or ($picrow[$j]['username'] == '') )
			{
				$pic_poster = ($picrow[$j]['pic_username'] == '') ? $lang['Guest'] : $picrow[$j]['pic_username'];
			}
			else
			{
				$pic_poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $picrow[$j]['user_id']) .'">'. UsernameColor($picrow[$j]['username']) .'</a>';
			}

			$template->assign_block_vars('picrow.pic_detail', array(
				'TITLE' => truncate($picrow[$j]['pic_title'], 12),
				'POSTER' => $pic_poster,
				'TIME' => create_date($board_config['default_dateformat'], $picrow[$j]['pic_time'], $board_config['board_timezone']),

				'VIEW' => $picrow[$j]['pic_view_count'],

				'RATING' => ($album_config['rate'] == 1) ? ( '<a href="'. append_sid("album_rate.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '" class="genmed">' . $lang['Rating'] . '</a>: ' . $picrow[$j]['rating'] . '<br />') : '',

				'COMMENTS' => ($album_config['comment'] == 1) ? ( '<a href="'. append_sid("album_comment.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '" class="genmed">' . $lang['Comments'] . '</a>: ' . $picrow[$j]['comments'] . '<br />') : '',

				'EDIT' => ( ( $auth_data['edit'] and ($picrow[$j]['pic_user_id'] == $userdata['user_id']) ) or ($auth_data['moderator'] and ($thiscat['cat_edit_level'] != ALBUM_ADMIN) ) or ($userdata['user_level'] == ADMIN) ) ? '<a href="'. append_sid("album_edit.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '">' . $lang['Edit_pic'] . '</a>' : '',

				'DELETE' => ( ( $auth_data['delete'] and ($picrow[$j]['pic_user_id'] == $userdata['user_id']) ) or ($auth_data['moderator'] and ($thiscat['cat_delete_level'] != ALBUM_ADMIN) ) or ($userdata['user_level'] == ADMIN) ) ? '<a href="'. append_sid("album_delete.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '">' . $lang['Delete_pic'] . '</a>' : '',

				'MOVE' => ($auth_data['moderator']) ? '<a href="'. append_sid("album_cp.$phpEx?mode=move&amp;pic_id=". $picrow[$j]['pic_id']) .'">'. $lang['Move'] .'</a>' : '',

				'LOCK' => ($auth_data['moderator']) ? '<a href="'. append_sid("album_cp.$phpEx?mode=". (($picrow[$j]['pic_lock'] == 0) ? 'lock' : 'unlock') ."&amp;pic_id=". $picrow[$j]['pic_id']) .'">'. (($picrow[$j]['pic_lock'] == 0) ? $lang['Lock'] : $lang['Unlock']) .'</a>' : '',

				'IP' => ($userdata['user_level'] == ADMIN) ? $lang['IP_Address'] . ': <a href="http://www.dnsstuff.com/tools/whois/?ip=' . decode_ip($picrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($picrow[$j]['pic_user_ip']) .'</a><br />' : ''
				)
			);
		}
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(append_sid("album_cat.$phpEx?cat_id=$cat_id&amp;sort_method=$sort_method&amp;sort_order=$sort_order"), $total_pics, $pics_per_page, $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pics_per_page ) + 1 ), ceil( $total_pics / $pics_per_page ))
		)
	);
}
else
{
	$template->assign_block_vars('no_pics', array());
}
//
// END thumbnails table
//

/*****[BEGIN]******************************************
 [ Mod:     Waiting Approval                   v1.2.3 ]
 ******************************************************/
if( ($userdata['user_level'] == ADMIN) or ($auth_data['moderator'] == 1) ) {
    $waiting_approval = $tot_unapproved;
    if ( $waiting_approval == 0 ) {
        $waiting_approval = sprintf($lang['Pic_approval_none_cp'],$waiting_approval);
    } else {
        $waiting_approval = sprintf(( ($waiting_approval == 1) ? $lang['Pic_approval_one_cp'] : $lang['Pic_approval_many_cp']), $waiting_approval);
        $waiting_approval = '<b>' . $waiting_approval . '</strong>';
    }
    $waiting_link = '<a href="' . append_sid("album_cp.$phpEx?cat_id=$cat_id") . '">' . $waiting_approval . '</a>';
} else {
    $waiting_link = '';
}
/*****[END]********************************************
 [ Mod:     Waiting Approval                   v1.2.3 ]
 ******************************************************/

// ------------------------------------
// Build Jumpbox - based on $catrows which was created at the top of this file
// ------------------------------------
$album_jumpbox = '<form name="jumpbox" action="'. append_sid("album_cat.$phpEx") .'" method="post">';
$album_jumpbox .= $lang['Jump_to'] . ':&nbsp;<select name="cat_id" onChange="forms[\'jumpbox\'].submit()">';
for ($i = 0; $i < count($catrows); $i++)
{
	$album_jumpbox .= '<option value="'. $catrows[$i]['cat_id'] .'"';
	$album_jumpbox .= ($catrows[$i]['cat_id'] == $cat_id) ? 'selected="selected"' : '';
	$album_jumpbox .= '>' . $catrows[$i]['cat_title'] .'</option>';
}
$album_jumpbox .= '</select>';
$album_jumpbox .= '&nbsp;<input type="submit" class="liteoption" value="'. $lang['Go'] .'" />';
$album_jumpbox .= '<input type="hidden" name="sid" value="'. $userdata['session_id'] .'" />';
$album_jumpbox .= '</form>';
//
// END build jumpbox
//


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
	'body' => 'album_cat_body.tpl')
);

$template->assign_vars(array(
	'U_VIEW_CAT' => append_sid("album_cat.$phpEx?cat_id=$cat_id"),
	'CAT_TITLE' => $thiscat['cat_title'],

	'L_MODERATORS' => $lang['Moderators'],
	'MODERATORS' => $moderators_list,

	'U_UPLOAD_PIC' => append_sid("album_upload.$phpEx?cat_id=$cat_id"),
	'UPLOAD_PIC_IMG' => $images['upload_pic'],
	'L_UPLOAD_PIC' => $lang['Upload_Pic'],

	'L_CATEGORY' => $lang['Category'],

	'L_NO_PICS' => $lang['No_Pics'],
/*****[BEGIN]******************************************
 [ Mod:     Waiting Approval                   v1.2.3 ]
 ******************************************************/
        'WAITING' => $waiting_link,
/*****[END]********************************************
 [ Mod:     Waiting Approval                   v1.2.3 ]
 ******************************************************/
	'S_COLS' => $album_config['cols_per_page'],
	'S_COL_WIDTH' => (100/$album_config['cols_per_page']) . '%',

	'L_VIEW' => $lang['View'],
	'L_POSTER' => $lang['Poster'],

	'ALBUM_JUMPBOX' => $album_jumpbox,

	'S_ALBUM_ACTION' => append_sid("album_cat.$phpEx?cat_id=$cat_id"),

	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],

	'L_TIME' => $lang['Time'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_USERNAME' => $lang['Sort_Username'],

	'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
	'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
	'SORT_USERNAME' => ($sort_method == 'username') ? 'selected="selected"' : '',
	'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',

	'SORT_RATING_OPTION' => $sort_rating_option,
	'SORT_COMMENTS_OPTION' => $sort_comments_option,
	'SORT_NEW_COMMENT_OPTION' => $sort_new_comment_option,

	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],

	'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
	'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',

	'S_AUTH_LIST' => $auth_list)
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