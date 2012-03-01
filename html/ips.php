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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

global $ips, $users_ips;

// IPs that are used to allowed access to admin.php and forum admin; all others will be denied
// Seperate all IPs by a comma.
// ex: $ips = array('127.0.0.1', '192.168.1.1');

//$ips = array('xxx.xxx.xxx.xxx');

// IPs that are allowed to login to the specified user accounts
// Seperate all IPs by a comma inside the second ''.
// ex: $users_ips = array('Technocrat', '127.0.0.1,192.168.1.1');

//$users_ips = array('name', 'xxx.xxx.xxx.xxx');

?>