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

if(!defined('NUKE_CE')) exit;

if (!is_active('Groups')) {
    $content = 'ERROR: Groups module is inactive';
    return $content;
}

global $db, $prefix, $userinfo;
get_lang('blocks');

$in_group = array();
// Select all groups where the user is a member
if (isset($userinfo['groups'])) {
    $s_member_groups = '';
    foreach ($userinfo['groups'] as $id => $name) {
        $in_group[] = $id;
        if (!empty($name)){
            $s_member_groups .= '
                                 &nbsp;    
                                 <a title="'.$name.'" href="modules.php?name=Groups&amp;g='.$id . '">
                                     <img src="images/arrow.gif" alt="'.$name.'" height="9" width="9" /> 
                                     ' . GroupColor($name) . '
                                 </a>
                                 <br />
                                ';
        }
    }
}
// Select all groups where the user has a pending membership.
if (is_user()) {
    $result = $db->sql_query('SELECT g.group_id, g.group_name, g.group_type
            FROM ' . $prefix.'_bbgroups g, ' . $prefix.'_bbuser_group ug
            WHERE ug.user_id = ' . $userinfo['user_id'] . '
                AND ug.group_id = g.group_id
                AND ug.user_pending = 1
                AND g.group_single_user = 0
            ORDER BY g.group_name, ug.user_id');
    if ($db->sql_numrows($result)) {
        $s_pending_groups = '';
        while ( $row = $db->sql_fetchrow($result) ) {
            $in_group[] = $row['group_id'];
            $s_pending_groups .= '
                                  &nbsp;    
                                  <a title="'.$row['group_name'].'" href="modules.php?name=Groups&amp;g='.$row['group_id'] . '">
                                      <img src="images/arrow.gif" alt="'.$row['group_name'].'" height="9" width="9" /> 
                                      ' . GroupColor($row['group_name']) . '
                                  </a>
                                  <br />
                                 ';
        }
        
    }
    $db->sql_freeresult($result);
}

// Select all other groups i.e. groups that this user is not a member of
$ignore_group_sql = ( count($in_group) ) ? "AND group_id NOT IN (" . implode(', ', $in_group) . ")" : '';
$result = $db->sql_query("SELECT group_id, group_name, group_type
        FROM " . $prefix."_bbgroups
        WHERE group_single_user = 0
            $ignore_group_sql
        ORDER BY group_name");

$s_group_list = '';
while ($row = $db->sql_fetchrow($result)) {
    if ($row['group_type'] != 2 || is_admin()) {
        $s_group_list .='
                         &nbsp;    
                         <a title="'.$row['group_name'].'" href="modules.php?name=Groups&amp;g='.$row['group_id'] . '">
                             <img src="images/arrow.gif" alt="'.$row['group_name'].'" height="9" width="9" /> 
                             ' . GroupColor($row['group_name']) . '
                         </a>
                         <br />
                        ';
    }
}
$db->sql_freeresult($result);

$content = '';
if (is_user()) {
    if (isset($s_member_groups)) {
        $content .= '
                     <img src="images/blocks/group-1.gif" border="0" height="14" width="17" />
                         <u>
                             <strong>
                                 '._CURRENT_MEMBERSHIPS.':
                             </strong>
                         </u>
                     <br />
                     '.$s_member_groups.'
                     <hr noshade="noshade" />
                    ';
    }
    if (isset($s_pending_groups)) {
        $content .= '
                     <img src="images/blocks/group-3.gif" border="0" height="14" width="17" />
                         <u>
                             <strong>
                                 '._MEMBERSHIPS_PENDING.':
                             </strong>
                         </u>
                     <br />
                     '.$s_pending_groups.'
                     <hr noshade="noshade" />
                    ';
    }
}
if ($s_group_list != '') {
    $content .= '
                 <img src="images/blocks/group-5.gif" border="0" height="14" width="17" />
                     <u>
                         <strong>
                             '._GROUP_MEMBER_JOIN.':
                         </strong>
                     </u>
                 <br />
                 '.$s_group_list.'
                ';
}
if (!is_user()) {
    $content .= '
                 <br />
                 <div align="center">
                     <font class="content">
                         '._LOGIN_TO_JOIN.'
                     </font>
                 </div>
                ';
}

?>