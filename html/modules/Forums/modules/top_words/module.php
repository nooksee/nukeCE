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

// true == use db cache
$core->start_module(true);

$core->set_content('bars');

$core->set_view('rows', $core->return_limit);
$core->set_view('columns', 5);

$core->define_view('set_columns', array(
    $core->pre_defined('rank'),
    'word' => $lang['Word'],
    'count' => $lang['Count'],
    $core->pre_defined('percent'),
    $core->pre_defined('graph'))
);

$content->percentage_sign = TRUE;

$core->set_header($lang['module_name']);

$core->assign_defined_view('align_rows', array(
    'left',
    'left',
    'center',
    'center',
    'left')
);

$sql = "SELECT COUNT( word_id ) total_words FROM ".SEARCH_MATCH_TABLE;

$result = $core->sql_query($sql, 'Unable to retrieve total words');
$row = $core->sql_fetchrow($result);

$total_words = $row['total_words'];

$sql = "SELECT COUNT( swm.word_id ) word_count, swm.word_id word_id, swl.word_text word_text 
FROM " . SEARCH_MATCH_TABLE . " swm, " . SEARCH_WORD_TABLE . " swl 
WHERE swm.word_id = swl.word_id AND swl.word_text != 'nbsp'
GROUP BY swm.word_id ORDER BY word_count DESC 
LIMIT " . $core->return_limit;

$result = $core->sql_query($sql, 'Unable to retrieve word count data');
$data = $core->sql_fetchrowset($result);

$content->init_math('word_count', $data[0]['word_count'], $total_words);
$core->set_data($data);

$core->define_view('set_rows', array(
    '$core->pre_defined()',
    '$core->data(\'word_text\')',
    '$core->data(\'word_count\')',
    '$core->pre_defined()',
    '$core->pre_defined()')
);

$core->run_module();

?>