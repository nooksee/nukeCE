<?php

/**************************************************************************/
/* PHP-EVOLVED: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.php-evolved.com                                             */
/*                                                                        */
/* All PHP-EVOLVED code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
echo '<html>
    <head>
    <title>Path Help</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <style type="text/css">
        h1.myclass {font-size: 20pt; font-weight: bold; color: blue; text-align: center}
        h1.myclass2 {font-size: 11pt; font-style: normal; text-align: left}
    </style>
    </head>';

echo'<body>
        <table border="0" width="100%">
            <tr><td>
                <h1 class="myclass">
                    Path Help
                </h1>
            </td></tr>
        </table>';

echo '    <table border="0" width="100%">
            <tr><td>
                <h1 class="myclass2">
                Your path is going to start with modules/Downloads/files/ then finish with the file name<br />For example: modules/Downloads/files/test.zip is valid
                </h1>
            </td></tr>';
echo '    </table>';


echo '        </table>
    </body>
</html>';

?>