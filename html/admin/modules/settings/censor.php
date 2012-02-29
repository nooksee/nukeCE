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

global $censor_words, $censor;

$censor_words = str_replace(" ", "\n", $censor_words);

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  "._CENSOR."
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      "._CENSOR_WORDS . ":
                  </td>
                  <td colspan=\"3\">
                      <textarea name='xcensor_words' cols='40' rows='8'>$censor_words</textarea>
                  </td>
              </tr>
              <tr>
                  <td>
                      "._CENSOR_SETTINGS."
                  </td>
                  <td colspan=\"3\">
     ";
if($censor == 0) {
    echo "
          <input type='radio' name='xcensor' value='0' checked>" . _CENSOR_OFF . " 
          <input type='radio' name='xcensor' value='1'>" . _CENSOR_WHOLE . " 
          <input type='radio' name='xcensor' value='2'>" . _CENSOR_PARTIAL . "
         ";
} elseif($censor == 1) {
    echo "
          <input type='radio' name='xcensor' value='0'>" . _CENSOR_OFF . " 
          <input type='radio' name='xcensor' value='1' checked>" . _CENSOR_WHOLE . " 
          <input type='radio' name='xcensor' value='2'>" . _CENSOR_PARTIAL . "
         ";
} elseif($censor == 2) {
    echo "
          <input type='radio' name='xcensor' value='0'>" . _CENSOR_OFF . " 
          <input type='radio' name='xcensor' value='1'>" . _CENSOR_WHOLE . " 
          <input type='radio' name='xcensor' value='2' checked>" . _CENSOR_PARTIAL . "
         ";
}
echo "
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>