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

$dateDB = (date("d-M-Y", $selectdate));
$dateView = (date("F d, Y", $selectdate));
$newdownloadDB = Date("Y-m-d", $selectdate);

$pagetitle = _NEWDOWNLOADS." for $dateView";
include_once(NUKE_BASE_DIR.'header.php');

$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE date LIKE '%$newdownloadDB%' AND active>'0'");
$totaldownloads = $db->sql_numrows($result);

menu(1);
if ($totaldownloads == 0) {
    DisplayError(_NONEWDOWNLOADS." $dateView", 1);
    exit;
} else {
    OpenTable();
    echo "
          <br>
          <div align='center'>
              <font class=\"option\"><b>$dateView - $totaldownloads "._NEWDOWNLOADS."</b></font>
          </div>
          <br>
          <table width=\"100%\" cellspacing=\"4\" cellpadding=\"0\" border=\"0\">
         ";
    $result = $db->sql_query("SELECT lid FROM ".$prefix."_downloads_downloads WHERE date LIKE '%$newdownloadDB%' AND active>'0' ORDER BY title ASC");
    $a = 0;
    while(list($lid) = $db->sql_fetchrow($result)) {
        if ($a == 0) { echo "<tr>"; }
        echo "<td valign='top' width='50%'><font class=\"content\">";
        showresulting($lid);
        echo "</font></td>";
        $a++;
        if ($a == 2) { echo "</tr>"; $a = 0; }
    }
    if ($a ==1) { echo "<td width=\"50%\">&nbsp;</td></tr>"; } else { echo "</tr>"; }
    echo "</table>\n";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
}

?>