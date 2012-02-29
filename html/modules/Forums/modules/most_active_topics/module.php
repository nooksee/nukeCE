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
// Most Active Topics
//
$core->start_module(true);

$core->set_content('statistical');

$core->set_view('rows', $core->return_limit);
$core->set_view('columns', 3);

$core->define_view('set_columns', array(
    $core->pre_defined('rank'),
    'replies' => $lang['Replies'],
    'topic' => $lang['Topic'])
);

$core->set_header($lang['module_name']);

$core->assign_defined_view('align_rows', array(
    'left',
    'center',
    'left')
);

$core->assign_defined_view('width_rows', array(
    '',
    '20%',
    '')
);

$sql = 'SELECT topic_id, forum_id, topic_title, topic_replies
FROM ' . TOPICS_TABLE . '
WHERE (topic_status <> 2) AND (topic_replies > 0)
ORDER BY topic_replies DESC
LIMIT ' . $core->return_limit;

$result = $core->sql_query($sql, 'Couldn\'t retrieve topic data');
$topic_data = $core->sql_fetchrowset($result);

$core->set_data($topic_data);

/*****[BEGIN]******************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 ******************************************************/
$core->topic_smiles();
/*****[END]********************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 ******************************************************/

//
// Now this one could get a big beast
// We will explain the structure, no fear, but not now. :D
//
$core->define_view('set_rows', array(
    '$core->pre_defined()',
    '$core->data(\'topic_replies\')',
    '$core->generate_link(append_sid(\'viewtopic.php?t=\' . $core->data(\'topic_id\')), $core->data(\'topic_title\'), \'target="_blank"\')'
    ),
    array(
        '$core->data(\'forum_id\')', 'auth_view AND auth_read', 'forum', array(
            '',
            '$core->data(\'topic_replies\')',
            '$lang[\'Hidden_from_public_view\']'
        )
    )
);

$core->run_module();

?>