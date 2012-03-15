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

$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE title='$title' AND parentid='$cid'"));
$pagetitle = _CATEGORIESADMIN.": "._DL_ERROR;

if(!empty($uploaddir)) {
    if(substr($uploaddir,0,1) == '/') {
        $uploaddir = substr($uploaddir,1);
    }
    $folder = dirname(dirname(__FILE__)) . '/files/' . $uploaddir;
    if(!is_dir($folder)) {
        if(!mkdir($folder)) {
            DisplayErrorReturn(sprintf(_DL_ERROR_DIR,$folder));
            die();
        }
        if(!copy(dirname(dirname(__FILE__)) . '/files/.htaccess', $folder.'/.htaccess')) {
            DisplayErrorReturn(sprintf(_DL_ERROR_HT,$folder));
            die();
        }
        if(!copy(dirname(dirname(__FILE__)) . '/files/index.html', $folder.'/index.html')) {
            DisplayErrorReturn(sprintf(_DL_ERROR_INDEX,$folder));
            die();
        }
    }
}

if ($numrows>0) {
    DisplayErrorReturn(""._ERRORTHESUBCATEGORY." $title "._ALREADYEXIST."");
} else {
    $db->sql_query("INSERT INTO ".$prefix."_downloads_categories VALUES (NULL, '$title', '$cdescription', '$cid', '$whoadd', '$uploaddir', '$canupload', 1)");
    redirect($admin_file.".php?op=Categories");
}

?>