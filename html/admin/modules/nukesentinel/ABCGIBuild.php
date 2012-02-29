<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(is_god($_COOKIE['admin'])) {
    if($ab_config['staccess_path'] > "") {
        $stwrite = "";
        $adminresult = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_admins` WHERE `password_crypt`>'' ORDER BY `aid`");
        while($adminrow = $db->sql_fetchrow($adminresult)) {
            $stwrite .= $adminrow['login'].":".$adminrow['password_crypt']."\n";
            $doit = fopen($ab_config['staccess_path'], "w");
            fwrite($doit, $stwrite);
            fclose($doit);
        }
    }
    Header("Location: ".$admin_file.".php?op=ABAuthList");
} else {
    Header("Location: ".$admin_file.".php?op=ABMain");
}

?>