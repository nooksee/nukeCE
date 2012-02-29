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

global $admin_file, $prefix, $db, $cache, $topicid, $topicname, $moderate, $commentlimit, $anonymous, $ne_config;

if (file_exists(NUKE_MODULES_DIR.'News/admin/language/lang-'.$currentlang.'.php')) {
    include_once(NUKE_MODULES_DIR.'News/admin/language/lang-'.$currentlang.'.php');
} else {
    include_once(NUKE_MODULES_DIR.'News/language/lang-english.php');
}

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _NE_NEWSCONFIG . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _NE_DISPLAYTYPE . ":
                  </td>
                  <td colspan=\"3\">
                      <select name='xcolumns'>
     ";
  if ($ne_config["columns"] == 0) {
      $ck1 = " selected";
      $ck2 = "";
  } elseif ($ne_config["columns"] == 1) {
      $ck1 = "";
      $ck2 = " selected";
  }
echo "
                          <option name='xcolumns' value='0' $ck1>
                              " . _NE_SINGLE . "
                          </option>
                          <option name='xcolumns' value='1' $ck2>
                              " . _NE_DUAL . "
                          </option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NE_READLINK . ":
                  </td>
                  <td colspan=\"3\">
                      <select name='xreadmore'>
     ";
  if ($ne_config["readmore"] == 0) {
      $ck1 = " selected";
      $ck2 = "";
  } elseif ($ne_config["readmore"] == 1) {
      $ck1 = "";
      $ck2 = " selected";
  }
echo "
                          <option name='xreadmore' value='0' $ck1>
                              " . _NE_PAGE . "
                          </option>
                          <option name='xreadmore' value='1' $ck2>
                              " . _NE_POPUP . "
                          </option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _NE_TEXTTYPE . ":
                  </td>
                  <td colspan=\"3\">
                      <select name='xtexttype'>
     ";
  if ($ne_config["texttype"] == 0) {
      $ck1 = " selected";
      $ck2 = "";
  } elseif ($ne_config["texttype"] == 1) {
      $ck1 = "";
      $ck2 = " selected";
  }
echo "
                        <option name='xtexttype' value='0' $ck1>
                            " . _NE_COMPLETE . "
                        </option>
                        <option name='xtexttype' value='1' $ck2>
                            " . _NE_TRUNCATE . "
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    " . _NE_NOTIFYAUTH . "?
                </td>
                <td colspan=\"3\">
     ";
                    echo yesno_option('xnotifyauth', $ne_config['notifyauth']);
echo "
                </td>  
            </tr>
            <tr>
                <td>
                    " . _NE_HOMETOPIC . ":
                </td>
                <td colspan=\"3\">
                    <select name='xhometopic'>
     ";
  if ($ne_config["hometopic"] == $topicid) {
      $ck1 = " selected";
      $ck2 = "";
  } elseif ($ne_config["hometopic"] == 1) {
      $ck1 = "";
      $ck2 = " selected";
  }
$result = $db->sql_query("SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext");
while(list($topicid, $topicname) = $db->sql_fetchrow($result)) {
    echo "
                      <option value='$topicid'>
         ";
    if ($ne_config["hometopic"] == $topicid)
    echo "
                          $topicname
                      </option>
                  </select>
              </td>
          </tr>
         ";
}
echo "
        <tr>
            <td>
                "._NE_HOMENUMBER.":
            </td>
            <td colspan=\"3\">
                <select name='xhomenumber'>
                    <option value='0'
     ";
  if ($ne_config["homenumber"] == 0) { echo " selected"; }
  echo "
                                     >
                        "._NE_NUKEDEFAULT."
                    </option>
       ";
      $i = 1;
  while ($i <= 10) {
      $k = $i * 5;
      echo "
                    <option value='$k'
           ";
  if ($ne_config["homenumber"] == $k) { echo " selected"; }
      echo "
                                      >
                        $k "._NE_ARTICLES."
                    </option>
           ";
      $i++;
  }
echo "
                      </select>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>