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

global $notify, $notify_email, $notify_subject, $notify_message, $notify_from;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _MAIL2ADMIN . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _NOTIFYSUBMISSION . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xnotify', $notify);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _EMAIL2SENDMSG . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xnotify_email' value='$notify_email' size='30' maxlength='100'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _EMAILSUBJECT . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xnotify_subject' value='$notify_subject' size='40' maxlength='100'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _EMAILMSG . ":
                  </td>
                  <td colspan=\"3\">
                      <textarea name='xnotify_message' cols='40' rows='8'>$notify_message</textarea>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _EMAILFROM . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xnotify_from' value='$notify_from' size='15' maxlength='25'>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>