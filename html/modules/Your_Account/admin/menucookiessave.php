<?php

/********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ================================================================             */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion */
/********************************************************************************/

if(!defined('IN_YA')) {
    exit('Access Denied');
}

if(is_mod_admin($module_name)) {
    ya_save_config('cookiecheck', $xcookiecheck);
    ya_save_config('cookiecleaner', $xcookiecleaner);
    ya_save_config('cookietimelife', $xcookietimelife, 'nohtml');
    ya_save_config('cookiepath', $xcookiepath, 'nohtml');
    ya_save_config('cookieinactivity', $xcookieinactivity, 'nohtml');

    global $cache;
    $cache->delete('ya_config', 'config');
    redirect($admin_file.".php?op=CookieConfig");

}

?>