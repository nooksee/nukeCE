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

if (!defined('IN_PHPBB'))
{
    die('Hacking attempt');
}

define('HEADER_INC', TRUE);

global $name, $sitename, $is_inline_review, $prefix, $db, $cache;

OpenTable();

/*****[BEGIN]******************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/
if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_adv_time.' . $phpEx)) )
{
    include_once($phpbb_root_path . 'language/lang_english/lang_adv_time.' . $phpEx);
} else
{
    include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_adv_time.' . $phpEx);
}

if ( ($userdata['user_id'] != ANONYMOUS && $userdata['user_time_mode'] >= 4)
  || ($userdata['user_id'] == ANONYMOUS && $board_config['default_time_mode'] >= 4) )
{
    global $pc_dateTime, $HTTP_SESSION_VARS, $HTTP_GET_VARS;
    if ( !isset($pc_dateTime['pc_timezoneOffset']) && !isset($HTTP_GET_VARS['pc_tzo']) )
    {
        $template->assign_block_vars('switch_send_pc_dateTime', array());
        if ( $userdata['user_pc_timeOffsets'] != '0' )
        {
            $template->assign_block_vars('switch_valid_time', array());
        }
    } else
    {
        $template->assign_block_vars('switch_valid_time', array());
    }
} else
{
    $template->assign_block_vars('switch_valid_time', array());
}
/*****[END]********************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/

//
// Parse and show the overall header.
//
$template->set_filenames(array(
        'overall_header' => ( empty($gen_simple_header) ) ? 'overall_header.tpl' : 'simple_header.tpl')
);

//
// Generate logged in/logged out status
//
if ( $userdata['session_logged_in'] )
{
        $u_login_logout = 'modules.php?name=Your_Account&amp;op=logout&amp;redirect=Forums';
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
        $l_login_logout = $lang['Logout'] . ' [ ' . UsernameColor($userdata['username']) . ' ]';
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
}
else
{
        $u_login_logout = 'modules.php?name=Your_Account&amp;redirect=index';
        $l_login_logout = $lang['Login'];
}

$s_last_visit = ( $userdata['session_logged_in'] ) ? create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']) : '';

//
// Get basic (usernames + totals) online
// situation
//
$logged_visible_online = 0;
$logged_hidden_online = 0;
$guests_online = 0;
$online_userlist = '';
$l_online_users = '';
if (defined('SHOW_ONLINE'))
{
/*****[BEGIN]******************************************
 [ Mod:    Online Time                         v1.0.0 ]
 ******************************************************/
        $user_forum_sql = ( !empty($forum_id) ) ? "AND s.session_page = " . intval($forum_id) : '';
        $sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip
                FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
                WHERE u.user_id = s.session_user_id
                        AND s.session_time >= ".( time() - $board_config['online_time'] ) . "
                        $user_forum_sql
                ORDER BY u.username ASC, s.session_ip ASC";
