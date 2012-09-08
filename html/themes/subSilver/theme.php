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
    global $bgcolor4;
    echo "
          <table role=\"presentation\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
              <tr>
                  <td>
                      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                          <tr>
                              <td bgcolor=\"#5E7388\">
                                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                                      <tr>
                                          <td bgcolor=\"#FFFFFF\">
                                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                  <tr>
                                                      <td bgcolor=".$bgcolor4.">
                                                          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                                                              <tr>
                                                                  <td>
         ";
}

function CloseTable() {
    echo "
                                                                  </td>
                                                              </tr>
                                                          </table>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;
    echo "
          <table role=\"presentation\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=".$bgcolor2." align=\"center\">
              <tr>
                  <td>
                      <table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=".$bgcolor1.">
                          <tr>
                              <td>
         ";
}

function CloseTable2() {
    echo "
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
         ";
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
/* Function themeheader()                                   */
/*                                                          */
/* Control the header for your site. You need to define the */
/* BODY tag and in some part of the code call the blocks    */
/* function for left side with: blocks(left);               */
/************************************************************/

function themeheader() {
    global  $sitename, $nukeurl, $theme_name, $bgcolor1, $textcolor1, $link1text, $link2text, $link3text, $link4text, $link1, $link2, $link3, $link4;
    $ads = ads(0);
    echo "
          <body bgcolor=".$bgcolor1." text=".$textcolor1." link=\"0000ff\">
              <table role=\"presentation\" class=\"bodyline\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"70%\" align=\"center\">
                  <tr>
                      <td width=\"100%\">
                          <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                              <tr>
                                  <td width=\"100%\">
                                      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                                          <tr>
                                              <td width=\"100%\" height=\"88\" bgcolor=\"#FFFFFF\">
                                                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" class=\"topnav\">
                                                      <tr>
                                                          <td class=\"catHead\"><div align=\"right\"><font class=\"content\"><b><a href=".$link1."><u>$link1text</u></a>&nbsp;::&nbsp;<a href=".$link2."><u>$link2text</u></a>&nbsp;::&nbsp;<a href=".$link3."><u>$link3text</u></a>&nbsp;::&nbsp;<a href=".$link4."><u>$link4text</u></a></b>&nbsp;</font></div></td>
                                                      </tr>
                                                  </table>
                                                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                                                      <tr>
                                                          <td>&nbsp;</td>
                                                      </tr>
                                                  </table>
                                                  <table border=0 width=100% cellpadding=0 cellspacing=0>
                                                      <tr>
                                                          <td align=\"left\"><a href=".$nukeurl."><img border=\"0\" src=\"themes/".$theme_name."/images/nukece.png\" alt=\"".$sitename."\" title=\"".$sitename."\" hspace=\"20\"></a></td>
                                                          <td><img src=\"themes/".$theme_name."/images/pixel.gif\" width=\"60\" height=\"60\" border=\"0\" alt=\"\"></td>
                                                          <td align=\"right\">$ads</td>
                                                          <td><img src=\"themes/".$theme_name."/images/pixel.gif\" width=\"10\" height=\"60\" border=\"0\" alt=\"\"></td>
                                                      </tr>
                                                  </table>
                                              </td>
                                          </tr>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td width=\"100%\">
                                          <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                                              <tr>
                                                  <td>&nbsp;</td>
                                              </tr>
                                          </table>
                                          <table width=\"100%\" cellpadding=\"0\" bgcolor=\"ffffff\" cellspacing=\"0\" border=\"0\">
                                              <tr valign=\"top\">
                                                  <td><img src=\"themes/".$theme_name."/images/pixel.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
                                                  <td width=\"145\" bgcolor=\"ffffff\" valign=\"top\">
         ";
    if(blocks_visible('left')) {
        blocks("left");
        echo "
              </td>
              <td><img src=\"themes/".$theme_name."/images/pixel.gif\" width=\"10\" height=\"1\" border=\"0\" alt=\"\"></td>
              <td width=\"100%\">
             ";
    } else {
        echo "
              </td>
              <td><img src=\"themes/".$theme_name."/images/pixel.gif\" width=\"1\" height=\"1\" border=\"0\" alt=\"\"></td>
              <td width=\"100%\">
             ";
    }
}
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
    global  $theme_name;
    echo '<br />';
    if (blocks_visible('right') && !defined('ADMIN_FILE')) {
        echo '
              </td>
              <td><img src="themes/'.$theme_name.'/images/pixel.gif" width="10" height="1" border="0" alt="" /></td>
              <td valign="top" width="145" bgcolor="#ffffff" align="left">
             ';
        blocks('right');
        // blocks right inserts two whole tables from themesidebox
        echo '
              </td>
              <td><img src="themes/'.$theme_name.'/images/pixel.gif" width="10" height="1" border="0" alt="" /></td>
             ';
    } else {
        echo '
              </td>
              <td colspan="2"><img src="themes/'.$theme_name.'/images/pixel.gif" width="9" height="1" border="0" alt="" /></td>
             ';
    }
    echo '
              </tr>
          </table>
          <br />
          <div align="center">
         ';
    footmsg();
    echo '
                      </div>
                  </td>
              </tr>
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
    global $collapse, $theme_name, $bgcolor3, $bgcolor4, $tipath;
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
          <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                  <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="1">
                          <tr>
                              <td bgcolor="#5E7388">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                      <tr>
                                          <td bgcolor="#FFFFFF">
                                              <table width="100%" border="0" cellspacing="1" cellpadding="0">
         ';
    if($collapse) { echo '<tr title="Click to expand/collapse" style="cursor: pointer;" class="parent" id="rowindex">'; } else { echo '<tr>'; }
    echo '
                                                      <td height="30" background="themes/'.$theme_name.'/images/cellpic3.gif" bgcolor="#98aab1">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
														      <tr>
                                                                  <td><font class="storytitle">'.$title.'</font></td>
                                                              </tr>
                                                          </table>
                                                      </td>
                                                  </tr>
         ';
    if($collapse) { echo '<tr class="child-rowindex">'; } else { echo '<tr>'; }
    echo '
                                                      <td bgcolor='.$bgcolor4.'>
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
                                                  </tr>
         ';
    if($collapse) { echo '<tr class="child-rowindex">'; } else { echo '<tr>'; }
    echo '
                                                      <td bgcolor='.$bgcolor3.'>
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                  <td><div align="center"><font class="content">'.$posted.' ( Reads: '.$counter.' )<br>'.$datetime.' | '.$morelink.'</font></div></td>
                                                              </tr>
                                                          </table>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          <br />
         ';
}

