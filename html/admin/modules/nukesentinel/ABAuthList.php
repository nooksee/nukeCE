<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(is_god($_COOKIE['admin'])) {
    $pagetitle = _AB_SENTINEL.": "._AB_LISTHTTPAUTH;
    include(NUKE_BASE_DIR."header.php");
    sentinel_header();
    OpenTable();
    echo "
          <span class=\"gen\">
          <br />
          </span>
         ";
    if($ab_config['staccess_path'] > "" AND is_writable($ab_config['staccess_path'])){
        echo "
              <div align=\"center\">
                  <strong>
                      "._AB_BUILDCGI.": 
                  </strong>
                  <a href='".$admin_file.".php?op=ABCGIBuild'>
                      ".$ab_config['staccess_path']."
                  </a>
              </div>
              <br />
             ";
    }
    echo "
          <table align=\"center\" width=\"96%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <th colspan=\"1\" align=\"center\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\">
                      <strong>
                          "._AB_ADMIN."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._AB_AUTHLOGIN."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._AB_PASSWORD."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thTop\" nowrap=\"nowrap\">
                      <strong>
                          "._AB_PROTECTED."
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" class=\"thCornerR\" nowrap=\"nowrap\">
                      <strong>
                          "._FUNCTIONS."
                      </strong>
                  </th>
              </tr>
         ";
    $adminresult = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_admins` ORDER BY `aid`");
    while($adminrow = $db->sql_fetchrow($adminresult)) {
        $row_class = ($c++%2==1) ? 'row2' : 'row1';
        if($adminrow['password'] > "") { $adminrow['password'] = _AB_SET; } else { $adminrow['password'] = _AB_UNSET; }
        if($adminrow['protected']==0) { $adminrow['protected'] = "<i>"._AB_NO."</i>"; } else { $adminrow['protected'] = _AB_YES; }
        echo "
              <tr>
                  <td class=".$row_class." align=\"center\">
                      ".$adminrow['aid']."
                  </td>
                  <td class=".$row_class." align=\"center\">
                      ".$adminrow['login']."
                  </td>
                  <td class=".$row_class." align=\"center\">
                      ".$adminrow['password']."
                  </td>
                  <td class=".$row_class." align=\"center\">
                      ".$adminrow['protected']."
                  </td>
                  <td class=".$row_class." align=\"center\">
                      <a href='".$admin_file.".php?op=";
        if($adminrow['password']==_AB_SET) { echo "ABAuthResend"; } else { echo "ABAuthEdit"; }
        echo "&amp;a_aid=".$adminrow['aid']."'>";
        echo "
                          <img src='images/resend.gif' height='17' width='17' border='0' alt='"._AB_RESEND."' title='"._AB_RESEND."' />
                      </a>
                      <a href='".$admin_file.".php?op=ABAuthEdit&amp;a_aid=".$adminrow['aid']."'>
                          <img src='images/edit.gif' height='17' width='17' border='0' alt='"._EDIT."' title='"._EDIT."' />
                      </a>
                  </td>
              </tr>
             ";
    }
    echo "
          </table>
          <span class=\"gen\">
          <br />
          </span>
         ";
    CloseTable();
    include(NUKE_BASE_DIR."footer.php");
} else {
    Header("Location: ".$admin_file.".php?op=ABMain");
}

?>