/*****[END]********************************************
 [ Mod:    Online Time                         v1.0.0 ]
 ******************************************************/
        if( !($result = $db->sql_query($sql)) )
        {
                message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
        }

        $userlist_ary = array();
        $userlist_visible = array();

        $prev_user_id = 0;
        $prev_user_ip = $prev_session_ip = '';

        while( $row = $db->sql_fetchrow($result) )
        {
                // User is logged in and therefor not a guest
                if ( $row['session_logged_in'] )
                {
                        // Skip multiple sessions for one user
                        if ( $row['user_id'] != $prev_user_id )
                        {
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                                $row['username'] = UsernameColor($row['username']);
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/

                                if ( $row['user_allow_viewonline'] )
                                {
                                        $user_online_link = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '">' . $row['username'] . '</a>';
                                        $logged_visible_online++;
                                }
                                else
                                {
                                        $user_online_link = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '"><i>' . $row['username'] . '</i></a>';
                                        $logged_hidden_online++;
                                }

/*****[BEGIN]******************************************
 [ Mod:    Hidden Status Viewing               v1.0.0 ]
 ******************************************************/
                                if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN || $userdata['user_id'] == $row['user_id'] )
/*****[END]********************************************
 [ Mod:    Hidden Status Viewing               v1.0.0 ]
 ******************************************************/
                                {
                                        $online_userlist .= ( $online_userlist != '' ) ? ', ' . $user_online_link : $user_online_link;
                                }
                        }

                        $prev_user_id = $row['user_id'];
                }
                else
                {
                        // Skip multiple sessions for one user
                        if ( $row['session_ip'] != $prev_session_ip )
                        {
                                $guests_online++;
                        }
                }

                $prev_session_ip = $row['session_ip'];
        }
        $db->sql_freeresult($result);

        if ( empty($online_userlist) )
        {
                $online_userlist = $lang['None'];
        }
        $online_userlist = ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Registered_users'] ) . ' ' . $online_userlist;

        $total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

        if ( $total_online_users > $board_config['record_online_users'])
        {
                $board_config['record_online_users'] = $total_online_users;
                $board_config['record_online_date'] = time();

                $sql = "UPDATE " . CONFIG_TABLE . "
                        SET config_value = '$total_online_users'
                        WHERE config_name = 'record_online_users'";
                if ( !$db->sql_query($sql) )
                {
                        message_die(GENERAL_ERROR, 'Could not update online user record (nr of users)', '', __LINE__, __FILE__, $sql);
                }

                $sql = "UPDATE " . CONFIG_TABLE . "
                        SET config_value = '" . $board_config['record_online_date'] . "'
                        WHERE config_name = 'record_online_date'";
                if ( !$db->sql_query($sql) )
                {
                        message_die(GENERAL_ERROR, 'Could not update online user record (date)', '', __LINE__, __FILE__, $sql);
                }
/*****['BEGIN']******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
                $cache->delete('board_config', 'config');
/*****['END']********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        }

        if ( $total_online_users == 0 )
        {
                $l_t_user_s = $lang['Online_users_zero_total'];
        }
        else if ( $total_online_users == 1 )
        {
                $l_t_user_s = $lang['Online_user_total'];
        }
        else
        {
                $l_t_user_s = $lang['Online_users_total'];
        }

        if ( $logged_visible_online == 0 )
        {
                $l_r_user_s = $lang['Reg_users_zero_total'];
        }
        else if ( $logged_visible_online == 1 )
        {
                $l_r_user_s = $lang['Reg_user_total'];
        }
        else
        {
                $l_r_user_s = $lang['Reg_users_total'];
        }

        if ( $logged_hidden_online == 0 )
        {
                $l_h_user_s = $lang['Hidden_users_zero_total'];
        }
        else if ( $logged_hidden_online == 1 )
        {
                $l_h_user_s = $lang['Hidden_user_total'];
        }
        else
        {
                $l_h_user_s = $lang['Hidden_users_total'];
        }

        if ( $guests_online == 0 )
        {
                $l_g_user_s = $lang['Guest_users_zero_total'];
        }
        else if ( $guests_online == 1 )
        {
                $l_g_user_s = $lang['Guest_user_total'];
        }
        else
        {
                $l_g_user_s = $lang['Guest_users_total'];
        }

        $l_online_users = sprintf($l_t_user_s, $total_online_users);
        $l_online_users .= sprintf($l_r_user_s, $logged_visible_online);
        $l_online_users .= sprintf($l_h_user_s, $logged_hidden_online);
        $l_online_users .= sprintf($l_g_user_s, $guests_online);
}

//
// Obtain number of new private messages
// if user is logged in
//
if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) )
{
        if ( $userdata['user_new_privmsg'] )
        {
                $l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_pm'] : $lang['New_pms'];
                $l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

                if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] )
                {
                        $sql = "UPDATE " . USERS_TABLE . "
                                SET user_last_privmsg = " . $userdata['user_lastvisit'] . "
                                WHERE user_id = " . $userdata['user_id'];
                        if ( !$db->sql_query($sql) )
                        {
                                message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
                        }
/*****[BEGIN]******************************************
 [ Mod:     Suppress Popup                     v1.0.0 ]
 ******************************************************/
                        if ( isset($_REQUEST["suppress"]) )
                        {
                                $suppress = 1;
                        }
                        else
                        {
                                $suppress = '';
                        }
                        if(empty($suppress)) {
                            $s_privmsg_new = 1;
                            $icon_pm = $images['pm_new_msg'];
                        } else {
                            $s_privmsg_new = 0;
                            $icon_pm = $images['pm_new_msg'];
                        }
/*****[END]********************************************
 [ Mod:     Suppress Popup                     v1.0.0 ]
 ******************************************************/
                }
                else
                {
                        $s_privmsg_new = 0;
                        $icon_pm = $images['pm_new_msg'];
                }
        }
        else
        {
                $l_privmsgs_text = $lang['No_new_pm'];

                $s_privmsg_new = 0;
                $icon_pm = $images['pm_no_new_msg'];
        }

        if ( $userdata['user_unread_privmsg'] )
        {
                $l_message_unread = ( $userdata['user_unread_privmsg'] == 1 ) ? $lang['Unread_pm'] : $lang['Unread_pms'];
                $l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
        }
        else
        {
                $l_privmsgs_text_unread = $lang['No_unread_pm'];
        }
}
else
{
        $icon_pm = $images['pm_no_new_msg'];
        $l_privmsgs_text = $lang['Login_check_pm'];
        $l_privmsgs_text_unread = '';
        $s_privmsg_new = 0;
}

