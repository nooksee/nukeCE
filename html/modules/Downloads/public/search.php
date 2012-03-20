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

if (!isset($min)) $min=0;
if (!isset($max)) $max=$min+$dl_config['results'];
if(isset($orderby)) { $orderby = convertorderbyin($orderby); } else { $orderby = "title ASC"; }
$query = addslashes($query);
$totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE (title LIKE '%$query%' OR description LIKE '%$query%') AND active>'0'"));
$the_query = stripslashes($query);
$the_query = str_replace("\'", "'", $the_query);
$pagetitle = _SEARCHRESULTS4.": $the_query";
include_once(NUKE_BASE_DIR.'header.php');
menu(1);
if ($query != "") {
    title(_SEARCHRESULTS4.": $the_query");
    OpenTable();
    echo "
          <div align='center'>
              <font class=\"option\"><b>"._USUBCATEGORIES."</b></font>
          </div>
          <br>
         ";
    $crows  = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE title LIKE '%$query%' AND active>'0' ORDER BY title DESC"));
    if ($crows > 0) {
        $result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE title LIKE '%$query%' ORDER BY title DESC");
        while($cidinfo2 = $db->sql_fetchrow($result2)) {
            $numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE cid='".$cidinfo2['cid']."'"));
            $cidinfo3 = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid='".$cidinfo2['cid']."'"));
            if ($cidinfo3['parentid'] > 0) $cidinfo3['title'] = getparent($cidinfo3['parentid'], $cidinfo3['title']);
            $cidinfo3['title'] = ereg_replace($query, "<strong>$query</strong>", $cidinfo3['title']);
            echo "<strong><big>&middot;</big></strong>&nbsp;<a href='modules.php?name=$module_name&amp;cid=".$cidinfo2['cid']."'>".$cidinfo3['title']."</a> ($numrows)<br />";
        }
    } else {
        echo "
              <div align=\"center\">
                  <span class=\"option\"><b><em>"._NOMATCHES."</em></b></span>        
              </div>
              <br />
             ";
    }
    CloseTable();
    echo"<br />\n";
    OpenTable();
    echo "
          <div align='center'>
              <font class=\"option\"><b>"._DOWNLOADS."</b></font>
          </div>
          <br>
         ";
    $nrows  = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE (title LIKE '%$query%' OR description LIKE '%$query%') AND active>'0' ORDER BY $orderby LIMIT $min,".$dl_config['results']));
    if ($nrows>0) {
        echo "<table border='0' cellpadding='0' cellspacing='4' width='100%'>";
        $orderbyTrans = convertorderbytrans($orderby);
        echo "
                  <tr>
                      <td align='center' colspan='2'>
                          <span class='content'>
                              "._SORTDOWNLOADSBY.": 
                              "._TITLE." (<a href='modules.php?name=$module_name&amp;op=search&amp;query=$the_query&amp;orderby=titleA'>A</a>\<a href='modules.php?name=$module_name&amp;op=search&amp;query=$the_query&amp;orderby=titleD'>D</a>) 
                              "._DATE." (<a href='modules.php?name=$module_name&amp;op=search&amp;query=$the_query&amp;orderby=dateA'>A</a>\<a href='modules.php?name=$module_name&amp;op=search&amp;query=$the_query&amp;orderby=dateD'>D</a>) 
                              "._POPULARITY." (<a href='modules.php?name=$module_name&amp;op=search&amp;query=$the_query&amp;orderby=hitsA'>A</a>\<a href='modules.php?name=$module_name&amp;op=search&amp;query=$the_query&amp;orderby=hitsD'>D</a>)<br /><strong>"._RESSORTED.": $orderbyTrans</strong>
                          </span>
                      </td>
                  </tr>
             ";
        $x = 0;
        $a = 0;
        $result = $db->sql_query("SELECT lid FROM ".$prefix."_downloads_downloads WHERE (title LIKE '%$query%' OR description LIKE '%$query%') AND active>'0' ORDER BY $orderby LIMIT $min,".$dl_config['results']);
        echo "<table border='0' cellpadding='0' cellspacing='4' width='100%'>";
        while(list($lid) = $db->sql_fetchrow($result)) {
            if ($a == 0) { echo "<tr>"; }
            echo "<td valign='top' width='50%'><span class='content'>";
            showresulting($lid);
            echo "</span></td>";
            $a++;
            if ($a == 2) { echo "</tr>"; $a = 0; }
            $x++;
        }
        if ($a ==1) { echo "<td width=\"50%\">&nbsp;</td></tr>"; } else { echo "</tr>"; }
        $orderby = convertorderbyout($orderby);
        echo "</td></tr>";
        dlPagenums($cid, $query, $orderby, $op, $totalselected, $dl_config['perpage'], $max);
        echo "
              <br>
              <div align='center'>
                  <font class=\"content\">
                      "._TRY2SEARCH." \"$the_query\" "._INOTHERSENGINES."<br>
                      <a target=\"_blank\" href=\"http://www.bing.com/search?q=$the_query\">Bing</a>&nbsp;-&nbsp;
                      <a target=\"_blank\" href=\"http://search.yahoo.com/bin/search?p=$the_query\">Yahoo</a>&nbsp;-&nbsp;
                      <a target=\"_blank\" href=\"http://www.google.com/search?q=$the_query\">Google</a>
                  </font>
              </div>
             ";
    } else {
        echo "
              <div align=\"center\">
                  <span class=\"option\"><b><em>"._NOMATCHES."</em></b></span>        
              </div>
              <br />
             ";
    }
} else {
    DisplayError(_NOMATCHES, 1);
    exit;
}
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>