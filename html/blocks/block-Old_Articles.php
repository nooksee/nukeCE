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

global $locale, $oldnum, $storynum, $storyhome, $cookie, $categories, $cat, $prefix, $multilingual, $currentlang, $db, $new_topic, $user_news, $userinfo, $user;

if ($multilingual == 1) {
    if ($categories == 1) {
        $querylang = "where catid='$cat' AND (alanguage='$currentlang' OR alanguage='')";
    } else {
        $querylang = "where (alanguage='$currentlang' OR alanguage='')";
    if ($new_topic != 0) {
        $querylang .= " AND topic='$new_topic'";
    }
    }
} else {
    if ($categories == 1) {
       $querylang = "where catid='$cat'";
    } else {
    $querylang = "";
    if ($new_topic != 0) {
        $querylang = "WHERE topic='$new_topic'";
    }
    }
}
if (isset($userinfo['storynum']) AND $user_news == 1) {
    $storynum = $userinfo['storynum'];
} else {
    $storynum = $storyhome;
}
$boxstuff = "
             <table border=\"0\" width=\"100%\">
            ";
$boxTitle = _PASTARTICLES;
$sql = "SELECT sid, title, time, comments FROM ".$prefix."_stories $querylang ORDER BY time DESC LIMIT $storynum, $oldnum";
$result = $db->sql_query($sql);
$vari = 0;

if (!isset($mode) OR empty($mode)) {
    if(isset($userinfo['umode'])) {
      $mode = $userinfo['umode'];
    } else {
      $mode = "thread";
    }
}
if (!isset($order) OR empty($order)) {
    if(isset($userinfo['uorder'])) {
      $order = $userinfo['uorder'];
    } else {
      $order = 0;
    }
}
if (!isset($thold) OR empty($thold)) {
    if(isset($userinfo['thold'])) {
      $thold = $userinfo['thold'];
    } else {
      $thold = 0;
    }
}
$r_options = "";
$r_options .= "&amp;mode=".$mode;
$r_options .= "&amp;order=".$order;
$r_options .= "&amp;thold=".$thold;

while (list($sid, $title, $time, $comments) = $db->sql_fetchrow($result)) {
    $sid = intval($sid);
    $title = stripslashes($title);
    $see = 1;
    setlocale(LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime2);
    $datetime2 = strftime(_DATESTRING2, mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
    $datetime2 = ucfirst($datetime2);
    if ($articlecomm == 1) {
        $comments = "(".$comments.")";
    } else {
        $comments = "";
    }
    if($time2==$datetime2) {
        $boxstuff .= "
                          <tr>
                              <td valign=\"top\">
                                  <strong>
                                      <big>
                                          &middot;
                                      </big>
                                  </strong>
                              </td>
                              <td> 
                                  <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">
                                      $title
                                  </a> 
                                  $comments
                              </td>
                          </tr>
                     ";
    } else {
        if(empty($a)) {
            $boxstuff .= "
                              <tr>
                                  <td colspan=\"2\">
                                      <strong>
                                          $datetime2
                                      </strong>
                                  </td>
                              </tr>
                              <tr>
                                  <td valign=\"top\">
                                      <strong>
                                          <big>
                                              &middot;
                                          </big>
                                      </strong>
                                  </td>
                                  <td> 
                                      <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">
                                          $title
                                      </a> 
                                      $comments
                                  </td>
                              </tr>
                         ";
        $time2 = $datetime2;
        $a = 1;
    } else {
        $boxstuff .= "
                          <tr>
                              <td colspan=\"2\">
                                  <strong>
                                      $datetime2
                                  </strong>
                              </td>
                          </tr>
                          <tr>
                              <td valign=\"top\">
                                  <strong>
                                      <big>
                                          &middot;
                                      </big>
                                  </strong>
                              </td>
                              <td> 
                                  <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">
                                      $title
                                  </a> 
                                  $comments
                              </td>
                          </tr>
                     ";
        $time2 = $datetime2;
    }
    }
    $vari++;
    if ($vari==$oldnum) {
    if (isset($userinfo['storyhome'])) {
        $storynum = $userinfo['storyhome'];
    } else {
        $storynum = $storyhome;
    }
    $min = $oldnum + $storynum;
    $dummy = 1;
    }
}
$db->sql_freeresult($result);

if ($dummy == 0 AND is_active("Stories_Archive")) {
    $boxstuff .= "
                  </table>
                  <br />
                  <div align=\"center\">
                      [ 
                      <a href=\"modules.php?name=Stories_Archive\">
                          "._OLDERARTICLES."
                      </a> 
                      ]
                  </div>
                 ";
} else {
    $boxstuff .= "
                  </table>
                 ";
}

if ($see == 1) {
    $content = $boxstuff;
}

?>