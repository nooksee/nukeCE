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

global $backend_title, $backend_language;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _BACKENDCONF . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _BACKENDTITLE . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xbackend_title' value='$backend_title' size='40' maxlength='100'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _BACKENDLANG . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xbackend_language' value='$backend_language' size='10' maxlength='10'>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>