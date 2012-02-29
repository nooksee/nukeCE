<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(is_god($_COOKIE['admin'])) {
    $aidrow = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_admins` WHERE `aid`='$a_aid'"));
    $subject = _AB_ACCESSFOR." ".$nuke_config['sitename'];
    $message  = ""._AB_HTTPONLY."\n";
    $message .= ""._AB_LOGIN.": ".$aidrow['login']."\n";
    $message .= ""._AB_PASSWORD.": ".$aidrow['password']."\n";
    $message .= ""._AB_PROTECTED.": ";
    if($aidrow['protected']==0) { $message .= ""._AB_NO."\n"; } else { $message .= ""._AB_YES."\n"; }
    list($amail) = $db->sql_fetchrow($db->sql_query("SELECT `email` FROM `".$prefix."_authors` WHERE `aid`='$a_aid'"));
    @nuke_mail($amail, $subject, $message,"From: ".$nuke_config['adminmail']."\r\nX-Mailer: "._AB_SENTINEL."\r\n");
    Header("Location: ".$admin_file.".php?op=ABAuthList");
} else {
    Header("Location: ".$admin_file.".php?op=ABMain");
}

?>