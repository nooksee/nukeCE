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

global $moderate, $commentlimit, $anonymous;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _COMMENTSOPT . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _MODTYPE . ":
                  </td>
                  <td colspan=\"3\">
                      <select name='xmoderate'>
     ";
if ($moderate==1) {
    $sel1 = "selected";
    $sel2 = "";
    $sel3 = "";
} elseif ($moderate==2) {
    $sel1 = "";
    $sel2 = "selected";
    $sel3 = "";
} elseif ($moderate==0) {
    $sel1 = "";
    $sel2 = "";
    $sel3 = "selected";
}
echo "
                          <option name='xmoderate' value='1' $sel1>
                              " . _MODADMIN . "
                          </option>
                          <option name='xmoderate' value='2' $sel2>
                              " . _MODUSERS . "
                          </option>
                          <option name='xmoderate' value='0' $sel3>
                              " . _NOMOD . "
                          </option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _COMMENTSLIMIT . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xcommentlimit' value='$commentlimit' size='11' maxlength='10'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ANONYMOUSNAME . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xanonymous' value='$anonymous'>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>