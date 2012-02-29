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
    "signature" => "admin/board_config/board_signature.tpl")
);

/*****[BEGIN]******************************************
 [ Mod:     Advance Signature Divider Control  v1.0.0 ]
 ******************************************************/
$new['sig_line'] = str_replace('"', '&quot;', $new['sig_line']);
/*****[END]********************************************
 [ Mod:     Advance Signature Divider Control  v1.0.0 ]
 ******************************************************/
 
//General Template variables
$template->assign_vars(array(
    "DHTML_ID" => "c" . $dhtml_id)
);
    
//Language Template variables
$template->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:     Advance Signature Divider Control  v1.0.0 ]
 ******************************************************/
    "L_SIG_TITLE" => $lang['sig_title'],
    "L_SIG_EXPLAIN" => $lang['sig_explain'],
    "L_SIG_INPUT" => $lang['sig_divider'],
/*****[END]********************************************
 [ Mod:     Advance Signature Divider Control  v1.0.0 ]
 ******************************************************/
    "L_MAX_SIG_LENGTH" => $lang['Max_sig_length'],
    "L_MAX_SIG_LENGTH_EXPLAIN" => $lang['Max_sig_length_explain'],
));

//Data Template Variables
$template->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:     Advance Signature Divider Control  v1.0.0 ]
 ******************************************************/
    "SIG_DIVIDERS" => $new['sig_line'],
/*****[END]********************************************
 [ Mod:     Advance Signature Divider Control  v1.0.0 ]
 ******************************************************/
    "SIG_SIZE" => $new['max_sig_chars'],
 ));
$template->pparse("signature");

?>