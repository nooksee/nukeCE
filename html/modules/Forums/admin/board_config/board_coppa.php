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
    "coppa" => "admin/board_config/board_coppa.tpl")
);

//General Template variables
$template->assign_vars(array(
    "DHTML_ID" => "c" . $dhtml_id)
);
    
//Language Template variables
$template->assign_vars(array(
    "L_COPPA_SETTINGS" => $lang['COPPA_settings'],
    "L_COPPA_FAX" => $lang['COPPA_fax'],
    "L_COPPA_MAIL" => $lang['COPPA_mail'],
    "L_COPPA_MAIL_EXPLAIN" => $lang['COPPA_mail_explain'],
));

//Data Template Variables
$template->assign_vars(array(
    "COPPA_MAIL" => $new['coppa_mail'],
    "COPPA_FAX" => $new['coppa_fax'],
 ));
$template->pparse("coppa");

?>