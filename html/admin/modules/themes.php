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

if (!defined('ADMIN_FILE')) {
    die ("Illegal File Access");
}

global $prefix, $db;

function theme_header1() {
    global $admin_file;
    GraphicAdmin();
    OpenTable();
    echo "
          <div align='center'>
              <font class='title'>
                  <a href='$admin_file.php?op=themes'>" . _THEMES_HEADER . "</a>
              </font>
          </div>
          <br />
          <table align='center' border='0' width='70%'>
              <caption align=\"bottom\" style=\"padding: 10; font-size: 10pt\">
                  <span class=\"content\">
                      [
                      <a href='$admin_file.php?op=theme_transfer'>
                          " . _THEMES_THEME_TRANSFER . "
                      </a>
                      |
                      <a href='$admin_file.php?op=theme_uninstalled'>
                          " . _THEMES_UNINSTALLED . "
                      </a>
                      ]
                  </span>
              </caption>
              <tr>
                  <td>
                      <img src='images/sys/ok.png' alt='' width='10' height='10' />
                  </td>
                  <td>
                      <i>" . _THEMES_DEFAULT . "</i>
                  </td>
                  <td>
                      " . get_default() . "
                  </td>
              </tr>
              <tr>
                  <td>
                      <img src='images/sys/null.png' alt='' width='10' height='10' />
                  </td>
                  <td>
                      <i>" . _THEMES_NUMTHEMES . "</i>
                  </td>
                  <td>
                      " . count(get_themes('all')) . "
                  </td>
              </tr>
              <tr>
                  <td>
                      <img src='images/sys/null.png' alt='' width='10' height='10' />
                  </td>
                  <td>
                      <i>" . _THEMES_NUMUNINSTALLED . "</i>
                  </td>
                  <td>" . count(get_themes('uninstalled')) . "
                  </td>
              </tr>
              <tr>
                  <td>
                      <img src='images/sys/null.png' alt='' width='10' height='10' />
                  </td>
                  <td>
                      <i>" . _THEMES_MOSTPOPULAR . "</i>
                  </td>
                  <td>
                      " . ThemeMostPopular() . "
                  </td>
              </tr>
          </table>
         ";
    CloseTable();
    echo "
          <br />
         ";
}

function theme_header2() {
    global $admin_file;
    GraphicAdmin();
    OpenTable();
    echo "
          <div align='center'>
              <font class='title'>
                  <a href='$admin_file.php?op=themes'>
                      " . _THEMES_HEADER . "
                  </a>
              </font>
          </div>
         ";
    CloseTable();
    echo "
          <br />
         ";
}

function theme_header3() {
    global $admin_file;
    OpenTable();
    echo "
          <div align='center'>
              <font class='title'>
                  <a href='$admin_file.php?op=themes'>
                      " . _THEMES_HEADER . "
                  </a>
              </font>
          </div>
         ";
    CloseTable();
    echo "
          <br />
         ";
}

