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

global $cat, $language, $prefix, $multilingual, $currentlang, $db;
if ($multilingual == 1) {
        $querylang = "AND (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
} else {
        $querylang = '';
}
$sql = "SELECT catid, title FROM ".$prefix."_stories_cat ORDER BY title";
$result = $db->sql_query($sql);
$numrows = $db->sql_numrows($result);
if ($numrows == 0) {
    return;
} else {
    while (list($catid, $title) = $db->sql_fetchrow($result)) {
        $catid = intval($catid);
        $title = stripslashes($title);
        $numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_stories WHERE catid='$catid' $querylang LIMIT 1"));
        if ($numrows > 0) {
            if ($cat == 0 AND !$a) {
                $boxstuff .= "
                              &nbsp;
                              <strong>
                                  <big>
                                      &middot;
                                  </big>
                              </strong>
                              <a href=\"modules.php?name=News\">
                                  "._ALLCATEGORIES."
                              </a>
                              <br>
                             ";
                $a = 1;
            }
		
            if ($cat == $catid) {
                $boxstuff .= "
                              &nbsp;
                              <strong>
                                  <big>
                                     &middot;
                                  </big>
                                  $title
                              </strong>
                              <br />
                             ";
            } else {
                $boxstuff .= "
                              &nbsp;
                              <strong>
                                  <big>
                                      &middot;
                                   </big>
                              </strong>
                              <a href=\"modules.php?name=News&amp;file=categories&amp;op=newindex&amp;catid=$catid\">
                                  $title
                              </a>
                              <br />
                             ";
            }
        }
    }
    $content = $boxstuff;
}
$db->sql_freeresult($result);

?>