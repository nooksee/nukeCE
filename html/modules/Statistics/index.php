<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
get_lang($module_name);

include_once(NUKE_BASE_DIR.'header.php');
require_once(NUKE_INCLUDE_DIR.'functions_stats.php');

$year = isset($_GET['year']) ? intval($_GET['year']) : 0;
$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
$date = isset($_GET['date']) ? intval($_GET['date']) : 0;

switch(strtolower($op)) {
    case 'stats':   Stats();                        break;
    case 'yearly':  YearlyStats($year);             break;
    case 'monthly': MonthlyStats($year,$month);     break;
    case 'daily':   DailyStats($year,$month,$date); break;
    default:        Stats_Main();                   break;
}

include_once(NUKE_BASE_DIR.'footer.php');

?>