function display_main() {
    global $admin_file, $db, $prefix;
    $installed_themes = get_themes('all');
    if(count($installed_themes) == 0) {
        DisplayError(_THEMES_NOINSTALL, 1);
        exit;
    }

    if (eregi(_THEMES_THEME_MISSING,  ThemeGetStatus($theme['theme_name'], $theme['active']))) {
        if ($db->sql_query("DELETE FROM " . $prefix . "_themes WHERE theme_name = '".$theme['theme_name']."'")) {
            $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '".$theme['theme_name']."'");
        }
        return ;
    }

    OpenTable();
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table align=\"center\" width=\"96%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <td class=\"catHead\" colspan=\"6\" height=\"28\" align=\"center\">
                      <span class=\"cattitle\">
                          "._THEMES_INSTALLED."
                      </span>
                  </td>
              </tr>
              <tr>
                  <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      <strong>
                          "._THEMES_NAME."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._THEMES_CUSTOMN."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._THEMES_NUMUSERS."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._STATUS."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._PERMISSIONS."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                      <strong>
                          "._FUNCTIONS."
                      </strong>
                  </th>
              </tr>
         ";
    if (is_array($installed_themes)) {
        foreach($installed_themes as $theme) {
            
            $row_class = ($c++%2==1) ? 'row2' : 'row1';
            $theme_name = (is_default($theme['theme_name'])) ? "<strong>".$theme['theme_name']."</strong>" : ((ThemeIsActive($theme['theme_name'], true)) ? "".$theme['theme_name']."":"".$theme['theme_name']."");
            $theme_edit = "<img src='images/edit.gif' alt='"._THEMES_EDIT."' title='"._THEMES_EDIT."' border='0' width='17' height='17'>";
            $default_link = (is_default($theme['theme_name']) || !theme_exists($theme['theme_name'])) ? "<img src='images/key_x.gif' alt='"._THEMES_MAKEDEFAULT."' title='"._THEMES_MAKEDEFAULT."' border='0' width='17' height='17'>" : "<a href='$admin_file.php?op=theme_makedefault&amp;theme=" . $theme['theme_name'] . "'><img src='images/key.gif' alt='"._THEMES_MAKEDEFAULT."' title='"._THEMES_MAKEDEFAULT."' border='0' width='17' height='17'></a>";
            $activate_link = (is_default($theme['theme_name'])) ? "<img src='images/inactive_x.gif' alt='"._THEMES_DEACTIVATE."' title='"._THEMES_DEACTIVATE."' border='0' width='16' height='16'>" : ((ThemeIsActive($theme['theme_name'], true)) ? "<a href='$admin_file.php?op=theme_deactivate&amp;theme=" . $theme['theme_name'] . "'><img src='images/inactive.gif' alt='"._THEMES_DEACTIVATE."' title='"._THEMES_DEACTIVATE."' border='0' width='16' height='16'></a>" : "<a href='$admin_file.php?op=theme_activate&amp;theme=" . $theme['theme_name'] . "'><img src='images/active.gif' alt='"._THEMES_ACTIVATE."' title='"._THEMES_ACTIVATE."' border='0' width='16' height='16'></a>");
            $theme_uninstall = (is_default($theme['theme_name'])) ? "<img src='images/icon_uninstall_x.gif' alt='"._THEMES_UNINSTALL."' title='"._THEMES_UNINSTALL."' border='0' width='16' height='18'>" : ((ThemeIsActive($theme['theme_name'], true)) ? "<a href='$admin_file.php?op=theme_uninstall&amp;theme=" . $theme['theme_name'] . "'><img src='images/icon_uninstall.gif' alt='"._THEMES_UNINSTALL."' title='"._THEMES_UNINSTALL."' border='0' width='16' height='18'></a>":"");
            $theme_view = (is_default($theme['theme_name'])) ? "<img src='images/view_x.gif' alt='"._THEMES_VIEW."' title='"._THEMES_VIEW."' border='0' width='17' height='17'>" : ((ThemeIsActive($theme['theme_name'], true)) ? "<a href='index.php?tpreview=" . $theme['theme_name'] . "' rel='5' class='newWindow'><img src='images/view.gif' alt='"._THEMES_VIEW."' title='"._THEMES_VIEW."' border='0' width='17' height='17'></a>":"");

            if($theme['permissions'] == 1) {
                $permissions = _THEMES_ALLUSERS;
            } elseif ($theme['permissions'] == 2) {
                $permissions = _THEMES_GROUPSONLY;
            } elseif ($theme['permissions'] == 3) {
                $permissions = _THEMES_ADMINS;
            }
            
            echo "
                  <tr>
                      <td class=".$row_class." align=\"center\">
                          ".$theme_name."
                      </td>
                      <td class=".$row_class." align=\"center\">
                          ".$theme['custom_name']."
                      </td>
                      <td class=".$row_class." align=\"center\">
                          ".ThemeNumUsers($theme['theme_name'])."
                      </td>
                      <td class=".$row_class." align=\"center\">
                          ".ThemeGetStatus($theme['theme_name'], $theme['active'])."
                      </td>
                      <td class=".$row_class." align=\"center\">
                          ".$permissions."
                      </td>
                      <td class=".$row_class." align=\"center\">
                          <small>
                              <a href='$admin_file.php?op=theme_edit&amp;theme=" .$theme['theme_name']. "'>
                                  " .$theme_edit. "
                              </a> 
                              " .$default_link. "
                              " .$activate_link. " 
                              " .$theme_uninstall. " 
                              " .$theme_view. "
                          </small>
                      </td>
                  </tr>          
                 ";
        }
    }
    echo  "
           </table>
           <span class=\"gen\">
           <br />
           </span>
          ";
    CloseTable();
}

