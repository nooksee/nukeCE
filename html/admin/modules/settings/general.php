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

if(!defined('IN_SETTINGS')) {
  exit('Access Denied');
}

global $sitename, $nukeurl, $site_logo, $slogan, $startdate, $adminmail, $top, $storyhome, $oldnum, $ultramode, $anonpost, $language, $locale;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _GENSITEINFO . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _SITENAME . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xsitename' value='$sitename' size='40' maxlength='255'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _SITEURL . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xnukeurl' value='$nukeurl' size='40' maxlength='255'>
                  </td>
              </tr>
              <tr>
                  <td valign=\"top\">
                      " . _SITELOGO . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xsite_logo' value='$site_logo' size='20' maxlength='255'>
                      <br />
                      <span class='tiny'>
                          [ 
                          " . _MUSTBEINIMG . " 
                          ]
                      </span>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _SITESLOGAN . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xslogan' value='$slogan' size='40' maxlength='255'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _STARTDATE . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xstartdate' value='$startdate' size='20' maxlength='50'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ADMINEMAIL . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xadminmail' value='$adminmail' size='30' maxlength='255'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ITEMSTOP . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xtop' value='$top' size='1' maxlength='2'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _STORIESHOME . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xstoryhome' value='$storyhome' size='1' maxlength='2'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _OLDSTORIES . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xoldnum' value='$oldnum' size='1' maxlength='2'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ACTULTRAMODE . "
                  </td>
                  <td colspan=\"3\">
     ";
echo yesno_option('xultramode', $ultramode);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ALLOWANONPOST . " 
                  </td>
                  <td colspan=\"3\">
     ";
echo yesno_option('xanonpost', $anonpost);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _LOCALEFORMAT . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xlocale' value='$locale' size='20' maxlength='40'>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>