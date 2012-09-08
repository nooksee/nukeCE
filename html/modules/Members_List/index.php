<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/***************************************************************************
 *                              memberlist.php
 *                            -------------------
 *   begin                : Friday, May 11, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: memberlist.php,v 1.36.2.10 2004/07/11 16:46:15 acydburn Exp $
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

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
-=[Mod]=-
      Advanced Username Color                  v1.0.5       06/11/2005
      Memberlist Find User                     v1.0.0       07/06/2005
      Online/Offline/Hidden (Selection Order)  v1.0.0       08/21/2005
      Online/Offline/Hidden                    v2.2.7       01/24/2006
 ************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
require(NUKE_FORUMS_DIR.'/nukebb.php');

define('IN_PHPBB', true);
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include(NUKE_FORUMS_DIR.'includes/functions_selects.php');

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_VIEWMEMBERS);
init_userprefs($userdata);
//
// End session management
//

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
        $mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
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
/*****[BEGIN]******************************************
 [ Mod:    Selection Order                     v1.0.0 ]
 ******************************************************/
$mode_types_text = array($lang['Sort_Joined'], $lang['Sort_Username'], $lang['Sort_Location'], $lang['Sort_Posts'], $lang['Sort_Email'],  $lang['Sort_Website'], $lang['Sort_Top_Ten'], $lang['Online_status']);
$mode_types = array('joined', 'username', 'location', 'posts', 'email', 'website', 'topten', 'online');
/*****[END]********************************************
 [ Mod:    Selection Order                     v1.0.0 ]
 ******************************************************/

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

//
// Generate page
//
$page_title = $lang['Memberlist'];
include(NUKE_FORUMS_DIR.'includes/page_header.php');

$template->set_filenames(array(
        'body' => 'memberlist_body.tpl')
);
if (is_active("Forums")) {
    make_jumpbox('viewforum.'.$phpEx);
}

$template->assign_vars(array(
        'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
        'L_EMAIL' => $lang['Email'],
        'L_WEBSITE' => $lang['Website'],
        'L_FROM' => $lang['Location'],
        'L_ORDER' => $lang['Order'],
        'L_PRIVATE_MESSAGE' => $lang['Private_Message'],
/*****[BEGIN]******************************************
 [ Mod:     Memberlist Find User               v1.0.0 ]
 ******************************************************/
        'L_LOOK_UP' => $lang['Look_up_User'],
        'L_FIND_USERNAME' => $lang['Find_username'],
        'U_SEARCH_USER' => "modules.php?name=Forums&amp;file=search&amp;mode=searchuser&amp;popup=1",
/*****[END]********************************************
 [ Mod:     Memberlist Find User               v1.0.0 ]
 ******************************************************/
        'L_SORT' => $lang['Sort'],
        'L_SUBMIT' => $lang['Sort'],
        'L_AIM' => $lang['AIM'],
        'L_YIM' => $lang['YIM'],
        'L_MSNM' => $lang['MSNM'],
        'L_ICQ' => $lang['ICQ'],
        'L_JOINED' => $lang['Joined'],
        'L_POSTS' => $lang['Posts'],
/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
        'L_ONLINE_STATUS' => $lang['Online_status'],
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
        'L_PM' => $lang['Private_Message'],

        'S_MODE_SELECT' => $select_sort_mode,
        'S_ORDER_SELECT' => $select_sort_order,
        'S_MODE_ACTION' => append_sid("memberlist.$phpEx"))
);

switch( $mode )
{
        case 'joined':
                $order_by = "user_id $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
        case 'username':
                $order_by = "username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
        case 'location':
                $order_by = "user_from $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
        case 'posts':
                $order_by = "user_posts $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
        case 'email':
                $order_by = "user_email $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
        case 'website':
                $order_by = "user_website $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
        case 'topten':
                $order_by = "user_posts $sort_order LIMIT 10";
                break;
/*****[BEGIN]******************************************
 [ Mod:    Selection Order                     v1.0.0 ]
 ******************************************************/
        case 'online':
                $order_by = "user_session_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
/*****[END]********************************************
 [ Mod:    Selection Order                     v1.0.0 ]
 ******************************************************/
        default:
                $order_by = "user_id $sort_order LIMIT $start, " . $board_config['topics_per_page'];
                break;
}
/*****[BEGIN]******************************************
 [ Mod:     Memberlist Find User               v1.0.0 ]
 [ Mod:     Online/Offline/Hidden              v2.2.7 ]
 ******************************************************/
