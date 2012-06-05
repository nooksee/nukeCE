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

global $prefix, $user_prefix, $db, $anonymous, $board_config, $userinfo, $client;

$useavatars = 1; //1 to Show Avatars - 0 is off
$showip = 0; //1 to Show your current IP address - 0 is off
$content = '';

list($lastuser) = $db->sql_ufetchrow("SELECT username FROM ".$user_prefix."_users WHERE user_active = 1 AND user_level > 0 ORDER BY user_id DESC LIMIT 1", SQL_NUM);
list($numrows) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_id > 1 AND user_level > 0", SQL_NUM);
$result = $db->sql_query("SELECT uname, guest FROM ".$prefix."_session WHERE guest='0' OR guest='2'");
$member_online_num = $db->sql_numrows($result);
$who_online_now = "";
$i = 1;
while ($session = $db->sql_fetchrow($result, SQL_ASSOC)) {
    $username2=UsernameColor($session['uname']);
    if (isset($session['guest']) and $session['guest'] == 0 && !empty($username2)) {
        if ($i < 10) {
            $who_online_now .= "0" .$i.".&nbsp;<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$session[uname]\">$username2</a><br />\n";
        } else {
            $who_online_now .= $i.".&nbsp;<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$session[uname]\">$username2</a><br />\n";
        }
        $who_online_now .= ($i != $member_online_num ? "  " : "");
        $i++;
    }
}
$db->sql_freeresult($result);

if ($showip == 1) {
    $client = new Client();
    $ip = $client->getIp();
    $content .= "
                 <br />
                 <div align=\"center\">
                     "._YOURIP.": ".$ip."
                 </div>";
}
if ($useavatars == 1) {
    //Avatars...
    if ($userinfo['user_allowavatar']) {
        $content .= "
                     <br />
                     <br />
                     <div align=\"center\">
                         <img src=\"".$board_config['avatar_path']."/".$userinfo['user_avatar']."\" alt=\"\" />
                     </div>
                     <br />
                    ";
    } elseif ($userinfo['user_avatar_type'] == 2) {
        $content .= "
                     <br />
                     <br />
                     <div align=\"center\">
                         <img src=\"".$userinfo['user_avatar']."\" alt=\"\" />
                     </div>
                     <br />
                    ";
    } elseif (empty($userinfo['user_avatar'])) {
        $content .= "
                     <br />
                     <br />
                     <div align=\"center\">
                         <img src=\"".$board_config['avatar_gallery_path']."/gallery/blank.png\" alt=\"\" />
                     </div>
                    ";
    } else {
        $content .= "
                     <br />
                     <br />
                     <div align=\"center\">
                         <img src=\"".$board_config['avatar_gallery_path']."/".$userinfo['user_avatar']."\" alt=\"\" />
                     </div>
                     <br />
                    ";
    }
}

// Formatting date - Fix
$month = date('M');
$curDate2 = "%".$month[0].$month[1].$month[2]."%".date('d')."%".date('Y')."%";
$ty = time() - 86400;
$preday = strftime('%d', $ty);
$premonth = strftime('%B', $ty);
$preyear = strftime('%Y', $ty);
$curDateP = "%".$premonth[0].$premonth[1].$premonth[2]."%".$preday."%".$preyear."%";

//Select new today
//Select new yesterday
list($userCount) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_regdate LIKE '$curDate2'", SQL_NUM);
list($userCount2) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_regdate LIKE '$curDateP'", SQL_NUM);
//end

list($guest_online_num) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$prefix."_session WHERE guest='1' OR guest='3'", SQL_NUM);
list($member_online_num) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$prefix."_session WHERE guest='0' OR guest='2'", SQL_NUM);

$who_online_num = $guest_online_num + $member_online_num;
$who_online_num = intval($who_online_num);
$content .= "
             <form onsubmit=\"this.submit.disabled='true'\" action=\"modules.php?name=Your_Account\" method=\"post\">
            ";

