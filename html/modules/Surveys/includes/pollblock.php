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

if (!defined('NUKE_CE')) {
    die('You can\'t access this file directly...');
}

global $prefix, $db, $content, $pollcomm, $user, $userinfo, $cookie, $multilingual, $currentlang, $sysconfig, $cache, $client;

// Fetch random poll
$make_random = intval($sysconfig['poll_random']);

// Fetch number of days in between voting per user
$number_of_days = intval($sysconfig['poll_days']);

$querylang = ($multilingual == 1) ? "WHERE (planguage='$currentlang' OR planguage='') AND artid='0'" : "WHERE artid='0'";
$queryorder = ($make_random) ? 'RAND()' : 'pollID DESC';

$pollID = (isset($_REQUEST['pollID'])) ? (int)$_REQUEST['pollID'] : '';

if(isset($pollID) && is_numeric($pollID)) {
    $result = $db->sql_query("SELECT pollID, pollTitle, voters FROM ".$prefix."_poll_desc WHERE `pollID`=".intval($pollID));
} else {
    $result = $db->sql_query("SELECT pollID, pollTitle, voters FROM ".$prefix."_poll_desc $querylang ORDER BY $queryorder LIMIT 1");
}

if ($db->sql_numrows($result) < 1) {
    $content = "
                <div align=\"center\">
                    "._NOSURVEYS."
                </div>
               ";
} else {
    list($pollID, $pollTitle, $voters) = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    $pollTitle = stripslashes($pollTitle);
    $url = "modules.php?name=Surveys&amp;op=results&amp;pollID=$pollID";
    $sum = "";
    $button = "";
    $content = '
                <span class="content">
                    <strong>
                        '.$pollTitle.'
                    </strong>
                </span>
                <br />
                <br />
                <form action="modules.php?name=Surveys" method="post">
                    <table border="0" cellpadding="2" cellspacing="0" width="100%">
               ';
    $client = new Client();
    $ip = $client->getIp();
    $past = time()-86400*$number_of_days;
    $result = $db->sql_query("SELECT ip FROM ".$prefix."_poll_check WHERE ip='$ip' AND pollID='$pollID'");
    $result2 = $db->sql_query("SELECT optionText, voteID, optionCount FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND optionText!='' ORDER BY voteID");
    if ($db->sql_numrows($result) > 0) {
        while ($row = $db->sql_fetchrow($result2)) {
            $options[] = $row;
            $sum += (int)$row['optionCount'];
        }
        $ThemeSel = get_theme();
        $leftbar = file_exists("themes/$ThemeSel/images/survey_leftbar.gif") ? 'survey_leftbar.gif' : 'leftbar.gif';
        $mainbar = file_exists("themes/$ThemeSel/images/survey_mainbar.gif") ? 'survey_mainbar.gif' : 'mainbar.gif';
        $rightbar = file_exists("themes/$ThemeSel/images/survey_rightbar.gif") ? 'survey_rightbar.gif' : 'rightbar.gif';
        $l_size = @getimagesize("themes/$ThemeSel/images/$leftbar");
        $m_size = @getimagesize("themes/$ThemeSel/images/$mainbar");
        $r_size = @getimagesize("themes/$ThemeSel/images/$rightbar");
        if (file_exists("themes/$ThemeSel/images/survey_mainbar_d.gif")) $mainbar_d = 'survey_mainbar_d.gif';
        if (isset($mainbar_d)) $m1_size = @getimagesize("themes/$ThemeSel/images/$mainbar_d");

        foreach ($options as $option) {
            $percent = @(100 / $sum * $option['optionCount']);
            $percentInt = (int)$percent * .85;
            $percent2 = (int)$percent;
            $content .= "
                         <tr>
                             <td>
                                 $option[optionText]
                                 <br />
                                 <img src=\"themes/$ThemeSel/images/$leftbar\" height=\"$l_size[1]\" width=\"$l_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
            if ($percent > 0) {
                    $content .= "<img src=\"themes/$ThemeSel/images/$mainbar\" height=\"$m_size[1]\" width=\"$percentInt%\" alt=\"$percent2 %\" title=\"$percent2 %\">";
            } else {
                if (!isset($mainbar_d)) {
                    $content .= "<img src=\"themes/$ThemeSel/images/$mainbar\" height=\"$m_size[1]\" width=\"$m_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
                }
            }
            $content .= "<img src=\"themes/$ThemeSel/images/$rightbar\" height=\"$r_size[1]\" width=\"$r_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">
                                 <br />
                             </td>
                         </tr>
                        ";
        }
        $button = '';
    }
    else {
        while ($row = $db->sql_fetchrow($result2)) {
            $content .= "
                         <tr>
                             <td valign=\"top\">
                                 <input type=\"radio\" name=\"voteID\" value=\"".$row['voteID']."\">
                             </td>
                             <td width=\"100%\">
                                 <span class=\"content\">
                                     ".$row['optionText']."
                                 </span>
                             </td>
                         </tr>
                        ";
            $sum += (int)$row['optionCount'];
        }
        $button .= '<input type="hidden" name="pollID" value="'.$pollID.'">';
        $button .= '<input type="hidden" name="forwarder" value="'.$url.'">';
        $button .= '<input type="submit" value="'._VOTE.'"><br /><br />';
    }
    $db->sql_freeresult($result2);

    $content .= "
                    </table>
                    <br />
                    <div align=\"center\">
                        $button
                        <span class=\"content\">
                            <a href=\"modules.php?name=Surveys&amp;op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\">
                                <strong>
                                    "._RESULTS."
                                </strong>
                            </a>
                            <br />
                            <a href=\"modules.php?name=Surveys\">
                                <strong>
                                    "._POLLS."
                                </strong>
                            </a>
                            <br />
                            <br />
                            "._VOTES." 
                            <strong>
                                $sum
                            </strong>
                ";
    if ($pollcomm) {
        $result = $db->sql_query("SELECT COUNT(*) FROM ".$prefix."_pollcomments WHERE pollID='$pollID'");
        list($numcom) = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        $content .= "
                            <br /> 
                            "._PCOMMENTS." 
                            <strong>
                                $numcom
                            </strong>
                    ";
    }
    $content .= "
                        </span>
                    </div>
                 </form>
                ";
}

?>