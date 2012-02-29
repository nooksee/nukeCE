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

global $multilingual, $useflags, $language;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _MULTILINGUALOPT . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _SELLANGUAGE . ":
                  </td>
                  <td colspan=\"3\">
                      <select name='xlanguage'>
     ";
$languageslist = lang_list();
for ($i=0, $maxi=count($languageslist); $i < $maxi; $i++) {
    if(!empty($languageslist[$i])) {
        echo "<option name='xlanguage' value='".$languageslist[$i]."' ";
        if($languageslist[$i]==$language) echo "selected='selected'";
        echo ">".ucwords($languageslist[$i])."\n";
    }
}
echo "
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ACTMULTILINGUAL . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xmultilingual', $multilingual);
echo "        
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ACTUSEFLAGS . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xuseflags', $useflags);
echo "
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>