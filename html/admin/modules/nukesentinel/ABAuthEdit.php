<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(is_god($_COOKIE['admin'])) {
    $pagetitle = _AB_SENTINEL." &raquo; "._AB_EDITADMINS;
    include(NUKE_BASE_DIR."header.php");
    $sapi_name = strtolower(php_sapi_name());
    $admin_row = abget_admin($a_aid);
    sentinel_header();
    OpenTable();
    echo "
        <fieldset>
            <legend>
                <span class='option'>
                    " ._AB_EDITADMINS . "
                    &nbsp;
                </span>
            </legend>
                <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                    <form action='".$admin_file.".php' method='post'>
                        <tr>
                            <td>
                                "._AB_ADMIN.":
                            </td>
                            <td colspan=\"3\">
                                <input type=\"text\" value=\"$a_aid\" size=\"20\" disabled=\"disabled\">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                "._AB_AUTHLOGIN.":
                            </td>
                            <td colspan=\"3\">
                                <input type='text' name='xlogin' size='20' maxlength='25' value='".$admin_row['login']."'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                "._AB_PASSWORD.":
                            </td>
                            <td colspan=\"3\">
                                <input type='text' name='xpassword' size='20' maxlength='20' value='".$admin_row['password']."'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                "._AB_PROTECTED.":
                            </td>
       ";
    $sel1=$sel2='';
    if($admin_row['protected']==0) { $sel1 = " selected='selected'"; } else { $sel2 = " selected='selected'"; }
    echo "
                          <td colspan=\"3\">
                              <select name='xprotected'>
                                  <option value='0'$sel1>
                                      "._AB_NOTPROTECTED."
                                  </option>
                                  <option value='1'$sel2>
                                      "._AB_ISPROTECTED."
                                  </option>
                              </select>
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
                  <div align=\"center\">
                      <input type='hidden' name='op' value='ABAuthEditSave' />
                      <input type='hidden' name='a_aid' value='$a_aid' />                      
                      <input type=\"submit\" value=\""._SAVECHANGES."\" />
                  </div>
              </form>
          </td>
         ";
    CloseTable();
    include(NUKE_BASE_DIR."footer.php");
} else {
    header("Location: ".$admin_file.".php?op=ABMain");
}

?>