if (is_user()) {
    $uname = $userinfo['username'];
    $uname_color = UsernameColor($uname);
    $content .= "
                 <br />
                 <img src=\"images/blocks/group-4.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BWEL.", 
                 <strong>
                     $uname_color
                 </strong>
                 <br />
                 <hr />
                ";
    $uid = $userinfo['user_id'];
    list($newpms) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND (privmsgs_type='5' OR privmsgs_type='1')");
    list($oldpms) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND privmsgs_type='0'");
    $content .= "
                 <img src=\"images/blocks/email-y.gif\" height=\"10\" width=\"14\" alt=\"\" /> 
                 <a href=\"modules.php?name=Private_Messages\">
                     <u>
                         <strong>
                             "._MESSAGES.":
                         </strong>
                     </u>
                 </a>
                 <br />
                 <img src=\"images/blocks/email-r.gif\" height=\"10\" width=\"14\" alt=\"\" /> 
                 "._BUNREAD.": 
                 <strong>
                     ".intval($newpms)."
                 </strong>
                 <br />
                 <img src=\"images/blocks/email-g.gif\" height=\"10\" width=\"14\" alt=\"\" /> 
                 "._BREAD.": 
                 <strong>
                     ".intval($oldpms)."
                 </strong>
                 <br />
                 <hr noshade='noshade' />
                ";
    if (is_user()) {
        $content .= "
                     <img src=\"images/blocks/group-5.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                     <a href=\"modules.php?name=Groups\">
                         <u>
                             <strong>
                                 "._GROUPS.":
                             </strong>
                         </u>
                     </a>
                     <br />
                    ";
        $result = $db->sql_query("SELECT group_name FROM ".$prefix."_bbgroups LEFT JOIN ".$prefix."_bbuser_group on ".$prefix."_bbuser_group.group_id=".$prefix."_bbgroups.group_id WHERE ".$prefix."_bbuser_group.user_id='$uid' and ".$prefix."_bbgroups.group_description != 'Personal User'");
        if ($db->sql_numrows($result) == 0) {
            $content .= "
                         &nbsp;
                         <img src=\"images/arrow.gif\" height=\"9\" width=\"9\" alt=\"\" /> 
                         <em>
                             <a href=\"modules.php?name=Groups\">
                                 "._GROUP_MEMBER_JOIN."!
                             </a>
                         </em>
                         <br />
                        ";
        } else {
           while(list($gname) = $db->sql_fetchrow($result, SQL_NUM)) {
              $gname = GroupColor($gname);
              $content .= "
                           &nbsp;
                           <img src=\"images/arrow.gif\" height=\"9\" width=\"9\" alt=\"\" /> 
                           $gname
                           <br />
                          ";
           }
        }
        $db->sql_freeresult($result);
        $content .= "
                     <hr noshade='noshade' />
                    ";
    }
} else {
    $content .= "
                 <img src=\"images/blocks/group-4.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BWEL.", 
                 <strong>
                     $anonymous
                 </strong>
                 <hr />
                 "._NICKNAME." 
                 <input type=\"text\" name=\"username\" size=\"10\" maxlength=\"25\" />
                 <br />
                 "._PASSWORD." 
                 <input type=\"password\" name=\"user_password\" size=\"10\" maxlength=\"20\" />
                 <br />
                ";
    $gfxchk = array(2,4,5,7);
    $content .= security_code($gfxchk, 'stacked');
    $content .= "
                  <input type=\"hidden\" name=\"op\" value=\"login\" />
                  <input type=\"submit\" value=\""._LOGIN."\"> 
                  (
                  <a href=\"modules.php?name=Your_Account&amp;op=new_user\" />
                      "._BREG."
                  </a>
                  )
                  <hr />
                 ";
}
    $content .= "
                 <img src=\"images/blocks/group-2.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 <strong>
                     <u>
                         "._BMEMP.":
                     </u>
                 </strong>
                 <br />
                 <img src=\"images/blocks/ur-moderator.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BLATEST.": 
                 <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$lastuser\">
                     <strong>
                         $lastuser
                     </strong>
                 </a>
                 <br />
                 <img src=\"images/blocks/ur-author.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BTD.": 
                 <strong>
                     $userCount
                 </strong>
                 <br />
                 <img src=\"images/blocks/ur-admin.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BYD.": 
                 <strong>
                     $userCount2
                 </strong>
                 <br />
                 <img src=\"images/blocks/ur-guest.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BOVER.": 
                 <strong>
                     $numrows
                 </strong>
                 <br />
                 <hr />
                 <img src=\"images/blocks/group-3.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 <strong>
                     <u>
                         "._BVISIT.":
                     </u>
                 </strong>
                 <br />
                 <img src=\"images/blocks/ur-anony.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BVIS.": 
                 <strong>
                     $guest_online_num
                 </strong>
                 <br />
                 <img src=\"images/blocks/ur-member.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BMEM.": 
                 <strong>
                     $member_online_num
                 </strong>
                 <br />
                 <img src=\"images/blocks/ur-registered.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 "._BTT.": 
                 <strong>
                     $who_online_num
                 </strong>
                 <br />
                 <hr noshade='noshade' />
                 <img src=\"images/blocks/group-1.gif\" height=\"14\" width=\"17\" alt=\"\" /> 
                 <strong>
                     <u>
                         "._BON.":
                     </u>
                 </strong>
                 <br />
                 $who_online_now
                 </form>
                ";

?>