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

/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

if(!defined('IN_DOWNLOADS')) {
    exit('Access Denied');
}

if ($cid == 0) {
    $pagetitle = _MAIN;
} else {
    $titleinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid=$cid AND active>'0'"));
    $title = getparentlink($titleinfo['parentid'], $titleinfo['title']);
    $pagetitle = _MAIN." &raquo; $title";
}
include_once(NUKE_BASE_DIR.'header.php');

if (!isset($min)) $min=0;
if (!isset($max)) $max=$min+$dl_config['perpage'];
if(isset($orderby)) { $orderby = convertorderbyin($orderby); } else { $orderby = "title ASC"; }

if (!isset($cid)) { $cid = 0; }
$cid = intval($cid);

if ($cid == 0) {
    menu(0);
    $title = _MAIN;
} else {
    menu(1);
    $cidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid=$cid AND active>'0'"));
    $title = getparentlink($cidinfo['parentid'], $cidinfo['title']);
    $title = "<a href=modules.php?name=$module_name>"._MAIN."</a>/$title";
}

OpenTable();
echo "<center><font class=\"option\"><b>"._CATEGORY.": $title</b></font></center><br>";
$result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE parentid=$cid ORDER BY title");
$numrows2 = $db->sql_numrows($result2);
if ($numrows2 > 0) {
    echo "<table border=\"0\" cellspacing=\"10\" cellpadding=\"0\" align=\"center\"><tr>";
    $count = 0;
    while($cidinfo2 = $db->sql_fetchrow($result2)) {
        if ($count == 0) { echo "<tr>\n"; }
        if ($dl_config['show_links_num'] == 1) {
            $cnumrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE cid='".$cidinfo2['cid']."' AND active>'0'"));
            $categoryinfo = getcategoryinfo($cidinfo2['cid']);
            $cnumm = " (".$cnumrows."/".$categoryinfo['downloads'].")";
        } else {
            $cnumm = "";
        }
        echo "<td valign=\"top\" style=\"word-wrap: break-word;\" width=\"50%\"><span class=\"option\"><strong><big>&middot;</big></strong> <a href='modules.php?name=$module_name&amp;cid=".$cidinfo2['cid']."'><strong>".$cidinfo2['title']."</strong></a></span> $cnumm";
        newcategorygraphic($cidinfo2['cid']);
        if ($cidinfo2['cdescription']) {
            echo "<br /><font class=\"content\">".$cidinfo2['cdescription']."</font><br>";
        } else {
            echo "<br />";
        }
        $space = 0;
        $result3 = $db->sql_query("SELECT cid, title FROM ".$prefix."_downloads_categories WHERE parentid='".$cidinfo2['cid']."' AND active>'0' ORDER BY title");
        while($cidinfo3 = $db->sql_fetchrow($result3)) {
            if ($dl_config['show_links_num'] == 1) {
                $snumrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE cid='".$cidinfo3['cid']."' AND active>'0'"));
                $categoryinfosub = getcategoryinfo($cidinfo3['cid']);
                $cnum = " (".$snumrows."/".$categoryinfosub['downloads'].")";
            } else {
                $cnum = "";
            }
            echo "&nbsp;&nbsp;<span style=\"color: rgb(153, 153, 153);\" class=\"content\"><strong><big>&middot;</big></strong> <a href='modules.php?name=$module_name&amp;cid=".$cidinfo3['cid']."'><strong>".$cidinfo3['title']."</strong></a></span> $cnum";
            newcategorygraphic($cidinfo3['cid']);
            echo "<br />";
            $space++;
        }
        if ($count < 1) { echo "</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>"; $dum = 1; }
        $count++;
        if ($count == 2) { echo "</td></tr><tr>"; $count = 0; $dum = 0; }
    }
    if ($dum == 1 && $count == 2) {
        echo "</tr></table>";
    } elseif ($dum == 1 && $count == 1) {
        echo "<td>&nbsp;</td>\n<td>&nbsp;</td>\n</tr>\n</table>\n";
    } elseif ($dum == 0) {
        echo "<td>&nbsp;</td></tr></table>";
    }
}
echo "<div style=\"height: 12px; line-height: 12px;\">&nbsp;</div>";
$listrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE active>'0' AND cid='$cid'"));
if ($listrows > 0) {
    $op = $query = "";
    $orderbyTrans = convertorderbytrans($orderby);
    echo "
          <table border='0' cellpadding='0' cellspacing='4' width='100%'>
              <tr>
                  <td align='center' colspan='2'>
                      <span class='content'>"._SORTDOWNLOADSBY.": 
                          "._TITLE." (<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=titleA'>A</a>\<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=titleD'>D</a>) 
                          "._DATE." (<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=dateA'>A</a>\<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=dateD'>D</a>) 
                          "._POPULARITY." (<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=hitsA'>A</a>\<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=hitsD'>D</a>)
                          <br><b>"._RESSORTED.": $orderbyTrans</b>
                      </span>
                  </td>
              </tr>
          </table>
         ";
    $totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE active>'0' AND cid=$cid"));
    echo "<table border='0' cellpadding='0' cellspacing='4' width='100%'>";
    // START LISTING
    $x = 0;
    $a = 0;
    $result=$db->sql_query("SELECT lid FROM ".$prefix."_downloads_downloads WHERE active>'0' AND cid=$cid ORDER BY $orderby LIMIT $min,".$dl_config['perpage']);
    while(list($lid)=$db->sql_fetchrow($result)) {
        if ($a == 0) { echo "<tr>"; }
        echo "<td valign='top' width='50%'><span class='content'>";
        showlisting($lid);
        echo "</span></td>";
        $a++;
        if ($a == 2) { echo "</tr>"; $a = 0; }
        $x++;
    }
    if ($a == 1) { echo "<td width=\"50%\">&nbsp;</td></tr>"; } else { echo "</tr>"; }
    echo "</td></tr>";
    // END LISTING
    $orderby = convertorderbyout($orderby);
    dlPagenums($cid, $query, $orderby, $op, $totalselected, $dl_config['perpage'], $max);
}
$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads"));
$catnum = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories"));
echo "<br /><center><font class=\"content\">"._THEREARE." <b>$numrows</b> "._DOWNLOADS." "._AND." <b>$catnum</b> "._CATEGORIES." "._INDB."</font></center>";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>