//
// Generate HTML required for Mozilla Navigation bar
//
if (!isset($nav_links))
{
        $nav_links = array();
}

$nav_links_html = '';
$nav_link_proto = '<link rel="%s" href="%s" title="%s" />' . "\n";
while( list($nav_item, $nav_array) = @each($nav_links) )
{
        if ( !empty($nav_array['url']) )
        {
                $nav_links_html .= sprintf($nav_link_proto, $nav_item, append_sid($nav_array['url']), $nav_array['title']);
        }
        else
        {
                // We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
                while( list(,$nested_array) = each($nav_array) )
                {
                        $nav_links_html .= sprintf($nav_link_proto, $nav_item, $nested_array['url'], $nested_array['title']);
                }
        }
}

/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
$online_color = ' style="color: #' . $theme['online_color'] . '"';
$offline_color = ' style="color: #' . $theme['offline_color'] . '"';
$hidden_color = ' style="color: #' . $theme['hidden_color'] . '"';
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
$template->assign_block_vars('colors',array(
    'GROUPS'    => GetColorGroups())
);
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Quick Search                       v3.0.1 ]
 ******************************************************/
$sql = "SELECT * FROM " . QUICKSEARCH_TABLE . "
    ORDER BY search_name";
if( !$result = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, "Couldn't obtain quick search data", "", __LINE__, __FILE__, $sql);
}
$search_count = $db->sql_numrows($result);
$search_rows = array();
$search_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$search_list = '<option value="forum_search" selected="selected">' . $board_config['sitename'] . '</option>';
$checkSearch = '';

//
// First Search Entry
//
if ( $search_count != '' )
{
    $search_name = $search_rows[0]['search_name'];
    $search_url1 = $search_rows[0]['search_url1'];
    $search_url2 = $search_rows[0]['search_url2'];

    $search_list .= '<option value="' . $search_name . '">' . $search_name . '</option>';

    // checkSearch() function, adapted from Smartor's ezPortal
    $checkSearch .= "if (document.search_block.site_search.value == '$search_name')
         {
            window.open('$search_url1' + document.search_block.search_keywords.value + '$search_url2', '_$search_name', '');
            return false;
         }\n";
}

//
// Start from Second Entry
//
for($i = 1; $i < $search_count; $i++)
{
    $search_name = $search_rows[$i]['search_name'];
    $search_url1 = $search_rows[$i]['search_url1'];
    $search_url2 = $search_rows[$i]['search_url2'];

    $search_list .= '<option value="' . $search_name . '">' . $search_name . '</option>';

    // checkSearch() function, adapated from Smartor's ezPortal
    $checkSearch .= "else if (document.search_block.site_search.value == '$search_name')
         {
            window.open('$search_url1' + document.search_block.search_keywords.value + '$search_url2', '_$search_name', '');
            return false;
         }\n";

}

