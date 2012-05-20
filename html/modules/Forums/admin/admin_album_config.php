<?php
/***************************************************************************
 *                            admin_album_config.php
 *                             -------------------
 *   begin                : Sunday, February 02, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: admin_album_config.php,v 1.0.5 2004/07/21, 21:50:06 ngoctu Exp $
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
	$module['Photo_Album']['Configuration'] = $filename;
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

//
// Pull all config data
//
$sql = "SELECT * FROM " . ALBUM_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query Album config information", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . ALBUM_CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update Album configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Album_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_album_config'], "<a href=\"" . append_sid("admin_album_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array(
	"body" => "admin/album_config_body.tpl")
);

$template->assign_vars(array(
	'L_ALBUM_CONFIG' => $lang['Album_config'],
	'L_ALBUM_CONFIG_EXPLAIN' => $lang['Album_config_explain'],
	'S_ALBUM_CONFIG_ACTION' => append_sid('admin_album_config.'.$phpEx),

	'MAX_PICS' => $new['max_pics'],
	'MAX_FILE_SIZE' => $new['max_file_size'],
	'MAX_WIDTH' => $new['max_width'],
	'MAX_HEIGHT' => $new['max_height'],
	'ROWS_PER_PAGE' => $new['rows_per_page'],
	'COLS_PER_PAGE' => $new['cols_per_page'],
	'THUMBNAIL_QUALITY' => $new['thumbnail_quality'],
	'THUMBNAIL_SIZE' => $new['thumbnail_size'],
	'PERSONAL_GALLERY_LIMIT' => $new['personal_gallery_limit'],

	'USER_PICS_LIMIT' => $new['user_pics_limit'],
	'MOD_PICS_LIMIT' => $new['mod_pics_limit'],

	'THUMBNAIL_CACHE_ENABLED' => ($new['thumbnail_cache'] == 1) ? 'checked="checked"' : '',
	'THUMBNAIL_CACHE_DISABLED' => ($new['thumbnail_cache'] == 0) ? 'checked="checked"' : '',

	'JPG_ENABLED' => ($new['jpg_allowed'] == 1) ? 'checked="checked"' : '',
	'JPG_DISABLED' => ($new['jpg_allowed'] == 0) ? 'checked="checked"' : '',

	'PNG_ENABLED' => ($new['png_allowed'] == 1) ? 'checked="checked"' : '',
	'PNG_DISABLED' => ($new['png_allowed'] == 0) ? 'checked="checked"' : '',

	'GIF_ENABLED' => ($new['gif_allowed'] == 1) ? 'checked="checked"' : '',
	'GIF_DISABLED' => ($new['gif_allowed'] == 0) ? 'checked="checked"' : '',

	'PIC_DESC_MAX_LENGTH' => $new['desc_length'],

	'HOTLINK_PREVENT_ENABLED' => ($new['hotlink_prevent'] == 1) ? 'checked="checked"' : '',
	'HOTLINK_PREVENT_DISABLED' => ($new['hotlink_prevent'] == 0) ? 'checked="checked"' : '',

	'HOTLINK_ALLOWED' => $new['hotlink_allowed'],

	'PERSONAL_GALLERY_USER' => ($new['personal_gallery'] == ALBUM_USER) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_PRIVATE' => ($new['personal_gallery'] == ALBUM_PRIVATE) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_ADMIN' => ($new['personal_gallery'] == ALBUM_ADMIN) ? 'checked="checked"' : '',

	'PERSONAL_GALLERY_VIEW_ALL' => ($new['personal_gallery_view'] == ALBUM_GUEST) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_VIEW_REG' => ($new['personal_gallery_view'] == ALBUM_USER) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_VIEW_PRIVATE' => ($new['personal_gallery_view'] == ALBUM_PRIVATE) ? 'checked="checked"' : '',

	'RATE_ENABLED' => ($new['rate'] == 1) ? 'checked="checked"' : '',
	'RATE_DISABLED' => ($new['rate'] == 0) ? 'checked="checked"' : '',

	'RATE_SCALE' => $new['rate_scale'],

	'COMMENT_ENABLED' => ($new['comment'] == 1) ? 'checked="checked"' : '',
	'COMMENT_DISABLED' => ($new['comment'] == 0) ? 'checked="checked"' : '',

	'NO_GD' => ($new['gd_version'] == 0) ? 'checked="checked"' : '',
	'GD_V1' => ($new['gd_version'] == 1) ? 'checked="checked"' : '',
	'GD_V2' => ($new['gd_version'] == 2) ? 'checked="checked"' : '',

	'SORT_TIME' => ($new['sort_method'] == 'pic_time') ? 'selected="selected"' : '',
	'SORT_PIC_TITLE' => ($new['sort_method'] == 'pic_title') ? 'selected="selected"' : '',
	'SORT_USERNAME' => ($new['sort_method'] == 'pic_user_id') ? 'selected="selected"' : '',
	'SORT_VIEW' => ($new['sort_method'] == 'pic_view_count') ? 'selected="selected"' : '',
	'SORT_RATING' => ($new['sort_method'] == 'rating') ? 'selected="selected"' : '',
	'SORT_COMMENTS' => ($new['sort_method'] == 'comments') ? 'selected="selected"' : '',
	'SORT_NEW_COMMENT' => ($new['sort_method'] == 'new_comment') ? 'selected="selected"' : '',

	'SORT_ASC' => ($new['sort_order'] == 'ASC') ? 'selected="selected"' : '',
	'SORT_DESC' => ($new['sort_order'] == 'DESC') ? 'selected="selected"' : '',

	'S_GUEST' => ALBUM_GUEST,
	'S_USER' => ALBUM_USER,
	'S_PRIVATE' => ALBUM_PRIVATE,
	'S_MOD' => ALBUM,
	'S_ADMIN' => ALBUM_ADMIN,

	'L_MAX_PICS' => $lang['Max_pics'],
	'L_MAX_FILE_SIZE' => $lang['Max_file_size'],
	'L_MAX_WIDTH' => $lang['Max_width'],
	'L_MAX_HEIGHT' => $lang['Max_height'],
	'L_USER_PICS_LIMIT' => $lang['User_pics_limit'],
	'L_MOD_PICS_LIMIT' => $lang['Moderator_pics_limit'],
	'L_ROWS_PER_PAGE' => $lang['Rows_per_page'],
	'L_COLS_PER_PAGE' => $lang['Cols_per_page'],
	'L_MANUAL_THUMBNAIL' => $lang['Manual_thumbnail'],
	'L_THUMBNAIL_QUALITY' => $lang['Thumbnail_quality'],
	'L_THUMBNAIL_SIZE' => $lang['Thumbnail_size'],
	'L_THUMBNAIL_CACHE' => $lang['Thumbnail_cache'],
	'L_JPG_ALLOWED' => $lang['JPG_allowed'],
	'L_PNG_ALLOWED' => $lang['PNG_allowed'],
	'L_GIF_ALLOWED' => $lang['GIF_allowed'],
	'L_PIC_DESC_MAX_LENGTH' => $lang['Pic_Desc_Max_Length'],
	'L_HOTLINK_PREVENT' => $lang['Hotlink_prevent'],
	'L_HOTLINK_ALLOWED' => $lang['Hotlink_allowed'],
	'L_PERSONAL_GALLERY' => $lang['Personal_gallery'],
	'L_PERSONAL_GALLERY_LIMIT' => $lang['Personal_gallery_limit'],
	'L_PERSONAL_GALLERY_VIEW' => $lang['Personal_gallery_view'],
	'L_RATE_SYSTEM' => $lang['Rate_system'],
	'L_RATE_SCALE' => $lang['Rate_Scale'],
	'L_COMMENT_SYSTEM' => $lang['Comment_system'],
	'L_GD_VERSION' => $lang['GD_version'],
	'L_THUMBNAIL_SETTINGS' => $lang['Thumbnail_Settings'],
	'L_EXTRA_SETTINGS' => $lang['Extra_Settings'],
	'L_DEFAULT_SORT_METHOD' => $lang['Default_Sort_Method'],
	'L_TIME' => $lang['Time'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_USERNAME' => $lang['Sort_Username'],
	'L_VIEW' => $lang['View'],
	'L_RATING' => $lang['Rating'],
	'L_COMMENTS' => $lang['Comments'],
	'L_NEW_COMMENT' => $lang['New_Comment'],
	'L_DEFAULT_SORT_ORDER' => $lang['Default_Sort_Order'],
	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],
	'L_GUEST' => $lang['Forum_ALL'], 
	'L_REG' => $lang['Forum_REG'], 
	'L_PRIVATE' => $lang['Forum_PRIVATE'], 
	'L_MOD' => $lang['Forum_MOD'], 
	'L_ADMIN' => $lang['Forum_ADMIN'],

	'L_DISABLED' => $lang['Disabled'],
	'L_ENABLED' => $lang['Enabled'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'])
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

/* Powered by Photo Album v2.x.x (c) 2002-2003 Smartor */

?>