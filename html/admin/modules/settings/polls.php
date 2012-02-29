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

if (file_exists(NUKE_MODULES_DIR.'Surveys/admin/language/lang-'.$currentlang.'.php')) {
    include_once(NUKE_MODULES_DIR.'Surveys/admin/language/lang-'.$currentlang.'.php');
} else {
    include_once(NUKE_MODULES_DIR.'Surveys/language/lang-english.php');
}

global $admin_file, $db, $prefix, $sysconfig;

// Fetch random poll
$make_random = intval($sysconfig['poll_random']);

// Fetch number of days in between voting per user
$number_of_days = intval($sysconfig['poll_days']);

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _POLL_OPTIONS . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _POLLRANDOM . "?
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xmake_random', $make_random);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _POLLDAYS . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xnumber_of_days' value='$number_of_days' size='2' maxlength='2'>
                  </td>
              </tr>
              <tr>
                  <td>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>