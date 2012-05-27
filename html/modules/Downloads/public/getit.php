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

if(!defined('IN_DOWNLOADS')) {
    exit('Access Denied');
}

$lid = intval($lid);
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE lid=$lid AND active>'0'");
$lidinfo = $db->sql_fetchrow($result);
$pagetitle = _DOWNLOADPROFILE.": ".stripslashes($lidinfo['title']);
$priv = $lidinfo['sid'] - 2;
include_once(NUKE_BASE_DIR.'header.php');
$maindownload = 1;
menu(1);
if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv)) || $dl_config['show_download'] == '1') {
    if (empty($lidinfo['lid']) OR $lidinfo['active'] == 0) {
        DisplayError(_INVALIDDOWNLOAD, 1);
        exit;
    } else {
        $fetchid = base64_encode($lidinfo['url']);
        $title = stripslashes($lidinfo['title']);
        OpenTable();
        echo "<center><font class=\"option\"><b>"._DOWNLOADPROFILE.": $title</b></font><br><br>";
        downloadinfomenu($lid);
        mt_srand ((double)microtime()*1000000);
        $maxran = 1000000;
        $random_num = mt_rand(0, $maxran);
        /*****[BEGIN]******************************************
        [ Mod:     News BBCodes                       v1.0.0 ]
        ******************************************************/
        $lidinfo['description'] = BBCode2Html(stripslashes($lidinfo['description']));
        $lidinfo['description'] = nuke_img_tag_to_resize($lidinfo['description']);
        /*****[END]********************************************
        [ Mod:     News BBCodes                       v1.0.0 ]       
        ******************************************************/
echo "<br><font class=\"content\">".$lidinfo['description']."<br>";

        if (empty($lidinfo['name'])) {
            $lidinfo['name'] = "<i>"._UNKNOWN."</i>";
        } else {
            if (empty($lidinfo['email'])) {
                $lidinfo['name'] = $lidinfo['name'];
            } else {
                $lidinfo['email'] = str_replace ("@"," <i>at</i> ", $lidinfo['email']);
                $lidinfo['email'] = str_replace ("."," <i>dot</i> ", $lidinfo['email']);
                $lidinfo['name'] = "".$lidinfo['name']." (".$lidinfo['email'].")";
            }
        }
        echo "
              <br>
              <b>"._AUTHOR.":</b> ".$lidinfo['name']."<br>
              <b>"._VERSION.":</b> ".$lidinfo['version']." <b>"._FILESIZE.":</b> ".CoolSize($lidinfo['filesize'])."<br>
              <b>"._ADDEDON.":</b> ".CoolDate($lidinfo['date'])."<br>
              <b>"._HOMEPAGE.":</b></font>
             ";
        if (empty($lidinfo['homepage']) || $lidinfo['homepage'] == "http://") {
            echo _DL_NOTLIST;
        } else {
            echo "<a href='".$lidinfo['homepage']."' target='new'>".$lidinfo['homepage']."</a>";
        }
        echo "<br /><br />";
        if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv))) {
            $message = "<b>"._DL_DIRECTIONS."</b>&nbsp;"._DL_DLNOTES1."$title"._DL_DLNOTES2."<br>";
            info_box("warning", $message);
        }
        if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv))) {
            echo "
                  <center>
                    <table border='0'>
                    <form action='modules.php?name=$module_name' method='POST'>
                 ";
            if ($dl_config['usegfxcheck'] == 1) {
                echo security_code(1,'bold', 1);
            }
            echo "
                        <tr>
                            <td colspan='2' align='center'><br />
                                <input type='hidden' name='op' value='go'>
                                <input type='hidden' name='lid' value='".$lidinfo['lid']."'>
                                <input type='hidden' name='fetchid' value='$fetchid'>
                                <input type='submit' name='"._DL_GOGET."' value='"._DL_GOGET."'>
                            </td>
                        </tr>
                    </form>
                    </table>
                  </center>
                  <br />
                 ";
        } else {
            restricted($lidinfo['sid']);
        }
    }
    CloseTable();
} else {
    OpenTable();
    restricted($lidinfo['sid']);
    CloseTable();
}
include_once(NUKE_BASE_DIR.'footer.php');

?>