//
// Set $l_advanced_forum_search variable
//
$l_advanced_forum_search = sprintf($lang['Forum_advanced_search'], $board_config['sitename']);

//
// Is Quick Search enabled? If so, assign our vars for the template.
//
if ( $board_config['quick_search_enable'] == 1 )
{
    $template->assign_block_vars('switch_quick_search', array(
        'L_QUICK_SEARCH_FOR' => $lang['Quick_search_for'],
        'L_QUICK_SEARCH_AT' => $lang['Quick_search_at'],
        'L_ADVANCED_FORUM_SEARCH' => $l_advanced_forum_search,
        'CHECKSEARCH' => $checkSearch,
        'SEARCHLIST' => $search_list)
    );
}
/*****[END]********************************************
 [ Mod:     Quick Search                       v3.0.1 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:     Advanced Security Code Control     v1.0.0 ]
 ******************************************************/
    $gfxchk = array(2,4,5,7);
    $gfx = "<br />" . security_code($gfxchk, 'small') . "<br />";
/*****[END]********************************************
 [ Mod:     Advanced Security Code Control     v1.0.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/
if ( $userdata['user_id'] != ANONYMOUS )
{
    switch ( $userdata['user_time_mode'] )
    {
        case MANUAL_DST:
            $time_message = sprintf($lang['All_times'], $l_timezone) . $lang['dst_enabled_mode'];
            break;
        case SERVER_SWITCH:
            $time_message = sprintf($lang['All_times'], $l_timezone);
            if ( date('I', time()) )
            {
                $time_message = $time_message . $lang['dst_enabled_mode'];
            }
            break;
        case FULL_SERVER:
            $time_message = $lang['full_server_mode'];
            break;
        case SERVER_PC:
            $time_message = $lang['server_pc_mode'];
            break;
        case FULL_PC:
            $time_message = $lang['full_pc_mode'];
            break;
        default:
            $time_message = sprintf($lang['All_times'], $l_timezone);
            break;
    }
} else
{
    switch ( $board_config['default_time_mode'] )
    {
        case MANUAL_DST:
            $time_message = sprintf($lang['All_times'], $l_timezone) . $lang['dst_enabled_mode'];
            break;
        case SERVER_SWITCH:
            $time_message = sprintf($lang['All_times'], $l_timezone);
            if ( date('I', time()) )
            {
                $time_message = $time_message . $lang['dst_enabled_mode'];
            }
            break;
        case FULL_SERVER:
            $time_message = $lang['full_server_mode'];
            break;
        case SERVER_PC:
            $time_message = $lang['server_pc_mode'];
            break;
        case FULL_PC:
            $time_message = $lang['full_pc_mode'];
            break;
        default:
            $time_message = sprintf($lang['All_times'], $l_timezone);
            break;
    }
}

$time_message = str_replace('GMT', 'UTC', $time_message);

$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
$server_name = trim($board_config['server_name']);
$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

$server_url = $server_protocol . $server_name . $server_port . "modules.php?name=Forums";

/*****[END]********************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/

//
// The following assigns all _common_ variables that may be used at any point
// in a template.
//
if(!isset($page_title)) {
    $page_title = basename(dirname(__FILE__));
}
if(!isset($day_userlist)) {
    $day_userlist = '';
}
$template->assign_vars(array(
        'SITENAME' => $board_config['sitename'],
        'SITE_DESCRIPTION' => $board_config['site_desc'],
        'PAGE_TITLE' => $page_title,
        'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
        'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
        'TOTAL_USERS_ONLINE' => $l_online_users,
        'LOGGED_IN_USER_LIST' => $online_userlist,
        'RECORD_USERS' => sprintf($lang['Record_online_users'], $board_config['record_online_users'], create_date($board_config['default_dateformat'], $board_config['record_online_date'], $board_config['board_timezone'])),
        'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
        'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
        'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,

        'PRIVMSG_IMG' => $icon_pm,

/*****[BEGIN]******************************************
 [ Mod:     Disable Board Admin Override       v0.1.1 ]
 ******************************************************/
        'L_Board_Currently_Disabled' => $lang['Board_Currently_Disabled'],
