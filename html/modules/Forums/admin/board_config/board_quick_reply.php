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

if (!defined('BOARD_CONFIG')) {
    die('Access Denied');
}

$template->set_filenames(array(
    "quick_reply" => "admin/board_config/board_quick_reply.tpl")
);

/*****[BEGIN]******************************************
 [ Mod:    PM Quick Reply                      v1.3.5 ]
 ******************************************************/
$ropm_quick_reply_yes = ( $new['ropm_quick_reply'] ) ? "checked=\"checked\"" : "";
$ropm_quick_reply_no = ( !$new['ropm_quick_reply'] ) ? "checked=\"checked\"" : "";
$ropm_quick_reply_bbc_yes = ( $new['ropm_quick_reply_bbc'] ) ? "checked=\"checked\"" : "";
$ropm_quick_reply_bbc_no = ( !$new['ropm_quick_reply_bbc'] ) ? "checked=\"checked\"" : "";
/*****[END]********************************************
 [ Mod:    PM Quick Reply                      v1.3.5 ]
 ******************************************************/

//General Template variables
$template->assign_vars(array(
    "DHTML_ID" => "c" . $dhtml_id)
);
    
//Language Template variables
$template->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:    PM Quick Reply                      v1.3.5 ]
 ******************************************************/
    "L_ENABLE_ROPM_QUICK_REPLY" => $lang['enable_ropm_quick_reply'],
    "L_ROPM_QUICK_REPLY" => $lang['ropm_quick_reply'],
    "L_ROPM_QUICK_REPLY_BBC" => $lang['ropm_quick_reply_bbc'],
    "L_ROPM_QUICK_REPLY_SMILIES" => $lang['ropm_quick_reply_smilies'],
    "L_ROPM_QUICK_REPLY_SMILIES_INFO" => $lang['ropm_quick_reply_smilies_info'],
/*****[END]********************************************
 [ Mod:    PM Quick Reply                      v1.3.5 ]
 ******************************************************/
));

//Data Template Variables
$template->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:    PM Quick Reply                      v1.3.5 ]
 ******************************************************/
    "ROPM_QUICK_REPLY_YES" => $ropm_quick_reply_yes,
    "ROPM_QUICK_REPLY_NO" => $ropm_quick_reply_no,
    "ROPM_QUICK_REPLY_BBC_YES" => $ropm_quick_reply_bbc_yes,
    "ROPM_QUICK_REPLY_BBC_NO" => $ropm_quick_reply_bbc_no,
    "ROPM_QUICK_REPLY_SMILIES" => $new['ropm_quick_reply_smilies'],
/*****[END]********************************************
 [ Mod:    PM Quick Reply                      v1.3.5 ]
 ******************************************************/
 ));
$template->pparse("quick_reply");

?>