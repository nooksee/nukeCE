<?

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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$current_theme = basename(dirname(__FILE__));
$param_names = array('Link 1 URL:', 'Link 1 Text:', 'Link 2 URL:', 'Link 2 Text:', 'Link 3 URL:', 'Link 3 Text:', 'Link 4 URL:', 'Link 4 Text:', 'BG Color 1:', 'BG Color 2:', 'BG Color 3:', 'BG Color 4:', 'Text Color 1:', 'Text Color 2:');
$params = array('link1', 'link1text', 'link2', 'link2text', 'link3', 'link3text', 'link4', 'link4text', 'bgcolor1', 'bgcolor2', 'bgcolor3', 'bgcolor4', 'textcolor1', 'textcolor2');
$default = array('index.php', 'Home', 'modules.php?name=Forums', 'Forums', 'modules.php?name=Downloads', 'Downloads', 'modules.php?name=Your_Account', 'Account', '#e5e5e5', '#D1D7DC', '#DEE3E7', '#EFEFEF', '#000000', '#000000');
$ThemeInfo = LoadThemeInfo($current_theme);

?>