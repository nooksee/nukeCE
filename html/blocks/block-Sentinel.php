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

if(!defined('NUKE_FILE') && !defined('BLOCK_FILE')) { die('You can\'t access this file directly...'); }
global $db, $prefix, $ab_config;
$total_ips = $db->sql_numrows($db->sql_query('SELECT * FROM `'.$prefix.'_nsnst_blocked_ips`'));
if(!$total_ips) { $total_ips = 0; }
$content  = '
             <div align="center">
                 <img src="images/nukesentinel/Sentinel_Medium.png" alt="You have been warned!" title="You have been warned!" />
                 <br />
                 We have caught '.intval($total_ips).' shameful hackers.
                 <hr />
                 <a href="http://www.ravenphpscripts.com" target="_blank" title="NukeSentinel(tm) Available at RavenPHPScripts">
                     '._AB_SENTINEL.' '.$ab_config['version_number'].'
                 </a>
             </div>
            ';

?>