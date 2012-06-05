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

global $user, $cookie, $prefix, $db, $user_prefix, $userinfo, $client, $result;
$client = new Client();
$ip = $client->getIp();
$url = $_SERVER['REQUEST_URI'];
$uname = $ip;
$guest = 1;
$result = UA::parse();

if (is_user()) {
    $uname = $userinfo['username'];
    $guest = 0;
} elseif(is_admin()) {
    $guest = 2;
/*****[BEGIN]******************************************
 [ Base:    Advanced Security Extension        v1.0.0 ]
 ******************************************************/
} elseif($result->isSpider) {
    $uname = "Spider";
    $guest = 3;
}
/*****[END]********************************************
 [ Base:    Advanced Security Extension        v1.0.0 ]
 ******************************************************/

$guest_online_sql = "SELECT * FROM ".$prefix."_session WHERE guest='1' OR guest='3'";
$guest_online_query = $db->sql_query($guest_online_sql);
$guest_online_num = $db->sql_numrows($guest_online_query);

$member_online_sql = "SELECT * FROM ".$prefix."_session WHERE guest='0' OR guest='2'";
$member_online_query = $db->sql_query($member_online_sql);
$member_online_num = $db->sql_numrows($member_online_query);

$who_online_num = $guest_online_num + $member_online_num;
$who_online = "
               <div align=\"center\">
                   <span class=\"content\">
                       "._CURRENTLY." $guest_online_num "._GUESTS." $member_online_num "._MEMBERS."
                   <br />";

$content = $who_online;

if (is_user()) {
    if (is_active("Private_Messages")) {
        $sql = "SELECT user_id FROM ".$user_prefix."_users WHERE username='$uname'";
        $query = $db->sql_query($sql);
        list($user_id) = $db->sql_fetchrow($query);
        $db->sql_freeresult($query);
        $uid = intval($user_id);
        $sql = "SELECT * FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND (privmsgs_type='5' OR privmsgs_type='1')";
        $query = $db->sql_query($sql);
        $newpm = $db->sql_numrows($query);
        $db->sql_freeresult($query);
    }
}

$db->sql_freeresult($query);
if (is_user()) {
    $content .= "
                   <br />
                   "._YOUARELOGGED." 
                   <strong>
                       $uname
                   </strong>
                   <br />
                ";
    if (is_active("Private_Messages")) {
        $sql = "SELECT user_id FROM ".$user_prefix."_users WHERE username='$uname'";
        $query = $db->sql_query($sql);
        list($user_id) = $db->sql_fetchrow($query);
        $uid = intval($user_id);
        $sql = "SELECT privmsgs_to_userid FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND (privmsgs_type='1' OR privmsgs_type='5' OR privmsgs_type='0')";
        $query = $db->sql_query($sql);
        $numrow = $db->sql_numrows($query);
        $content .= "
                     "._YOUHAVE." 
                     <a href=\"modules.php?name=Private_Messages\">
                         <strong>
                             $numrow
                         </strong>
                     </a> 
                     "._PRIVATEMSG."
                    ";
    }
    $content .= "
                     </span>
                 </div>
                ";
} else {
    $content .= "
                     <br />
                     "._YOUAREANON."
                     </span>
                 </div>
                ";
}

?>