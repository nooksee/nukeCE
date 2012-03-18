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

$lid = intval($lid);
$pagetitle = _REQUESTDOWNLOADMOD;
include_once(NUKE_BASE_DIR.'header.php');
$maindownload = 1;
menu(1);
$result = $db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE lid=$lid AND active>'0'");
$lidinfo = $db->sql_fetchrow($result);
if ($dl_config['blockunregmodify'] == 1 && !is_user()) {
    DisplayError(_DONLYREGUSERSMODIFY, 1);
    return;
} else {
    if (empty($lidinfo['lid'])) {
        DisplayError(_INVALIDDOWNLOAD, 1);
        exit;
    } else {
        OpenTable();
        echo "<center><font class=\"title\"><b>"._REQUESTDOWNLOADMOD."</b></font></center><br>";
        $lidinfo['title'] = stripslashes($lidinfo['title']);
        $lidinfo['description'] = stripslashes($lidinfo['description']);
        echo "
              <br><br>
              <table width=\"100%\" border=\"0\" cellspacing=\"3\">
              <form method='post' action='modules.php?name=$module_name'>
                  <tr>
                    <td width=\"20%\" nowrap><font class=\"content\"><b>"._DOWNLOADNAME.":</b></font></td>
                    <td><input type='text' name='title' value='".$lidinfo['title']."' size='40' maxlength='100'></td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._FILEURL.":</b></font></td>
                      <td><input type='text' name='url' value='' size='40' maxlength='255'><br />("._PATHHIDE.")</td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._DESCRIPTION.":</b></font></td>
                      <td width=\"100%\">
             ";
        Make_TextArea('description', $lidinfo['description'], 'modifydownloadrequestS'  );
        echo "
                      </td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._CATEGORY.":</b></font></td>
                      <td>
                          <select name='cat'>
             ";
        $result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories ORDER BY parentid,title");
        while($cidinfo = $db->sql_fetchrow($result2)) {
            if ($cidinfo['cid'] == $lidinfo['cid']) { $sel = "selected"; } else { $sel = ""; }
            if ($cidinfo['parentid'] != 0) $cidinfo['title'] = getparent($cidinfo['parentid'], $cidinfo['title']);
            echo "<option value='".$cidinfo['cid']."' $sel>".$cidinfo['title']."</option>\n";
        }
        echo "
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._AUTHORNAME.":</b></font></td>
                      <td><input type='text' name='auth_name' value='".$lidinfo['name']."' size='30' maxlength='100'></td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._AUTHOREMAIL.":</b></font></td>
                      <td><input type='text' name='email' value='".$lidinfo['email']."' size='30' maxlength='100'></td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._FILESIZE.":</b></font></td>
                      <td><input type='text' name='filesize' value='".$lidinfo['filesize']."' size='12' maxlength='20'> ("._INBYTES.")</td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._VERSION.":</b></font></td>
                      <td><input type='text' name='version' value='".$lidinfo['version']."' size='11' maxlength='20'></td>
                  </tr>
                  <tr>
                      <td nowrap><font class=\"content\"><b>"._HOMEPAGE.":</b></font></td>
                      <td><input type='text' name='homepage' value='".$lidinfo['homepage']."' size='40' maxlength='255'></td>
                  </tr>
                  <tr>
                      <td>&nbsp;</td>
                      <td>
                          <input type='hidden' name='lid' value='$lid'>
                          <input type='hidden' name='op' value='modifydownloadrequestS'>
                          <input type='submit' value='"._SENDREQUEST."'>
                      </td>
                  </tr>
              </form>
              </table>
             ";
    }
}
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>