/*****[END]********************************************
 [ Mod:     Disable Board Admin Override       v0.1.1 ]
 ******************************************************/
        'L_USERNAME' => $lang['Username'],
        'L_PASSWORD' => $lang['Password'],
        'L_LOGIN_LOGOUT' => $l_login_logout,
        'L_LOGIN' => $lang['Login'],
        'L_LOG_ME_IN' => $lang['Log_me_in'],
        'L_AUTO_LOGIN' => $lang['Log_me_in'],
        'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
        'L_REGISTER' => $lang['Register'],
        'L_PROFILE' => $lang['Edit_profile'],
        'L_SEARCH' => $lang['Search'],
        'L_PRIVATEMSGS' => $lang['Private_Messages'],
        'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
        'L_MEMBERLIST' => $lang['Memberlist'],
        'L_FAQ' => $lang['FAQ'],
/*****[BEGIN]******************************************
 [ Mod:     Forum Statistics                   v3.0.0 ]
 ******************************************************/
        'L_STATISTICS' => $lang ['Statistics'],
/*****[END]********************************************
 [ Mod:     Forum Statistics                   v3.0.0 ]
 ******************************************************/
        'L_USERGROUPS' => $lang['Usergroups'],
        'L_SEARCH_NEW' => $lang['Search_new'],
        'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
        'L_SEARCH_SELF' => $lang['Search_your_posts'],
        'L_WHOSONLINE_ADMIN' => sprintf($lang['Admin_online_color'], '<span style="color:#' . $theme['fontcolor3'] . '">', '</span>'),
        'L_WHOSONLINE_MOD' => sprintf($lang['Mod_online_color'], '<span style="color:#' . $theme['fontcolor2'] . '">', '</span>'),
/*****[BEGIN]******************************************
 [ Base:    Recent Topics                      v1.2.4 ]
 ******************************************************/
        'U_RECENT' => append_sid("recent.$phpEx"),
          'L_RECENT' => $lang['Recent_topics'],
/*****[END]********************************************
 [ Base:    Recent Topics                      v1.2.4 ]
 ******************************************************/
        'U_SEARCH_UNANSWERED' => append_sid('search.'.$phpEx.'?search_id=unanswered'),
        'U_SEARCH_SELF' => append_sid('search.'.$phpEx.'?search_id=egosearch'),
        'U_SEARCH_NEW' => append_sid('search.'.$phpEx.'?search_id=newposts'),
        'U_INDEX' => append_sid('index.'.$phpEx),
        'U_REGISTER' => append_sid('profile.'.$phpEx.'?mode=register'),
        'U_PROFILE' => append_sid('profile.'.$phpEx.'?mode=editprofile'),
        'U_PRIVATEMSGS' => append_sid('privmsg.'.$phpEx.'?folder=inbox'),
        'U_PRIVATEMSGS_POPUP' => append_sid('privmsg.'.$phpEx.'?mode=newpm&popup=1',true),
        'U_SEARCH' => append_sid('search.'.$phpEx),
        'U_MEMBERLIST' => append_sid('memberlist.'.$phpEx),
        'U_MODCP' => append_sid('modcp.'.$phpEx),
        'U_FAQ' => append_sid('faq.'.$phpEx),
/*****[BEGIN]******************************************
 [ Mod:     Forum Statistics                   v3.0.0 ]
 ******************************************************/
        'U_STATISTICS' => append_sid('statistics.'.$phpEx),
/*****[END]********************************************
 [ Mod:     Forum Statistics                   v3.0.0 ]
 ******************************************************/
        'U_VIEWONLINE' => append_sid('viewonline.'.$phpEx),
        'U_LOGIN_LOGOUT' => append_sid($u_login_logout),
        'U_MEMBERSLIST' => append_sid('memberlist.'.$phpEx),
        'U_GROUP_CP' => append_sid('groupcp.'.$phpEx),
