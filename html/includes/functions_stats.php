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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$now = explode('-', formatTimestamp(time(),'d-m-Y'));
$nowdate = $now[0];
$nowmonth = $now[1];
$nowyear = $now[2];

function Stats_Main() {
    global $prefix, $db, $startdate, $sitename, $ThemeSel, $user_prefix, $module_name, $cache;
    $result  = $db->sql_query('SELECT `type`, `var`, `count` FROM `'.$prefix.'_counter` ORDER BY `count` DESC, var');
    $browser = $os = array();
    $totalos = $totalbr = 0;
    while (list($type, $var, $count) = $db->sql_fetchrow($result)) {
        if ($type == 'browser') {
            $browser[$var] = $count;
        } elseif ($type == 'os') {
            $os[$var] = $count;
            $totalos += $count;
        }
    }
    list($totalbr) = $db->sql_fetchrow($db->sql_query('SELECT SUM(hits) FROM `'.$prefix.'_stats_hour`'));
    $db->sql_freeresult($result);
    if ((($m_size = $cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $cache->save('r_size', 'config', $r_size);
    }
    OpenTable();
    echo '
          <table align="center" class="forumline" cellspacing="1" width="100%">
              <tr>
                  <th height=\"25\" align=\"center\"><span class=\"block-title\"><strong>'.$sitename.' '._STATS.'</strong></span></th>
              </tr>
              <tr>
                  <td class="row1" align="center">
                      <span class="gen"><br />'._WERECEIVED.'&nbsp;<strong>'.$totalbr.'</strong>&nbsp;'._PAGESVIEWS.' '.$startdate.'<br /><br />[ <a href="modules.php?name='.$module_name.'&amp;op=stats">'._VIEWDETAILED.'</a> ]&nbsp;&nbsp;[ <a href="modules.php?name=Forums&amp;file=statistics">'._VIEWFORUMSTATS.'</a> ]</span><br />&nbsp;
                  </td>
              </tr>
          </table>
          <span class="gen"><br /></span>
          <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
              <tr>
                  <td class="catHead" colspan="4" height="28" align="center">
                      <span class="cattitle">'._BROWSERS.'</span>
                  </td>
              </tr>
              <tr>
                  <th colspan="1" align="left" height="25" class="thCornerL" nowrap="nowrap" width="25%">&nbsp;'._BROWSER.'&nbsp;</th>
                  <th colspan="1" align="center" class="thTop" nowrap="nowrap" width="10%">&nbsp;'._COUNT.'&nbsp;</th>
                  <th colspan="1" align="center" class="thTop" nowrap="nowrap" width="10%">&nbsp;'._PERCENT.'&nbsp;</th>
                  <th colspan="1" align="center" class="thCornerR" nowrap="nowrap">&nbsp;'._GRAPH.'&nbsp;</th>
              </tr>
         ';
    // Browsers
    $totalbr = 100 / $totalbr;
    if (is_array($browser)) {
        foreach ($browser AS $var => $count) {
            $perc = @round(($totalbr * $count), 2);
            echo '
                  <tr align="left">
                      <td class="row1">
                          <div class="gen"><img src="modules/'.$module_name.'/images/'.strtolower($var).'.png" alt="" />&nbsp;
                 ';
            if  ($var == 'IE') { echo 'Internet Explorer'; }
            elseif ($var == 'operamobile') { echo 'Opera Mobile'; }
            elseif ($var == 'mobilesafari') { echo 'Mobile Safari'; }
            else { echo ''.$var.'';}
            echo '
                          </div>
                      </td>
                      <td class="row2" align="center">
                          <div class="gen">'.$count.'</div>
                      </td>
                      <td class="row3" align="center">
                          <div class="gen">'.$perc.' %</div>
                      </td>
                      <td class="row2"><img src="themes/'.$ThemeSel.'/images/leftbar.gif" alt="" height="'.$l_size[1].'" /><img src="themes/'.$ThemeSel.'/images/mainbar.gif" alt="" height="'.$m_size[1].'" width="'.$perc.'" /><img src="themes/'.$ThemeSel.'/images/rightbar.gif" alt="" height="'.$r_size[1].'" /></td>
                  </tr>
                 ';
        }
        echo '</table>';
    }
    // Operating System
    $totalos = 100 / $totalos;
    echo '
          <span class="gen"><br /></span>
          <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
              <tr>
                  <td class="catHead" colspan="4" height="28" align="center">
                      <span class="cattitle">'._OPERATINGSYS.'</span>
                  </td>
              </tr>
              <tr>
                  <th colspan="1" align="left" height="25" class="thCornerL" nowrap="nowrap" width="25%">&nbsp;'._OS.'&nbsp;</th>
                  <th colspan="1" align="center" class="thTop" nowrap="nowrap" width="10%">&nbsp;'._COUNT.'&nbsp;</th>
                  <th colspan="1" align="center" class="thTop" nowrap="nowrap" width="10%">&nbsp;'._PERCENT.'&nbsp;</th>
                  <th colspan="1" align="center" class="thCornerR" nowrap="nowrap">&nbsp;'._GRAPH.'&nbsp;</th>
              </tr>
         ';
    if (is_array($os)) {
        foreach ($os AS $var => $count) {
            $perc = @round(($totalos * $count), 2);
            echo '
                  <tr align="left">
                      <td class="row1">
                          <div class="gen"><img src="modules/'.$module_name.'/images/'.strtolower($var).'.png" alt="" />&nbsp;
                 ';
            if ($var == 'windows8') { echo 'Windows 8'; }
            elseif ($var == 'windows7') { echo 'Windows 7'; }
            elseif ($var == 'windowsxp') { echo 'Windows XP'; }
            elseif ($var == 'windowsvista') { echo 'Windows Vista'; }
            elseif ($var == 'windowsmobile') { echo 'Windows Mobile'; }
            elseif ($var == 'macosx') { echo 'Mac OS X'; }
            else { echo ''.$var.'';}
            echo '
                          </div>
                      </td>
                      <td class="row2" align="center">
                          <div class="gen">'.$count.'</div>
                      </td>
                      <td class="row3" align="center">
                          <div class="gen">'.$perc.' %</div>
                      </td>
                      <td class="row2"><img src="themes/'.$ThemeSel.'/images/leftbar.gif" alt="" height="'.$l_size[1].'" /><img src="themes/'.$ThemeSel.'/images/mainbar.gif" alt="" height="'.$m_size[1].'" width="'.$perc.'" /><img src="themes/'.$ThemeSel.'/images/rightbar.gif" alt="" height="'.$r_size[1].'" /></td>
                  </tr>
                 ';
        }
        echo '</table>';
    }
    // Miscellaneous Stats
    list($unum) = $db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$user_prefix.'_users` WHERE `user_id` > 1');
    list($snum) = $db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_stories`');
    list($cnum) = $db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_comments`');
    list($subnum) = $db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_queue`');
    $cever = ucfirst(CE_EDITION);
    echo '
          <span class="gen"><br /></span>
          <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
              <tr>
                  <td class="catHead" colspan="2" height="28" align="center">
                      <span class="cattitle">'._MISCSTATS.'</span>
                  </td>
              </tr>
              <tr>
                  <th colspan="1" align="left" height="25" class="thCornerL" nowrap="nowrap" width="85%">&nbsp;'._MISC.'&nbsp;</th>
                  <th colspan="1" align="center" class="thCornerR" nowrap="nowrap" width="15%">&nbsp;'._COUNT.'&nbsp;</th>
              </tr>
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/users.gif" alt="" />&nbsp;'._REGUSERS.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.$unum.'</span>
                  </td>
              </tr>
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/news.gif" alt="" />&nbsp;'._STORIESPUBLISHED.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.$snum.'</span>
                  </td>
              </tr>
         ';
    if (is_active('Topics')) {
        list($tnum) = $db->sql_ufetchrow("SELECT COUNT(*) FROM `".$prefix."_topics`");
        echo '
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/topics.gif" alt="" />&nbsp;'._SACTIVETOPICS.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.$tnum.'</span>
                  </td>
              </tr>
             ';
    }
    echo '
          <tr align="left">
              <td class="row1">
                  <span class="gen"><img src="modules/'.$module_name.'/images/comments.gif" alt="" />&nbsp;'._COMMENTSPOSTED.'</span>
              </td>
              <td class="row2" align="center">
                  <span class="gen">'.$cnum.'</span>
              </td>
          </tr>
         ';
    if (is_active('Web_Links')) {
        list($links) = $db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_links_links`');
        list($cat) = $db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_links_categories`');
        echo '
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/topics.gif" alt="" />&nbsp;'._LINKSINLINKS.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.$links.'</span>
                  </td>
              </tr>
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/news.gif" alt="" />&nbsp;'._LINKSCAT.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.$cat.'</span>
                  </td>
              </tr>
             ';
    }
    echo '
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/waiting.gif" alt="" />&nbsp;'._NEWSWAITING.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.$subnum.'</span>
                  </td>
              </tr>
              <tr align="left">
                  <td class="row1">
                      <span class="gen"><img src="modules/'.$module_name.'/images/sections.gif" alt="" />&nbsp;'._CEVER.'</span>
                  </td>
                  <td class="row2" align="center">
                      <span class="gen">'.ucfirst($cever).'</span>
                  </td>
              </tr>
          </table>
         ';
    CloseTable();
}

function Stats() {
    global $nowyear, $nowmonth, $nowdate, $sitename, $startdate, $prefix, $db, $now, $module_name;

    list($total) = $db->sql_ufetchrow('SELECT SUM(hits) FROM `'.$prefix."_stats_hour`");
    OpenTable();
    echo '
          <table class="forumline" cellspacing="1" width="100%">
              <tr>
                  <th height=\"25\" align=\"center\"><span class=\"block-title\"><strong>'.$sitename.' '._STATS.'</strong></span></th>
              </tr>
              <tr>
                  <td class="row1" align="center">
                      <span class="gen"><br />'._WERECEIVED.' <strong>'.$total.'</strong> '._PAGESVIEWS.' '.$startdate.'<br /><br />'._TODAYIS.": $now[0]/$now[1]/$now[2]<br />
         ";

    list($year, $month, $hits) = $db->sql_ufetchrow("SELECT `year`, `month`, SUM(hits) as hits FROM `".$prefix."_stats_hour` GROUP BY `month`, `year` ORDER BY `hits` DESC LIMIT 0,1");
    echo _MOSTMONTH.": ".getmonth($month)." $year ($hits "._HITS.")<br />";

    list($year, $month, $date, $hits) = $db->sql_ufetchrow("SELECT `year`, `month`, `date`, SUM(hits) as hits FROM `".$prefix."_stats_hour` GROUP BY `date`, `month`, `year` ORDER BY `hits` DESC LIMIT 0,1");
    echo _MOSTDAY.": $date ".getmonth($month)." $year ($hits "._HITS.")<br />";

    list($year, $month, $date, $hour, $hits) = $db->sql_ufetchrow("SELECT `year`, `month`, `date`, `hour`, `hits` from `".$prefix."_stats_hour` ORDER BY `hits` DESC LIMIT 0,1");
    if ($hour < 10) {
        $hour = "0$hour:00 - 0$hour:59";
    } else {
        $hour = "$hour:00 - $hour:59";
    }
    echo _MOSTHOUR.": $hour "._ON." ".getmonth($month)." $date, $year ($hits "._HITS.")<br /><br />[ <a href=\"modules.php?name=".$module_name."\">"._RETURNBASICSTATS.'</a> ]</span><br />&nbsp;</td></tr></table><br />';
    showYearStats($nowyear);
    echo '<br />';
    showMonthStats($nowyear,$nowmonth);
    echo '<br />';
    showDailyStats($nowyear,$nowmonth,$nowdate);
    echo '<br />';
    showHourlyStats($nowyear,$nowmonth,$nowdate);
    CloseTable();
}

function YearlyStats($year) {
    global $nowmonth, $sitename, $module_name;
    OpenTable();
    showMonthStats($year,$nowmonth);
    echo '<br />';
    echo "<center>[ <a href=\"modules.php?name=".$module_name."\">"._BACKTOMAIN."</a> | <a href=\"modules.php?name=$module_name&amp;op=stats\">"._BACKTODETSTATS."</a> ]</center>";
    CloseTable();
}

function MonthlyStats($year, $month) {
    global $sitename, $nowdate, $module_name;
    OpenTable();
    showDailyStats($year,$month,$nowdate);
    echo '<br />';
    echo "<center>[ <a href=\"modules.php?name=".$module_name."\">"._BACKTOMAIN."</a> | <a href=\"modules.php?name=".$module_name."&amp;op=stats\">"._BACKTODETSTATS."</a> ]</center>";
    CloseTable();
}

function DailyStats($year, $month, $date) {
    global $sitename, $module_name;
    OpenTable();
    showHourlyStats($year,$month,$date);
    echo '<br />';
    echo "<center>[ <a href=\"modules.php?name=".$module_name."\">"._BACKTOMAIN."</a> | <a href=\"modules.php?name=".$module_name."&amp;op=stats\">"._BACKTODETSTATS."</a> ]</center>";
    CloseTable();
}

function showYearStats($nowyear) {
    global $prefix, $db, $ThemeSel, $module_name, $cache;
    if ((($m_size = $cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $cache->save('r_size', 'config', $r_size);
    }
    list($TotalHitsYear) = $db->sql_ufetchrow("SELECT SUM(hits) AS TotalHitsYear FROM `".$prefix."_stats_hour`");
    $result = $db->sql_query("SELECT `year`, SUM(hits) FROM `".$prefix."_stats_hour` GROUP BY `year` ORDER BY year");
    echo '
          <table class="forumline" cellspacing="1" width="100%">
              <tr>
                  <td colspan="3" class="cat">
                      <div class="cattitle" align="center">'._YEARLYSTATS.'</div>
                  </td>
              </tr>
              <tr>
                  <td width="25%" class="row2">
                      <span class="gen"><strong>'._YEAR.'</strong></span>
                  </td>
                  <td colspan="2" class="row2">
                      <span class="gen"><strong>'._SPAGESVIEWS.'</strong></span>
                  </td>
              </tr>
         ';
    while (list($year,$hits) = $db->sql_fetchrow($result)){
        echo '
              <tr>
                  <td class="row1">
                      <span class="gen">
             ';
        if ($year != $nowyear) {
            echo '<a href="modules.php?name='.$module_name.'&amp;op=yearly&amp;year='.$year.'">'.$year.'</a>';
        } else {
            echo $year;
        }
        echo '</span></td><td class="row1" nowrap="nowrap">';
        $WidthIMG = @round(100 * $hits/$TotalHitsYear,0);
        echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"".$l_size[1]."\" />";
        echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
        echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" />";
        echo "</td><td class=\"row1\"><span class=\"gen\">$hits</span></td></tr>";
    }
    $db->sql_freeresult($result);
    echo '</table>';
}

function showMonthStats($nowyear, $nowmonth) {
    global $prefix, $db, $ThemeSel, $module_name, $cache;
    if ((($m_size = $cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $cache->save('r_size', 'config', $r_size);
    }
    list($TotalHitsMonth) = $db->sql_ufetchrow("SELECT sum(hits) AS TotalHitsMonth FROM `".$prefix."_stats_hour` WHERE `year`='$nowyear'");
    echo '
          <table class="forumline" cellspacing="1" width="100%">
              <tr>
                  <td colspan="3" class="cat">
                      <div class="cattitle" align="center">'._MONTLYSTATS.' '.$nowyear.'</div>
                  </td>
              </tr>
              <tr>
                  <td width="25%" class="row2">
                      <span class="gen"><strong>'._UMONTH.'</strong></span>
                  </td>
                  <td class="row2" colspan="2">
                      <span class="gen"><strong>'._SPAGESVIEWS.'</strong></span>
                  </td>
              </tr>
         ';
    $result = $db->sql_query("SELECT month, SUM(hits) FROM ".$prefix."_stats_hour WHERE year='$nowyear' GROUP BY month ORDER BY month");
    while (list($month,$hits) = $db->sql_fetchrow($result)){
        echo '
              <tr>
                  <td class="row1">
                      <span class="gen">
             ';
        if ($month != $nowmonth) {
            echo "<a href=\"modules.php?name=".$module_name."&amp;op=monthly&amp;year=$nowyear&amp;month=$month\">".getmonth($month)."</a>";
        } else {
            echo getmonth($month);
        }
        echo '</span></td><td class="row1" nowrap="nowrap">';
        $WidthIMG = @round(100 * $hits/$TotalHitsMonth,0);
        echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"$l_size[1]\" />";
        echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
        echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" />";
        echo "</td><td class=\"row1\"><span class=\"gen\">$hits</span></td></tr>";
    }
    $db->sql_freeresult($result);
    echo '</table>';
}

function showDailyStats($year, $month, $nowdate) {
    global $prefix, $db, $ThemeSel, $module_name, $cache;
    if ((($m_size = $cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $cache->save('r_size', 'config', $r_size);
    }

    $result = $db->sql_query("SELECT `date`, SUM(hits) as `hits` FROM `".$prefix."_stats_hour` WHERE `year`='$year' AND `month`='$month' GROUP BY `date` ORDER BY `date`");
    $TotalHitsDate = $date = 0;
    $days = array();
    while ($row = $db->sql_fetchrow($result)) {
        $TotalHitsDate = $TotalHitsDate + $row['hits'];
        $date++;
        while ($date < $row['date']) {
            $days[] = array('date'=>$date, 'hits'=>0);
            $date++;
        }
        $days[] = $row;
    }
    $db->sql_freeresult($result);
    echo '
          <table class="forumline" cellspacing="1" width="100%">
              <tr>
                  <td colspan="3" class="cat">
                      <div class="cattitle" align="center">'._DAILYSTATS.' '.getmonth($month).', '.$year.'</div>
                  </td>
              </tr>
              <tr>
                  <td width="25%" class="row2">
                      <span class="gen"><strong>'._DATE.'</strong></span>
                  </td>
                  <td class="row2" colspan="2">
                      <span class="gen"><strong>'._SPAGESVIEWS.'</strong></span>
                  </td>
              </tr>
         ';
    foreach ($days as $day) {
        $date = $day['date'];
        $hits = $day['hits'];
        echo '<tr><td class="row1"><span class="gen">';
        if ($date != $nowdate && $hits > 0 ) {
            echo '<a href="modules.php?name='.$module_name.'&amp;op=daily&amp;year='.$year.'&amp;month='.$month.'&amp;date='.$date.'">'.$date.'</a>';
        } else {
            echo $date;
        }
        echo '</span></td><td class="row1" nowrap="nowrap">';
        if ($hits == 0) {
            $WidthIMG = 0;
            $d_percent = 0;
        } else {
            $WidthIMG = @round(100 * $hits/$TotalHitsDate,0);
            $d_percent = @substr(100 * $hits / $TotalHitsDate, 0, 5);
        }
        echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"".$l_size[1]."\" />";
        echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
        echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" />";
        echo "</td><td class=\"row1\"><span class=\"gen\">$hits ($d_percent%)</span></td></tr>";
    }
    echo '</table>';
}

function showHourlyStats($year, $month, $date) {
    global $prefix, $db, $ThemeSel, $module_name, $cache;
    if ((($m_size = $cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $cache->save('r_size', 'config', $r_size);
    }
    list($TotalHitsHour) = $db->sql_ufetchrow('SELECT SUM(hits) AS TotalHitsHour FROM `'.$prefix."_stats_hour` WHERE `year`='$year' AND `month`='$month' AND `date`='$date'");
    $nowdate = date('d-m-Y');
    $nowdate_arr = explode('-', $nowdate);
    echo '
          <table class="forumline" cellspacing="1" width="100%">
              <tr>
                  <td colspan="3" class="cat">
                      <div class="cattitle" align="center">'._HOURLYSTATS.' '.getmonth($month).' '.$date.', '.$year.'</div>
                  </td>
              </tr>
              <tr>
                  <td width="25%" class="row2">
                      <span class="gen"><strong>'._HOUR.'</strong></span>
                  </td>
                  <td class="row2" colspan="2">
                      <span class="gen"><strong>'._SPAGESVIEWS.'</strong></span>
                  </td>
              </tr>
         ';
    for ($k = 0;$k<=23;$k++) {
    $result = $db->sql_query("SELECT hour, hits FROM ".$prefix."_stats_hour WHERE year='$year' AND month='$month' AND date='$date' AND hour='$k'");
    if ($db->sql_numrows($result) == 0){
        $hits=0;
    } else {
        list($hour,$hits) = $db->sql_fetchrow($result);
    }
    $db->sql_freeresult($result);
    $a = ($k < 10) ? "0$k" : $k;
    echo '<tr><td class="row1"><span class="gen">';
    echo "$a:00 - $a:59";
    $a = '';
    echo '</span></td><td class="row1" nowrap="nowrap">';
    if ($hits == 0) {
        $WidthIMG = $d_percent = 0;
    } else {
        $WidthIMG = @round(100 * $hits/$TotalHitsHour,0);
        $d_percent = @substr(100 * $hits / $TotalHitsHour, 0, 5);
    }
    echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"".$l_size[1]."\" />";
    echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
    echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" />";
    echo "</td><td class=\"row1\"><span class=\"gen\">$hits ($d_percent%)</span></td></tr>";
    }
    echo '</table>';
}

function getmonth($month) {
    $month = intval($month);
    $months = array(1=>_JANUARY, _FEBRUARY, _MARCH, _APRIL, _MAY, _JUNE, _JULY, _AUGUST, _SEPTEMBER, _OCTOBER, _NOVEMBER, _DECEMBER);
    return (array_key_exists($month, $months) ? $months[$month] : '');
}

?>