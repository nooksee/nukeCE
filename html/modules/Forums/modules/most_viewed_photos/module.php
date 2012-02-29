<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('IN_PHPBB'))
{
    die('Hacking attempt');
}

//
// Most Viewed Photos
//

$core->start_module(true);

$core->set_content('statistical');

$core->set_view('rows', $core->return_limit);
$core->set_view('columns', 5);

$core->define_view('set_columns', array(
	$core->pre_defined('rank'),
	'photo' => $lang['photo'],
	'views' => $lang['views'],
	'posted' => $lang['start_date'],
	'desc' => $lang['desc'])
);

$core->set_header($lang['module_name']);

$core->assign_defined_view('align_rows', array(
	'left',
	'center',
	'center',
	'left',
	'left')
);

$core->assign_defined_view('width_rows', array(
	'5%',
	'',
	'5%',
	'30%',
	'')
);

// check if mod installed and if yes, do the apropreate actions
$sql = "SELECT pic_user_id, pic_username, pic_time, pic_user_id, pic_cat_id FROM " . ALBUM_TABLE . ""; 
if (!$result = $db->sql_query($sql))
{
	$there=0;
}
else
{
	$there=1;
	if (file_exists($phpbb_root_path.'album/album_hierarchy_auth.php'))
	{
		if (!$allowed_cat)
		{
			// Get general album information
			$album_root_path = $phpbb_root_path . ALBUM_MOD_PATH . '';
			include_once($album_root_path . 'album_common.' . $phpEx);

			$album_view_mode = '';

			$album_user_id = ALBUM_PUBLIC_GALLERY;
			$catrows = array ();
			$options = ($album_view_mode == ALBUM_VIEW_LIST ) ? ALBUM_READ_ALL_CATEGORIES|ALBUM_AUTH_VIEW : ALBUM_AUTH_VIEW;
			$catrows = album_read_tree($album_user_id, $options);
			// --------------------------------
			// Build allowed category-list (for recent pics after here)
			// $catrows array now stores all categories which this user can view.
			// --------------------------------
			$allowed_cat = ''; // For Recent Public Pics below
			for ($i = 0; $i < count($catrows); $i ++)
			{
				// --------------------------------
				// build list of allowd category id's
				// --------------------------------
				$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ','.$catrows[$i]['cat_id'];
			}
		}
	}
	else
	{
		if (!$allowed_cat)
		{
			// Get general album information
			$album_root_path = $phpbb_root_path . 'album/';
			include_once($album_root_path . 'album_common.' . $phpEx);

			$sql = "SELECT c.*, COUNT(p.pic_id) AS count
					FROM ". ALBUM_CAT_TABLE ." AS c
						LEFT JOIN ". ALBUM_TABLE ." AS p ON c.cat_id = p.pic_cat_id
					WHERE cat_id <> 0
					GROUP BY cat_id
					ORDER BY cat_order ASC";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
			}

			$catrows = array();

			while( $row = $db->sql_fetchrow($result) )
			{
				$album_user_access = album_user_access($row['cat_id'], $row, 1, 0, 0, 0, 0, 0); // VIEW
				if ($album_user_access['view'] == 1)
				{
					$catrows[] = $row;
				}
			}
			$allowed_cat = ''; // For Recent Public Pics below
			//
			// $catrows now stores all categories which this user can view. Dump them out!
			//
			for ($i = 0; $i < count($catrows); $i++)
			{
				// --------------------------------
				// Build allowed category-list (for recent pics after here)
				// --------------------------------

				$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ',' . $catrows[$i]['cat_id'];
			}
		}
	}
}
if ($there == 0)
{
	message_die(GENERAL_MESSAGE, 'This module requires Photo Album to be installed.');
}

        $user_id = intval($userdata['user_id']);

$sql = "SELECT  u.user_timezone, u.user_dateformat, p.pic_id, p.pic_title, p.pic_thumbnail, p.pic_time, p.pic_user_id, p.pic_view_count, p.pic_username, p.pic_desc 
		FROM " . USERS_TABLE . " AS u, " . ALBUM_CAT_TABLE . " AS c 
		LEFT JOIN " . ALBUM_TABLE . " AS p ON c.cat_id = p.pic_cat_id 
		WHERE (p.pic_user_id <> " . ANONYMOUS . ")
		AND ( p.pic_approval = 1 )
		AND c.cat_id IN ($allowed_cat)
		AND p.pic_cat_id IN ($allowed_cat)
		AND u.user_id = " . $user_id . "
		ORDER BY p.pic_view_count DESC
		LIMIT " . $core->return_limit;

$result = $core->sql_query($sql, 'Couldn\'t retrieve album post data');

$lastposts = $core->sql_fetchrowset($result);

$core->set_data($lastposts);

$core->define_view('set_rows', array(
	'$core->pre_defined()',
	'$core->generate_image_link(append_sid(\'album_thumbnail.php?pic_id=\' . $core->data(\'pic_id\'), $core->data(\'pic_desc\'), \'border="0"\'))',
	'$core->data(\'pic_view_count\')',
	'create_date($core->data(\'user_dateformat\'), $core->data(\'pic_time\'), $core->data(\'user_timezone\')).$lang[\'br\'].$core->generate_link(append_sid(\'album_page.php?pic_id=\' . $core->data(\'pic_id\')), $core->data(\'pic_id\'), \'target="_blank"\')." ".$core->generate_link(append_sid(\'album_page.php?pic_id=\' . $core->data(\'pic_id\')), $core->data(\'pic_title\'), \'target="_blank"\').$lang[\'br\'].$core->generate_link(append_sid(\'profile.php?mode=viewprofile&u=\' . $core->data(\'pic_user_id\')), $core->data(\'pic_username\'), \'target="_blank"\')',
	'$core->data(\'pic_desc\')')
);

$core->run_module();