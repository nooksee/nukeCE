<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_DELETEIP;
include(NUKE_BASE_DIR."header.php");
GraphicAdmin();
echo "
      <table class=\"forumline\" width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">
          <tr>
              <th class=\"thHead\" height=\"25\" valign=\"middle\">
                  <span class=\"tableTitle\">
                      Confirm
                  </span>
              </th>
          </tr>
          <tr>
              <td class=\"row1\" align=\"center\">
                  <span class=\"gen\">
                      <br />
                      "._AB_DELETEIPS." $xIPs?
                      <form action='".$admin_file.".php' method='post'>
                          <input type='hidden' name='op' value='ABBlockedIPDeleteSave' />
                          <input type='hidden' name='xIPs' value='$xIPs' />
                          <input type='hidden' name='xop' value='$xop' />
                          <input type='hidden' name='min' value='$min' />
                          <input type='hidden' name='sip' value='$sip' />
                          <input type='hidden' name='column' value='$column' />
                          <input type='hidden' name='direction' value='$direction' />
                          <br />
                          <input type=\"submit\" value=\""._YES."\" class=\"mainoption\" />
                          <input type=\"button\" name=\"cancel\" value=\""._NO."\" class=\"liteoption\" onclick=\"window.location = '".$admin_file.".php?op=ABBlockedIP' \" />
                      </form>
                  </span>
              </td>
          </tr>
      </table>
      <br clear=\"all\" />
     ";
include(NUKE_BASE_DIR."footer.php");

?>