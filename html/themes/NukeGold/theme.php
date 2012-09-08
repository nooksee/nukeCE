<?

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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$theme_name = basename(dirname(__FILE__));

include_once(NUKE_THEMES_DIR.$theme_name.'/theme_info.php');

/************************************************************/
/* Theme Colors Definition                                  */
/************************************************************/

global $ThemeInfo;
$bgcolor1 = $ThemeInfo['bgcolor1'];
$bgcolor2 = $ThemeInfo['bgcolor2'];
$bgcolor3 = $ThemeInfo['bgcolor3'];
$bgcolor4 = $ThemeInfo['bgcolor4'];
$textcolor1 = $ThemeInfo['textcolor1'];
$textcolor2 = $ThemeInfo['textcolor2'];

/************************************************************/
/* OpenTable Functions                                      */
/*                                                          */
/* Define the tables look&feel for you whole site. For this */
/* we have two options: OpenTable and OpenTable2 functions. */
/* Then we have CloseTable and CloseTable2 function to      */
/* properly close our tables. The difference is that        */
/* OpenTable has a 100% width and OpenTable2 has a width    */
/* according with the table content                         */
/************************************************************/

function OpenTable() {
    echo '
          <table role="presentation" style="background-color: #888888;" width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                  <td>
                      <table style="background-color: #f9f9f9;" width="100%" border="0" cellspacing="1" cellpadding="8">
                          <tr>
                              <td style="background-image: url(themes/NukeGold/images/bg.jpg)">
         ';
}

function CloseTable() {
    echo '
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ';
}

function OpenTable2() {
    echo '
          <table role="presentation" style="background-color: #888888;" border="0" cellspacing="1" cellpadding="0" align="center">
              <tr>
                  <td>
                      <table style="background-color: #f9f9f9;" border="0" cellspacing="1" cellpadding="8">
                          <tr>
                              <td style="background-image: url(themes/NukeGold/images/t2_bg.jpg)">
         ';
}

function CloseTable2() {
    echo '
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          <br>
         ';
}

/************************************************************/
/* Function themeheader()                                   */
/*                                                          */
/* Control the header for your site. You need to define the */
/* BODY tag and in some part of the code call the blocks    */
/* function for left side with: blocks(left);               */
/************************************************************/