function uninstalled_theme() {
    global $admin_file, $db, $prefix, $bgcolor, $bgcolor1, $bgcolor2, $bgcolor3;
    $pagetitle = _THEMES_HEADER;
    $uninstalled_themes = get_themes('uninstalled');
    if(count($uninstalled_themes) == 0) {
        DisplayErrorReturn(_THEMES_NOUNINSTALL, 1);
        exit;
    }
    OpenTable();
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table align=\"center\" class=\"bodyline\" cellspacing=\"1\" cellpadding=\"2\" width=\"96%\">
              <tr bgcolor=\"".$bgcolor2."\">
                  <th colspan=\"3\" height=\"20\" align=\"center\"><strong>"._THEMES_UNINSTALLED."</strong></th>
              </tr>
              <tr bgcolor='".$bgcolor2."' height=\"20\">
                  <td align='center' nowrap='nowrap'>
                      <strong>
                          "._THEMES_NAME."
                      </strong>
                  </td>
                  <td align='center' nowrap='nowrap'>
                      <strong>
                          "._STATUS."
                      </strong>
                  </td>
                  <td align='center' nowrap='nowrap'>
                      <strong>
                          "._FUNCTIONS."
                      </strong>
                  </td>
              </tr>
         ";
    if (is_array($uninstalled_themes)) {
        foreach($uninstalled_themes as $theme) {
            $row_class = ($c++%2==1) ? 'row2' : 'row1';
            echo "
                  <tr>
                      <td class=".$row_class." align=\"center\">
                          ".$theme."
                      </td>
                      <td class=".$row_class." align=\"center\">
                          <em>
                              ".ThemeGetStatus($theme)."
                          </em>
                      </td>
                      <td class=".$row_class." align=\"center\">
                          <small>
                              <a href='$admin_file.php?op=theme_quickinstall&amp;theme=" .$theme. "'>
                                  <img src=\"images/active.gif\" alt=\""._THEMES_QINSTALL."\" title=\""._THEMES_QINSTALL."\" border=\"0\" width=\"16\" height=\"16\">
                              </a> 
                              <a href='$admin_file.php?op=theme_install&amp;theme=" .$theme. "'>
                                  <img src=\"images/icon_install.gif\" alt=\""._THEMES_INSTALL."\" title=\""._THEMES_INSTALL."\" border=\"0\" width=\"16\" height=\"18\">
                              </a> 
                              <a href='$admin_file.php?op=theme_makedefault&amp;theme=" .$theme. "'>
                                  <img src=\"images/key_x.gif\" alt=\""._THEMES_MAKEDEFAULT."\" title=\""._THEMES_MAKEDEFAULT."\" border=\"0\" width=\"17\" height=\"17\">
                              </a> 
                              <a href='index.php?tpreview=" . $theme . "' rel='5' class='newWindow'>
                                  <img src=\"images/view.gif\" alt=\""._THEMES_VIEW."\" title=\""._THEMES_VIEW."\" border=\"0\" width=\"17\" height=\"17\">
                              </a> 
                          </small>
                      </td>
                  </tr>            
                 ";
        }
    }
    echo "
          </table>
          <span class=\"gen\">
          <br />
          </span>
         ";
    CloseTable();
}