/************************************************************/
/* Function themearticle()                                  */
/*                                                          */
/* This function format the stories on the story page, when */
/* you click on that "Read More..." link in the home        */
/************************************************************/

function themearticle($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
    global $collapse, $theme_name, $bgcolor3, $bgcolor4, $tipath;
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
          <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                  <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="1">
                          <tr>
                              <td bgcolor="#5E7388">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                      <tr>
                                          <td bgcolor="#FFFFFF">
                                              <table width="100%" border="0" cellspacing="1" cellpadding="0">
         ';
    if($collapse) { echo '<tr title="Click to expand/collapse" style="cursor: pointer;" class="parent" id="rowarticle">'; } else { echo '<tr>'; }
    echo '
                                                      <td height="30" background="themes/'.$theme_name.'/images/cellpic3.gif" bgcolor="#98aab1">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                  <td><font class="storytitle">'.$title.'</font></td>
                                                              </tr>
                                                          </table>
                                                      </td>
                                                  </tr>
         ';
    if($collapse) { echo '<tr class="child-rowarticle">'; } else { echo '<tr>'; }
    echo '
                                                      <td bgcolor='.$bgcolor4.'>
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
                                                  </tr>
         ';
    if($collapse) { echo '<tr class="child-rowarticle">'; } else { echo '<tr>'; }
    echo '
                                                      <td bgcolor='.$bgcolor3.'>
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                  <td><div align="center"><font class="content">'.$posted.'</font></div></td>
                                                              </tr>
                                                          </table>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          <br />
         ';
}

/************************************************************/
/* Function themesidebox()                                  */
/*                                                          */
/* Control look of your blocks. Just simple.                */
/************************************************************/

function themesidebox($title, $content) {
    // note:  this gets called by the mainfile render blocks function when side is left or right
    global $collapse, $theme_name, $bgcolor4;
    echo '
          <table role="presentation" width="145" border="0" cellspacing="0" cellpadding="1">
              <tr>
                  <td>
                      <table width="145" border="0" cellspacing="0" cellpadding="1">
                          <tr>
                              <td bgcolor="#5E7388">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                      <tr>
                                          <td bgcolor="#FFFFFF">
                                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
         ';
    if($collapse) { echo '<tr title="Click to expand/collapse" style="cursor: pointer;" class="parent" id="rowsidebox">'; } else { echo '<tr>'; }
    echo '
                                                     <td height="20" background="themes/'.$theme_name.'/images/cellpic3.gif" bgcolor="#98aab1">
                                                         <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                             <tr>
                                                                 <td><font class="block-title"><strong>'.$title.'</strong></font></td>
                                                             </tr>
                                                         </table>
                                                     </td>
                                                 </tr>
         ';
    if($collapse) { echo '<tr class="child-rowsidebox">'; } else { echo '<tr>'; }
    echo '
                                                     <td bgcolor='.$bgcolor4.'>
                                                         <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                             <tr>
                                                                 <td><font class="content">'.$content.'</font></td>
                                                             </tr>
                                                          </table>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>
          <br />
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