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

include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_auc.' . $phpEx);

// Start session management 
$userdata = session_pagestart($user_ip, PAGE_INDEX); 
init_userprefs($userdata); 
// End session management 

$group = (!empty($HTTP_POST_VARS['id'])) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id']; 
$exist = $HTTP_GET_VARS['group'];        
    
$template->set_filenames(array('body' => 'auc_listing_body.tpl') );    

if($exist) {
    if($exist == "admins") {
        $group_name = str_replace("%s", "", $lang['Admin_online_color']);        
        $g = ADMIN;
    } elseif($exist == "mods") {
        $group_name = str_replace("%s", "", $lang['Mod_online_color']);
        $g = MOD;
    } elseif($exist == "less_admins") {
        $group_name = str_replace("%s", "", $lang['Super_Mod_online_color']);    
        $g = LESS_ADMIN;
    }

    $template->assign_vars(array("T_L" => $lang['listing_left'], "T_C_2" => $group_name, "T_R" => $lang['listing_right'])); 

    $i = 1;
    $q = "SELECT * FROM ". USERS_TABLE ." WHERE user_level = '". $g ."' ORDER BY user_id ASC"; 
    $r = $db->sql_query($q);
    while($row1 = $db->sql_fetchrow($r)) {
        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 

        $msn = ($row1['user_msnm']) ? '<a href="mailto: '. $row1['user_msnm'] .'"><img src="'. $images['icon_msnm'] .'" alt="'. $lang['MSNM'] .'" title="'. $lang['MSNM'] .'" border="0" /></a>' : '';
        $yim = ($row1['user_yim']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='. $row1['user_yim'] .'&amp;.src=pg"><img src="'. $images['icon_yim'] .'" alt="'. $lang['YIM'] .'" title="'. $lang['YIM'] .'" border="0" /></a>' : '';
        $aim = ($row1['user_aim']) ? '<a href="aim:goim?screenname='. $row1['user_aim'] .'&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] .'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
        $icq = ($row1['user_icq']) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='. $row1['user_icq'] .'"><img src="' . $images['icon_icq'] .'" alt="'. $lang['ICQ'] .'" title="' . $lang['ICQ'] .'" border="0" /></a>' : '';       
        $www = ($row1['user_website']) ? '<a href="'. $row1['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] . '" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" border="0" /></a>' : '';
        $mailto = ($board_config['board_email_form']) ? append_sid("profile.$phpEx?mode=email&amp;". POST_USERS_URL .'='. $row1['user_id']) : 'mailto:'. $row1['user_email'];            
        $mail = ($row1['user_email']) ? '<a href="'. $mailto .'"><img src="'. $images['icon_email'] .'" alt="'. $lang['Send_email'] .'" title="'. $lang['Send_email'] .'" border="0" /></a>' : '';
/*****[BEGIN]******************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/
        $temp_url = append_sid('album_personal.' . $phpEx . '?user_id=' . $row1[$a]['user_id']);
        $album = '<a href="' . $temp_url . '"><img src="' . $images['icon_album'] . '" alt="' . sprintf($lang['Personal_Gallery_Of_User'], $row1[$a]['username']) . '" title="' . sprintf($lang['Personal_Gallery_Of_User'], $row1[$a]['username']) . '" border="0" /></a>';
/*****[END]********************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/					
        $pmto = append_sid("privmsg.$phpEx?mode=post&amp;". POST_USERS_URL ."=$row1[user_id]");
        $pm = '<a href="'. $pmto .'"><img src="'. $images['icon_pm'] .'" alt="'. $lang['Send_private_message'] .'" title="'. $lang['Send_private_message'] .'" border="0" /></a>';
        $pro = append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=$row1[user_id]");
        $profile = '<a href="'. $pro .'"><img src="'. $images['icon_profile'] .'" alt="'. $lang['Profile'] .'" title="'. $lang['Profile'] .'" border="0" /></a>';        

        $info = $profile ." ". $pm;
        if($msn) $info .= " ". $msn;
        if($yim) $info .= " ". $yim;
        if($aim) $info .= " ". $aim;
        if($icq) $info .= " ". $icq;
/*****[BEGIN]******************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/
        if($album) $info .= " ". $album;
/*****[END]********************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/					
        if($www) $info .= " ". $www;
        if($mail) $info .= " ". $mail;

        if ($row1['user_level'] == ADMIN) $style_color = '#' . $theme['fontcolor3'];
        elseif ($row1['user_level'] == MOD) $style_color = '#' . $theme['fontcolor2'];
        elseif ($row['user_level'] == LESS_ADMIN) $style_color = '#' . $theme['fontcolor4'];

        $template->assign_block_vars("colors", array("USER" => "<font color='". $style_color ."'>". $row1['username'] ."</font>", "ROW_CLASS" => $row_class, "INFO_LINE" => $info)); 
        $i++;        
    }       
} elseif ($group) { 
    $sql = "SELECT * FROM ". $prefix ."_bbadvanced_username_color WHERE group_id = '". $group ."' "; 
    if (!$result = $db->sql_query($sql)) 
    message_die(GENERAL_ERROR, "Error Selecting Group Name.", "", __LINE__, __FILE__, $sql); 
    $row = $db->sql_fetchrow($result);

    $i = 1;
    $q = "SELECT * FROM ". USERS_TABLE ." WHERE user_color_gi <> '' ORDER BY username ASC"; 
    $r = $db->sql_query($q);
    $row1 = $db->sql_fetchrowset($r);

    for ($a = 0; $a < count($row1); $a++) {
        if (!$row1[$a]['user_id'])
        break;

        if (eregi('--'. $group .'--', $row1[$a]['user_color_gi'])) {
            $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
            $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 

            $msn = ($row1[$a]['user_msnm']) ? '<a href="mailto: '. $row1[$a]['user_msnm'] .'"><img src="'. $images['icon_msnm'] .'" alt="'. $lang['MSNM'] .'" title="'. $lang['MSNM'] .'" border="0" /></a>' : '';
            $yim = ($row1[$a]['user_yim']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='. $row1[$a]['user_yim'] .'&amp;.src=pg"><img src="'. $images['icon_yim'] .'" alt="'. $lang['YIM'] .'" title="'. $lang['YIM'] .'" border="0" /></a>' : '';
            $aim = ($row1[$a]['user_aim']) ? '<a href="aim:goim?screenname='. $row1[$a]['user_aim'] .'&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] .'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
            $icq = ($row1[$a]['user_icq']) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='. $row1[$a]['user_icq'] .'"><img src="' . $images['icon_icq'] .'" alt="'. $lang['ICQ'] .'" title="' . $lang['ICQ'] .'" border="0" /></a>' : '';       
            $www = ($row1[$a]['user_website']) ? '<a href="'. $row1[$a]['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] . '" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" border="0" /></a>' : '';
            $mailto = ($board_config['board_email_form']) ? append_sid("profile.$phpEx?mode=email&amp;". POST_USERS_URL .'='. $row1[$a]['user_id']) : 'mailto:'. $row1[$a]['user_email'];            
            $mail = ($row1[$a]['user_email']) ? '<a href="'. $mailto .'"><img src="'. $images['icon_email'] .'" alt="'. $lang['Send_email'] .'" title="'. $lang['Send_email'] .'" border="0" /></a>' : '';
/*****[BEGIN]******************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/
            $temp_url = append_sid('album_personal.' . $phpEx . '?user_id=' . $row1[$a]['user_id']);
            $album = '<a href="' . $temp_url . '"><img src="' . $images['icon_album'] . '" alt="' . sprintf($lang['Personal_Gallery_Of_User'], $row1[$a]['username']) . '" title="' . sprintf($lang['Personal_Gallery_Of_User'], $row1[$a]['username']) . '" border="0" /></a>';
/*****[END]********************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/					
            $pmto = append_sid("privmsg.$phpEx?mode=post&amp;". POST_USERS_URL ."=". $row1[$a]['user_id']);
            $pm  = '<a href="'. $pmto .'"><img src="'. $images['icon_pm'] .'" alt="'. $lang['Send_private_message'] .'" title="'. $lang['Send_private_message'] .'" border="0" /></a>';
            $pro = append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=". $row1[$a]['user_id']);
            $profile = '<a href="'. $pro .'"><img src="'. $images['icon_profile'] .'" alt="'. $lang['Profile'] .'" title="'. $lang['Profile'] .'" border="0" /></a>';                
            $info = $profile .' '. $pm;

            if ($msn)
                $info .= ' '. $msn;
            if ($yim)    
                $info .= ' '. $yim;
            if ($aim) 
                $info .= ' '. $aim;
            if ($icq)    
                $info .= ' '. $icq;
/*****[BEGIN]******************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/
            if ($album)
                $info .= ' '. $album;
/*****[END]********************************************
[ Mod:     Album                             v2.0.56 ]
******************************************************/					
            if ($www)
                $info .= ' '. $www;
            if ($mail)
                $info .= ' '. $mail;

            $i++;

            $template->assign_block_vars('colors', array('USER' => UsernameColor($row1[$a]['username']),'ROW_CLASS' => $row_class, 'INFO_LINE' => $info)); 
        }    
    }
} else
redirect('index.'. $phpEx, TRUE);

if ($i == 1) message_die(GENERAL_MESSAGE, sprintf($lang['listing_none'], '<strong>'. $row['group_name'] .'</strong>'));

$template->assign_vars(array("T_L" => $lang['listing_left'], "T_C_2" => $row['group_name'], "T_R" => $lang['listing_right'])); 
                            
// Generate page
include(NUKE_FORUMS_DIR.'includes/page_header.'.$phpEx);
$template->pparse('body');
include(NUKE_FORUMS_DIR.'includes/page_tail.'.$phpEx);

?>