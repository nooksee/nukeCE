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

global $httpref, $httprefmax, $pollcomm, $articlecomm, $minpass, $my_headlines, $user_news, $adminssl, $queries_count, $use_colors, $lock_modules, $banners, $lazy_tap, $wysiwyg, $img_resize, $collapse;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _MISCOPT . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
              <tr>
                  <td>
                      " . _ACTIVATEHTTPREF . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xhttpref', $httpref);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      ". _MAXREF . ":
                  </td>
                  <td colspan=\"3\">
                      <input type='text' name='xhttprefmax' value='$httprefmax' size='2' maxlength='4'>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _COMMENTSPOLLS . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xpollcomm', $pollcomm);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _COMMENTSARTICLES . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xarticlecomm', $articlecomm);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _MYHEADLINES . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xmy_headlines', $my_headlines);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _USERSHOMENUM . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xuser_news', $user_news);
echo "
                  </td>
              </tr>              
              <tr>
                  <td>
                      " . _SSLADMIN . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xadminssl', $adminssl);
echo "
                      &nbsp;
                      <span class='tiny'>
                          [ 
                          " . _SSLWARNING . " 
                          ]
                      </span>
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _QUERIESCOUNT . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xqueries_count', $queries_count);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _COLORTOGGLE . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xuse_colors', $use_colors);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _LOCK_MODULES . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xlock_modules', $lock_modules);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _ACTBANNERS . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xbanners', $banners);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      "._TEXT_AREA.":
                  </td>
                  <td colspan=\"3\">
     ";
$admin_wysiwyg = new Wysiwyg('','');
echo $admin_wysiwyg->getSelect();

if(!isset($lazy_tap) || is_null($lazy_tap) || $lazy_tap == 0) {
     $tap_off = 'checked';
} else if($lazy_tap == 1) {
     $tap_bots = 'checked';
} else if($lazy_tap == 2) {
     $tap_all = 'checked';
} else if($lazy_tap == 3) {
     $tap_admin = 'checked';
}
echo "
              <tr>
                  <td>
                      "._LAZY_TAP.":
                  </td>
                  <td colspan=\"3\">
                      <input type='radio' name='xlazytap' value='0' $tap_off>"._LAZY_TAP_OFF." 
                      <input type='radio' name='xlazytap' value='1' $tap_bots>"._LAZY_TAP_BOT." 
                      <input type='radio' name='xlazytap' value='2' $tap_all>"._LAZY_TAP_EVERYONE." 
                      <input type='radio' name='xlazytap' value='3' $tap_admin>"._LAZY_TAP_ADMIN."
                      <a href=\"includes/help/lazytaphelp.php\" rel='4' class='newWindow'>
                          <img src=\"images/icon_help.gif\" alt=\""._LAZY_TAP_HELP."\" title=\""._LAZY_TAP_HELP."\" border=\"0\" width=\"13\" height=\"13\">
                      </a>                          
                  </td>
              </tr>
              <tr>
                  <td>
                      "._IMG_RESIZE."?
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('ximg_resize', $img_resize);
echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _COLLAPSE . "
                  </td>
                  <td colspan=\"3\">
     ";
                      echo yesno_option('xcollapse', $collapse);
echo "
                  </td>
              </tr>
          </table>
      </fieldset>
      <br />
     ";

?>