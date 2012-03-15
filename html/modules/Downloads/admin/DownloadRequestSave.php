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

/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

$pagetitle = _DOWNLOADSADMIN;
$numrows = $db->sql_numrows($db->sql_query("SELECT url FROM ".$prefix."_downloads_downloads WHERE url='$url'"));
if ($numrows>0) {
    DisplayError(_ERRORURLEXIST);
    die();
} else {
    if ($title=="" || $url=="" || $description=="") {
        if($title=="") {
            DisplayErrorReturn(_ERRORNOTITLE);
            return;
        }
        if($url=="") {
            DisplayErrorReturn(_ERRORNOURL);
            return;
        }
        if($description=="") {
            DisplayErrorReturn(_ERRORNODESCRIPTION);
            return;
        }
    }
    
    $title = Fix_Quotes($title);
    $url = Fix_Quotes($url);
    $description = Fix_Quotes($description);
    $sname = Fix_Quotes($sname);
    $email = Fix_Quotes($email);
    $sub_ip = $_SERVER['REMOTE_ADDR'];
    $filesize = str_replace(',', '', $filesize);
    $filesize = str_replace('.', '', $filesize);
    $filesize = intval($filesize);
    
    if (empty($submitter)) { $submitter = $aname; }
    $db->sql_query("INSERT INTO ".$prefix."_downloads_downloads VALUES (NULL, '$cat', '$perm', '$title', '$url', '$description', now(), '$sname', '$email', '$hits', '$submitter', '$sub_ip', '$filesize', '$version', '$homepage', '1')");
    if ($new==1) {
        $result = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_accesses WHERE username='$sname'"));
        if ($result < 1) {
            $db->sql_query("INSERT INTO ".$prefix."_downloads_accesses VALUES ('$sname', 0, 1)");
        } else {
            $db->sql_query("UPDATE ".$prefix."_downloads_accesses SET uploads=uploads+1 WHERE username='$submitter'");
        }
        $db->sql_query("DELETE FROM ".$prefix."_downloads_new WHERE lid='$lid'");
        if ($email!="") {
            $subject = ""._YOURDOWNLOADAT." $sitename";
            $message = ""._HELLO." $sname:\n\n"._DL_APPROVEDMSG."\n\n"._TITLE.": $title\n"._URL.": $url\n"._DESCRIPTION.": $description\n\n\n"._YOUCANBROWSEUS." $nukeurl/modules.php?name=$module_name\n\n"._THANKS4YOURSUBMISSION."\n\n$sitename "._TEAM."";
            $from = "$sitename";
            @nuke_mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . PHPVERS);
            $cache->delete('numwaitd', 'submissions');
        }
    }
    if($xop == "DownloadNew") { $zop = $xop; } else { $zop = "Downloads"; }
    redirect($admin_file.".php?op=".$zop);
}

?>