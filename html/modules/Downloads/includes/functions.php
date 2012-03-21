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

function of_group($gid) {
    global $prefix, $db, $userinfo, $module_name;
    if (is_mod_admin($module_name)) {
        return 1;
    } elseif (is_user()) {
        $guid = $userinfo['user_id'];
        $currdate = time();
        $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_bbuser_group WHERE group_id='".$gid."' AND user_id='$guid' AND user_pending != '1'");
        list($ingroup) = $db->sql_fetchrow($result);
        if ($ingroup > 0) { return 1; }
    }
    return 0;
}

function myimage($imgfile) {
    global $module_name;
    $ThemeSel = get_theme();
    if (file_exists("themes/$ThemeSel/images/$imgfile")) {
        $myimage = "themes/$ThemeSel/images/$imgfile";
    } else {
        $myimage = "images/$imgfile";
    }
    return($myimage);
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function downloads_get_configs(){
    global $prefix, $db, $cache;
    static $config;
    if(isset($config)) return $config;
/*****[BEGIN]******************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
    if(($config = $cache->load('downloads', 'config')) === false) {
/*****[END]********************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
        $configresult = $db->sql_query("SELECT config_name, config_value FROM ".$prefix."_downloads_config");
        while (list($config_name, $config_value) = $db->sql_fetchrow($configresult)) {
            $config[$config_name] = $config_value;
        }
/*****[BEGIN]******************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
        $cache->save('downloads', 'config', $config);
        }
/*****[END]********************************************
[ Base:    Caching System                     v3.0.0 ]
******************************************************/
    return $config;
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function downloads_save_config($config_name, $config_value){
    global $prefix, $db, $cache;
    $db->sql_query("UPDATE ".$prefix."_downloads_config SET config_value='$config_value' WHERE config_name='$config_name'");
    /*****[BEGIN]******************************************
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
    $cache->delete('downloads', 'config');
    /*****[END]********************************************
    [ Base:    Caching System                     v3.0.0 ]
    ******************************************************/
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function CrawlLevelR($parentid) {
    global $prefix, $db, $crawler;
    $bresult = $db->sql_query("SELECT parentid FROM ".$prefix."_downloads_categories WHERE cid='$parentid' ORDER BY title");
    while(list($parentid2)=$db->sql_fetchrow($bresult)){
        array_push($crawler,$parentid2);
        CrawlLevelR($parentid2);
    }
    return $crawler;
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function CrawlLevel($cid) {
    global $prefix, $db, $crawled;
    $bresult = $db->sql_query("SELECT cid FROM ".$prefix."_downloads_categories WHERE parentid='$cid' ORDER BY title");
    while(list($cid2)=$db->sql_fetchrow($bresult)){
        array_push($crawled,$cid2);
        CrawlLevel($cid2);
    }
    return $crawled;
}

function CoolSize($size) {
    $mb = 1024*1024;
    $gb = $mb*1024;
    if ( $size > $gb ) {
        $mysize = sprintf ("%01.2f",$size/$gb)." "._GB;
    } elseif ( $size > $mb ) {
        $mysize = sprintf ("%01.2f",$size/$mb)." "._MB;
    } elseif ( $size >= 1024 ) {
        $mysize = sprintf ("%01.2f",$size/1024)." "._KB;
    } else {
        $mysize = $size." "._BYTES;
    }
    return $mysize;
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function CoolDate($date) {
    global $dl_config;
    $mydate = formatTimestamp($date);
    return $mydate;
}

// Copyright (c) 2003 --- Michael K. Squires ---
// Can not be reproduced in whole or in part without
// written consent from Michael K. Squires
function getcategoryinfo($catID){
    global $prefix, $db, $user;
    $category = array($catID);
    $cats_detected = 0;
    $downloads_detected = 0;
    while(count($category) != 0){
        sort($category, SORT_STRING);
        reset($category);
        $curr_category = end($category);
        $dresult = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE cid='$curr_category'");
        $catdownloads = $db->sql_numrows($dresult);
        $downloads_detected += $catdownloads;
        $cresult = $db->sql_query("SELECT cid FROM ".$prefix."_downloads_categories WHERE parentid='$curr_category'");
        while (list($cid) = $db->sql_fetchrow($cresult)){
            array_unshift($category, "$cid");
            $cats_detected++;
        }
        array_pop($category);
    }
    $categoryinfo['categories'] = $cats_detected;
    $categoryinfo['downloads'] = $downloads_detected;
    return $categoryinfo;
}

function getparent($parentid,$title) {
    global $prefix,$db;
    $result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid='$parentid'");
    $cidinfo = $db->sql_fetchrow($result);
    if ($cidinfo['title'] != "") $title = $cidinfo['title']."/".$title;
    if ($cidinfo['parentid'] != 0) { $title=getparent($cidinfo['parentid'], $title); }
    return $title;
}

function getparentlink($parentid,$title) {
    global $prefix, $db, $module_name;
    $parentid = intval($parentid);
    $cidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid=$parentid"));
    if ($cidinfo['title'] != "") $title = "<a href='modules.php?name=$module_name&amp;cid=".$cidinfo['cid']."'>".$cidinfo['title']."</a>/".$title;
    if ($cidinfo['parentid'] != 0) { $title = getparentlink($cidinfo['parentid'],$title); }
    return $title;
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function restricted($perm) {
    global $db, $prefix, $module_name;
    if ($perm == 1) {
        $who_view = _DL_USERS;
    } elseif ($perm == 2) {
        $who_view = _DL_ADMIN;
    } elseif ($perm >2) {
        $newView = $perm - 2;
        list($who_view) = $db->sql_fetchrow($db->sql_query("SELECT group_name FROM ".$prefix."_bbgroups WHERE group_id=$newView"));
        $who_view = $who_view." "._DL_ONLY;
    }
    $myimage = myimage("restricted.png");
    echo "
          <br />
          <div align='center'>
              <img src='$myimage' alt=''><br /><br>
              <font class=\"title\">
                  <b>"._DL_DENIED."</b>
              </font><br><br>
              "._DL_CANBEDOWN." $who_view<br><br>
              "._DOWNLOADSNOTUSER8."
          </div>
         ";
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function restricted2($perm) {
    global $db, $prefix, $module_name;
    if ($perm == 1) {
        $who_view = _DL_USERS;
    } elseif ($perm == 2) {
        $who_view = _DL_ADMIN;
    } elseif ($perm >2) {
        $newView = $perm - 2;
        list($who_view) = $db->sql_fetchrow($db->sql_query("SELECT group_name FROM ".$prefix."_bbgroups WHERE group_id=$newView"));
        $who_view = $who_view." "._DL_ONLY;
    }
    $myimage = myimage("restricted.png");
    echo "
          <div align='center'>
              <img src='$myimage' alt=''><br>
              <font class=\"option\"><b>"._DL_DENIED."</b></font><br>
              <em>"._DL_CANBEVIEW."</em><br>
              <b>$who_view</b><br><br>
              "._DOWNLOADSNOTUSER8."
          </div>
         ";
}

function newdownloadgraphic($datetime, $time) {
    global $module_name;
    setlocale (LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    $startdate = time();
    $count = 0;
    
    echo "&nbsp;";
    while ($count <= 14) {
        $daysold = date("d-M-Y", $startdate);
        if ($daysold == $datetime) {
            $myimage = myimage("new_1.gif");
            if ($count<=1) { echo "<img align='top' src='$myimage' alt='"._NEWTODAY."' title='"._NEWTODAY."'>"; }
            $myimage = myimage("new_7.gif");
            if ($count<=3 && $count>1) { echo "<img align='top' src='$myimage' alt='"._NEWLAST3DAYS."' title='"._NEWLAST3DAYS."'>"; }
            $myimage = myimage("new-english.gif");
            if ($count<=7 && $count>3) { echo "<img align='top' src='$myimage' alt='"._NEWTHISWEEK."' title='"._NEWTHISWEEK."'>"; }
            $myimage = myimage("new_3.gif");
            if ($count<=14 && $count>7) { echo "<img align='top' src='$myimage' alt='"._NEWLAST2WEEKS."' title='"._NEWLAST2WEEKS."'>"; }
        }
        $count++;
        $startdate = (time()-(86400 * $count));
    }
}

function newcategorygraphic($cat) {
    global $prefix, $db, $module_name;
    $cat = intval($cat);
    $newresult = $db->sql_query("SELECT date FROM ".$prefix."_downloads_downloads WHERE cid=$cat ORDER BY date DESC LIMIT 1");
    list($time)=$db->sql_fetchrow($newresult);
    setlocale (LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime(""._LINKSDATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    $startdate = time();
    $count = 0;
    
    echo "&nbsp;";
    while ($count <= 14) {
        $daysold = date("d-M-Y", $startdate);
        if ("$daysold" == "$datetime") {
            $myimage = myimage("new_1.gif");
            if ($count<=1) { echo "<img align='top' src='$myimage' alt='"._DCATNEWTODAY."' title='"._DCATNEWTODAY."'>"; }
            $myimage = myimage("new_7.gif");
            if ($count<=3 && $count>1) { echo "<img align='top' src='$myimage' alt='"._DCATLAST3DAYS."' title='"._DCATLAST3DAYS."'>"; }
            $myimage = myimage("new-english.gif");
            if ($count<=7 && $count>3) { echo "<img align='top' src='$myimage' alt='"._DCATTHISWEEK."' title='"._DCATTHISWEEK."'>"; }
            $myimage = myimage("new_3.gif");
            if ($count<=14 && $count>7) { echo "<img align='top' src='$myimage' alt='"._DCATLAST2WEEKS."' title='"._DCATLAST2WEEKS."'>"; }
        }
        $count++;
        $startdate = (time()-(86400 * $count));
    }
}

function popgraphic($hits) {
    global $module_name, $dl_config;
    $hits = intval($hits);
    $myimage = myimage("popular.gif");
    if ($hits >= $dl_config['popular']) { echo "&nbsp;<img align='top' src='$myimage' alt='"._POPULAR."' title='"._POPULAR."'>"; }
}

function DLadminmain() {
    global $admin_file;
    OpenTable();
    echo "
          <table align='center' cellpadding='2' cellspacing='2' border='0' width='100%'>
               <tr>
                   <td align='center' colspan='3'><a href=\"$admin_file.php?op=DLMain\"><strong>" . _DOWNLOADSADMIN . "</strong></a></td>
               </tr>
               <tr>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=DownloadAdd'>"._ADDDOWNLOAD."</a></td>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=CategoryAdd'>"._ADDCATEGORY."</a></td>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=ExtensionAdd'>"._ADDEXTENSION."</a></td>
               </tr>
               <tr>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=Downloads'>"._DOWNLOADSLIST."</a></td>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=Categories'>"._CATEGORIESLIST."</a></td>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=Extensions'>"._EXTENSIONSLIST."</a></td>
               </tr>
               <tr>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=FilesizeCheck'>"._VALIDATESIZES."</a></td>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=CategoryTransfer'>"._CATTRANS."</a></td>
                   <td align='center' width='33%'><a href='".$admin_file.".php?op=DLConfig'>"._DOWNCONFIG."</a></td>
               </tr>
               <tr>
                   <td align='center' width='33%'>&nbsp;</td>
                   <td align='center' width='33%'>&nbsp;</td>
               </tr>
           </table>
          ";
    CloseTable();
    echo '<br />';
}

function DLsub() {
    global $admin_file;
    OpenTable();
    echo "
          <table align='center' cellpadding='2' cellspacing='2' border='0' width='100%'>
            <tr>
                <td align='center' colspan='3'><a href=\"$admin_file.php?op=DLMain\"><strong>" . _DOWNLOADSADMIN . "</strong></a></td>
            </tr>
          </table>
         ";
    CloseTable();
    echo '<br />';
}

function convertorderbyin($orderby) {
    if ($orderby == "titleA") $orderby = "title ASC";
    if ($orderby == "dateA") $orderby = "date ASC";
    if ($orderby == "hitsA") $orderby = "hits ASC";
    if ($orderby == "titleD") $orderby = "title DESC";
    if ($orderby == "dateD") $orderby = "date DESC";
    if ($orderby == "hitsD") $orderby = "hits DESC";
    return $orderby;
}

function convertorderbytrans($orderby) {
    if ($orderby == "hits ASC") $orderbyTrans = _POPULARITY1;
    if ($orderby == "hits DESC") $orderbyTrans = _POPULARITY2;
    if ($orderby == "title ASC") $orderbyTrans = _TITLEAZ;
    if ($orderby == "title DESC") $orderbyTrans = _TITLEZA;
    if ($orderby == "date ASC") $orderbyTrans = _DDATE1;
    if ($orderby == "date DESC") $orderbyTrans = _DDATE2;
    return $orderbyTrans;
}

function convertorderbyout($orderby) {
    if ($orderby == "title ASC") $orderby = "titleA";
    if ($orderby == "date ASC") $orderby = "dateA";
    if ($orderby == "hits ASC") $orderby = "hitsA";
    if ($orderby == "title DESC") $orderby = "titleD";
    if ($orderby == "date DESC") $orderby = "dateD";
    if ($orderby == "hits DESC") $orderby = "hitsD";
    return $orderby;
}

function menu($maindownload) {
    global $prefix, $user_adddownload, $ThemeSel, $module_name, $query;
    OpenTable();
    $ThemeSel = get_theme();
    if (@file_exists('themes/'.$ThemeSel.'/images/down-logo.gif'))
    echo '
          <br />
          <center>
            <a href="modules.php?name='.$module_name.'"><img src="themes/'.$ThemeSel.'/images/down-logo.gif" border="0" alt="" /></a>
          <br /><br />
         ';
    else echo '
               <br>
               <center>
                <a href="modules.php?name='.$module_name.'"><img src="modules/'.$module_name.'/images/down-logo.gif" border="0" alt="" /></a>
               <br /><br />
              ';
    SearchForm();
    echo '<font class="content"><br />[ ';
    if ($maindownload>0)
    echo '<a href="modules.php?name='.$module_name.'">'._DOWNLOADSMAIN.'</a> | ';
    echo '<a href="modules.php?name='.$module_name.'&amp;op=SubmitDownloads">'._ADDDOWNLOAD.'</a>'.' | ';
    echo '<a href="modules.php?name='.$module_name.'&amp;d_op=NewDownloads">'._NEW.'</a>';
    echo ' | <a href="modules.php?name='.$module_name.'&amp;d_op=MostPopular">'._POPULAR.'</a> ]';
    echo ' </font></center>';
    CloseTable();
    echo "<br />";
}

function downloadinfomenu($lid) {
    global $module_name, $user, $admin_file;
    echo "<br><font class=\"content\">[ <a href='modules.php?name=$module_name&amp;op=modifydownloadrequest&amp;lid=$lid'>"._MODIFY."</a>";
    if (is_user($user)) {
        echo " | <a href=\"modules.php?name=$module_name&amp;d_op=brokendownload&amp;lid=$lid\">"._REPORTBROKEN."</a>";
    }
    if(is_mod_admin($module_name)) {
        echo " | <a href='" . $admin_file . ".php?op=DownloadModify&amp;lid=$lid'>"._EDIT."</a>";
    }
    echo " ]</font><br><br>";
}


function infomenu($lid) {
    global $module_name, $user, $admin_file;
    echo "<span class=\"tiny\"><a href='modules.php?name=$module_name&amp;op=modifydownloadrequest&amp;lid=$lid'>"._MODIFY."</a>";
    if (is_user($user)) {
        echo " | <a href=\"modules.php?name=$module_name&amp;d_op=brokendownload&amp;lid=$lid\">"._REPORTBROKEN."</a>";
    }
    if(is_mod_admin($module_name)) {
        echo " | <a href='" . $admin_file . ".php?op=DownloadModify&amp;lid=$lid'>"._EDIT."</a>";
    }
    echo " | <a href=\"modules.php?name=$module_name&amp;d_op=viewdownloaddetails&amp;lid=$lid\">"._DETAILS."</a></span>";
}

function SearchForm() {
    global $module_name, $query;
    echo "
          <table border='0' cellspacing='0' cellpadding='0' align='center'>
          <form action='modules.php?name=$module_name&amp;op=search&amp;query=$query' method='post'>
              <tr>
                <td><span class='content'><input type='text' size='25' name='query' value='$query'> <input type='submit' value='"._DL_SEARCH."'></span></td>
              </tr>
          </form>
          </table>
         ";
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function showlisting($lid) {
    global $admin_file, $module_name, $admin, $db, $prefix, $user, $dl_config;
    $lid = intval($lid);
    $result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE lid=$lid");
    $lidinfo = $db->sql_fetchrow($result);
    OpenTable();
    $priv = $lidinfo['sid'] - 2;
    if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv)) || $dl_config['show_download'] == '1') {
        $lidinfo['title'] = stripslashes($lidinfo['title']);
        $lidinfo['description'] = stripslashes($lidinfo['description']);
        if (is_mod_admin($module_name)) {
            $myimage = myimage("folders.gif");
            echo "<a href='".$admin_file.".php?op=DownloadModify&amp;lid=$lid' target='$lid'><img align='top' src='$myimage' border='0' alt='"._DL_EDIT."'></a>&nbsp;";
        } else {
            $myimage = myimage("folders.gif");
            echo "<img align='top' src='$myimage' border='0' alt=''>&nbsp;";
        }
        echo "<a href='modules.php?name=$module_name&amp;op=getit&amp;lid=$lid'>".$lidinfo['title']."</a>";
        newdownloadgraphic($datetime, $lidinfo['date']);
        popgraphic($lidinfo['hits']);
        echo "<br /><font class=\"content\">";
        if ($lidinfo['sid'] == 0) {
            $who_view = _DL_ALL;
        } elseif ($lidinfo['sid'] == 1) {
            $who_view = _DL_USERS;
        } elseif ($lidinfo['sid'] == 2) {
            $who_view = _DL_ADMIN;
        } elseif ($lidinfo['sid'] >2) {
            $newView = $lidinfo['sid'] - 2;
            list($who_view) = $db->sql_fetchrow($db->sql_query("SELECT group_name FROM ".$prefix."_bbgroups WHERE group_id=$newView"));
            $who_view = $who_view." "._DL_ONLY;
        }
        echo "
              <b>"._DL_PERM.":</b> $who_view<br />
              <b>"._VERSION.":</b> ".$lidinfo['version']."<br />
              <b>"._FILESIZE.":</b> ".CoolSize($lidinfo['filesize'])."<br />
              <b>"._ADDEDON.":</b> ".CoolDate($lidinfo['date'])."<br />
              <b>"._DOWNLOADS.":</b> ".$lidinfo['hits']."<br />
              <b>"._HOMEPAGE.":</b>
             ";
        if (empty($lidinfo['homepage']) || $lidinfo['homepage'] == "http://") {
            echo _DL_NOTLIST;
        } else {
            echo "<a href='".$lidinfo['homepage']."' target='new'>".$lidinfo['homepage']."</a>";
        }
        echo "</font><br />";
        infomenu($lid);
    } else {
        restricted2($lidinfo['sid']);
    }
    CloseTable();
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function showresulting($lid) {
    global $admin_file, $module_name, $admin, $db, $prefix, $user, $dl_config;
    $lid = intval($lid);
    $lidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE lid=$lid"));
    OpenTable();
    $priv = $lidinfo['sid'] - 2;
    if (($lidinfo['sid'] == 0) || ($lidinfo['sid'] == 1 AND is_user())  || ($lidinfo['sid'] == 2 AND is_mod_admin($module_name)) || ($lidinfo['sid'] > 2 AND of_group($priv)) || $dl_config['show_download'] == '1') {
        $lidinfo['title'] = stripslashes($lidinfo['title']);
        $lidinfo['description'] = stripslashes($lidinfo['description']);
        if (is_mod_admin($module_name)) {
            $myimage = myimage("folders.gif");
            echo "<a href='".$admin_file.".php?op=DownloadModify&amp;lid=$lid' target='$lid'><img align='top' src='$myimage' border='0' alt='"._DL_EDIT."' title='"._DL_EDIT."'></a>&nbsp;";
        } else {
            $myimage = myimage("folders.gif");
            echo "<img align='top' src='$myimage' border='0' alt='' title=''>&nbsp;";
        }
        echo "<a href='modules.php?name=$module_name&amp;op=getit&amp;lid=$lid'>".$lidinfo['title']."</a>";
        newdownloadgraphic($datetime, $lidinfo['date']);
        popgraphic($lidinfo['hits']);
        echo "<br /><font class=\"content\">";
        if ($lidinfo['sid'] == 0) {
            $who_view = _DL_ALL;
        } elseif ($lidinfo['sid'] == 1) {
            $who_view = _DL_USERS;
        } elseif ($lidinfo['sid'] == 2) {
            $who_view = _DL_ADMIN;
        } elseif ($lidinfo['sid'] >2) {
            $newView = $lidinfo['sid'] - 2;
            list($who_view) = $db->sql_fetchrow($db->sql_query("SELECT group_name FROM ".$prefix."_bbgroups WHERE group_id=$newView"));
            $who_view = $who_view." "._DL_ONLY;
        }
        echo "
              <b>"._DL_PERM.":</b> $who_view<br />
              <b>"._VERSION.":</b> ".$lidinfo['version']."<br />
              <b>"._FILESIZE.":</b> ".CoolSize($lidinfo['filesize'])."<br />
              <b>"._ADDEDON.":</b> ".CoolDate($lidinfo['date'])."<br />
              <b>"._DOWNLOADS.":</b> ".$lidinfo['hits']."<br />
              <b>"._HOMEPAGE.":</b>
             ";
        if (empty($lidinfo['homepage']) || $lidinfo['homepage'] == "http://") {
            echo _DL_NOTLIST."<br />\n";
        } else {
            echo "<a href='".$lidinfo['homepage']."' target='new'>".$lidinfo['homepage']."</a><br />\n";
        }
        $result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid='".$lidinfo['cid']."'");
        $cidinfo = $db->sql_fetchrow($result2);
        $cidinfo['title'] = "<a href=modules.php?name=$module_name&amp;cid=".$lidinfo['cid'].">".$cidinfo['title']."</a>";
        $cidinfo['title'] = getparentlink($cidinfo['parentid'], $cidinfo['title']);
        echo "<b>"._CATEGORY.":</b> ".$cidinfo['title']."\n";
        echo "</font><br />";
        infomenu($lid);
    } else {
        restricted2($lidinfo['sid']);
    }
    CloseTable();
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function pagenums_admin($op, $totalselected, $perpage, $max) {
    global $admin_file;
    $pagesint = ($totalselected / $perpage);
    $pageremainder = ($totalselected % $perpage);
    if ($pageremainder != 0) {
        $pages = ceil($pagesint);
        if ($totalselected < $perpage) { $pageremainder = 0; }
    } else {
        $pages = $pagesint;
    }
    if ($pages != 1 && $pages != 0) {
        $counter = 1;
        $currentpage = ($max / $perpage);
        echo "<table border='0' cellpadding='2' cellspacing='2' width='100%'>\n";
        echo "<tr><form action='".$admin_file.".php' method='post'>\n";
        echo "<td align='right'><strong>"._DL_SELECTPAGE.": </strong><select name='min' onChange='top.location.href=this.options[this.selectedIndex].value'>\n";
        while ($counter <= $pages ) {
            $cpage = $counter;
            $mintemp = ($perpage * $counter) - $perpage;
            if ($counter == $currentpage) {
            echo "<option selected>$counter</option>\n";
            } else {
            echo "<option value='".$admin_file.".php?op=$op&amp;min=$mintemp";
            if ($op > "" ) { echo "&amp;op=$op"; }
            if ($query > "") { echo "&amp;query=$query"; }
            if (isset($cid)) { echo "&amp;cid=$cid"; }
            echo "'>$counter</option>\n";
            }
            $counter++;
        }
        echo "</select><strong> "._DL_OF." $pages "._DL_PAGES."</strong></td>\n</form>\n</tr>\n";
        echo "</table>\n";
    }
}

// Copyright (c) 2003 --- NukeScripts Network ---
// Can not be reproduced in whole or in part without
// written consent from NukeScripts Network CEO
function dlPagenums($cid, $query, $orderby, $op, $totalselected, $perpage, $max) {
    global $module_name;
    $pagesint = ($totalselected / $perpage);
    $pageremainder = ($totalselected % $perpage);
    if ($pageremainder != 0) {
        $pages = ceil($pagesint);
        if ($totalselected < $perpage) { $pageremainder = 0; }
    } else {
        $pages = $pagesint;
    }
    if ($op > "" ) {
        if ($pages != 1 && $pages != 0) {
            $counter = 1;
            $currentpage = ($max / $perpage);
            echo "
                  <table align='center' border='0' cellspacing='0' cellpadding='0' width='70%'>
                      <tr>
                          <td colspan='6'><img src='images/pix.gif' height='2' width='2' alt='' title='' /><br /><br /></td>
                      </tr>
                      <tr>
                          <td colspan='6'>
                              <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                  <tr>
                                      <td align='left' width='20%'>
                 ";
            if($currentpage <= 1) {
                echo "";
            } else {
                echo "
                      <a href='modules.php?name=$module_name&amp;min=".($perpage * $counter - $perpage)."&amp;op=".$op."&amp;query=$query'>
                          <font face='Verdana' size='2'>&laquo; "._PREVPAGE."</font>
                      </a>
                     ";
            }
            echo "
                  </td>
                  <td align='center' width='60%'>"._PAGE.":&nbsp;</font>
                 ";
            $start_page = ($currentpage-5<1)?1:($currentpage-5); //Set starting page to page-5, or 1 if less than 1
            $end_page = ($currentpage+4>$pages)?$pages:($currentpage+4); //Set ending page to page+5, or pages if more than 1
            for($counter=$start_page; $counter<=$end_page; $counter++) {
                $cpage = $counter;
                $mintemp = ($perpage * $counter) - $perpage;
                if($counter == $currentpage) {
                    echo "<font face='Verdana' size='2' color=red>$counter</font>&nbsp;";
                } else {
                    echo "
                          <a href='modules.php?name=$module_name&amp;min=$mintemp&amp;op=".$op."&amp;query=$query'>
                              <font face='Verdana' size='2'>$counter</font>
                          </a>&nbsp;
                         ";
                }
            }
            echo "
                  </td>
                  <td align='right' width='20%'>
                 ";
            if($currentpage >= $pages) {
                echo "";
            } else {
                echo "
                      <a href='modules.php?name=$module_name&amp;min=".($min + $perpage)."&amp;op=".$op."&amp;query=$query'>
                          <font face='Verdana' size='2'>"._NEXTPAGE." &raquo;</font>
                      </a>
                     ";
            }
            echo "
                                  </td>
                              </tr>
                          </table>
                      </td>
                  </tr>
                 ";
        }
    } else {
        if ($pages != 1 && $pages != 0) {
            $counter = 1;
            $currentpage = ($max / $perpage);
            echo "
                  <table align='center' border='0' cellspacing='0' cellpadding='0' width='70%'>
                      <tr>
                          <td colspan='6'><img src='images/pix.gif' height='2' width='2' alt='' title='' /><br /><br /></td>
                      </tr>
                      <tr>
                          <td colspan='6'>
                              <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                  <tr>
                                      <td align='left' width='20%'>
                 ";
            if($currentpage <= 1) {
                echo "";
            } else {
                echo "
                      <a href='modules.php?name=$module_name&amp;min=".($perpage * $counter - $perpage)."&amp;cid=$cid'>
                          <font face='Verdana' size='2'>&laquo; "._PREVPAGE."</font>
                      </a>
                     ";
            }
            echo "
                  </td>
                  <td align='center' width='60%'>"._PAGE.":&nbsp;</font>
                 ";
            $start_page = ($currentpage-5<1)?1:($currentpage-5); //Set starting page to page-5, or 1 if less than 1
            $end_page = ($currentpage+4>$pages)?$pages:($currentpage+4); //Set ending page to page+5, or pages if more than 1
            for($counter=$start_page; $counter<=$end_page; $counter++) {
                $cpage = $counter;
                $mintemp = ($perpage * $counter) - $perpage;
                if($counter == $currentpage) {
                    echo "<font face='Verdana' size='2' color=red>$counter</font>&nbsp;";
                } else {
                    echo "
                          <a href='modules.php?name=$module_name&amp;min=$mintemp&amp;cid=$cid'>
                              <font face='Verdana' size='2'>$counter</font>
                          </a>&nbsp;
                         ";
                }
            }
            echo "
                  </td>
                  <td align='right' width='20%'>
                 ";
            if($currentpage >= $pages) {
                echo "";
            } else {
                echo "
                      <a href='modules.php?name=$module_name&amp;min=".($min + $perpage)."&amp;cid=$cid'>
                          <font face='Verdana' size='2'>"._NEXTPAGE." &raquo;</font>
                      </a>
                     ";
            }
            echo "
                                   </td>
                               </tr>
                           </table>
                      </td>
                  </tr>
                 ";
        }
    }
    echo "</table>";
}

?>