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

global $prefix, $row_class, $db, $admin_file;

if (is_mod_admin()) {
    if (isset($_POST['save'])) {
        $xsitename = str_replace('', '&nbsp', Fix_Quotes($_POST['xsitename']));
        $headlinesurl = Fix_Quotes($_POST['headlinesurl']);
        $db->sql_query('UPDATE '.$prefix."_headlines SET sitename='$xsitename', headlinesurl='$headlinesurl' where hid=".intval($_POST['save']));
        redirect($admin_file.'.php?op=headlines');
    } else if (isset($_POST['addHeadline'])) {
        $xsitename = str_replace('', '&nbsp', Fix_Quotes($_POST['xsitename']));
        $headlinesurl = Fix_Quotes($_POST['headlinesurl']);
        $db->sql_query('INSERT INTO '.$prefix."_headlines VALUES (NULL, '$xsitename', '$headlinesurl')");
        redirect($admin_file.'.php?op=headlines');
    } elseif (isset($_GET['edit'])) {
        $hid = intval($_GET['edit']);
        include_once(NUKE_BASE_DIR.'header.php');
        GraphicAdmin();
        list($xsitename, $headlinesurl) = $db->sql_ufetchrow("SELECT sitename, headlinesurl FROM ".$prefix."_headlines WHERE hid='$hid'",SQL_NUM);
        OpenTable();
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php?op=headlines\">
                          "._HEADLINESADMIN."
                      </a>
                  </font>
              </div>
             ";
        CloseTable();
        echo "
              <br />
             ";
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class='option'>
                          " ._EDITHEADLINE . "
                          &nbsp;
                      </span>
                  </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <form method=\"post\" action=\"".$admin_file.".php?op=headlines\">
                      <tr>
                          <td>
                              <label class=\"ulog\" for=\"xsitename\">
                                  "._SITENAME.":
                              </label>
                          </td>
                          <td colspan=\"3\">
                              <input type=\"text\" name=\"xsitename\" size=\"30\" maxlength=\"30\" value=\"".htmlentities($xsitename)."\" />
                          </td>
                      </tr>
                      <tr>
                          <td>
                              <label class=\"ulog\" for=\"headlinesurl\">
                                  "._RSSFILE.":
                              </label>
                          </td>
                          <td colspan=\"3\">
                              <input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\" value=\"".$headlinesurl."\" />
                          </td>
                      </tr>
                      <input type=\"hidden\" name=\"save\" value=\"".$hid."\" />
                      </tr>
                          </td>
                  </table>
              </fieldset>
              <br />
              <div align=\"center\">
                  <input type=\"submit\" value=\"" . _SAVECHANGES . "\">
              </div>
                  </form>
              </td>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    } else if (isset($_GET['del'])) {
        if (isset($_POST['cancel'])) { redirect($admin_file.'.php?op=headlines'); }
        if (isset($_POST['confirm'])) {
            $db->sql_query('DELETE FROM '.$prefix."_headlines WHERE hid='".intval($_GET['del'])."'");
            redirect($admin_file.'.php?op=headlines');
        }
        include_once(NUKE_BASE_DIR.'header.php');
        GraphicAdmin();
        echo '
              <table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0">
                  <tr>
                      <th class="thHead" height="25" valign="middle"><span class="tableTitle">Confirm</span></th>
                  </tr>
                  <tr>
                      <td class="row1" align="center"><form action="'.$admin_file.'.php?op=headlines&amp;del='.intval($_GET['del']).'" method="post"><span class="gen">
                          <br />'._SURE2DELHEADLINE.'<br /><br /><input type="submit" name="confirm" value="'._YES.'" class="mainoption" />
                          &nbsp;&nbsp;<input type="submit" name="cancel" value="'._NO.'" class="liteoption" /></span></form>
                      </td>
                  </tr>
              </table>
              <br clear="all" />
             ';
        include_once(NUKE_BASE_DIR.'footer.php');
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        GraphicAdmin();
        OpenTable();
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php?op=headlines\">
                          "._HEADLINESADMIN."
                      </a>
                  </font>
              </div>
             ";
        CloseTable();
        echo "
              <br />
             ";
        OpenTable();
        echo "
              <span class=\"gen\">
              <br />
              </span>
              <table align=\"center\" width=\"96%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                  <tr>
                      <th colspan=\"1\" align=\"left\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                          <strong>
                              "._SITENAME."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"left\" class=\"thTop\" nowrap=\"nowrap\">
                          <strong>
                              "._URL."
                          </strong>
                      </th>
                      <th colspan=\"1\" align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                          <strong>
                              "._FUNCTIONS."
                          </strong>
                      </th>
                  </tr>
             ";
        $result = $db->sql_uquery("SELECT hid, sitename, headlinesurl FROM ".$prefix."_headlines ORDER BY hid");
        while (list($hid, $sitename, $headlinesurl) = $db->sql_fetchrow($result)) {
            $row_class = ($c++%2==1) ? 'row2' : 'row1';
            echo "
                  <tr>
                      <td class=".$row_class." align=\"left\">
                          ".$sitename."
                      </td>
                      <td class=".$row_class." align=\"left\">
                          <a href=".$headlinesurl." target=\"new\">
                              ".$headlinesurl."
                          </a>
                      </td>
                      <td class=".$row_class." align=\"center\">
                          <a href=".$admin_file.".php?op=headlines&amp;edit=".$hid.">
                              <img src=\"images/edit.gif\" alt=\""._EDIT."\" title=\""._EDIT."\" border=\"0\" width=\"17\" height=\"17\">
                          </a>
                          <a href=".$admin_file.".php?op=headlines&amp;del=".$hid.">
                              <img src=\"images/delete.gif\" alt=\""._DELETE."\" title=\""._DELETE."\" border=\"0\" width=\"17\" height=\"17\">
                          </a>
                      </td>
                  </tr>
                 ";
        }
         echo '
               </table>
               <span class=\"gen\">
               <br />
               </span>
              ';
         CloseTable();
         echo "
               <br />
              ";
         OpenTable();
         echo "
               <fieldset>
                   <legend>
                       <span class='option'>
                           " . _ADDHEADLINE . "
                           &nbsp;
                       </span>
                   </legend>
                   <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                   <form method=\"post\" action=\"".$admin_file.".php?op=headlines\">
                       <tr>
                           <td>
                               <label class=\"ulog\" for=\"xsitename\">
                                   "._SITENAME.":
                               </label>
                           </td>
                           <td colspan=\"3\">
                               <input type=\"text\" name=\"xsitename\" size=\"30\" maxlength=\"30\" />
                           </td>
                       </tr>
                       <tr>
                           <td>
                               <label class=\"ulog\" for=\"headlinesurl\">
                                   "._RSSFILE.":
                               </label>
                           </td>
                           <td colspan=\"3\">
                               <input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\" />
                           </td>
                       </tr>
                       </tr>
                           </td>
                   </table>
               </fieldset>
               <br />
               <div align=\"center\">
                   <input type=\"submit\" name=\"addHeadline\" value=\"" . _ADDHEADLINE . "\">
               </div>
                   </form>
               </td>
              ";
         CloseTable();
         include_once(NUKE_BASE_DIR.'footer.php');
    }

} else {
    echo "Access Denied";
}

?>