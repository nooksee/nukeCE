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

global $redirect, $mode, $f, $t, $sitekey, $nukeurl, $user, $cookie, $prefix, $user_prefix, $db, $anonymous, $userinfo, $sysconfig;

$content = '';

// User Login
if (is_user()) {
    $memname = UsernameColor($userinfo['username']);
    $content .= "
                 <div align=\"center\">
                     "._BWEL.", 
                         <strong>
                             $memname
                         </strong>
                     <br />
                     (
                     <a href=\"modules.php?name=Your_Account&amp;op=logout\">
                         "._BLOGOUT."
                     </a>
                     )
                     <hr noshade size=\"1\" />
                 </div>
                ";
if(is_active('Private_Messages')) {
    list($uid) = $db->sql_fetchrow($db->sql_query("select user_id from $user_prefix"._users." where username='$memname'"));
    $newpms = $db->sql_numrows($db->sql_query("select privmsgs_to_userid from $prefix"._bbprivmsgs." where privmsgs_to_userid='$uid' and (privmsgs_type='1' OR privmsgs_type='5')"));
    $savpms = $db->sql_numrows($db->sql_query("select privmsgs_to_userid from $prefix"._bbprivmsgs." where privmsgs_to_userid='$uid' and privmsgs_type='3'"));
    $oldpms = $db->sql_numrows($db->sql_query("select privmsgs_to_userid from $prefix"._bbprivmsgs." where privmsgs_to_userid='$uid' and privmsgs_type='0'"));
    $totpms = $newpms + $oldpms + $savpms;
    $content .= "
                 &nbsp;
                 <a href=\"modules.php?name=Private_Messages\">
                     <strong>
                         "._BPM.":
                     </strong>
                 </a>
                 <br />
                 &nbsp;
                 <big>
                     <strong>
                         &middot;
                     </strong>
                 </big> 
                     "._BUNREAD.": 
                     <strong>
                         $newpms
                     </strong>
                 <br />
                 &nbsp;
                 <big>
                     <strong>
                         &middot;
                     </strong>
                 </big> 
                     "._BREAD.": 
                     <strong>
                         $oldpms
                     </strong>
                 <br />
                 &nbsp;
                 <big>
                     <strong>
                         &middot;
                     </strong>
                 </big> 
                     "._BSAVED.": 
                     <strong>
                         $savpms
                     </strong>
                 <br />
                 &nbsp;
                 <big>
                     <strong>
                         &middot;
                     </strong>
                 </big> 
                     "._BTT.": 
                     <strong>
                         $totpms
                     </strong>
                 <br />
                 <hr noshade size=\"1\">
                ";
}
} else {
    mt_srand ((double)microtime()*1000000);
    $maxran = 10 * intval($ya_config['codesize']);
    $random_num = mt_rand(0, $maxran);
    $content .= "
                 <div align=\"center\">
                     "._BWEL.", 
                         <strong>
                             $anonymous
                         </strong>
                 </div>
                 <br />
                 <form action=\"modules.php?name=Your_Account\" method=\"post\">
                 <table align=\"center\" border=\"0\">
                     <tr>
                         <td align=\"center\">
                             "._NICKNAME.": 
                         </td>
                     </tr>
                     <tr>
                         <td align=\"center\">
                             <input type=\"text\" name=\"username\" size=\"10\" maxlength=\"25\" />
                         </td>
                     </tr>
                     <tr>
                         <td align=\"center\">
                             "._PASSWORD.": 
                         </td>
                     </tr>
                     <tr>
                         <td align=\"center\">
                             <input type=\"password\" name=\"user_password\" size=\"10\" maxlength=\"20\" />
                         </td>
                     </tr>
                    ";
    $gfxchk = array(2,4,5,7);
    $content .= security_code($gfxchk, 'block');
    if(!empty($redirect)) {
        $content .= "<input type=\"hidden\" name=\"redirect\" value=\"$redirect\" />";
    }
    if(!empty($mode)) {
        $content .= "<input type=\"hidden\" name=\"mode\" value=\"$mode\" />";
    }
    if(!empty($f)) {
        $content .= "<input type=\"hidden\" name=\"f\" value=\"$f\" />";
    }
    if(!empty($t)) {
        $content .= "<input type=\"hidden\" name=\"t\" value=\"$t\" />";
    }
    $content .= "
                     <tr>
                         <td align=\"center\">
                             <input type=\"hidden\" name=\"op\" value=\"login\" />
                             <input type=\"submit\" value=\""._LOGIN."\" />
                         </td>
                     </tr>
                 </table>
                 </form>
                 <div align=\"center\">
                     <font class=\"content\">
                         "._ASREGISTERED."
                     </font>
                 </div>
                ";
}

?>