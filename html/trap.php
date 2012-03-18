<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

$ip = $_SERVER['REMOTE_ADDR'];
header("Content-type: text/html; charset=utf-8");
echo '
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
          <head>
              <title>Caught You!</title>
          </head>
          <body>
              <p>You will now be banned!</p>
          </body>
      </html>
     ';
$text = 'deny from ' . $ip . "\r\n";
$file = dirname(__FILE__).'/.htaccess';
if (is_file($file) && is_writable($file)){
    if ($handle = @fopen($file, 'a')) {
        fwrite($handle, $text);
        fclose($handle);
    }
}

?>