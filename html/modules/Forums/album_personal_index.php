<?php
/***************************************************************************
 *                         album_personal_index.php
 *                            -------------------
 *   begin                : Monday, February 24, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: album_personal_index.php,v 2.0.3 2003/02/28 11:32:52 ngoctu Exp $
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


$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = 'joined';
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
}

//
// Memberlist sorting
//
$mode_types_text = array($lang['Sort_Joined'], $lang['Sort_Username'], $lang['Pics'], $lang['Last_Pic']);
$mode_types = array('joindate', 'username', 'pics', 'last_pic');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < count($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';


/*
+----------------------------------------------------------
| Start output the page
+----------------------------------------------------------
*/

$page_title = $lang['Album'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'album_personal_index_body.tpl')
);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
  	'L_LAST_PIC_DATE' => $lang['Last_Pic'],	
	'L_JOINED' => $lang['Joined'],
	'L_PICS' => $lang['Pics'],
	'L_USERS_PERSONAL_GALLERIES' => $lang['Users_Personal_Galleries'],
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid("album_personal_index.$phpEx")
	)
);


switch( $mode )
{
	case 'joined':
		$order_by = "user_regdate ASC LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'username':
		$order_by = "username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'pics':
		$order_by = "pics $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'last_pic':
		$order_by = "last_pic $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	default:
		$order_by = "user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

$sql = "SELECT u.username, u.user_id, u.user_regdate, MAX(p.pic_id) as pic_id, p.pic_title, p.pic_user_id, COUNT(p.pic_id) AS pics, MAX(p.pic_time) as pic_time 
      FROM ". USERS_TABLE ." AS u, ". ALBUM_TABLE ." as p 
      WHERE u.user_id <> ". ANONYMOUS ." 
         AND u.user_id = p.pic_user_id 
         AND p.pic_cat_id = ". PERSONAL_GALLERY ." 
      GROUP BY user_id 
      ORDER BY $order_by"; 

if( !($result = $db->sql_query($sql)) ) 
{ 
   message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql); 
} 

$memberrow = array(); 

while( $row = $db->sql_fetchrow($result) ) 
{ 
   $memberrow[] = $row; 
} 


for ($i = 0; $i < count($memberrow); $i++) 
{ 

   $pic_number = $memberrow[$i]['pics'];

   $pic_id = $memberrow[$i]['pic_id'];
$sql = "SELECT * 
         FROM ". ALBUM_TABLE ." 
         WHERE pic_id = '$pic_id'"; 
   if( !($result = $db->sql_query($sql)) ) 
   { 
      message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql); 
   } 
   $thispic = $db->sql_fetchrow($result); 

   $pic_title = $thispic['pic_title']; 

   if( !isset($album_config['last_pic_title_length']) ) 
   { 
      $album_config['last_pic_title_length'] = 50; 
   } 
   $pic_title_full = $pic_title; 
   if (strlen($pic_title) > $album_config['last_pic_title_length']) 
   { 
      $pic_title = substr($pic_title, 0, $album_config['last_pic_title_length']) . '...'; 
   } 

   $last_pic_info = '<br />'. $lang['Pic_Title'] .': <a href="'; 

   $last_pic_info .= ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=". $pic_id) .'" title="' . $pic_title_full . '" target="_blank">' : append_sid("album_page.$phpEx?pic_id=". $pic_id) .'" title="' . $pic_title_full . '" class="genmed">' ; 

   $last_pic_info .= $pic_title .'</a>';
   
   $poster_joined = ( $memberrow[$i]['user_id'] != ANONYMOUS ) ? ' ' . $memberrow[$i]['user_regdate'] : '';
   
   $template->assign_block_vars('memberrow', array( 
      'ROW_CLASS' => ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'], 
      'USERNAME' => UsernameColor($memberrow[$i]['username']), 
      'U_VIEWGALLERY' => append_sid("album_personal.$phpEx?user_id=". $memberrow[$i]['user_id']), 
      'JOINED' => $poster_joined,
      'LAST_PIC' => create_date($board_config['default_dateformat'], $memberrow[$i]['pic_time'], $board_config['board_timezone']) . $last_pic_info,
      'PICS' => $pic_number) 

   ); 
}

$sql = "SELECT COUNT(DISTINCT u.user_id) AS total
		FROM ". USERS_TABLE ." AS u, ". ALBUM_TABLE ." AS p
		WHERE u.user_id <> ". ANONYMOUS ."
			AND u.user_id = p.pic_user_id
			AND p.pic_cat_id = ". PERSONAL_GALLERY;

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting total galleries', '', __LINE__, __FILE__, $sql);
}

if ( $total = $db->sql_fetchrow($result) )
{
	$total_galleries = $total['total'];

	$pagination = generate_pagination("album_personal_index.$phpEx?mode=$mode&amp;order=$sort_order", $total_galleries, $board_config['topics_per_page'], $start). '&nbsp;';
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_galleries / $board_config['topics_per_page'] ))
	)
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