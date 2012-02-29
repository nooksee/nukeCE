<?php

/********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ================================================================             */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion */
/********************************************************************************/

if(!defined('IN_YA')) {
    exit('Access Denied');
}

if (is_active("Private_Messages") AND ($username == $cookie[1]) AND ($usrinfo['user_password'] == $cookie[2])) {
    $ya_memname = htmlspecialchars($username);
    list($uid) = $db->sql_fetchrow($db->sql_query("SELECT user_id FROM ".$user_prefix."_users WHERE username='$ya_memname'"));
    $uid = intval($uid);
    $ya_newpms = $db->sql_numrows($db->sql_query("SELECT privmsgs_to_userid FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND (privmsgs_type='1' OR privmsgs_type='5')"));
    $ya_savpms = $db->sql_numrows($db->sql_query("SELECT privmsgs_to_userid FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND privmsgs_type='3'"));
    $ya_oldpms = $db->sql_numrows($db->sql_query("SELECT privmsgs_to_userid FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND privmsgs_type='0'"));
    $ya_outpms = $db->sql_numrows($db->sql_query("SELECT privmsgs_from_userid FROM ".$prefix."_bbprivmsgs WHERE privmsgs_from_userid='$uid' AND privmsgs_type='1'"));
    $ya_newpms = intval($ya_newpms);
    $ya_oldpms = intval($ya_oldpms);
    $ya_savpms = intval($ya_savpms);
    $ya_totpms = $ya_newpms + $ya_oldpms + $ya_savpms;
    $bbconfig = $board_config;
    $bbstyle = $bbconfig['default_style'];
    $sql = "SELECT template_name FROM ".$prefix."_bbthemes WHERE themes_id='$bbstyle'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $bbtheme = $row['template_name'];

    //escudero: modification to get the theme FROM nukemods
    if (file_exists("./themes/$ThemeSel/forums/images/whosonline.gif")) {
        $imagedir = "./themes/$ThemeSel/forums/images";
    } else {
        $imagedir = "./modules/Forums/templates/$bbtheme/images";
    }

    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"6\" border=\"0\" align=\"center\">
              <tr>
                  <th class=\"thHead\" colspan=\"14\">"._YAMESSAGES."</th>
              </tr>
              <tr bgcolor=\"".$bgcolor1."\">
                  <td height=\"1\" colspan=\"14\">
                      <img src=\"modules/Forums/templates/subSilver/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" />
                  </td>
              </tr>
              <tr align=\"center\" bgcolor=\"".$bgcolor1."\">
                  <td align=\"left\">
                      <a href=\"modules.php?name=Private_Messages\">
                          <img src=\"$imagedir/msg_inbox.gif\" style=\"border:none;\" alt=\""._YAPM."\">
                      </a>
                  </td>
                  <td valign=\"middle\">
                      <a href=\"modules.php?name=Private_Messages\">
                          <strong>
                              "._YAPM.":&nbsp;$ya_totpms
                          </strong>
                      </a>
                      <br />
                  </td>
                  <td width=\"25%\">
                  &nbsp;
                  </td>
                  <td >
                      <a href=\"modules.php?name=Private_Messages\">
                          <img src=\"$imagedir/msg_inbox.gif\" style=\"border:none;\" alt=\""._YAUNREAD."\">
                      </a>
                  </td>
                  <td valign=\"middle\">
                      <a href=\"modules.php?name=Private_Messages\">
                          <strong>
                              "._YAUNREAD.":&nbsp;$ya_newpms
                          </strong>
                      </a>
                  </td>
                  <td width=\"25%\">
                  &nbsp;
                  </td>
                  <td>
                      <a href=\"modules.php?name=Private_Messages\">
                          <img src=\"$imagedir/msg_inbox.gif\" style=\"border:none;\" alt=\""._YAREAD."\">
                      </a>
                  </td>
                  <td align=\"middle\">
                      <a href=\"modules.php?name=Private_Messages\">
                          <strong>
                              "._YAREAD.":&nbsp;$ya_oldpms
                          </strong>
                      </a>
                  </td>
                  <td width=\"25%\">
                  &nbsp;
                  </td>
                  <td>
                      <a href=\"modules.php?name=Private_Messages&amp;file=index&amp;folder=savebox\">
                          <img src=\"$imagedir/msg_savebox.gif\" style=\"border:none;\" alt=\""._YASAVED."\">
                      </a>
                  </td>
                  <td align=\"middle\">
                      <a href=\"modules.php?name=Private_Messages&amp;file=index&amp;folder=savebox\">
                          <strong>
                              "._YASAVED.":&nbsp;$ya_savpms
                          </strong>
                      </a>
                  </td>
                  <td width=\"25%\">
                  &nbsp;
                  </td>
                  <td>
                      <a href=\"modules.php?name=Private_Messages&amp;file=index&amp;folder=outbox\">
                          <img src=\"$imagedir/msg_inbox.gif\" style=\"border:none;\" alt=\""._YAOUTBOX."\">
                      </a>
                  </td>
                  <td valign=\"middle\">
                      <a href=\"modules.php?name=Private_Messages&amp;file=index&amp;folder=outbox\">
                          <strong>
                              "._YAOUTBOX.":&nbsp;$ya_outpms
                          </strong>
                      </a>
                  </td>
              </tr>
              <tr bgcolor=\"".$bgcolor1."\">
                  <td height=\"1\" colspan=\"14\">
                      <img src=\"modules/Forums/templates/subSilver/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" />
                  </td>
              </tr>
          </table>
          <br clear=\"all\" />
         ";
}

?>