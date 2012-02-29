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

global $prefix, $db, $sitename, $currentlang, $admin_file;

if (file_exists(NUKE_MODULES_DIR.'Site_Map/language/lang-'.$currentlang.'.php')) {
    include_once(NUKE_MODULES_DIR.'Site_Map/language/lang-'.$currentlang.'.php');
} else {
    include_once(NUKE_MODULES_DIR.'Site_Map/language/lang-english.php');
}

$result = $db->sql_query("SELECT * FROM ".$prefix."_smap");
    while ($row=$db->sql_fetchrow($result))
    {
        $nametask = $row["name"];
        $value = $row["value"];
        $conf[$nametask]=$value;
    }
    
$xml = $conf["xml"];
$ndown = $conf["ndown"];
$nnews = $conf["nnews"];
$nrev = $conf["nrev"];
$ntopics = $conf["ntopics"];
$nuser = $conf["nuser"];

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _SITEMAPSET . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _XMLCREATE . "?
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xml', $xml);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NDOWN . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='ndown' value='$ndown' size='6' maxlength='6'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NNEWS . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='nnews' value='$nnews' size='6' maxlength='6'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NREV . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='nrev' value='$nrev' size='6' maxlength='6'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NTOPICS . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='ntopics' value='$ntopics' size='6' maxlength='6'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NUSER . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='nuser' value='$nuser' size='6' maxlength='6'>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>