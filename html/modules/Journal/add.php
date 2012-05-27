<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

define('NUKE_BASE_MODULES', preg_replace('/modules/i', '', dirname(dirname(__FILE__))));

    require_once(NUKE_BASE_MODULES.'mainfile.php');
    $module_name = basename(dirname(__FILE__));
    get_lang($module_name);
    $pagetitle = '- '._USERSJOURNAL;
    include_once(NUKE_BASE_DIR.'header.php');
    include(NUKE_MODULES_DIR.$module_name.'/functions.php');
    if (is_user()) {
        $cookie = cookiedecode();
        $username = $cookie[1];
    }
    $user = check_html($user, "nohtml");
    $username = check_html($username, "nohtml");
    $sitename = check_html($sitename, "nohtml");
    if (isset($jbodytext)) { $jbodytext = check_html(ADVT_stripslashes($jbodytext), $AllowableHTML); }
    else { $jbodytext = ''; }
    $debug = check_html($debug, "nohtml");
    if ($debug == "true") {
        echo ("UserName:$username<br />SiteName: $sitename");
    }
    startjournal($sitename, $user);

    if (!is_user()) {
        echo ("<br />");
        OpenTable();
        echo ("<div align=center>"._YOUMUSTBEMEMBER."<br /></div>");
        CloseTable();
        journalfoot();
        exit;
    }

if (is_user()) {
    echo "<br />";
    OpenTable();
    echo ("<div align=center class=title>"._ADDJOURNAL."</div><br />");
    echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&amp;file=add\">"._ADDENTRY."</a> | <a href=\"modules.php?name=$module_name&amp;file=edit&amp;op=last\">"._YOURLAST20."</a> | <a href=\"modules.php?name=$module_name&amp;file=edit&amp;op=all\">"._LISTALLENTRIES."</a> ]</div>");
    CloseTable();
    echo "<br />";
    OpenTable();
    print  ("<form name='journal' action='modules.php?name=$module_name&amp;file=savenew' method='post'>");
    print  ("<table align='center' border='0' width='100%'>");
    print  ("<tr>");
    print  ("<td align=right valign=top><strong>"._TITLE.": </strong></td>");
    print  ("<td valign=top><input type=\"text\" value=\"\" size=50 maxlength=80 name=\"title\" /></td>");
    print  ("</tr>");
    print  ("<tr>");
    print  ("<td align=right valign=top><strong>"._BODY.": </strong></td>");
    echo "<td valign=top>";
    Make_TextArea('jbodytext', '', 'journal');
    echo "<br />"._WRAP."</td>";
    print  ("</tr>");
    print  ("<tr>");
    print  ("<td align=right valign=top><strong>"._LITTLEGRAPH.": </strong><br />"._OPTIONAL."</td>");
    echo "<td valign=top><table cellpadding=3><tr>";
    $tempcount = 0;
    $tempcount = intval($tempcount);
    $direktori = $jsmiles;
    $handle = opendir($direktori);
    while ($file = readdir($handle)) {
        if (is_file($file) && strtolower(substr($file, -4)) == '.gif' || '.jpg' || '.png') {
            $filelist[] = $file;
        } else {
            OpenTable();
            echo "<center><strong>"._ANERROR."</strong></center>";
            CloseTable();
            exit;
        }
    }
    asort($filelist);
    while (list ($key, $file) = each ($filelist)) {
        if (!ereg(".gif|.jpg|.png",$file)) { }
        elseif ($file == "." || $file == "..") {
        $a=1;
        } else {
            if ($tempcount == 6):
                echo "</tr><tr>";
                echo "<td><input type='radio' name='mood' value='$file' /></td><td><img src=\"$jsmiles/$file\" alt=\"$file\" title=\"$file\" /></td>";
                $tempcount = 0;
            else :
                echo "<td><input type='radio' name='mood' value='$file' /></td><td><img src=\"$jsmiles/$file\" alt=\"$file\" title=\"$file\" /></td>";
            endif;
	$tempcount = $tempcount + 1;
        }
    }
echo "</tr></table>";
print  ("</td>");
print  ("</tr>");
print  ("<tr>");
print  ("<tr>");
print  ("<td align=right valign=top><strong>"._PUBLIC.": </strong></td>");
print  ("<td align=left valign=top>");
print  ("<select name='status'>");
print  ("<option value=\"yes\" SELECTED>"._YES."</option>");
print  ("<option value=\"no\">"._NO."</option>");
print  ("</select>");
print  ("</td>");
print  ("</tr>");
print  ("<td colspan=2 align=center><input type='submit' name='submit' value='"._ADDENTRY."' /><br /><br />"._TYPOS."</td>");
print  ("</tr>");
print  ("</table>");
print  ("</form>");
CloseTable();
echo ("<br />");
journalfoot();
    } else {
        echo ("<br />");
        OpenTable();
        echo ("<div align=center>"._YOUMUSTBEMEMBER."<br /></div>");
        CloseTable();
        journalfoot();
        exit;
    }

?>