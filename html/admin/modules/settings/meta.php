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

global $prefix, $db, $admdata;

function Get_Meta_Array() {
    global $prefix, $db;
    
    $sql = 'SELECT meta_property, meta_content FROM '.$prefix.'_meta';
    $result = $db->sql_query($sql);
    $i=0;
    while(list($meta_property, $meta_content) = $db->sql_fetchrow($result)) {
        $metatags[$i] = array();
        $metatags[$i]['meta_property'] = $meta_property;
        $metatags[$i]['meta_content'] = $meta_content;
        $i++;
    }
    $db->sql_freeresult($result);
    
    return $metatags;
}

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _METACONFIG . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
     ";

$metatags = Get_Meta_Array();
for($i=0, $maxi=count($metatags);$i<$maxi;$i++) {
    $metatag = $metatags[$i];
    echo "
          <tr>
              <td>
                  " . $metatag['meta_property'] . ":
              </td>
              <td colspan=\"3\">
                  <input type='text' name='x" . $metatag['meta_property'] . "' value='".$metatag['meta_content']."' size='40'>
                  <a href='" . $admin_file . ".php?op=ConfigSave&amp;sub=11&amp;act=delete&amp;meta=" . $metatag['meta_property'] . "'>
                      <img src='images/sys/delete.png' alt='' border='0' align='middle'>
                  </a>
              </td>
          </tr>
         ";
}

echo "
              <tr>
                  <td>
                      <input type='text' name='new_name' value='' size='15'>
                  </td>
                  <td>
                      <input type='text' name='new_value' value='' size='40'>
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";
        
?>