function theme_edit($theme_name) {
    global $prefix, $db, $admin_file;
    $sql = "SELECT * FROM " . $prefix . "_themes WHERE theme_name = '$theme_name'";
    $result = $db->sql_query($sql);
    $theme_info = $db->sql_fetchrow($result);
    OpenTable();
    echo "
          <fieldset>
              <legend>
                  <span class='option'>
                      " . $theme_info['theme_name'] . "&nbsp;
                  </span>
              </legend>
              <form action='".$admin_file.".php' method='get'>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              " . _THEMES_CUSTOMNAME . "
                          </td>
                          <td colspan=\"3\">
                              <input type='text' name='custom_name' value='".$theme_info['custom_name']."' size='30'>
                          </td>
                      </tr>
                     <tr>
         ";
     if(is_file(NUKE_THEMES_DIR.$theme_info['theme_name'].'/theme_info.php')) {
         include(NUKE_THEMES_DIR.$theme_info['theme_name'].'/theme_info.php');
         $loaded_params = (!empty($theme_info['theme_info'])) ? explode(':::', $theme_info['theme_info']) : $default;
         if(empty($theme_info['theme_info'])) {
             echo "
                   <tr>
                       <td>
                       </td>
                       <td align='left' colspan='3'>
                           <strong>
                               <em>
                                   " . _THEMES_DEF_LOADED . "
                               </em>
                           </strong>
                       </td>
                   </tr>
                  ";
         }
         if (is_array($params)) {
             foreach($params as $key => $param) {
                 echo "
                       <tr>
                           <td>
                               " . $param_names[$key] . "
                           </td>
                           <td>
                               <input type='text' name='" . $param . "' value='".$loaded_params[$key]."'size='40'>
                           </td>
                       </tr>
                      ";
             }
         }
         echo "
               <tr>
                   <td>
                       " . _THEMES_REST_DEF . "
                   </td>
                   <td>
                       <input type='checkbox' value='1' name='restore_default'>
                   </td>
               </tr>
              ";

         $selected1 = ($theme_info['permissions'] == 1) ? "selected" : "";
         $selected2 = ($theme_info['permissions'] == 2) ? "selected" : "";
         $selected3 = ($theme_info['permissions'] == 3) ? "selected" : "";
         if (is_default($theme_info['theme_name'])) {
             $disabled = "disabled";
             $selected1 = "selected";
             $selected2 = "";
             $selected3 = "";
         }
         echo "
               <tr>
                   <td>
                       " . _THEMES_ACTIVE . "?
                   </td>
                   <td colspan=\"3\">
                       ".yesno_option("active", $theme_info['active'])."
                   </td>
               </tr>
              ";
         if (is_default($theme_info['theme_name'])) {
             echo "
                   <input type='hidden' name='active' value='1'>
                   <input type='hidden' name='permissions' value='1'>
                  ";
         }
         echo "
               <tr>
                   <td>
                       " . _VIEWPRIV . "
                   </td>
                   <td colspan=\"3\">
                       <select name=\"permissions\" $disabled>
                           <option value=\"1\" $selected1>
                               " . _MVALL . "
                           </option>
                           <option value=\"2\" $selected2>
                               "._MVGROUPS."
                           </option>
                           <option value=\"3\" $selected3>
                               " . _MVADMIN . "
                           </option>
                       </select>
                   </td>
               </tr>
               <tr>
                   <td nowrap valign='middle'>
                       "._WHATGROUPS.":
                   </td>
                   <td>
                       <select name='groups[]' multiple size='5'>
              ";
           $ingroups = explode("-",$theme_info['groups']);
           $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups WHERE group_description <> 'Personal User'");
           while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
               if(in_array($gid,$ingroups)) { $sel = " selected"; } else { $sel = ""; }
               echo "
                     <option value='$gid'$sel>
                         $gname
                     </option>
                    ";
           }
           echo "
                         </select>
                         <br />
                         <span class='tiny'>
                             ("._WHATGRDESC.")
                         </span>
                     </td>
                 </tr>
                ";
           } else {
               echo "
                     <tr>
                         <td>
                         </td>
                         <td align='center' colspan='3'>
                             <em>
                                 <font color='red'>
                                     " . _THEMES_NOT_COMPAT . "
                                 </font>
                             </em>
                         </td>
                     </tr>
                     <tr>
                         <td>
                    ";

           $selected1 = ($theme_info['permissions'] == 1) ? "selected" : "";
           $selected2 = ($theme_info['permissions'] == 2) ? "selected" : "";
           $selected3 = ($theme_info['permissions'] == 3) ? "selected" : "";
           
           if (is_default($theme_info['theme_name'])) {
               $disabled = "disabled";
               $selected1 = "selected";
               $selected2 = "";
               $selected3 = "";
           }
           echo "
                 <tr>
                     <td>
                         " . _THEMES_ACTIVE . "?
                     </td>
                     <td colspan=\"3\">
                         ".yesno_option("active", $theme_info['active'])."
                     </td>
                 </tr>
                ";
           if (is_default($theme_info['theme_name'])) {
               echo "
                     <input type='hidden' name='active' value='1'>
                     <input type='hidden' name='permissions' value='1'>
                    ";
           }
           echo "
                 <tr>
                     <td>
                         " . _VIEWPRIV . "
                     </td>
                     <td colspan=\"3\">
                         <select name=\"permissions\" $disabled>
                             <option value=\"1\" $selected1>
                                 " . _MVALL . "
                             </option>
                             <option value=\"2\" $selected2>
                                 "._MVGROUPS."
                             </option>
                             <option value=\"3\" $selected3>
                                 " . _MVADMIN . "
                             </option>
                         </select>
                     </td>
                 </tr>
                 <tr>
                     <td nowrap valign='middle'>
                         "._WHATGROUPS.":
                     </td>
                     <td>
                         <select name='groups[]' multiple size='5'>
                ";
             $ingroups = explode("-",$theme_info['groups']);
             $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups WHERE group_description <> 'Personal User'");
             while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
                 if(in_array($gid,$ingroups)) { $sel = " selected"; } else { $sel = ""; }
                 echo "
                       <option value='$gid'$sel>
                           $gname
                       </option>
                      ";
             }
             echo "
                           </select>
                           <br />
                           <span class='tiny'>
                               ("._WHATGRDESC.")
                           </span>
                       </td>
                   </tr>
                  ";
           }
    echo "
                  <input type='hidden' name='theme_name' value='" . $theme_info['theme_name'] . "'>
                  <input type='hidden' name='op' value='theme_edit_save'>
                      </td>
                  </tr>
              </table>
          </fieldset>
          <br />
                  <div align='center'>
                      <input type='submit' value='"._SAVECHANGES."'>
                  </div>
              </form>
          </td>
         ";
    CloseTable();
}

