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

if(!empty($uploaddir)) {
    if(substr($uploaddir,0,1) == '/') {
        $uploaddir = substr($uploaddir,1);
    }
    $folder = dirname(dirname(__FILE__)) . '/files' . $uploaddir;
    if(!is_dir($folder)) {
        if(mkdir($folder)) {
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            $pagetitle = _CATEGORIESADMIN.": "._DL_ERROR;
            title($pagetitle);
            OpenTable();
            echo "<center><strong>".sprintf(_DL_ERROR_DIR,$folder)."</strong></center><br />\n";
            echo "<center>"._GOBACK."</center>\n";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
            die();
        }
        if(!copy(dirname(dirname(__FILE__)) . '/files/.htaccess', $folder.'/.htaccess')) {
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            $pagetitle = _CATEGORIESADMIN.": "._DL_ERROR;
            title($pagetitle);
            OpenTable();
            echo "<center><strong>".sprintf(_DL_ERROR_HT,$folder)."</strong></center><br />\n";
            echo "<center>"._GOBACK."</center>\n";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
            die();
        }
        if(!copy(dirname(dirname(__FILE__)) . '/files/index.html', $folder.'/index.html')) {
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            $pagetitle = _CATEGORIESADMIN.": "._DL_ERROR;
            title($pagetitle);
            OpenTable();
            echo "<center><strong>".sprintf(_DL_ERROR_INDEX,$folder)."</strong></center><br />\n";
            echo "<center>"._GOBACK."</center>\n";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
            die();
        }
    }
}

if (!isset($min)) { $min = 0; }
$title = Fix_Quotes($title);
$url = Fix_Quotes($url);
$description = Fix_Quotes($description);
$name = Fix_Quotes($name);
$email = Fix_Quotes($email);
$perm = Fix_Quotes($perm);
$filesize = str_replace(',', '', $filesize);
$filesize = str_replace('.', '', $filesize);
$filesize = intval($filesize);
$db->sql_query("UPDATE ".$prefix."_downloads_downloads SET cid='$cat', sid='$perm', title='$title', url='$url', description='$description', name='$rname', email='$email', hits='$hits', filesize='$filesize', version='$version', homepage='$homepage' WHERE lid='$lid'");
redirect($admin_file.".php?op=Downloads&min=$min");

?>