/*****[BEGIN]******************************************
 [ Mod:     Album                             v2.0.56 ]
 ******************************************************/
	'L_ALBUM' => $lang['Album'],
	'U_ALBUM' => append_sid('album.'.$phpEx),
/*****[END]********************************************
 [ Mod:     Album                             v2.0.56 ]
 ******************************************************/    
/*****[BEGIN]******************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/
        'U_SELF' => $server_url,
/*****[END]********************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/

        'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
        'S_CONTENT_ENCODING' => $lang['ENCODING'],
        'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
        'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
/*****[BEGIN]******************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/
        'S_TIMEZONE' => $time_message,
/*****[END]********************************************
 [ Mod:    Advanced Time Management            v2.2.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     YA Modifications                   v1.0.0 ]
 ******************************************************/
        'S_LOGIN_ACTION' => 'modules.php?name=Your_Account',
        'GFX' => $gfx,
/*****[END]********************************************
 [ Mod:     YA Modifications                   v1.0.0 ]
 ******************************************************/

        'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],

        'T_BODY_BACKGROUND' => $theme['body_background'],
        'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
        'T_BODY_TEXT' => '#'.$theme['body_text'],
        'T_BODY_LINK' => '#'.$theme['body_link'],
        'T_BODY_VLINK' => '#'.$theme['body_vlink'],
        'T_BODY_ALINK' => '#'.$theme['body_alink'],
        'T_BODY_HLINK' => '#'.$theme['body_hlink'],

        'T_TR_COLOR1' => '#'.$theme['tr_color1'],
        'T_TR_COLOR2' => '#'.$theme['tr_color2'],
        'T_TR_COLOR3' => '#'.$theme['tr_color3'],
        'T_TR_CLASS1' => $theme['tr_class1'],
        'T_TR_CLASS2' => $theme['tr_class2'],
        'T_TR_CLASS3' => $theme['tr_class3'],
        'T_TH_COLOR1' => '#'.$theme['th_color1'],
        'T_TH_COLOR2' => '#'.$theme['th_color2'],
        'T_TH_COLOR3' => '#'.$theme['th_color3'],
        'T_TH_CLASS1' => $theme['th_class1'],
        'T_TH_CLASS2' => $theme['th_class2'],
        'T_TH_CLASS3' => $theme['th_class3'],
        'T_TD_COLOR1' => '#'.$theme['td_color1'],
        'T_TD_COLOR2' => '#'.$theme['td_color2'],
        'T_TD_COLOR3' => '#'.$theme['td_color3'],
        'T_TD_CLASS1' => $theme['td_class1'],
        'T_TD_CLASS2' => $theme['td_class2'],
        'T_TD_CLASS3' => $theme['td_class3'],
        'T_FONTFACE1' => $theme['fontface1'],
        'T_FONTFACE2' => $theme['fontface2'],
        'T_FONTFACE3' => $theme['fontface3'],
        'T_FONTSIZE1' => $theme['fontsize1'],
        'T_FONTSIZE2' => $theme['fontsize2'],
        'T_FONTSIZE3' => $theme['fontsize3'],
        'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
        'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
        'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
        'T_SPAN_CLASS1' => $theme['span_class1'],
        'T_SPAN_CLASS2' => $theme['span_class2'],
        'T_SPAN_CLASS3' => $theme['span_class3'],
/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
        // Not used, but can help you...
        'T_ONLINE_COLOR' => '#' . $theme['online_color'],
        'T_OFFLINE_COLOR' => '#' . $theme['offline_color'],
        'T_HIDDEN_COLOR' => '#' . $theme['hidden_color'],
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/

        'NAV_LINKS' => $nav_links_html)
);

/*****[BEGIN]******************************************
 [ Mod:     Disable Board Admin Override       v0.1.1 ]
 ******************************************************/
if ($userdata['user_level'] == ADMIN ) {
     if($board_config['board_disable'] == 1) {
         $template->assign_block_vars('boarddisabled', array());
     }
}
/*****[END]********************************************
 [ Mod:     Disable Board Admin Override       v0.1.1 ]
 ******************************************************/

