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

global $prefix, $db, $admin_file, $currentlang;

if (is_mod_admin()) {
    if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
        $eol = "\r\n";
    } elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
        $eol = "\r";
    } else {
        $eol = "\n";
    }

    function newsletter_selection($fieldname, $current) {
        static $groups;
        if (!isset($groups)) {
            global $db, $prefix;
            $groups = array(0=>_NL_ALLUSERS, 1=>_SUBSCRIBEDUSERS, 2=>_NL_ADMINS);
            $groupsResult = $db->sql_query("SELECT group_id, group_name FROM ".$prefix."_bbgroups WHERE group_single_user=0");
            while (list($groupID, $groupName) = $db->sql_fetchrow($groupsResult, SQL_NUM)) {
                $groups[($groupID+2)] = $groupName;
            }
        }
        $tmpgroups = $groups;
        return select_box($fieldname, $current, $tmpgroups);
    }

        $subject = isset($_POST['subject']) ? Remove_Slashes($_POST['subject']) : '';
        $mailcontent = isset($_POST['mailcontent']) ? $_POST['mailcontent'] : '';
        $group = isset($_POST['group']) ? intval($_POST['group']) : 1;

        if (isset($_POST['discard'])) {
            redirect($admin_file.'.php?op=newsletter');
        } elseif (isset($_POST['send'])) {
            global $aid;
            $pagetitle = _NEWSLETTER;
            $row = $db->sql_ufetchrow('SELECT `adminmail` FROM `'.$prefix.'_config');
            $admin_email = $row[0];
            $admin_name = $aid;
            $headers = '';
            # Common Headers
            $headers .= 'From: '.$admin_name.' <'.$admin_email.'>'.$eol;
            $headers .= 'Reply-To: '.$admin_name.' <'.$admin_email.'>'.$eol;
            $headers .= 'Return-Path: '.$admin_name.' <'.$admin_email.'>'.$eol;    // these two to set reply address
            //$headers .= "Message-ID: <TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
            $headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters
            # Boundry for marking the split & Multitype Headers
            $mime_boundary=md5(time());
            $headers .= 'MIME-Version: 1.0'.$eol;
            //$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol;
            $headers .= "Content-type: text/html; charset="._CHARSET." ".$eol;
            $subject = $_POST['subject'];
            $n_group = (is_numeric($_POST['n_group'])) ? (int)$_POST['n_group'] : 0;
            if (empty($subject)) { DisplayErrorReturn(sprintf(_ERROR_NOT_SET, _SUBJECT)); }
            if (empty($mailcontent)) { DisplayErrorReturn(sprintf(_ERROR_NOT_SET, _MAILCONTENT)); }
            $mailcontent = Remove_Slashes($mailcontent);
            ignore_user_abort(true);
            if ($n_group == 0) {
                $query = "SELECT username, user_email FROM ".$user_prefix."_users WHERE user_level > 0 AND user_id > 1";
            } elseif ($n_group == 2) {
                $query = "SELECT aid, email FROM ".$prefix."_authors";
            } elseif ($n_group > 2) {
                $n_group -= 2;
                $query = "SELECT u.username, u.user_email FROM ".$user_prefix."_users u, ".$prefix."_bbuser_group g WHERE u.user_level>0 AND g.group_id=$n_group AND u.user_id = g.user_id AND user_pending=0";
            } else {
                $query = "SELECT username, user_email FROM ".$user_prefix."_users WHERE user_level > 0 AND user_id > 1 AND newsletter=1";
            }
            $mailcontent = _HELLO.",<br /><br /> $mailcontent $eol $eol"._NL_REGARDS.",<br /><br />$sitename "._STAFF."<br /><br />"._NLUNSUBSCRIBE;
            $recipients = array();
            $result = $db->sql_query($query, true);
            set_time_limit(0);
            while (list($u_name, $u_email) = $db->sql_fetchrow($result, SQL_NUM)) {
                $recipients[$u_name] = $u_email;
            }
            if (empty($recipients) || count($recipients) < 1) {
                include_once(NUKE_BASE_DIR.'header.php');
                GraphicAdmin();
                DisplayErrorReturn(_NL_RECIPS, _NEWSLETTER);
            }
             nuke_mail(nuke_mail_batch($recipients), $subject, $mailcontent, $headers, '', true);
             DisplayError(_NEWSLETTERSENT);
        }

        $pagetitle = _NEWSLETTER;
        $title = _NEWSLETTER;
        $preview = $notes = $submit = '';
        if (isset($_POST['preview'])) {
            $title .= ' '._PREVIEW;
            if (empty($subject)) { DisplayErrorReturn(sprintf(_ERROR_NOT_SET, _SUBJECT)); }
            if (empty($mailcontent)) { DisplayErrorReturn(sprintf(_ERROR_NOT_SET, _MAILCONTENT)); }
            if ($group == 0) {
                list($num_users) = $db->sql_fetchrow($db->sql_query('SELECT COUNT(*) FROM '.$user_prefix.'_users WHERE user_level > 0 AND user_id > 1'));
                $group_name = strtolower(_NL_ALLUSERS);
            } elseif ($group == 2) {
                list($num_users) = $db->sql_fetchrow($db->sql_query('SELECT COUNT(*) FROM '.$prefix.'_authors'));
                $group_name = strtolower(_NL_ADMINS);
            } elseif ($group > 2) {
                $group_id = $group-2;
                list($num_users) = $db->sql_fetchrow($db->sql_query('SELECT COUNT(*) FROM '.$prefix.'_bbuser_group WHERE group_id="'.$group_id.'" AND user_pending="0"'));
                list($group_name) = $db->sql_ufetchrow("SELECT group_name FROM ".$prefix."_bbgroups WHERE group_id=$group_id", SQL_NUM);
            } else {
                list($num_users) = $db->sql_fetchrow($db->sql_query('SELECT COUNT(*) FROM '.$user_prefix.'_users WHERE user_level > 0 AND newsletter="1"'));
                $group_name = strtolower(_SUBSCRIBEDUSERS);
            }
            $status = '';
            if ($num_users < 1) { $status = ' disabled="disabled"'; }
            if ($num_users > 500) {
                $notes = '
                          <tr>
                              <td>
                              </td>
                              <td colspan=\"3\">
                                  '._MANYUSERSNOTE.'
                              </td>
                          </tr>
                         ';
            } elseif ($num_users < 1) {
                $notes = '
                          <tr>
                              <td>
                              </td>
                              <td colspan=\"3\">
                                  '._NL_NOUSERS.'
                              </td>
                          </tr>
                         ';
            }
            $preview = "
                        <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\" width=\"100%\">
                            <tr>
                                <td>
                                    <span class=\"gen\">
                                        ".$mailcontent."
                                    </span>
                                </td>
                            </tr>
                        </table>
                       ";
            $submit = '
                       &nbsp;
                       <input type="submit" name="send" value="'._SEND.'&nbsp;'._NEWSLETTER.'" class="mainoption"'.$status.' /> &nbsp;
                       <input type="submit" name="discard" value="'._DISCARD.'" class="liteoption" />
                       <input type="hidden" name="n_group" value="'.$group.'" />
                      ';
        }

        include_once(NUKE_BASE_DIR.'header.php');
        GraphicAdmin();
        if (isset($_POST['preview'])) {
            OpenTable();
            echo '
                  <div align="center">
                      <font class="title">
                          <b>
                              '.$title.'
                          </b>
                      </font>
                  </div>
                  <br />
                  <table align="center" border="0" width="70%">
                      <tr>
                          <td>
                              <img src="images/sys/ok.png" alt="" width="10" height="10" />
                          </td>
                          <td>
                              <i>
                                  '._MUSERGROUPWILLRECEIVE.'
                              </i>
                          </td>
                          <td>
                              '.$group_name.'
                          </td>
                      </tr>
                      <tr>
                 ';
                  if ($num_users < 1) {
                      echo '
                            <td>
                                <img src="images/sys/bad.png" alt="" width="10" height="10" />
                            </td>
                           ';
                  } else {
                      echo '
                            <td>
                                <img src="images/sys/ok.png" alt="" width="10" height="10" />
                            </td>
                           ';
                  }
            echo '
                          <td>
                              <i>
                                  '._NUSERWILLRECEIVE.'
                              </i>
                          </td>
                          <td>
                              '.$num_users.'
                          </td>
                      </tr>
                  </table>
                  <br />
                 ';
            CloseTable();
            echo '<br />';
            OpenTable();
            echo '
                  '.$preview.'
                 ';
            CloseTable();
            echo '<br />';
            } 
        OpenTable();
        echo "
              <fieldset>
                  <legend>
                      <span class=\"option\">
                          "._NEWSLETTER."
                          &nbsp;
                      </span>
                  </legend>
                  <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                  <form name=\"newsletter\" action=\"".$admin_file.".php?op=newsletter\" method=\"post\">
                      <tr>
                          <td>
                              "._SUBJECT.":
                          </td>
                          <td colspan=\"3\">
                              <input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"255\" value=\"".htmlspecialchars($subject)."\" />
                          </td>
                      </tr>
                     <tr>
                          <td>
                              "._MAILCONTENT.":
                          </td>
                          <td colspan=\"3\">
             ";
                              Make_TextArea('mailcontent', $mailcontent, 'newsletter');
        echo "   
                          </td>
                      </tr>
                      <tr>
                          <td>
                              "._NL_RECIPS.":
                          </td>
                          <td colspan=\"3\">
                              ".newsletter_selection('group', $group)."
                          </td>
                      </tr>
                      ".$notes."
                      </td>
                      </tr>
                  </table>
              </fieldset>
              <br />
              <div align=\"center\">
                  <input type=\"submit\" name=\"preview\" value="._PREVIEW." class=\"mainoption\" />
                      ".$submit."
              </div>
                  </form>
              </td>
             ";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
        
} else {
    echo "Access Denied";
}

?>