function themeheader() {
    global  $theme_name, $sitename, $slogan, $nukeurl;
    $ads = ads(0);
    $datetime = "
                 <script type=\"text/javascript\">
                     var monthNames = new Array( \""._JANUARY."\",\""._FEBRUARY."\",\""._MARCH."\",\""._APRIL."\",\""._MAY."\",\""._JUNE."\",\""._JULY."\",\""._AUGUST."\",\""._SEPTEMBER."\",\""._OCTOBER."\",\""._NOVEMBER."\",\""._DECEMBER."\")
                     var now = new Date()
                     thisYear = now.getYear()
                     if(thisYear < 1900) {thisYear += 1900}
                     document.write(monthNames[now.getMonth()] + \" \" + now.getDate() + \", \" + thisYear)
                 </script>
                ";
    echo '
          <body style="background-color: #ffffff;" text="#323232" topmargin="0">
              <BR>
              <table role="presentation" style="background-color: #ffffff;" border="0" width="100%">
                  <tr>
                      <td align="left"><a href="'.$nukeurl.'"><img src="themes/'.$theme_name.'/images/logo.gif" border="0" alt="'.$sitename.'" title="'.$sitename.'"></a></td>
                      <td width="100%">&nbsp;</td>
                      <td align="right"><center>'.$ads.'</center></td>
                  </tr>
              </table>
              <table border="0" width="100%" cellpadding="2" cellspacing="0">
                  <tr>
                      <td style="background-color: #efefef;" align="left"><font class="tinygrey">&nbsp;'.$slogan.'</font></td>
                      <td style="background-color: #efefef;" align="right"><font class="tinygrey">'.$datetime.'</font></td>
                  </tr>
              </table>
              <br>
              <table role="presentation" style="background-color: #ffffff;" cellpadding="0" cellspacing="0" width="99%" border="0" align="center">
                  <tr>
                      <td style="background-color: #ffffff;" valign="top">
         ';
    if(blocks_visible('left')) {
        blocks("left");
        echo '
              </td>
              <td valign="top"><img src="themes/'.$theme_name.'/images/pixel.gif" width="10" height="1" border="0" alt=""></td>
              <td width="100%" valign="top">
             ';
    } else {
        echo '
              </td>
              <td valign="top"><img src="themes/'.$theme_name.'/images/pixel.gif" width="1" height="1" border="0" alt=""></td>
              <td width="100%" valign="top">
             ';
    }
}

/************************************************************/
/* Header Menu Definition                                   */
/************************************************************/

$link1 = $ThemeInfo['link1'];
$link2 = $ThemeInfo['link2'];
$link3 = $ThemeInfo['link3'];
$link4 = $ThemeInfo['link4'];
$link1text = $ThemeInfo['link1text'];
$link2text = $ThemeInfo['link2text'];
$link3text = $ThemeInfo['link3text'];
$link4text = $ThemeInfo['link4text'];

/************************************************************/
/* Function themefooter()                                   */
/*                                                          */
/* Control the footer for your site. You don't need to      */
/* close BODY and HTML tags at the end. In some part call   */
/* the function for right blocks with: blocks(right);       */
/* Also, $index variable need to be global and is used to   */
/* determine if the page your're viewing is the Homepage or */
/* and internal one.                                        */
/************************************************************/

function themefooter() {
    global  $theme_name, $link1text, $link2text, $link3text, $link4text, $link1, $link2, $link3, $link4;
    $ads = ads(2);
    if (blocks_visible('right') && !defined('ADMIN_FILE')) {
        echo '
              </td>
              <td><img src="themes/'.$theme_name.'/images/pixel.gif" width="10" height="1" border="0" alt=""></td>
              <td valign="top">
             ';
        blocks('right');
        // blocks right inserts two whole tables from themesidebox
    } else {
        echo '
              </td>
              <td colspan="2"><img src="themes/'.$theme_name.'/images/pixel.gif" width="1" height="1" border="0" alt="" /></td>
              <td width="100%" valign="top">
             ';
    }
    echo '
                  </td>
              </tr>
          </table>
          <br>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody>
                  <tr>
                      <td style="background-color: #999999;"><img src="themes/'.$theme_name.'/images/pixel.gif" alt="" height="1" width="1"></td>
                  </tr>
              </tbody>
          </table>
          <div><img src="themes/'.$theme_name.'/images/pixel.gif" alt="" height="1" width="1"></div>
          <table style="background-color: #dedede;" align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
              <tbody>
                  <tr>
                      <td align="center"><a href='.$link1.'><font class="footmsg">'.$link1text.'</font></a></td>
                      <td align="center"><a href='.$link2.'><font class="footmsg">'.$link2text.'</font></a></td>
                      <td align="center"><a href='.$link3.'><font class="footmsg">'.$link3text.'</font></a></td>
                      <td align="center"><a href='.$link4.'><font class="footmsg">'.$link4text.'</font></a></td>
                      <td align="center"><a href="modules.php?name=Submit_News"><font class="footmsg">Submit News</font></a></td>
                      <td align="center"><a href="modules.php?name=Site_Map"><font class="footmsg">Site Map</font></a></td>
                      <td align="center"><a href="modules.php?name=FAQ"><font class="footmsg">F.A.Q.</font></a></td>
                      <td align="center"><a href="modules.php?name=Feedback"><font class="footmsg">Feedback</font></a></td>
                  </tr>
              </tbody>
          </table>
          <div><img src="themes/'.$theme_name.'/images/pixel.gif" alt="" height="1" width="1"></div>
          <table style="background-color: #efefef;" border="0" cellpadding="3" cellspacing="0" width="100%">
              <tbody>
                  <tr valign="middle">
                      <td width="60%"><font class="footmsg">
         ';
    footmsg();
    echo '                          
                      </font></td>
                      <td align="center"><img src="themes/'.$theme_name.'/images/dot_line.gif" alt="" height="92" width="7"></td>
                      <td valign="middle" width="40%">'.$ads.'</td>
                      <td align="center"><img src="themes/'.$theme_name.'/images/dot_line.gif" alt="" height="92" width="7"></td>
                      <td valign="middle" width="120"><a href="http://phpnuke.org"><img src="themes/'.$theme_name.'/images/88button.jpg" alt="Powered by PHP-Nuke" title="Powered by PHP-Nuke" border="0" height="31" width="88"></a></td>
                  </tr>
              </tbody>
          </table>
         ';
}

/************************************************************/
/* Function FormatStory()                                   */
/************************************************************/

function FormatStory($thetext, $notes, $aid, $informant) {
    global $anonymous;
    $content = '';
    $thetext = '<div>'.$thetext.'</div>';
    if (!empty($notes)) {
        $notes = '<br /><div><b>'._EDITORNOTE.'</b><i>'.$notes.'</i></div>';
    } else {
        $notes = '';
    }
    if ($aid == $informant) {
        $content = $thetext.$notes;
    } else {
        if(defined('WRITES')) {
            if(!empty($informant)) {
                global $admin, $user;
                $username = strip_tags($informant);
                if (is_user($user)||is_admin($admin)) $content = '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$username.'"><i>'.$informant.'</i></a> ';
                else $content = $informant.' ';//Raven 10/16/2005
            } else {
                $content = $anonymous.' ';
            }
            $content .= '<i>'._WRITES.':</i>&nbsp;'.$thetext.$notes.'';
        } else {
            $content = $thetext.$notes.' ';
        }
    }
    echo $content;
}

/************************************************************/
/* Function themeindex()                                    */
/*                                                          */
/* This function format the stories on the Homepage         */
/************************************************************/

function themeindex ($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
    global $theme_name, $tipath;
    $ThemeSel = get_theme();
    if(!empty($topicimage)) {
        if (file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
            $t_image = "themes/$ThemeSel/images/topics/$topicimage";
        } else {
            $t_image = "$tipath$topicimage";
        }
        $topic_img = "$t_image";
    } else {
        $topic_img = "";
    }
    $posted = ""._POSTEDBY." ";
    $posted .= get_author($aid);
    $posted .= " "._ON." $time $timezone";
    $datetime = substr($morelink, 0, strpos($morelink, "|") - strlen($morelink));
    $morelink = substr($morelink, strlen($datetime) + 2);    
    echo '
          <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
              <tr>
                  <td width="15" height="15"><img src="themes/'.$theme_name.'/images/up-left2.gif" alt="" border="0"></td>
                  <td style="background-image: url(themes/NukeGold/images/up2.gif)" align="center" width="100%" height="15">&nbsp;</td>
                  <td><img src="themes/'.$theme_name.'/images/up-right2.gif" width="15" height="15" alt="" border="0"></td>
              </tr>
              <tr>
                  <td style="background-image: url(themes/NukeGold/images/left2.gif)" width="15">&nbsp;</td>
                  <td style="background-color: #FFFFFF;" width="100%">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                          <tr>
                              <td><a href="modules.php?name=News&amp;new_topic='.$topic.'"><img src="'.$topic_img.'" border="0" alt="'.$topictext.'" title="'.$topictext.'" align="right" hspace="10" vspace="10"></a><b>'.$title.'</b><br><br>
         ';
    FormatStory($thetext, $notes, $aid, $informant);
    echo '
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="background-image: url(themes/NukeGold/images/right2.gif)">&nbsp;</td>
              </tr>
              <tr>
                  <td width="15" height="15"><img src="themes/'.$theme_name.'/images/middle-left.gif" alt="" border="0"></td>
                  <td style="background-image: url(themes/NukeGold/images/middle.gif)" align="center" height="15">&nbsp;</td>
                  <td><img src="themes/'.$theme_name.'/images/middle-right.gif" width="15" height="15" alt="" border="0"></td>
              </tr>
              <tr>
                  <td style="background-image: url(themes/NukeGold/images/left3.gif)" width="15">&nbsp;</td>
                  <td style="background-color: #FFFFFF;" align="center"><font class="tiny">'.$posted.'</font><br><font class="content">'.$datetime.' | '.$morelink.'</font></td>
                  <td style="background-image: url(themes/NukeGold/images/right3.gif)" width="15">&nbsp;</td>
              </tr>
              <tr>
                  <td width="15" height="11" valign="top"><img src="themes/'.$theme_name.'/images/down-left3.gif" alt="" border="0"></td>
                  <td width="100%"><img src="themes/'.$theme_name.'/images/down3.gif" width="100%" height="11"></td>
                  <td valign="top"><img src="themes/'.$theme_name.'/images/down-right3.gif" width="15" height="11" alt="" border="0"></td>
              </tr>
          </table>
          <br>
         ';
}

/************************************************************/
/* Function themearticle()                                  */
/*                                                          */
/* This function format the stories on the story page, when */
/* you click on that "Read More..." link in the home        */
/************************************************************/

function themearticle($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
    global $theme_name, $tipath;
    $ThemeSel = get_theme();
    if(!empty($topicimage)) {
        if (file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
            $t_image = "themes/$ThemeSel/images/topics/$topicimage";
        } else {
            $t_image = "$tipath$topicimage";
        }
        $topic_img = "$t_image";
    } else {
        $topic_img = "";
    }
    $posted = _POSTEDON." $datetime "._BY." ";
    $posted .= get_author($aid);    
    echo '
          <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
              <tr>
                  <td width="15" height="15"><img src="themes/'.$theme_name.'/images/up-left2.gif" alt="" border="0"></td>
                  <td style="background-image: url(themes/NukeGold/images/up2.gif)" align="center" width="100%" height="15">&nbsp;</td>
                  <td><img src="themes/'.$theme_name.'/images/up-right2.gif" width="15" height="15" alt="" border="0"></td>
              </tr>
              <tr>
                  <td style="background-image: url(themes/NukeGold/images/left2.gif)" width="15">&nbsp;</td>
                  <td style="background-color: #FFFFFF;" width="100%"><font class="option">'.$title.'</font><br><font class="content">'.$posted.'</font></td>
                  <td style="background-image: url(themes/NukeGold/images/right3.gif)" width="15">&nbsp;</td>
              </tr>
              <tr>
                  <td width="15" height="15"><img src="themes/'.$theme_name.'/images/middle-left.gif" alt="" border="0"></td>
                  <td style="background-image: url(themes/NukeGold/images/middle.gif)" align="center" height="15">&nbsp;</td>
                  <td><img src="themes/'.$theme_name.'/images/middle-right.gif" width="15" height="15" alt="" border="0"></td>
              </tr>
              <tr>
                  <td style="background-image: url(themes/NukeGold/images/left3.gif)" width="15">&nbsp;</td>
                  <td style="background-color: #FFFFFF;" width="100%">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                          <tr>
                              <td><a href="modules.php?name=News&amp;new_topic='.$topic.'"><img src="'.$topic_img.'" border="0" alt="'.$topictext.'" title="'.$topictext.'" align="right" hspace="10" vspace="10"></a>
         ';
    FormatStory($thetext, $notes, $aid, $informant);
    echo '
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="background-image: url(themes/NukeGold/images/right3.gif)">&nbsp;</td>
              </tr>
              <tr>
                  <td width="15" height="11" valign="top"><img src="themes/'.$theme_name.'/images/down-left3.gif" alt="" border="0"></td>
                  <td width="100%"><img src="themes/'.$theme_name.'/images/down3.gif" width="100%" height="11"></td>
                  <td valign="top"><img src="themes/'.$theme_name.'/images/down-right3.gif" width="15" height="11" alt="" border="0"></td>
              </tr>
          </table>        
          <br>
         ';
}

/************************************************************/
/* Function themesidebox()                                  */
/*                                                          */
/* Control look of your blocks. Just simple.                */
/************************************************************/

function themesidebox($title, $content) {
    // note:  this gets called by the mainfile render blocks function when side is left or right
    global $theme_name;
    echo '
                  <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="150">
                      <tr>
                          <td style="background-image: url(themes/NukeGold/images/block_title.jpg)" width="150" height="20">&nbsp;&nbsp;<b>'.$title.'</b></td>
                      </tr>
                      <tr>
                          <td width="150" height="5"><img src="themes/'.$theme_name.'/images/pixel.gif" alt="" border="0" height="3"><img src="themes/'.$theme_name.'/images/block_border_up.jpg" alt="" border="0"></td>
                      </tr>
                      <tr>
                          <td style="background-image: url(themes/NukeGold/images/block_bg.jpg)" width="150">
                              <table border="0" cellspacing="5" cellpadding="0" width="150">
                                  <tr>
                                      <td><font class="content">'.$content.'</font></td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                      <tr>
                          <td width="150" height="10"><img src="themes/'.$theme_name.'/images/block_border_down.jpg" alt="" border="0"></td>
                      </tr>
                  </td>
              </tr>
          </table>
          <br>
         ';
}

function themecenterbox($title, $content) {
    OpenTable();
    echo '<div align="center"><span class="option">'.$title.'</span></div><br />'.$content.'';
    CloseTable();
    echo '<br />';
}

function themepreview($title, $hometext, $bodytext='', $notes='') {
    echo '<strong>'.$title.'</strong><br /><br />'.$hometext.'';
    if (!empty($bodytext)) {
        echo '<br /><br />'.$bodytext.'';
    }
    if (!empty($notes)) {
        echo '<br /><div><b>'._EDITORNOTE.'</b><i>'.$notes.'</i></div>';
    }
}

?>