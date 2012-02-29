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

global $admingraphic, $admin_pos;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _GRAPHICOPT . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _ADMINGRAPHIC . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xadmingraphic', $admingraphic);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ADMIN_POS . "
                  </td>
                  <td colspan=\"3\">
     ";
$value = ($admin_pos>0) ? 1 : 0;
$sel[$value] = ' checked="checked"';
echo '
                      <input type="radio" name="xadmin_pos" value="1"'.$sel[1].' />'._UP.'
                      <input type="radio" name="xadmin_pos" value="0"'.$sel[0].' />'._DOWN.' 
     ';
echo "
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>