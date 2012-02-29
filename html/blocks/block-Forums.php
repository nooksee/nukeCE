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

if(!defined('NUKE_CE')) exit;

global $prefix, $db, $sitename, $admin, $ThemeSel, $currentlang, $board_config, $user_prefix;

get_lang('blocks');
$HideViewReadOnly = 1;
$Last_New_Topics  = 5;
$show = "
                                 </table>
                             </td>
                         </tr>
                     </table>
                 </td>
             </tr>
         </table>
         <table width=\"100%\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\">
             <tr>
                 <td align=\"right\" valign=\"bottom\">
                     <span class=\"gensmall\">
                         <a href=\"rss.php?feed=forums\" target=\"_blank\">
                             "._RSS."
                         </a>
                     </span>
                 </td>
             </tr>
         </table>
        ";

$Count_Topics = 0;
$Topic_Buffer = "";
$result = $db->sql_query( "SELECT t.topic_id, f.forum_id, t.topic_last_post_id, t.topic_first_post_id, t.topic_title, t.topic_poster, t.topic_views, t.topic_replies, t.topic_moved_id FROM ".$prefix."_bbtopics t, ".$prefix."_bbforums f WHERE t.forum_id=f.forum_id ORDER BY topic_last_post_id DESC");
while ( list( $topic_id, $forum_id, $topic_last_post_id, $topic_first_post_id, $topic_title, $topic_poster, $topic_views, $topic_replies, $topic_moved_id ) = $db->sql_fetchrow( $result) ) {
   $skip_display = 0;
   if ( $HideViewReadOnly == 1 ) {
      $result1 = $db->sql_query( "SELECT auth_view, auth_read FROM ".$prefix."_bbforums WHERE forum_id = '$forum_id'");
      list( $auth_view, $auth_read ) = $db->sql_fetchrow($result1);
      if ( ( $auth_view != 0 ) or ( $auth_read != 0 ) ) {
          $skip_display = 1;
      }
   }

   if ( $topic_moved_id != 0 ) {
      // Shadow Topic !!
      $skip_display = 1;
   }

   if ( $skip_display == 0 ) {
      $Count_Topics += 1;

      $result2 = $db->sql_query("SELECT username, user_id FROM ".$user_prefix."_users WHERE user_id='$topic_poster'");
      list($username, $user_id) = $db->sql_fetchrow($result2);
      $username = UsernameColor($username);
      $sifra = $user_id;

      $result3 = $db->sql_query("SELECT poster_id, post_time FROM ".$prefix."_bbposts WHERE post_id='$topic_last_post_id'");
      list($poster_id, $post_time) = $db->sql_fetchrow($result3);
      $post_time = NukeDate( $board_config['default_dateformat'] , $post_time , $board_config['board_timezone'] );

      $result4 = $db->sql_query("SELECT username, user_id FROM ".$user_prefix."_users WHERE user_id='$poster_id'");
      list($username, $user_id) = $db->sql_fetchrow($result4);
      $username = UsernameColor($username);

      $ThemeSel = get_theme();
      if (file_exists("themes/$ThemeSel/forums/images/folder_new.gif")) {
          $image = "themes/$ThemeSel/forums/images/folder_new.gif";
      } else {
          $image = "modules/Forums/templates/subSilver/images/folder_new.gif";
      }
      if (file_exists("themes/$ThemeSel/forums/images/icon_newest_reply.gif")) {
          $image2 = "themes/$ThemeSel/forums/images/icon_newest_reply.gif";
      } else {
          $image2 = "modules/Forums/templates/subSilver/images/icon_newest_reply.gif";
      }
      $viewlast .= "
                    <tr>
                        <td height=\"34\" nowrap class=\"row1\">
                            <img src=\"$image\" alt=\"New Topic\" border=\"0\" />
                        </td>
                        <td width=\"100%\" nowrap class=\"row1\">
                            <a href=\"modules.php?name=Forums&file=viewtopic&p=$topic_first_post_id#$topic_first_post_id\">
                                $topic_title
                            </a>
                        </td>
                        <td align=\"center\" class=\"row2\">
                            $topic_replies
                        </td>
                        <td align=\"center\" class=\"row2\">
                            $topic_views
                        </td>
                        <td align=\"center\" nowrap class=\"row3\">
                            <font size=\"-2\">
                                <i>
                                    &nbsp;
                                    $post_time
                                    &nbsp;
                                </i>
                            </font>
                            <br />
                            <a href=\"modules.php?name=Forums&amp;file=profile&amp;mode=viewprofile&amp;u=$user_id\">
                                $username
                            </a>
                            <a href=\"modules.php?name=Forums&amp;file=viewtopic&amp;p=$topic_last_post_id#$topic_last_post_id\">
                                <img src=\"$image2\" alt=\"New Post\" border=\"0\">
                            </a>
                        </td>
                    </tr>
                   ";
      }

      if ( $Last_New_Topics == $Count_Topics ) {
          break 1;
      }

   }

$content .= "
             <table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">
                 <tr> 
                     <td align=\"left\" valign=\"bottom\">
                         <span class=\"nav\">
                             <a href=\"modules.php?name=Forums\" class=\"nav\">
                                 $sitename "._UNIFORUM."
                             </a>
                         </span>
                     </td>
                     <td align=\"right\" valign=\"bottom\">
                         <a href=\"modules.php?name=Forums&file=search&search_id=unanswered\" class=\"gensmall\">
                             "._UNIUNANSWERED."
                         </a>
                         <br />
                         <a href=\"modules.php?name=Forums&amp;file=recent\" class=\"gensmall\">
                             "._UNIRECENT."
                         </a>
                     </td>
                 </tr>
             </table>
             <table align=\"center\" class=\"forumline\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                 <tr>
                     <td>
                         <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                             <tr>
                                 <td>
                                     <table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"1\">
                                         <tr>
                                             <th height=\"25\" colspan=\"2\" align=\"center\" nowrap class=\"thcornerl\">
                                                 <span class=\"block-title\">
                                                     <strong>
                                                         "._UNISUBJECT."
                                                     </strong>
                                                 </span>
                                             </th>
                                             <th width=\"50\" align=\"center\" class=\"thtop\">
                                                 <span class=\"block-title\">
                                                     <strong>
                                                         "._UNIREPLIES."
                                                     </strong>
                                                 </span>
                                             </th>
                                             <th width=\"50\" align=\"center\" class=\"thtop\">
                                                 <span class=\"block-title\">
                                                     <strong>
                                                         "._UNIVIEWS."
                                                     </strong>
                                                 </span>
                                             </th>
                                             <th align=\"center\" nowrap class=\"thcornerr\">
                                                 <span class=\"block-title\">
                                                     <strong>
                                                         "._UNILASTPOST."
                                                     </strong>
                                                 </span>
                                             </th>
                                         </tr>
                                         <tr> 
                                             <td class=\"catLeft\" colspan=\"2\" height=\"28\">
                                                 <span class=\"cattitle\">
                                                     "._UNIRECENT."
                                                 </span>
                                             </td>
                                             <td class=\"rowpic\" colspan=\"4\" align=\"right\">&nbsp;</td>
                                         </tr>
            ";
$content .= "$viewlast";

$content .= "$show";

?>