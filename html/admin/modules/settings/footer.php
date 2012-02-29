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

global $foot1, $foot2, $foot3;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _FOOTERMSG . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _FOOTERLINE1 . ":
                  </td>
                  <td colspan=\"3\">
                      <textarea name='xfoot1' cols='50' rows='5'>" . $foot1 . "</textarea>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _FOOTERLINE2 . ":
                  </td>
                  <td colspan=\"3\">
                      <textarea name='xfoot2' cols='50' rows='5'>" . $foot2 . "</textarea>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _FOOTERLINE3 . ":
                  </td>
                  <td colspan=\"3\">
                      <textarea name='xfoot3' cols='50' rows='5'>" . $foot3 . "</textarea>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>