function theme_install($theme_name) {
    global $prefix, $db, $admin_file;
    OpenTable();
    echo "
          <fieldset>
              <legend>
                  <span class='option'>
                      " . $theme_name . "&nbsp;
                  </span>
              </legend>
              <form action='".$admin_file.".php' method='get'>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <tr>
                          <td>
                              " . _THEMES_CUSTOMNAME . "
                          </td>
                          <td colspan=\"3\">
                              <input type='text' name='custom_name' value='".$theme_name."' size='30'>
                          </td>
                      </tr>
                     <tr>
         ";
      if(is_file(NUKE_THEMES_DIR.$theme_name.'/theme_info.php')) {
          include(NUKE_THEMES_DIR.$theme_name.'/theme_info.php');
          $loaded_params = $default;
          echo "
                <tr>
                    <td>
                    </td>
                    <td align='left' colspan='3'>
                        <strong>
                            <em>
                                " . _THEMES_DEF_LOADED . "
                            </em>
                        </strong>
                    </td>
                </tr>
               ";
          foreach($params as $key => $param) {
              echo "
                    <tr>
                        <td>
                            " . $param_names[$key] . "
                        </td>
                        <td>
                            <input type='text' name='" . $param . "' value='".$loaded_params[$key]."'size='40'>
                        </td>
                    </tr>
                   ";
          }
      } else {
          echo "
                <tr>
                    <td>
                    </td>
                    <td align='center' colspan='3'>
                        <em>
                            <font color='red'>
                                " . _THEMES_NOT_COMPAT . "
                            </font>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td>
               ";
      }
      echo "
            <tr>
                <td>
                    " . _THEMES_ACTIVE . "?
                </td>
                <td colspan=\"3\">
                    ".yesno_option("active")."
                </td>
            </tr>
            <tr>
                <td>
                    " . _VIEWPRIV . "
                </td>
                <td colspan=\"3\">
                    <select name=\"permissions\">
                        <option value=\"1\">
                            " . _MVALL . "
                        </option>
                        <option value=\"2\">
                            "._MVGROUPS."
                        </option>
                        <option value=\"3\">
                            " . _MVADMIN . "
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td nowrap valign='middle'>
                    "._WHATGROUPS.":
                </td>
                <td>
                    <select name='groups[]' multiple size='5'>
           ";
    $ingroups = explode("-",$theme_info['groups']);
    $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups WHERE group_description <> 'Personal User'");
    while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
        if(in_array($gid,$ingroups)) { $sel = " selected"; } else { $sel = ""; }
        echo "
              <option value='$gid'$sel>
                  $gname
              </option>
             ";
    }
    echo "
                                </select>
                                <br />
                                <span class='tiny'>
                                    ("._WHATGRDESC.")
                                </span>
                            </td>
                        </tr>
                      <input type='hidden' name='theme_name' value='" . $theme_name . "'>
                      <input type='hidden' name='op' value='theme_install_save'>
                          </td>
                      </tr>
                  </table>
          </fieldset>
          <br />
                  <div align='center'>
                      <input type='submit' value='"._THEMES_INSTALL."'>
                  </div>
              </form>
          </td>
         ";
    CloseTable();
}

