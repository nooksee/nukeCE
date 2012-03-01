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

$dbhost = 'localhost';
$dbname = '';
$dbuname = '';
$dbpass = '';
$dbtype = 'mysql';
$prefix = 'nuke';
$user_prefix = 'nuke';
$admin_file = 'admin';
$directory_mode = 0777;
$file_mode = 0666;
$debug = true;
$use_cache = 1;
$persistency = false;

?>