$username = ( !empty($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : '';

if ( $username && isset($HTTP_POST_VARS['submituser']) )
{
    $sql = "SELECT username, user_id, user_viewemail, user_posts, user_regdate, user_from, user_website, user_email, user_icq, user_aim, user_yim, user_msnm, user_avatar, user_avatar_type, user_allowavatar, user_allow_viewonline, user_session_time
        FROM " . USERS_TABLE . "
        WHERE username = '$username' AND user_id <> " . ANONYMOUS . " LIMIT 1";
}
else
{
    $sql = "SELECT username, user_id, user_viewemail, user_posts, user_regdate, user_from, user_website, user_email, user_icq, user_aim, user_yim, user_msnm, user_avatar, user_avatar_type, user_allowavatar, user_allow_viewonline, user_session_time
        FROM " . USERS_TABLE . "
        WHERE user_id <> " . ANONYMOUS . "
        ORDER BY $order_by";
}
/*****[END]********************************************
 [ Mod:     Online/Offline/Hidden              v2.2.7 ]
 [ Mod:     Memberlist Find User               v1.0.0 ]
 ******************************************************/
if( !($result = $db->sql_query($sql)) )
{
        message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
        $i = 0;
        do
        {
                $username = $row['username'];
                $user_id = intval($row['user_id']);
                if (( $row['user_website'] == "http:///") || ( $row['user_website'] == "http://")){
                    $row['user_website'] =  "";
                }
                if (($row['user_website'] != "" ) && (substr($row['user_website'],0, 7) != "http://")) {
                    $row['user_website'] = "http://".$row['user_website'];
                }
                $row['user_from'] = str_replace(".gif", "", $row['user_from']);
                $from = ( !empty($row['user_from']) ) ? $row['user_from'] : '&nbsp;';
                $joined = $row['user_regdate'];
                $posts = ( $row['user_posts'] ) ? $row['user_posts'] : 0;

                $poster_avatar = '';
                if ( $row['user_avatar_type'] && $user_id != ANONYMOUS && $row['user_allowavatar'] )
                {
                        switch( $row['user_avatar_type'] )
                        {
                                case USER_AVATAR_UPLOAD:
                                        $poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $row['user_avatar'] . '" alt="" border="0" />' : '';
                                        break;
                                case USER_AVATAR_REMOTE:
                                        $poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $row['user_avatar'] . '" alt="" border="0" />' : '';
                                        break;
                                case USER_AVATAR_GALLERY:
                                        $poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $row['user_avatar'] . '" alt="" border="0" />' : '';
                                        break;
                        }
                }

                if ( !empty($row['user_viewemail']) || $userdata['user_level'] == 2 )
                {
                        $email_uri = ( $board_config['board_email_form'] ) ? "modules.php?name=Profile&mode=email&amp;" . POST_USERS_URL .'=' . $user_id : 'mailto:' . $row['user_email'];

                        $email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
                        $email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
                }
                else
                {
                        $email_img = '&nbsp;';
                        $email = '&nbsp;';
                }

                $temp_url = "modules.php?name=Profile&amp;mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id";
                $profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
                $profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

                $temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id");
                if (is_active("Private_Messages"))
                {
                        $pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
                        $pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';
                }

                $www_img = ( $row['user_website'] ) ? '<a href="' . $row['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';
                $www = ( $row['user_website'] ) ? '<a href="' . $row['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

                if ( !empty($row['user_icq']) )
                {
                        $icq_status_img = '<a href="http://wwp.icq.com/' . $row['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $row['user_icq'] . '&amp;img=5" width="18" height="18" border="0" /></a>';
                        $icq_img = '<a href="icq:message?uin=' . $row['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>';
                        $icq =  '<a href="icq:message?uin=' . $row['user_icq'] . '">' . $lang['ICQ'] . '</a>';
                }
                else
                {
                        $icq_status_img = '';
                        $icq_img = '';
                        $icq = '';
                }

                $aim_img = ( $row['user_aim'] ) ? '<a href="aim:goim?screenname=' . $row['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
                $aim = ( $row['user_aim'] ) ? '<a href="aim:goim?screenname=' . $row['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>' : '';

                $temp_url = "modules.php?name=Profile&mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id";
                $msn_img = ( $row['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
                $msn = ( $row['user_msnm'] ) ? '<a href="' . $temp_url . '">' . $lang['MSNM'] . '</a>' : '';

                $yim_img = ( $row['user_yim'] ) ? '<a href="ymsgr:sendIM?' . $row['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
                $yim = ( $row['user_yim'] ) ? '<a href="ymsgr:sendIM?' . $row['user_yim'] . '&amp;.src=pg">' . $lang['YIM'] . '</a>' : '';

                $temp_url = "modules.php?name=Search&search_author=" . urlencode($username) . "&amp;showresults=posts";
                $search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $username) . '" title="' . sprintf($lang['Search_user_posts'], $username) . '" border="0" /></a>';
                $search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $username) . '</a>';
/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
                if ($row['user_session_time'] >= (time()-$board_config['online_time']))
                {
                    if ($row['user_allow_viewonline'])
                    {
                        $online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['icon_online'] . '" alt="' . sprintf($lang['is_online'], $username) . '" title="' . sprintf($lang['is_online'], $username) . '" /></a>';
                        $online_status = '<strong><a href="' . append_sid("viewonline.$phpEx") . '" title="' . sprintf($lang['is_online'], $username) . '"' . $online_color . '>' . $lang['Online'] . '</a></strong>';
                    }
                    else if ($userdata['user_level'] == ADMIN || $userdata['user_id'] == $user_id)
                    {
                        $online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['icon_hidden'] . '" alt="' . sprintf($lang['is_hidden'], $username) . '" title="' . sprintf($lang['is_hidden'], $username) . '" /></a>';
                        $online_status = '<strong><em><a href="' . append_sid("viewonline.$phpEx") . '" title="' . sprintf($lang['is_hidden'], $username) . '"' . $hidden_color . '>' . $lang['Hidden'] . '</a></em></strong>';
                    }
                    else
                    {
                        $online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $username) . '" title="' . sprintf($lang['is_offline'], $username) . '" />';
                        $online_status = '<span title="' . sprintf($lang['is_offline'], $username) . '"' . $offline_color . '><strong>' . $lang['Offline'] . '</strong></span>';
                    }
                }
                else
                {
                    $online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $username) . '" title="' . sprintf($lang['is_offline'], $username) . '" />';
                    $online_status = '<span title="' . sprintf($lang['is_offline'], $username) . '"' . $offline_color . '><strong>' . $lang['Offline'] . '</strong></span>';
                }
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/

                $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
                $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

                $template->assign_block_vars('memberrow', array(
                        'ROW_NUMBER' => $i + ( $start + 1 ),
                        'ROW_COLOR' => '#' . $row_color,
                        'ROW_CLASS' => $row_class,
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                        'USERNAME' => UsernameColor($row['username']),
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                        'FROM' => $from,
                        'JOINED' => $joined,
                        'POSTS' => $posts,
                        'AVATAR_IMG' => $poster_avatar,
                        'PROFILE_IMG' => $profile_img,
                        'PROFILE' => $profile,
                        'SEARCH_IMG' => $search_img,
                        'SEARCH' => $search,
                        'PM_IMG' => $pm_img,
                        'PM' => $pm,
                        'EMAIL_IMG' => $email_img,
                        'EMAIL' => $email,
                        'WWW_IMG' => $www_img,
                        'WWW' => $www,
                        'ICQ_STATUS_IMG' => $icq_status_img,
                        'ICQ_IMG' => $icq_img,
                        'ICQ' => $icq,
                        'AIM_IMG' => $aim_img,
                        'AIM' => $aim,
                        'MSN_IMG' => $msn_img,
                        'MSN' => $msn,
                        'YIM_IMG' => $yim_img,
                        'YIM' => $yim,
/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
                        'ONLINE_STATUS_IMG' => $online_status_img,
                        'ONLINE_STATUS' => $online_status,
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/

                        'U_VIEWPROFILE' => "modules.php?name=Profile&mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id")
                );

                $i++;
        }
        while ( $row = $db->sql_fetchrow($result) );
    $db->sql_freeresult($result);
}
/*****[BEGIN]******************************************
 [ Mod:     Memberlist Find User               v1.0.0 ]
 ******************************************************/
else
{
        $template->assign_block_vars('no_username', array(
            'NO_USER_ID_SPECIFIED' => $lang['No_user_id_specified']    )
        );
}
/*****[END]********************************************
 [ Mod:     Memberlist Find User               v1.0.0 ]
 ******************************************************/

if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
{
        $sql = "SELECT count(*) AS total
                FROM " . USERS_TABLE . "
                WHERE user_id <> " . ANONYMOUS;

        if ( !($result = $db->sql_query($sql)) )
        {
                message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
        }

        if ( $total = $db->sql_fetchrow($result) )
        {
                $total_members = $total['total'];

                $pagination = generate_pagination("memberlist.$phpEx?mode=$mode&amp;order=$sort_order", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
        }
    $db->sql_freeresult($result);
}
else
{
        $pagination = '&nbsp;';
        $total_members = 10;
}

$template->assign_vars(array(
        'PAGINATION' => $pagination,
        'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_members / $board_config['topics_per_page'] )),

        'L_GOTO_PAGE' => $lang['Goto_page'])
);

$template->pparse('body');

include(NUKE_FORUMS_DIR.'includes/page_tail.php');

?>