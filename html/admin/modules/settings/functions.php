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

if(!defined('ADMIN_FILE')) {
  exit('Access Denied');
}

include_once(NUKE_INCLUDE_DIR.'functions_news.php');

$ne_config = ne_get_configs();

define('IN_SETTINGS', true);

function settings_header() {
    global $admin_file;
    OpenTable();
    echo "
          <table align='center' cellpadding='2' cellspacing='2' border='0' width='100%'>
              <tr>
                  <td align='center' colspan='3'>
                      <a href=\"$admin_file.php?op=Configure\">
                          <strong>
                              " . _SITECONFIG . "
                          </strong>
                      </a>
                  </td>
              </tr>
              <tr>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=1\">
                          " . _GENSITEINFO . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=4\">
                          " . _FOOTERMSG . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=7\">
                          " . _COMMENTSOPT . "
                      </a>
                  </td>
              </tr>
              <tr>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=9\">
                          " . _MISCOPT . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=11\">
                          " . _META_TAGS . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=6\">
                          " . _MAIL2ADMIN . "
                      </a>
                  </td>
              </tr>
              <tr>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=3\">
                          " . _MULTILINGUALOPT . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=5\">
                          " . _BACKENDCONF . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=2\">
                          " . _CENSOR . "
                      </a>
                  </td>
              </tr>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=8\">
                          " . _GRAPHICOPT . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=10\">
                          " . _GFXOPT . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=12\">
                          " . _SITEMAPSET . "
                      </a>
                  </td>
              </tr>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=13\">
                          " . _NE_NEWSCONFIG . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=Configure&sub=14\">
                          " . _POLL_OPTIONS . "
                      </a>
                  </td>
                  <td align='center' width='25%'>
                      <a href=\"$admin_file.php?op=themes\">
                          " . _THEMES_HEADER . "
                      </a>
                  </td>
              </tr>
          </table>
         ";
    CloseTable();
    echo "
          <br />
         ";
}

function show_settings($sub) {
    global $admin_file;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    settings_header();
    if($sub) {
        OpenTable();
        echo "
              <form action='".$admin_file.".php' method='post'>
             ";
    }
    switch($sub) {

        case 1:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/general.php');
        break;

        case 2:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/censor.php');
        break;

        case 3:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/language.php');
        break;

        case 4:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/footer.php');
        break;

        case 5:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/backend.php');
        break;

        case 6:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/submissions.php');
        break;

        case 7:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/comments.php');
        break;

        case 8:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/graphicadmin.php');
        break;

        case 9:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/miscellaneous.php');
        break;

        case 10:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/gfxchk.php');
        break;
        
        case 11:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/meta.php');
        break;

        case 12:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/sitemap.php');
        break;

        case 13:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/news.php');
        break;
        
        case 14:
            include_once(NUKE_ADMIN_MODULE_DIR.'settings/polls.php');
        break;
        
    }
    if($sub) {
        echo "
                  <div align=\"center\">
                      <input type='hidden' name='sub' value='$sub' />
                      <input type='hidden' name='op' value='ConfigSave' />
                      <input type='submit' value='" . _SAVECHANGES . "' />
                  </div>
              </form>
             ";
        CloseTable();
    }
    include_once(NUKE_BASE_DIR.'footer.php');
}

