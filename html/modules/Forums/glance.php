<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/***************************************************************************
 *                                 glance.php
 *                            -------------------
 *   begin                : Monday, Apr 07, 2001
 *   copyright            : blulegend, Jack Kan
 *   contact              : www.phpbb.com, member: blulegend
 *   version              : 2.2.1
 *
 *   modified by          : netclectic - http://www.netclectic.com/forums/viewtopic.php?t=257
 *
 ***************************************************************************/

/*****[CHANGES]**********************************************************
 -=[Mod]=-
       Advanced Username Color                  v1.0.5       08/08/2005
       Smilies in Topic Titles                  v1.0.0       09/10/2005
       Smilies in Topic Titles Toggle           v1.0.0       09/10/2005
 ************************************************************************/

if (!defined('IN_PHPBB'))
{
    die('Hacking attempt');
}

    $glance_forum_dir = 'modules.php?name=Forums&amp;file=';
    $glance_news_forum_id = $board_config['glance_news_id'];
    $glance_num_news = intval($board_config['glance_num_news']);
    $glance_num_recent = intval($board_config['glance_num']);
    $glance_recent_ignore = $board_config['glance_ignore_forums'];
    $glance_news_heading = $lang['glance_news_heading'];
    $glance_recent_heading = $lang['glance_recent_heading'];
    $glance_table_width = $board_config['glance_table_width'];
    $glance_show_new_bullets = true;
    $glance_track = true;
    $glance_auth_read = intval($board_config['glance_auth_read']);
    $glance_topic_length = intval($board_config['glance_topic_length']);
    //
    // GET USER LAST VISIT
    //
    $glance_last_visit = $userdata['user_lastvisit'];

    //
    // MESSAGE TRACKING
    //
    if ( !isset($tracking_topics) && $glance_track ) $tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : '';

    // CHECK FOR BAD WORDS
    //
    // Define censored word matches
    //
    $orig_word = array();
    $replacement_word = array();
    obtain_word_list($orig_word, $replacement_word);

    // set the topic title sql depending on the character limit    set in glance_config
    $sql_title = ($glance_topic_length) ? ", LEFT(t.topic_title, " . $glance_topic_length . ") as topic_title" : ", t.topic_title";

    //
    // GET THE LATEST NEWS TOPIC
    //
    if ( $glance_num_news )
    {
        $news_data = $db->sql_fetchrow($result);
         $sql = "
            SELECT
                f.forum_id, f.forum_name" . $sql_title . ", t.topic_id, t.topic_last_post_id, t.topic_poster, t.topic_views, t.topic_replies, t.topic_type, t.topic_status,
                p2.post_time, p2.poster_id, p2.post_username, p.post_username,

                u.username as last_username,
                u2.username as author_username
            FROM "
                . FORUMS_TABLE . " f, "
                . POSTS_TABLE . " p, "
                . TOPICS_TABLE . " t, "
                . POSTS_TABLE . " p2, "
                . USERS_TABLE . " u, "
                . USERS_TABLE . " u2
            WHERE
                f.forum_id IN (" . $glance_news_forum_id . ")
                AND t.forum_id = f.forum_id
                AND p.post_id = t.topic_first_post_id
                AND p2.post_id = t.topic_last_post_id
                AND t.topic_moved_id = 0
                AND p2.poster_id = u.user_id
                AND t.topic_poster = u2.user_id
                ORDER BY t.topic_glance_priority DESC, t.topic_last_post_id DESC";

        $sql .= ($glance_news_offset) ? " LIMIT " . $glance_news_offset . ", " . $glance_num_news : " LIMIT " . $glance_num_news;

        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, "Could not query new news information", "", __LINE__, __FILE__, $sql);
        }
        $latest_news = array();
        while ( $topic_row = $db->sql_fetchrow($result) )
        {
            $topic_row['topic_title'] = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_row['topic_title']) : $topic_row['topic_title'];
            $latest_news[] = $topic_row;
        }
        $db->sql_freeresult($result);

    }

    //
    // GET THE LAST 5 TOPICS
    //
    if ( $glance_num_recent )
    {
        $glance_auth_level = ( $glance_auth_read ) ? AUTH_VIEW : AUTH_ALL;
        $is_auth_ary = auth($glance_auth_level, AUTH_LIST_ALL, $userdata);

        $forumsignore = $glance_news_forum_id;
        if ( $num_forums = count($is_auth_ary) )
        {
            while ( list($forum_id, $auth_mod) = each($is_auth_ary) )
            {
                $unauthed = false;
                if ( !$auth_mod['auth_view'] )
                {
                    $unauthed = true;
                }
                if ( !$glance_auth_read && !$auth_mod['auth_read'] )
                {
                    $unauthed = true;
                }
                if ( $unauthed )
                {
                    $forumsignore .= ($forumsignore) ? ',' . $forum_id : $forum_id;
                }
            }
        }

        $forumsignore .= ($forumsignore && $glance_recent_ignore) ? ',' : '';
        $glance_recent_ignore = ($glance_recent_ignore) ? $glance_recent_ignore : '';

         $sql = "
            SELECT
                f.forum_id, f.forum_name" . $sql_title . ", t.topic_id, t.topic_last_post_id, t.topic_poster, t.topic_views, t.topic_replies, t.topic_type, t.topic_status,
                p2.post_time, p2.poster_id, p2.post_username, p.post_username,

                u.username as last_username,
                u2.username as author_username
            FROM "
                . FORUMS_TABLE . " f, "
                . POSTS_TABLE . " p, "
                . TOPICS_TABLE . " t, "
                . POSTS_TABLE . " p2, "
                . USERS_TABLE . " u, "
                . USERS_TABLE . " u2
            WHERE
                f.forum_id NOT IN (" . $forumsignore . $glance_recent_ignore . ")
                AND t.forum_id = f.forum_id
                AND p.post_id = t.topic_first_post_id
                AND p2.post_id = t.topic_last_post_id
                AND t.topic_moved_id = 0
                AND p2.poster_id = u.user_id
                AND t.topic_poster = u2.user_id
                ORDER BY t.topic_glance_priority DESC, t.topic_last_post_id DESC";

        $sql .= ($glance_recent_offset) ? " LIMIT " . $glance_recent_offset . ", " . $glance_num_recent : " LIMIT " . $glance_num_recent;

        if( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, "Could not query latest topic information", "", __LINE__, __FILE__, $sql);
        }
        $latest_topics = array();
        $latest_anns = array();
        $latest_stickys = array();
        while ( $topic_row = $db->sql_fetchrow($result) )
        {
            $topic_row['topic_title'] = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_row['topic_title']) : $topic_row['topic_title'];
            switch ($topic_row['topic_type'])
                {
                    case POST_GLOBAL_ANNOUNCE:
                    case POST_ANNOUNCE:
                        $latest_anns[] = $topic_row;
                        break;
                    case POST_STICKY:
                        $latest_stickys[] = $topic_row;
                        break;
                    default:
                        $latest_topics[] = $topic_row;
                        break;
                }
        }
        $latest_topics = array_merge($latest_anns, $latest_stickys, $latest_topics);
        $db->sql_freeresult($result);

    }

    //
    // BEGIN OUTPUT
    //
    $template->set_filenames(array(
        'glance_output' => 'glance_body.tpl')
    );

    if ( $glance_num_news )
    {
        if ( !empty($latest_news) )
        {
            $bullet_pre = '<img src="';

            for ( $i = 0; $i < count($latest_news); $i++ )
            {
                if ( $userdata['session_logged_in'] )
                {
                    $unread_topics = false;
                    $glance_topic_id = $latest_news[$i]['topic_id'];
                    if ( $latest_news[$i]['post_time'] > $glance_last_visit )
                    {
                        $unread_topics = true;
                        if( !empty($tracking_topics[$glance_topic_id]) && $glance_track )
                        {
                            if( $tracking_topics[$glance_topic_id] >= $latest_news[$i]['post_time'] )
                            {
                                $unread_topics = false;
                            }
                        }
                    }
                    $shownew = $unread_topics;
                }
                else
                {
                    $unread_topics = false;
                    $shownew = ($board_config['time_today'] < $latest_news[$i]['post_time']);
                }

                $bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ?  $images['folder_announce_new'] :  $images['folder_announce'] ) . '" border="0" alt="" />';

                $newest_code = ( $unread_topics && $glance_show_new_bullets ) ? '&amp;view=newest' : '';

                $topic_link = $glance_forum_dir . 'viewtopic&amp;t=' . $latest_news[$i]['topic_id'] . $newest_code;

/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                 $guest = (!empty($latest_topics[$i]['post_username'])) ? $latest_topics[$i]['post_username'] : $latest_topics[$i]['last_username'] . ' ';
                $last_poster = ($latest_news[$i]['poster_id'] == ANONYMOUS ) ? ( ($latest_news[$i]['last_username'] != '' ) ? $guest : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $latest_news[$i]['poster_id']) . '">' . UsernameColor($latest_news[$i]['last_username']) . '</a> ';
                $last_poster .= '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $latest_news[$i]['topic_last_post_id']) . '#' . $latest_news[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
                $topic_poster = ($latest_news[$i]['topic_poster'] == ANONYMOUS ) ? ( ($latest_news[$i]['author_username'] != '' ) ? $guest : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $latest_news[$i]['topic_poster']) . '">' . UsernameColor($latest_news[$i]['author_username']) . '</a> ';
                $last_post_time = create_date($board_config['default_dateformat'], $latest_news[$i]['post_time'], $board_config['board_timezone']);
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                $template->assign_block_vars('news', array(
                    'BULLET' => $bullet_full,
                    'TOPIC_TITLE' => $latest_news[$i]['topic_title'],
                    'TOPIC_LINK' => $topic_link,
                    'TOPIC_TIME' => $last_post_time,

                    'TOPIC_POSTER' => $topic_poster,
                    'TOPIC_VIEWS' => $latest_news[$i]['topic_views'],
                    'TOPIC_REPLIES' => $latest_news[$i]['topic_replies'],
                    'LAST_POSTER' => $last_poster,
                    'FORUM_TITLE' => $latest_news[$i]['forum_name'],
                    'FORUM_LINK' => $glance_forum_dir . 'viewforum&amp;f=' . $latest_news[$i]['forum_id'])

                    );
            }
        }
        else
        {
            $template->assign_block_vars('news', array(
            'BULLET' => '<img src="' . $images['forum'] . '" border="0" alt="" />', $glance_recent_bullet_old,

            'TOPIC_TITLE' => 'None')
            );
        }
    }

    if ( $glance_num_recent )
    {
        $glance_info = 'counted recent';
        $bullet_pre = '<img src="';
        if ( !empty($latest_topics) )
        {
            for ( $i = 0; $i < count($latest_topics); $i++ )
            {
                if ( $userdata['session_logged_in'] )
                {
                    $unread_topics = false;
                    $glance_topic_id = $latest_topics[$i]['topic_id'];
                    if ( $latest_topics[$i]['post_time'] > $glance_last_visit )
                    {
                        $unread_topics = true;
                        if( !empty($tracking_topics[$glance_topic_id]) && $glance_track )
                        {
                            if( $tracking_topics[$glance_topic_id] >= $latest_topics[$i]['post_time'] )
                            {
                                $unread_topics = false;
                            }
                        }
                    }
                    $shownew = $unread_topics;
                }
                else
                {
                    $unread_topics = false;
                    $shownew = ($board_config['time_today'] < $latest_topics[$i]['post_time']);
                }
                switch ($latest_topics[$i]['topic_type'])
                {
                    case POST_GLOBAL_ANNOUNCE:
                        $bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ? $images['folder_global_announce_new'] :  $images['folder_global_announce'] ) . '" border="0" alt="" />';
                        break;
                    case POST_ANNOUNCE:
                        $bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ? $images['folder_announce_new'] :  $images['folder_announce'] ) . '" border="0" alt="" />';
                        break;
                    case POST_STICKY:
                        $bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ? $images['folder_sticky_new'] :  $images['folder_sticky'] ) . '" border="0" alt="" />';
                        break;
                    default:
                        if ($latest_topics[$i]['topic_status'] == TOPIC_LOCKED)
                        {
                            $folder = $images['folder_locked'];
                            $folder_new = $images['folder_locked_new'];
                        }
                        else if ($latest_topics[$i]['topic_replies'] >= $board_config['hot_threshold'])
                        {
                            $folder = $images['folder_hot'];
                            $folder_new = $images['folder_hot_new'];
                        }
                        else
                        {
                            $folder = $images['folder'];
                            $folder_new = $images['folder_new'];
                        }

                        $bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ? $folder_new :  $folder ) . '" border="0" alt="" />';
                        break;
                }
                $newest_code = ( $unread_topics && $glance_show_new_bullets ) ? '&amp;view=newest' : '';

                $topic_link = $glance_forum_dir . 'viewtopic&amp;t=' . $latest_topics[$i]['topic_id'] . $newest_code;
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                 $guest = (!empty($latest_topics[$i]['post_username'])) ? $latest_topics[$i]['post_username'] : $latest_topics[$i]['last_username'] . ' ';
                $topic_poster = ($latest_topics[$i]['topic_poster'] == ANONYMOUS ) ? ( ($latest_topics[$i]['author_username'] != '' ) ? $guest : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $latest_topics[$i]['topic_poster']) . '">' . UsernameColor($latest_topics[$i]['author_username']) . '</a> ';
                $last_post_time = create_date($board_config['default_dateformat'], $latest_topics[$i]['post_time'], $board_config['board_timezone']);
                $last_poster = ($latest_topics[$i]['poster_id'] == ANONYMOUS ) ? ( ($latest_topics[$i]['last_username'] != '' ) ? $guest : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $latest_topics[$i]['poster_id']) . '">' . UsernameColor($latest_topics[$i]['last_username']) . '</a> ';
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                $last_poster .= '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $latest_topics[$i]['topic_last_post_id']) . '#' . $latest_topics[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

                $template->assign_block_vars('recent', array(
                    'BULLET' => $bullet_full,
                    'TOPIC_LINK' => $topic_link,
/*****[BEGIN]******************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 [ Mod:     Smilies in Topic Titles Toggle     v1.0.0 ]
 ******************************************************/
                    'TOPIC_TITLE' => ($board_config['smilies_in_titles']) ? smilies_pass($latest_topics[$i]['topic_title']) : $latest_topics[$i]['topic_title'],
/*****[END]********************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 [ Mod:     Smilies in Topic Titles Toggle     v1.0.0 ]
 ******************************************************/
                    'TOPIC_POSTER' => $topic_poster,
                    'TOPIC_VIEWS' => $latest_topics[$i]['topic_views'],
                    'TOPIC_REPLIES' => $latest_topics[$i]['topic_replies'],
                    'LAST_POST_TIME' => $last_post_time,
                    'LAST_POSTER' => $last_poster,
                    'FORUM_TITLE' => $latest_topics[$i]['forum_name'],
                    'FORUM_LINK' => $glance_forum_dir . 'viewforum&amp;f=' . $latest_topics[$i]['forum_id'])
                );
            }

        }
        else
        {
            $template->assign_block_vars('recent', array(
            'BULLET' => '<img src="' . $images['forum'] . '" border="0" alt="" />', $glance_recent_bullet_old,

            'TOPIC_TITLE' => 'None')
            );
        }
    }

    if ( $glance_num_news )
    {
        $template->assign_block_vars('switch_glance_news');

    }
    if ( $glance_num_recent )
    {
        $template->assign_block_vars('switch_glance_recent');
    }

    $template->assign_vars(array(
        'GLANCE_TABLE_WIDTH' =>    $glance_table_width,
        'RECENT_HEADING' => $glance_recent_heading,
        'NEWS_HEADING' => $glance_news_heading,

        'L_TOPICS' => $lang['Topics'],
        'L_REPLIES' => $lang['Replies'],
        'L_VIEWS' => $lang['Views'],
        'L_LASTPOST' => $lang['Last_Post'],
        'L_FORUM' => $lang['Forum'],
        'L_AUTHOR' => $lang['Author'])
        );

    $template->assign_var_from_handle('GLANCE_OUTPUT', 'glance_output');

// THE END

?>