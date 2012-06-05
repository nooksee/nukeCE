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

global $file_mode, $client;

switch($op) {
    default:
    $pagetitle = _ADDADOWNLOAD;
    include_once(NUKE_BASE_DIR.'header.php');
    $maindownload = 1;
    menu(1);
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADDADOWNLOAD."</b></font></center><br>";
    if (is_user($user)) {
        $result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE active>'0' AND parentid='0' ORDER BY title");
        $numrow = $db->sql_numrows($result2);
        if ($numrow == 0) {
            echo "
                  <table width=\"100%\" border=\"0\" cellspacing=\"3\">
                      <tr>
                          <td>
                              <div align='center' class='content'>
                                  <em><b>"._NOCATEGORY."</b></em>
                              </div>
                 ";
        } else {
            $message = "
                        <b>"._INSTRUCTIONS.":</b><br>
                        <strong><big>&middot;</big></strong> "._DSUBMITONCE."<br>
                        <strong><big>&middot;</big></strong> "._DPOSTPENDING."<br>
                        <strong><big>&middot;</big></strong> "._USERANDIP."<br>
                       ";
            info_box("caution", $message);
            echo "
                  <br><br>
                  <table width=\"100%\" border=\"0\" cellspacing=\"3\">
                  <form method='post' action='modules.php?name=$module_name'>
                      <tr>
                          <td width='10%' nowrap><font class=\"content\"><b>"._CATEGORY.":</b></font></td>
                          <td>
                              <select name='cat'>
                 ";
            while($cidinfo = $db->sql_fetchrow($result2)) {
                $crawled = array($cidinfo['cid']);
                CrawlLevel($cidinfo['cid']);
                $x=0;
                while ($x <= (count($crawled)-1)) {
                    list($title, $parentid, $whoadd) = $db->sql_fetchrow($db->sql_query("SELECT title, parentid, whoadd FROM ".$prefix."_downloads_categories WHERE cid='$crawled[$x]'"));
                    if ($x > 0) { $title = getparent($parentid,$title); }
                    $priv = $whoadd - 2;
                    if ($whoadd == 0 OR ($whoadd == 1 AND (is_user() OR is_mod_admin($module_name))) OR ($whoadd == 2 AND is_mod_admin($module_name)) OR ($whoadd > 2 AND of_group($priv))) {
                        echo "<option value='$crawled[$x]'>$title</option>";
                    }
                    $x++;
                }
            }
            echo "
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td>&nbsp;</td>
                      <td>
                          <input type='hidden' name='op' value='Input'>
                          <input type='submit' value='"._GONEXT."'>
                 ";
        }
        echo "
                      </td>
                  </tr>
              </form>
              </table>
              <br>
             ";
    } else {
        echo "
              <center>
                  "._DOWNLOADSNOTUSER1."<br>
                  "._DOWNLOADSNOTUSER2."<br><br>
                  "._DOWNLOADSNOTUSER3."<br>
                  "._DOWNLOADSNOTUSER4."<br>
                  "._DOWNLOADSNOTUSER5."<br>
                  "._DOWNLOADSNOTUSER6."<br>
                  "._DOWNLOADSNOTUSER7."<br><br>
                  "._DOWNLOADSNOTUSER8."
              </center>
             ";
    }
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
    break;

    case "Input":
    $pagetitle = _ADDADOWNLOAD;
    include_once(NUKE_BASE_DIR.'header.php');
    $maindownload = 1;
    menu(1);
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADDADOWNLOAD."</b></font></center><br>";
    $cat = intval($cat);
    $cidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid='$cat' ORDER BY title"));
    $priv = $cidinfo['whoadd'] - 2;
    if ($cidinfo['whoadd'] == 0 OR ($cidinfo['whoadd'] == 1 AND (is_user() OR is_mod_admin($module_name))) OR ($cidinfo['whoadd'] == 2 AND is_mod_admin($module_name)) OR ($cidinfo['whoadd'] > 2 AND of_group($priv))) {
        echo "
              <br><br>
              <table width=\"100%\" border=\"0\" cellspacing=\"3\">
             ";
        if ($cidinfo['canupload'] > 0) {
            echo "<form method='post' action='modules.php?name=$module_name' enctype='multipart/form-data'>\n";
        } else {
            echo "<form method='post' action='modules.php?name=$module_name'>\n";
        }
        echo "
              <tr>
                  <td width=\"20%\" nowrap><font class=\"content\"><b>"._DOWNLOADNAME.":</b></font></td>
                  <td><input type=\"text\" name=\"title\" size=\"40\" maxlength=\"100\"></td>
              </tr>
             ";
        if ($cidinfo['canupload'] == 1) {
            $result = $db->sql_query("SELECT ext FROM ".$prefix."_downloads_extensions WHERE file=1");
            while (list ($exten) = $db->sql_fetchrow($result)) {
                if (empty($limitedext)) { $limitedext = $exten; }
                else { $limitedext = $limitedext.", ".$exten; }
            }
            $max = @ini_get('upload_max_filesize');
            if (str_replace("M", "", $max)) {
                $mtemp = sprintf($max * 1024 * 1024);
                $msize = number_format($mtemp, 0, '.', ',')." "._BYTES;
            } elseif (str_replace("K", "", $max)) {
                $mtemp = sprintf($max * 1024);
                $msize = number_format($mtemp, 0, '.', ',')." "._BYTES;
            } else {
                $msize = number_format($max, 0, '.', ',')." "._BYTES;
            }
            echo "
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._FILEURL.":</b></font></td>
                      <td><input type='file' name='url' size='40'> <span class=\"tiny\">&nbsp;("._MAXFSIZE.":" . $msize . ")</span><br /><em>"._DL_ALWEXT.": <font color='green'>$limitedext</font></em></td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b><a href='modules.php?name=$module_name&amp;op=TermsUseUp' rel='3' class='newWindow'>"._DL_TOU."</a>:</b></font></td>
                      <td>".yesno_option("tou", 0)."</td>
                  </tr>
                 ";
        } else {
            echo "
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._FILEURL.":</b></font></td>
                      <td><input type='text' name='url' size='40'>
                 ";
        if (is_mod_admin($module_name)) {
            echo "<a href=\"includes/help/pathhelp.php\" rel='4' class='newWindow'><img src=\"images/icon_help.gif\" alt=\""._BAD_URLS."\" title=\""._BAD_URLS."\" border=\"0\" width=\"13\" height=\"13\"></a>";
        }                      
        echo "
                      </td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b><a href='modules.php?name=$module_name&amp;op=TermsUse' rel='1' class='newWindow'>"._DL_TOU."</a>:</b></font></td>
                      <td>".yesno_option("tou", 0)."</td>
                  </tr>
                 ";
        }
        $title = getparent($cidinfo['parentid'],$cidinfo['title']);
        echo "
              <tr>
                  <td nowrap><font class=\"content\"><b>"._CATEGORY.":</b></font></td>
                  <td><input type=\"text\" value=\"$title\" size=\"40\" disabled=\"disabled\"></td>
              </tr>
              <tr>
                  <td nowrap><font class=\"content\"><b>"._DESCRIPTION.":</b></font></td>
                  <td width=\"100%\">
        ";
        Make_TextArea('description', '', 'add_download');
        echo "
                  </td>
              </tr>
             ";
        $usrinfo = $db->sql_fetchrow($db->sql_query("select * from ".$user_prefix."_users WHERE user_id='$cookie[0]'"));
        if ($usrinfo['user_website'] == "http://") { $usrinfo['user_website'] = ""; }
        echo "
              <tr>
                  <td nowrap><font class=\"content\"><b>"._AUTHORNAME.":</b></font></td>
                  <td><input type='text' name='auth_name' size='30' maxlength='60' value='".$usrinfo['username']."'></td>
              </tr>
              <tr>
                  <td nowrap><font class=\"content\"><b>"._AUTHOREMAIL.":</b></font></td>
                  <td><input type='text' name='email' size='30' maxlength='100' value='".$usrinfo['user_email']."'></td>
              </tr>
             ";
        if ($cidinfo['canupload'] == 0) {
            echo "
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._FILESIZE.":</b></font></td>
                      <td><input type='text' name='filesize' size='12' maxlength='20'> ("._INBYTES.")</td>
                  </tr>\n
                 ";
        }
        echo "
                <tr>
                    <td nowrap><font class=\"content\"><b>"._VERSION.":</b></font></td>
                    <td><input type='text' name='version' size='11' maxlength='20'></td>
                </tr>
                <tr>
                    <td nowrap><font class=\"content\"><b>"._HOMEPAGE.":</b></font></td>
                    <td><input type='text' name='homepage' size='40' maxlength='255' value='".$usrinfo['user_website']."'></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type='hidden' name='op' value='Add'>
                        <input type='hidden' name='cat' value='$cat'>
                        <input type='hidden' name='submitter' value='".$usrinfo['username']."'>
                        <input type='submit' value='"._ADDTHISFILE."'>
                    </td>
                </tr>
              </form>
              </table>
             ";
    } else {
        echo "
              <div align=\"center\">
                  <span class=\"option\">
                      <b><em>"._DL_CANTADD."</em></b>
                  </span>        
                  <br />
              </div>
             ";
    }
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
    break;

    case "Add":
    $pagetitle = _ADDADOWNLOAD;
    include_once(NUKE_BASE_DIR.'header.php');
    menu(1);
    if ($tou > 0) {
        $cat = intval($cat);
        $cidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid='$cat'"));
        $priv = $cidinfo['whoadd'] - 2;
        if ($cidinfo['whoadd'] == 0 OR ($cidinfo['whoadd'] == 1 AND (is_user() OR is_mod_admin($module_name))) OR ($cidinfo['whoadd'] == 2 AND is_mod_admin($module_name)) OR ($cidinfo['whoadd'] > 2 AND of_group($priv))) {
            if ($cidinfo['canupload'] > 0) {
                $result = $db->sql_query("SELECT ext FROM ".$prefix."_downloads_extensions WHERE file=1");
                $xi = 0;
                while (list ($exten) = $db->sql_fetchrow($result)) {
                    $limitedext[$xi] = $exten;
                    $xi++;
                }
                $imageurl_name = $_FILES['url']['name'];
                $imageurl_temp = $_FILES['url']['tmp_name'];
                $ext = substr($imageurl_name, strrpos($imageurl_name,'.'));
                if(substr($cidinfo['uploaddir'],0,1) !== '/') {
                    $cidinfo['uploaddir'] = '/'.$cidinfo['uploaddir'];
                }
                $folder = dirname(dirname(__FILE__)) . '/files' . $cidinfo['uploaddir'];
                $url_folder = 'modules/'.basename(dirname(dirname(__FILE__))).'/files'. $cidinfo['uploaddir'];
                if(substr($imageurl_name,0,1) == '/') {
                    DisplayErrorReturn(_DL_SLASH, 1);
                    exit;
                }
                if (!in_array($ext,$limitedext)) {
                    DisplayErrorReturn(_DL_BADEXT, 1);
                    return;
                } elseif (file_exists($folder."/$imageurl_name")) {
                    DisplayErrorReturn(_DL_FILEEXIST, 1);
                    return;
                } elseif (move_uploaded_file($imageurl_temp, $folder."/$imageurl_name")) {
                    chmod ($folder."/$imageurl_name", $file_mode);
                    $url = $url_folder."/$imageurl_name";
                } else {
                    DisplayErrorReturn(_DL_NOUPLOAD, 1);
                    return;
                }
                $filesize = sprintf("%u", filesize($url));
            } else {
                if ($url=="" OR $url=="http;//") {
                    DisplayErrorReturn(_ERRORNOURL, 1);
                    return;
                }
            }
            if ($title=="") {
                DisplayErrorReturn(DOWNLOADNOTITLE, 1);
                return;
            }
            if ($description=="") {
                DisplayErrorReturn(_ERRORNODESCRIPTION, 1);
                return;
            }

            $title = check_html($title, nohtml);
            $title = htmlentities($title, ENT_QUOTES);
            $url = check_html($url, nohtml);
            $description = eregi_replace("<br />", "\r\n", $description);
            $description = htmlentities($description, ENT_QUOTES);
            $description = Fix_Quotes(check_html($description, nohtml));
            $auth_name = Fix_Quotes(check_html($auth_name, nohtml));
            $submitter = Fix_Quotes(check_html($submitter, nohtml));
            if (empty($submitter)) { $submitter = $auth_name; }
            $email = Fix_Quotes(check_html($email, nohtml));
            $filesize = str_replace('.', '', $filesize);
            $filesize = str_replace(',', '', $filesize);
            $filesize = intval($filesize);
            $cat = intval($cat);
            $client = new Client();
            $sub_ip = $client->getIp();

            $db->sql_query("INSERT INTO ".$prefix."_downloads_new VALUES (NULL, $cat, 0, '$title', '$url', '$description', now(), '$auth_name', '$email', '$submitter', '$sub_ip', $filesize, '$version', '$homepage')");
            
            OpenTable();
            echo "
                  <div align=\"center\">
                      <span class=\"option\">
                          <b><em>"._DOWNLOADRECEIVED."</em></b>
                      </span>        
                      <br /><br />
                 ";
            if ($email != "") {
                echo ""._EMAILWHENADD."";
            } else {
                echo ""._CHECKFORIT."";
            }
            echo "</div>";
            CloseTable();

            $msg = "$sitename "._DOWSUB."\n\n";
            $msg .= _DOWNLOADNAME.": $title\n";
            $msg .= _FILEURL.": $url\n";
            $msg .= _DESCRIPTION.": $description\n";
            $msg .= _HOMEPAGE.": $homepage\n";
            $msg .= _SUBIP.": $sub_ip\n";
            $to = $adminmail;
            $subject = "$sitename - "._DOWSUBREC;
            $mailheaders = "From: $email <$auth_name> \n";
            $mailheaders .= "Reply-To: $email\n\n";
            nuke_mail($to, $subject, $msg, $mailheaders);

        } else {
            DisplayError(_DL_CANTADD, 1);
            exit;
        }
    } else {
        DisplayErrorReturn(_DL_TOUMUST, 1);
        return;
    }
    include_once(NUKE_BASE_DIR.'footer.php');    
    break;

    case "TermsUseUp":
    echo "
          <html>
              <head><title>"._DL_TOU."</title></head>
            <body>
                <strong>"._DL_TOU.":</strong> "._DUSAGEUP1." (i) "._DUSAGEUP2." (ii) "._DUSAGEUP3." (iii) "._DUSAGEUP4." (iv) "._DUSAGEUP5." (v) "._DUSAGEUP6."
            </body>
          </html>
         ";
    break;

    case "TermsUse":
    echo "
          <html>
              <head><title>"._DL_TOU."</title></head>
            <body>
                <u>"._DUSAGE1."</u> (i) "._DUSAGE2." (ii) "._DUSAGE3." (iii) "._DUSAGE4." (iv) "._DUSAGE5."
            </body>
          </html>
         ";
    break;

}

?>