function update_theme($post) {
    global $db, $prefix, $user_prefix, $admin_file, $cache;
    $pagetitle = _THEMES_HEADER;
    $error = false;
    if(is_array($post['groups'])) {
        $post['groups'] = implode('-', $post['groups']);
    }
    $theme_info = "";
    if(file_exists(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php')) {
        include(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php');
        for($i=0, $maxi=count($params);$i<$maxi;$i++) {
            $param = $params[$i];
            $theme_info[] = $post[$param];
        }
        $theme_info = implode(':::', $theme_info);
        if($post['restore_default']) {
            $theme_info = implode(':::', $default);
        }
    }

    $sql[] = "UPDATE " . $prefix . "_themes SET custom_name = '" . $post['custom_name'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    $sql[] = "UPDATE " . $prefix . "_themes SET active = '" . $post['active'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    $sql[] = "UPDATE " . $prefix . "_themes SET permissions = '" . $post['permissions'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    $sql[] = "UPDATE " . $prefix . "_themes SET theme_info = '" . $theme_info . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    if (($post['permissions'] > 1) || ($post['active'] != 1)) {
        $sql[] = "UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '" . $post['theme_name'] . "'";
    }
    $sql[] = "UPDATE " . $prefix . "_themes SET groups = '" . $post['groups'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    foreach($sql as $query) {
        if(!$db->sql_query($query)) {
            $error = true;
        }
    }
    $cache->delete($post['theme_name'], 'themes');
    if(!$error) {
        DisplayError(_THEMES_UPDATED, 1);
    } else {
        DisplayError(_THEMES_UPDATEFAILED, 1);
    }
}

function install_save($post) {
    global $db, $prefix, $admin_file;
    $pagetitle = _THEMES_HEADER;
    $post['groups'] = (is_array($post['groups'])) ? implode('-', $post['groups']) : '';

    $theme_info = "";
    if(file_exists(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php')) {
        include(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php');
        for($i=0, $maxi=count($params);$i<$maxi;$i++) {
            $param = $params[$i];
            $theme_info[] = $post[$param];
        }
        $theme_info = implode(':::', $theme_info);
        if($post['restore_default']) {
            $theme_info = implode(':::', $default);
        }
    }

    $sql = "INSERT INTO " . $prefix . "_themes VALUES('" . $post['theme_name'] . "', '" . $post['groups'] . "', '" . $post['permissions'] . "', '" . $post['custom_name'] . "', '" . $post['active'] . "', '" . $theme_info . "')";
    if($db->sql_query($sql)) {
        DisplayError(_THEMES_THEME_INSTALLED, 1);
    } else {
        DisplayError(_THEMES_THEME_INSTALLED_FAILED, 1);
    }
}

function uninstall_theme($theme) {
    global $db, $prefix, $user_prefix, $admin_file;
    if(!$_POST['confirm']) {
        echo "
              <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
                  <tr>
                      <th class=\"thHead\" height=\"25\" valign=\"middle\">
                          <span class=\"tableTitle\">
                              Confirm
                          </span>
                      </th>
                  </tr>
                  <tr>
                      <td class=\"row1\" align=\"center\">
                          <span class=\"gen\">
                              <br />
                              " . _THEMES_UNINSTALLSURE . "
                              <form name='confirm_uninstall' action='$admin_file.php' method='post'>
                                  <input type='hidden' name='theme' value='$theme'>
                                  <input type='hidden' name='op' value='theme_uninstall'>
                                  <input type='hidden' name='confirm' value='1'>
                                  <br />
                                  <input type=\"submit\" value=\""._YES."\" class=\"mainoption\" />
                                  <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$admin_file.".php?op=themes' \" />
                              </form>
                          </span>
                      </td>
                  </tr>
              </table>
             ";
        return false;
    } else {
        if (!is_default($theme)) {
            if ($db->sql_query("DELETE FROM " . $prefix . "_themes WHERE theme_name = '$theme'")) {
                $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '$theme'");
                theme_header3();
                DisplayError(_THEMES_THEME_UNINSTALLED, 1);
                return true;
            }
        }
        theme_header3();
        DisplayError(_THEMES_THEME_UNINSTALLED_FAILED, 1);
        return false;
    }
    theme_header3();
    DisplayError(_THEMES_THEME_UNINSTALLED_FAILED, 1);
    return false;
}

function theme_makedefault($theme) {
    global $db, $prefix, $admin_file, $cache;
    if(!theme_installed($theme)) {
        $sql = "INSERT INTO " . $prefix . "_themes VALUES('$theme', '', '1', '$theme', '1', '')";
        $db->sql_query($sql);
    }
    $sql=array();
    $sql[] = "UPDATE " . $prefix . "_themes SET active = '1' WHERE theme_name = '$theme'";
    $sql[] = "UPDATE " . $prefix . "_config SET default_Theme = '$theme'";
    $sql[] = "UPDATE " . $prefix . "_themes SET permissions = '1' WHERE theme_name = '$theme'";
    foreach($sql as $query) {
        $db->sql_query($query);
    }
    $cache->delete('nukeconfig', 'config');
    redirect($admin_file . '.php?op=themes');
}

function theme_deactivate($theme) {
    global $db, $prefix, $user_prefix, $admin_file;

    if(!$_POST['confirm']) {
        echo "
              <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
                  <tr>
                      <th class=\"thHead\" height=\"25\" valign=\"middle\">
                          <span class=\"tableTitle\">
                              Confirm
                          </span>
                      </th>
                  </tr>
                  <tr>
                      <td class=\"row1\" align=\"center\">
                          <span class=\"gen\">
                              <br />
                              " . _THEMES_DEACTIVATESURE . "
                              <form name='confirm_deactivate' action='$admin_file.php' method='post'>
                                  <input type='hidden' name='theme' value='$theme'>
                                  <input type='hidden' name='op' value='theme_deactivate'>
                                  <input type='hidden' name='confirm' value='1'>
                                  <br />
                                  <input type=\"submit\" value=\""._YES."\" class=\"mainoption\" />
                                  <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$admin_file.".php?op=themes' \" />
                              </form>
                          </span>
                      </td>
                  </tr>
              </table>
             ";
        return false;
    } else {
        if (!is_default($theme)) {
            if ($db->sql_query("UPDATE " . $prefix . "_themes SET active='0' WHERE theme_name = '$theme'")) {
                if($db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '$theme'")){
                    theme_header3();
                    DisplayError(_THEMES_THEME_DEACTIVATED, 1);
                    return true;
                }
            }
        }
        theme_header3();
        DisplayError(_THEMES_THEME_DEACTIVATED_FAILED, 1);
        return false;
    }
}

function theme_transfer() {
    global $prefix, $db, $admin_file, $user_prefix;
    if(!$_POST['transfer']) {
        $from_themes = get_themes('dir');
        $to_themes = get_themes('all');
        OpenTable();
        echo "
              <table align='center' cellpadding='2' cellspacing='2' border='0'>
              <form action='$admin_file.php' method='post'>
                  <tr>
                      <td align='center'>
                          ". _THEMES_FROM ."
                      </td>
                      <td align='center'>
                          <select name='from'>
                              <option value='all'>
                                  " . _THEMES_ALLUSERS . "
                              </option>
                 ";
                foreach($from_themes as $theme) {
                    echo "
                          <option value='" . $theme['theme_name'] . "'>
                              " . (($theme['custom_name']) ? $theme['custom_name'] : $theme['theme_name']) . " (" . ThemeCount($theme['theme_name']) . ")
                          </option>
                         ";
                }
                echo "
                          </select>
                      </td>
                      <td align='center'>
                          ". _THEMES_TO ."
                      </td>
                      <td align='center'>
                          <select name='to'>
                              <option value='default'>
                                  " . _THEMES_DEFAULT . "
                              </option>
                     ";
                foreach($to_themes as $theme) {
                    echo"
                         <option value='" . $theme['theme_name'] . "'>
                             " . (($theme['custom_name']) ? $theme['custom_name'] : $theme['theme_name']) . "
                         </option>
                        ";
                }
        echo "
                          </select>
                      </td>
                      <td align='center'>
                          <input type='hidden' name='transfer' value='1'>
                          <input type='hidden' name='op' value='theme_transfer'>
                          <input type='submit' value='" . _THEMES_SUBMIT . "'>
                      </td>
                  </tr>
              </form>
              </table>
             ";
        CloseTable();
    } else {
        $where = ($_POST['from'] == 'all') ? "WHERE user_id <> '1'" : "WHERE theme='" . $_POST['from'] . "' AND user_id <> '1'";
        $to = ($_POST['to'] == 'default') ? "" : $_POST['to'];
        $result = $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . $to . "' $where");
        $count = intval($db->sql_affectedrows($result));
        DisplayError("$count " . _THEMES_TRANSFER_UPDATED . "", 1);
    }

    return true;
}

if (is_mod_admin()) {

    include_once(NUKE_BASE_DIR.'header.php');
    switch ($op) {
        case 'theme_uninstalled':
            theme_header2();
            uninstalled_theme();
        break;
    
        case 'theme_edit':
            theme_header2();
            theme_edit($theme);
        break;
    
        case 'theme_install':
            theme_header2();
            theme_install($theme);
        break;
    
        case 'theme_makedefault':
            theme_makedefault($theme);
        break;
    
        case 'theme_deactivate':
            GraphicAdmin();
            theme_deactivate($theme);
        break;
    
        case 'theme_activate':
            if (!is_default($theme)) {
                $sql = "UPDATE " . $prefix . "_themes SET active='1' WHERE theme_name = '$theme'";
                $db->sql_query($sql);
            }
            theme_header1();
            display_main();
        break;
        
        case 'theme_install_save':
            theme_header1();
            install_save($_GET);
        break;
    
        case 'theme_edit_save':
            theme_header2();
            update_theme($_GET);
        break;
    
        case 'theme_quickinstall':
            if(!theme_installed($theme)) {
                $sql = "INSERT INTO " . $prefix . "_themes VALUES('$theme', '', '1', '$theme', '1', '')";
                $db->sql_query($sql);
            }
            theme_header1();
            display_main();
        break;
        
        case 'theme_uninstall':
            GraphicAdmin();
            uninstall_theme($theme);
        break;
    
        case 'theme_transfer':
            theme_header1();
            theme_transfer();
        break;
    
        default:
            theme_header1();
            display_main();
        break;
    }
    include_once(NUKE_BASE_DIR.'footer.php');

} else {
    echo "Access Denied";
}

?>