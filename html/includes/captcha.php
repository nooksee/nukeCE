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

if(defined('NUKE_CE')) return;
define('NO_DISABLE', true);

define('ROOT', dirname(dirname(__FILE__)) . '/');
require_once(ROOT.'mainfile.php');
//error_reporting(0);
require_once(NUKE_CLASSES_DIR.'class.php-captcha.php');
define('FONTS', NUKE_INCLUDE_DIR.'fonts/');

$aFonts = array('fonts/VeraBd.ttf', 'fonts/VeraIt.ttf', 'fonts/Vera.ttf');
$size = (isset($_GET['size'])) ? $_GET['size'] : 'normal';

switch ($size) {
    case 'normal':
        $width = 77;
        $height = 20;
        $length = 5;
    break;
    case 'large':
        $width = 77;
        $height = 20;
        $length = 5;
    break;
    case 'small':
        $width = 77;
        $height = 20;
        $length = 5;
    break;
}

$file = (isset($_GET['file'])) ? $_GET['file'] : '';
//Look for invalid crap
if (preg_match("/[^\w_\-]/i",$file)) {
    die();
}

if (!is_array($aFonts)) {
    die('Fonts Not Found');
}
global $capfile;
$oVisualCaptcha = new PhpCaptcha($aFonts, $width, $height);
$oVisualCaptcha->SetCharSet('a-z,A-Z,0-9');
$oVisualCaptcha->SetNumChars($length);
if (!empty($file) && $file != 'default') {
    if (file_exists(dirname(__FILE__).'/captcha/'.$file.'.jpg')) {
        $oVisualCaptcha->SetBackgroundImages('captcha/'.$file.'.jpg');
    }
} else if (!empty($capfile) && $file != 'default') {
    if (file_exists(dirname(__FILE__).'/captcha/'.$capfile.'.jpg')) {
        $oVisualCaptcha->SetBackgroundImages('captcha/'.$capfile.'.jpg');
    }
}

$oVisualCaptcha->Create();

?>