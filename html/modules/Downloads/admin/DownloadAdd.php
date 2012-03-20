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

if(is_mod_admin($module_name)) {
    $pagetitle = _DOWNLOADS." &raquo; "._ADDDOWNLOAD;
    include_once(NUKE_BASE_DIR.'header.php');
    GraphicAdmin();
    DLsub();
    OpenTable();
    echo "
          <fieldset>
            <legend>
                <span class='option'>" . _ADDDOWNLOAD . "&nbsp;</span>
            </legend>
            <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
            <form method='post' action='".$admin_file.".php' enctype='multipart/form-data'>
                <tr>
                    <td width=\"20%\" nowrap>"._TITLE.":</td>
                    <td><input type='text' name='title' size='50' maxlength='100'></td>
                </tr>
                <tr>
                    <td>"._URL.":</td>
                    <td><input type='file' name='url' size='50' maxlength='255' value='http://'></td>
                </tr>
                <tr>
                    <td>"._CATEGORY.":</td>
                    <td>
                        <select name='cat'>
                            <option value='0'>"._DL_NONE."</option>
         ";
    $result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories ORDER BY parentid,title");
    while($cidinfo = $db->sql_fetchrow($result2)) {
        if ($cidinfo['parentid'] != 0) $cidinfo['title'] = getparent($cidinfo['parentid'],$cidinfo['title']);
        echo "
              <option value='".$cidinfo['cid']."'>".$cidinfo['title']."</option>
             ";
    }
    echo "
                  </select>
              </td>
          </tr>
          <tr>
              <td>"._DL_PERM.":</td>
              <td>
                  <select name='perm'>
                      <optgroup label='General'>
                        <option value='0'>"._DL_ALL."</option>
                        <option value='1'>"._DL_USERS."</option>
                        <option value='2'>"._DL_ADMIN."</option>
                      </optgroup>
                      <optgroup label='Groups'>
         ";
    $gresult = $db->sql_query("SELECT * FROM ".$prefix."_bbgroups WHERE group_single_user != '1' ORDER BY group_name");
    while($gidinfo = $db->sql_fetchrow($gresult)) {
        $gidinfo['group_id'] = $gidinfo['group_id'] + 2;
        echo "
              <option value='".$gidinfo['group_id']."'>".$gidinfo['group_name']." "._DL_ONLY."</option>
             ";
    }
    echo "
                      </optgroup>
                  </select>
              </td>
          </tr>
          <tr>
              <td>"._DESCRIPTION.":</td>
              <td width=\"100%\">
         ";
    Make_TextArea('description', '', 'add_download');
    echo "
                <tr>
                    <td nowrap>"._AUTHORNAME.":</td>
                    <td><input type='text' name='sname' size='30' maxlength='60'></td>
                </tr>
                <tr>
                    <td nowrap>"._AUTHOREMAIL.":</td>
                    <td><input type='text' name='email' size='30' maxlength='60'></td>
                </tr>
                <tr>
                    <td>"._FILESIZE.":</td>
                    <td><input type='text' name='filesize' size='12' maxlength='20'> ("._INBYTES.")</td>
                </tr>
                <tr>
                    <td>"._VERSION.":</td>
                    <td><input type='text' name='version' size='11' maxlength='20'></td>
                </tr>
                <tr>
                    <td>"._HOMEPAGE.":</td>
                    <td><input type='text' name='homepage' size='50' maxlength='255' value='http://'></td>
                </tr>
                <tr>
                    <td>"._HITS.":</td>
                    <td><input type='text' name='hits' size='12' maxlength='11'></td>
                </tr>
            </table>
          </fieldset>
          <br />
                  <div align=\"center\">
                      <input type='hidden' name='op' value='DownloadAddSave'>
                      <input type='hidden' name='new' value='0'>
                      <input type='hidden' name='lid' value='0'>
                      <input type=\"submit\" value=\"" . _ADDDOWNLOAD . "\">
                  </div>
              </form>
          </td>
         ";
    CloseTable();
    @include(NUKE_BASE_DIR.'footer.php');
    
}
?>