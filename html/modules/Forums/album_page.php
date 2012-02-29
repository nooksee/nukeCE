<?php
/***************************************************************************
 *                              album_page.php
 *                            -------------------
 *   begin                : Sunday, February 23, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: album_page.php,v 2.0.1 2003/02/28 23:12:22 ngoctu Exp $
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
	message_die(GENERAL_ERROR, 'No pic_id set');
}


// ------------------------------------ 
// PREVIOUS & NEXT 
// ------------------------------------ 

if( isset($HTTP_GET_VARS['mode']) ) 
{ 
        if( ($HTTP_GET_VARS['mode'] == 'next') or ($HTTP_GET_VARS['mode'] == 'previous') ) 
        { 
                $sql = "SELECT pic_id, pic_cat_id, pic_user_id 
                                FROM ". ALBUM_TABLE ." 
                                WHERE pic_id = $pic_id"; 

                if( !($result = $db->sql_query($sql)) ) 
                { 
                        message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql); 
                } 
		  
		        $row = $db->sql_fetchrow($result); 
				$cur_pic_cat = $row['pic_cat_id'];

                if( empty($row) ) 
                { 
                        message_die(GENERAL_ERROR, 'Bad pic_id'); 
                } 

                $sql = "SELECT new.pic_id, new.pic_time 
                                FROM ". ALBUM_TABLE ." AS new, ". ALBUM_TABLE ." AS cur 
                                WHERE cur.pic_id = $pic_id 
                                        AND new.pic_id <> cur.pic_id 
                                        AND new.pic_cat_id = cur.pic_cat_id"; 

                $sql .= ($HTTP_GET_VARS['mode'] == 'next') ? " AND new.pic_time >= cur.pic_time" : " AND new.pic_time <= cur.pic_time"; 

                $sql .= ($row['pic_cat_id'] == PERSONAL_GALLERY) ? " AND new.pic_user_id = cur.pic_user_id" : ""; 

                $sql .= ($HTTP_GET_VARS['mode'] == 'next') ? " ORDER BY pic_time ASC LIMIT 1" : " ORDER BY pic_time DESC LIMIT 1"; 

                if( !($result = $db->sql_query($sql)) ) 
                { 
                        message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql); 
                } 

                $row = $db->sql_fetchrow($result); 

                $sql = "SELECT min(pic_id), max(pic_id)
                                FROM ". ALBUM_TABLE ."
								WHERE pic_cat_id = $cur_pic_cat"; 

                if( !($result = $db->sql_query($sql)) ) 
                { 
                        message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql); 
                } 

                $next = $db->sql_fetchrow($result);
				
				$first_pic = $next['min(pic_id)'];
				$last_pic = $next['max(pic_id)'];
				
				if( empty($row) AND ($HTTP_GET_VARS['mode'] == 'next')) 
                { 						  
				        redirect(append_sid("album_page.$phpEx?pic_id=$first_pic"));
			    } 
                if( empty($row) AND ($HTTP_GET_VARS['mode'] == 'previous')) 
                { 
                        redirect(append_sid("album_page.$phpEx?pic_id=$last_pic"));
                } 
						
                $pic_id = $row['pic_id']; // NEW pic_id 
        } 
}


// ------------------------------------
// Get this pic info
// ------------------------------------

$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
		FROM ". ALBUM_TABLE ." AS p
			LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
			LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
			LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
		WHERE pic_id = '$pic_id'
		GROUP BY p.pic_id";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

if( empty($thispic) or !file_exists(ALBUM_UPLOAD_PATH . $pic_filename) )
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

$album_user_access = album_user_access($cat_id, $thiscat, 1, 0, 0, 0, 0, 0); // VIEW

if ($album_user_access['view'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("modules.php?name=Your_Account&redirect=album_page&pic_id=$pic_id", true));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}



// ------------------------------------
// Check Pic Approval
// ------------------------------------

if ($userdata['user_level'] != ADMIN)
{
	if( ($thiscat['cat_approval'] == ADMIN) or (($thiscat['cat_approval'] == MOD) and !$album_user_access['moderator']) )
	{
		if ($thispic['pic_approval'] != 1)
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

//
// Start output of page
//
$page_title = $lang['Album'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'album_page_body.tpl')
);

if( ($thispic['pic_user_id'] == ALBUM_GUEST) or ($thispic['username'] == '') )
{
	$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
}
else
{
	$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $thispic['user_id']) .'">'. UsernameColor($thispic['username']) .'</a>';
}


$template->assign_vars(array(
	'CAT_TITLE' => $thiscat['cat_title'],
	'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx?user_id=$user_id"),

	'U_PIC' => append_sid("album_pic.$phpEx?pic_id=$pic_id"),

	'PIC_TITLE' => $thispic['pic_title'],
	'PIC_DESC' => nl2br($thispic['pic_desc']),

	'POSTER' => $poster,

	'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),

	'PIC_VIEW' => $thispic['pic_view_count'],

	'PIC_RATING' => ($thispic['rating'] != 0) ? round($thispic['rating'], 2) : $lang['Not_rated'],

	'PIC_COMMENTS' => $thispic['comments'],

	'U_RATE' => append_sid("album_rate.$phpEx?pic_id=$pic_id"),
	'U_COMMENT' => append_sid("album_comment.$phpEx?pic_id=$pic_id"),

	'U_NEXT' => append_sid("album_page.$phpEx?pic_id=$pic_id&amp;mode=next"),
	'U_PREVIOUS' => append_sid("album_page.$phpEx?pic_id=$pic_id&amp;mode=previous"),

	'L_NEXT_PIC' => $lang['View_next_pic'],
	'L_PREVIOUS_PIC' => $lang['View_previous_pic'],

	'L_RATING' => $lang['Rating'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_PIC_DESC' => $lang['Pic_Desc'],
	'L_POSTER' => $lang['Poster'],
	'L_POSTED' => $lang['Posted'],
	'L_VIEW' => $lang['View'],
	'L_COMMENTS' => $lang['Comments'])
);

if ($album_config['rate'])
{
	$template->assign_block_vars('rate_switch', array());
}

if ($album_config['comment'])
{
	$template->assign_block_vars('comment_switch', array());
}

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);


// +------------------------------------------------------+
// |  Powered by Photo Album 2.x.x (c) 2002-2003 Smartor  |
// +------------------------------------------------------+

?>