function save_settings($sub) {
    global $db, $prefix, $admin_file, $cache;

    switch($sub) {
        case 1:
            $xsitename = htmlentities($_POST['xsitename'], ENT_QUOTES);
            $xslogan = htmlentities($_POST['xslogan'], ENT_QUOTES);
            $xnukeurl = $_POST['xnukeurl'];
            $xsite_logo = htmlentities($_POST['xsite_logo'], ENT_QUOTES);
            $xstartdate = $_POST['xstartdate'];
            $xlocale = $_POST['xlocale'];
            $xadminmail = validate_mail($_POST['xadminmail']);
            Validate($xadminmail, 'email', 'Nuke Settings', 0, 1, 0, 0, '', '</span></b></em><br /><div align=\"center\">'. _GOBACK .'</div>');
            $xtop = intval($_POST['xtop']);
            $xstoryhome = intval($_POST['xstoryhome']);
            $xoldnum = intval($_POST['xoldnum']);
            $xultramode = intval($_POST['xultramode']);
            $xanonpost = intval($_POST['xanonpost']);
            $confirm = intval($_POST["confirm"]);
            ValidateURL($xnukeurl, 0, "Site URL");
            $server_url = "http://" . $_SERVER["HTTP_HOST"];
            $pos = strrpos($_SERVER["PHP_SELF"],"/");
            if(!empty($pos)) {
                $server_url .= substr($_SERVER["PHP_SELF"],0,$pos);
            }
            if($xnukeurl != $server_url && empty($confirm)) {
                include_once(NUKE_BASE_DIR.'header.php');
                GraphicAdmin();
                echo "
                      <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"5\" border=\"0\">
                          <tr>
                              <th class=\"thHead\" height=\"25\" valign=\"middle\">
                                  <span class=\"tableTitle\">
                                      Confirm
                                  </span>
                              </th>
                          </tr>
                          <tr>
                              <td class=\"row1\" align=\"center\">
                                  <span class=\"gen\">
                                      <br />
                                      ". sprintf(_URL_SERVER_ERROR, $xnukeurl,$server_url) .".
                                      <br />
                                      <br />
                                      "._URL_CONFIRM_ERROR."
                                      <form action='".$admin_file.".php?op=ConfigSave' method='post'>
                     ";
                foreach ($_POST as $key => $value) {
                    echo "
                                          <input type='hidden' name='".$key."' value='".$value."'>
                         ";
                }
                echo "                
                                          <input type='hidden' name='confirm' value='1'>
                                          <br />
                                          <input type=\"submit\" value=\""._YES."\" class=\"mainoption\" />
                                          <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$admin_file.".php?op=Configure&sub=".$sub."' \" />
                                      </form>
                                  </span>
                              </td>
                          </tr>
                      </table>
                      <br clear=\"all\" />
                     ";                
                include_once("footer.php");
            }
            $db->sql_query("UPDATE ".$prefix."_config SET sitename='$xsitename', nukeurl='$xnukeurl', site_logo='$xsite_logo', slogan='$xslogan', startdate='$xstartdate', adminmail='$xadminmail', anonpost='$xanonpost', top='$xtop', storyhome='$xstoryhome', oldnum='$xoldnum', ultramode='$xultramode', locale='$xlocale'");
        break;

        case 2:
            $xcensor = intval($_POST['xcensor']);
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xcensor."' WHERE sys_field='censor'");
            $xcensor_words = str_replace("\n"," ", $_POST['xcensor_words']);
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xcensor_words."' WHERE sys_field='censor_words'");
        break;

        case 3:
            $xlanguage = Fix_Quotes(strtolower($_POST['xlanguage']));
            $xmultilingual = intval($_POST['xmultilingual']);
            $xuseflags = intval($_POST['xuseflags']);
            $db->sql_query("UPDATE ".$prefix."_config SET multilingual='$xmultilingual', useflags='$xuseflags', language='$xlanguage'");
        break;

        case 4:
            $xfoot1 = Fix_Quotes($_POST['xfoot1']);
            $xfoot2 = Fix_Quotes($_POST['xfoot2']);
            $xfoot3 = Fix_Quotes($_POST['xfoot3']);
            $db->sql_query("UPDATE ".$prefix."_config SET foot1='$xfoot1', foot2='$xfoot2', foot3='$xfoot3'");
        break;

        case 5:
            $xbackend_title = htmlentities($_POST['xbackend_title'], ENT_QUOTES);
            $xbackend_language = Fix_Quotes($_POST['xbackend_language']);
            $db->sql_query("UPDATE ".$prefix."_config SET backend_title='$xbackend_title', backend_language='$xbackend_language'");
        break;

        case 6:
            $xnotify_subject = htmlentities($_POST['xnotify_subject'], ENT_QUOTES);
            $xnotify = intval($_POST['xnotify']);
            $xnotify_email = validate_mail($_POST['xnotify_email']);
            $xnotify_message = $_POST['xnotify_message'];
            $xnotify_from = $_POST['xnotify_from'];
            $db->sql_query("UPDATE ".$prefix."_config SET notify='$xnotify', notify_subject='$xnotify_subject', notify_email='$xnotify_email', notify_message='$xnotify_message', notify_from='$xnotify_from'");
        break;

        case 7:
            $xmoderate = intval($_POST['xmoderate']);
            $xcommentlimit = intval($_POST['xcommentlimit']);
            $xanonymous = Fix_Quotes($_POST['xanonymous']);
            $db->sql_query("UPDATE ".$prefix."_config SET moderate='$xmoderate', commentlimit='$xcommentlimit', anonymous='$xanonymous'");
        break;

        case 8:
            $xadmingraphic = intval($_POST['xadmingraphic']);
            $xadmin_pos = intval($_POST['xadmin_pos']);
            $db->sql_query("UPDATE ".$prefix."_config SET admingraphic='$xadmingraphic', admin_pos='$xadmin_pos'");
        break;

        case 9:
            $xhttpref = intval($_POST['xhttpref']);
            $xhttprefmax = intval($_POST['xhttprefmax']);
            $xpollcomm = intval($_POST['xpollcomm']);
            $xarticlecomm = intval($_POST['xarticlecomm']);
            $xmy_headlines = intval($_POST['xmy_headlines']);
            $xuser_news = intval($_POST['xuser_news']);
            $xadminssl = intval($_POST['xadminssl']);
            $xqueries_count = intval($_POST['xqueries_count']);
            $xuse_colors = intval($_POST['xuse_colors']);
            $xlock_modules = intval($_POST['xlock_modules']);
            $xbanners = intval($_POST['xbanners']);
            $xlazytap = intval($_POST['xlazytap']);
            $xtextarea = $_POST['xtextarea'];
            $ximg_resize = intval($_POST['ximg_resize']);
            lazy_tap_check($xlazytap);
            $xcollapse = intval($_POST['xcollapse']);
            $db->sql_query("UPDATE ".$prefix."_config SET httpref='$xhttpref', httprefmax='$xhttprefmax', pollcomm='$xpollcomm', articlecomm='$xarticlecomm', my_headlines='$xmy_headlines', user_news='$xuser_news', banners='$xbanners'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xadminssl."' WHERE sys_field='adminssl'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xqueries_count."' WHERE sys_field='queries_count'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xuse_colors."' WHERE sys_field='use_colors'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xlock_modules."' WHERE sys_field='lock_modules'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xtextarea."' WHERE sys_field='textarea'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xlazytap."' WHERE sys_field='lazy_tap'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$ximg_resize."' WHERE sys_field='img_resize'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xcollapse."' WHERE sys_field='collapse'");
            $cache->delete('sysconfig');
	    $cache->resync();

        break;

        case 10:
            $xusegfxcheck = intval($_POST['xusegfxcheck']);
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xusegfxcheck."' WHERE sys_field='usegfxcheck'");
            if (!GDSUPPORT) {
                break;
            }
            if(!defined('CAPTCHA')) {
                $xcodesize = intval($_POST['xcodesize']);
                $xcodefont = addslashes($_POST['xcodefont']);
                $xuseimage = intval($_POST['xuseimage']);
                $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xcodesize."' WHERE sys_field='codesize'");
                $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xcodefont."' WHERE sys_field='codefont'");
                $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xuseimage."' WHERE sys_field='useimage'");
            } else {
                $xcapfile = $_POST['xcapfile'];
                $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xcapfile."' WHERE sys_field='capfile'");
            }
        break;
        
        case 11:
            if($_GET['act'] == "delete") {
                if(!empty($_GET['meta'])) {
                    $db->sql_query("DELETE FROM " . $prefix . "_meta WHERE meta_property = '" . $_GET['meta'] . "'");
                }
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
                $cache->delete('metatags', 'config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            } else {
                $sql = 'SELECT meta_property, meta_content FROM '.$prefix.'_meta';
                $result = $db->sql_query($sql);
                $i=0;
                while(list($meta_property, $meta_content) = $db->sql_fetchrow($result)) {
                    $metatags[$i] = array();
                    $metatags[$i]['meta_property'] = $meta_property;
                    $metatags[$i]['meta_content'] = $meta_content;
                    $i++;
                }
                $db->sql_freeresult($result);
        
                for($i=0, $maxi=count($metatags);$i<$maxi;$i++) {
                    $metatag = $metatags[$i];
                    $db->sql_query("UPDATE ".$prefix."_meta SET meta_content='".$_POST['x' . $metatag['meta_property']]."' WHERE meta_property='".$metatag['meta_property']."'");
                }
                if(!empty($_POST['new_name']) && (!empty($_POST['new_value']) || $_POST['new_value'] == '0')) {
                    $db->sql_query("INSERT INTO ".$prefix."_meta (meta_property, meta_content) VALUES ('" . $_POST['new_name'] . "', '" . $_POST['new_value'] . "')");
                }
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
                $cache->delete('metatags', 'config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            }
        break;

        case 12:
            $xml=htmlspecialchars($_POST['xml']);
            $nnews=htmlspecialchars($_POST['nnews']);
            $ntopics=htmlspecialchars($_POST['ntopics']);
            $ndown=htmlspecialchars($_POST['ndown']);
            $nrev=htmlspecialchars($_POST['nrev']);
            $nuser=htmlspecialchars($_POST['nuser']);

            if( $xml!="" && $nnews!="" && $ntopics!="" && $ndown!="" && $nrev!="" && $nuser!="" ) {
                $db->sql_query("UPDATE " . $prefix . "_smap SET value = '".$xml."' WHERE name = 'xml'");
                $db->sql_query("UPDATE " . $prefix . "_smap SET value = '".$nnews."' WHERE name = 'nnews'");
                $db->sql_query("UPDATE " . $prefix . "_smap SET value = '".$ntopics."' WHERE name = 'ntopics'");
                $db->sql_query("UPDATE " . $prefix . "_smap SET value = '".$ndown."' WHERE name = 'ndown'");
                $db->sql_query("UPDATE " . $prefix . "_smap SET value = '".$nrev."' WHERE name = 'nrev'");
                $db->sql_query("UPDATE " . $prefix . "_smap SET value = '".$nuser."' WHERE name = 'nuser'");
            }
        break;
				 
        case 13:
            $xcolumns = $_POST['xcolumns'];
            $xreadmore = $_POST['xreadmore'];
            $xtexttype = $_POST['xtexttype'];
            $xnotifyauth = $_POST['xnotifyauth'];
            $xhomenumber = $_POST['xhomenumber'];
            $xhometopic = $_POST['xhometopic'];

            $db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='".$xcolumns."' WHERE config_name='columns'");
            $db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='".$xreadmore."' WHERE config_name='readmore'");
            $db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='".$xtexttype."' WHERE config_name='texttype'");
            $db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='".$xnotifyauth."' WHERE config_name='notifyauth'");
            $db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='".$xhomenumber."' WHERE config_name='homenumber'");
            $db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='".$xhometopic."' WHERE config_name='hometopic'");

/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            global $cache;
            $cache->delete('news', 'config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        break;
				 
        case 14:
            // Fetch random poll
            $make_random = intval($sysconfig['poll_random']);
            // Fetch number of days in between voting per user
            $number_of_days = intval($sysconfig['poll_days']);
            $xmake_random = $_POST['xmake_random'];
            $xnumber_of_days = $_POST['xnumber_of_days'];
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xmake_random."' WHERE sys_field='poll_random'");
            $db->sql_query("UPDATE ".$prefix."_system SET sys_value='".$xnumber_of_days."' WHERE sys_field='poll_days'");
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            global $cache;
            $cache->delete('sysconfig');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            
        break;	
    }
            $cache->delete('nukeconfig', 'config');
            $cache->delete('sysconfig', 'config');
            redirect($admin_file.'.php?op=Configure&sub='.$sub);
}

function lazy_tap_check($set) {
    if ($set == 0){
        return true;
    }
    $filename = NUKE_BASE_DIR . '.htaccess';
    $pagetitle = _LAZY_TAP;
    if(!is_file($filename)) {
        DisplayErrorReturn(_LAZY_TAP_NF);
    }
    if($handle = @fopen($filename,"r")) {
        $content = @fread($handle, filesize($filename));
        @fclose($handle);
    } else {
        DisplayError(_LAZY_TAP_ERROR_OPEN);
    }
    if (empty($content)) {
        DisplayErrorReturn(_LAZY_TAP_ERROR);
    }
    $pos = strpos($content,'RewriteEngine on');
    $pos2 = strpos($content,'RewriteRule');
    if ($pos === false || $pos2 === false) {
        DisplayError(_LAZY_TAP_ERROR);
    }
}

?>