//
// Login box?
//
if ( !$userdata['session_logged_in'] )
{
        $template->assign_block_vars('switch_user_logged_out', array());
    //
    // Allow autologin?
    //
    if (!isset($board_config['allow_autologin']) || $board_config['allow_autologin'] )
    {
        $template->assign_block_vars('switch_allow_autologin', array());
        $template->assign_block_vars('switch_user_logged_out.switch_allow_autologin', array());
    }
}
else
{
        $template->assign_block_vars('switch_user_logged_in', array());

        if ( !empty($userdata['user_popup_pm']) )
        {
                $template->assign_block_vars('switch_enable_pm_popup', array());
        }
}

// Add no-cache control for cookies if they are set
//$c_no_cache = (isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_data'])) ? 'no-cache="set-cookie", ' : '';

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
        header ('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
        header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: 0');
header ('Pragma: no-cache');
//{
    $template->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:     Theme Simplifications              v1.0.0 ]
 ******************************************************/
        'I_MINI_FAQ' => '<img src="' . $images['Mini_Faq'] . '" width="12" height="13" border="0" alt="' . $lang['FAQ'] . '" title="' . $lang['FAQ'] . '" hspace="3" />',
        'I_MINI_SEARCH' => '<img src="' . $images['Mini_Search'] . '" width="12" height="13" border="0" alt="' . $lang['Search'] . '" title="' . $lang['Search'] . '" hspace="3" />',
        'I_MINI_USERGROUPS' => '<img src="' . $images['Mini_Usergroups'] . '" width="12" height="13" border="0" alt="' . $lang['Usergroups'] . '" title="' . $lang['Usergroups'] . '" hspace="3" />',
        'I_MINI_PROFILE' => '<img src="' . $images['Mini_Profile'] . '" width="12" height="13" border="0" alt="' . $lang['Profile'] . '" title="' . $lang['Profile'] . '" hspace="3" />',
        'I_MINI_PRIVATEMSGS' => '<img src="' . $images['Mini_Private_Messages'] . '" width="12" height="13" border="0" alt="' . $lang['Private_Messages'] . '" title="' . $lang['Private_Messages'] . '" hspace="3" />',
        'I_MINI_LOGIN_LOGOUT' => '<img src="' . $images['Mini_Login_Logout'] . '" width="12" height="13" border="0" alt="' . $lang['Login_Logout'] . '" title="' . $lang['Login_Logout'] . '" hspace="3" />',
/*****[END]********************************************
 [ Mod:     Theme Simplifications              v1.0.0 ]
 ******************************************************/
        )
    );
//}
$template->pparse('overall_header');

/*****[BEGIN]******************************************
 [ Mod:     Disable Board Admin Override       v0.1.1 ]
 [ Mod:     Disable Board Message              v1.0.0 ]
 ******************************************************/
if ($userdata['user_level'] != ADMIN && $board_config['board_disable'] && !defined("IN_ADMIN") && !defined("IN_LOGIN"))
{
    if ( $board_config['board_disable_msg'] != "" )
    {
        message_die(GENERAL_MESSAGE, $board_config['board_disable_msg'], 'Information');
    }
    else
    {
        message_die(GENERAL_MESSAGE, 'Board_disable', 'Information');
    }
} else{
    if ($userdata['user_level'] == ADMIN && $board_config['board_disable_adminview'] != '1' && $board_config['board_disable'] && !defined("IN_ADMIN") && !defined("IN_LOGIN"))
    {
    if ( $board_config['board_disable_msg'] != "" )
    {
        message_die(GENERAL_MESSAGE, $board_config['board_disable_msg'], 'Information');
    }
    else
    {
        message_die(GENERAL_MESSAGE, 'Board_disable', 'Information');
    }
    }
}
/*****[END]********************************************
 [ Mod:     Disable Board Message              v1.0.0 ]
 [ Mod:     Disable Board Admin Override       v0.1.1 ]
 ******************************************************/

?>