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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

echo "<style type=\"text/css\">\n";
if (defined('ADMIN_FILE')) {
    echo "#l {\n";
    echo "    width: 200px;\n";
    echo "    float: left;\n";
    echo "    margin-left: 5px;\n";
    echo "}\n";
    echo "\n";
    echo "#c {\n";
    echo "    width: 200px;\n";
    echo "    float: left;\n";
    echo "    margin-left: 5px;\n";
    echo "}\n";
    echo "#d {\n";
    echo "    width: 200px;\n";
    echo "    float: left;\n";
    echo "    margin-left: 5px;\n";
    echo "}\n";
    echo "#r {\n";
    echo "    width: 200px;\n";
    echo "    float: left;\n";
    echo "    margin-left: 5px;\n";
    echo "}\n";
    echo "#new {\n";
    echo "    width: 200px;\n";
    echo "    margin-left: 5px;\n";
    echo "}\n";
    echo "\n";
    echo "div.menu {\n";
    echo "	list-style-type: none;\n";
    echo "	position: relative;\n";
    echo "	padding: 4px 4px 0 4px;\n";
    echo "	margin: 0px;\n";
    echo "	width: 200px;\n";
    echo "	font-size: 13px;\n";
    echo "	font-family: Arial, sans-serif;\n";
    echo "  border: 1px solid #ccc;\n";
    echo "}\n";
    echo "ul.sortable li {\n";
    echo "	position: relative;\n";
    echo "}\n";
    echo "\n";
    echo "ul.boxy {\n";
    echo "	list-style-type: none;\n";
    echo "	padding: 4px 4px 0 4px;\n";
    echo "	margin: 0px;\n";
    echo "	width: 10em;\n";
    echo "	font-size: 13px;\n";
    echo "	font-family: Arial, sans-serif;\n";
    echo "        border: 1px solid #ccc;\n";
    echo "}\n";
    echo "li.active {\n";
    echo "	cursor:move;\n";
    echo "	margin-bottom: 4px;\n";
    echo "	padding: 2px 2px;\n";
    echo "	border: 1px solid #ccc;\n";
    echo "}\n";
    echo "li.inactive {\n";
    echo "	cursor:move;\n";
    echo "	margin-bottom: 4px;\n";
    echo "	padding: 2px;\n";
    echo "	border: 1px solid #ccc;\n";
    echo "	background-color: #FF6C6C;\n";
    echo "}\n";
    echo "ul.boxy li {
    	cursor:move;
    	margin-bottom: 4px;
    	padding: 2px 2px;
    	border: 1px solid #ccc;
        }\n";
    echo "#left_col {
        width: 180px;
        float: center;
        margin-left: 5px;
        }\n";
    echo "#center {
        width: 180px;
        float: left;
        margin-left: 5px;
        }\n";
    echo "#right_col {
        width: 180px;
        float: left;
        margin-left: 5px;
        }\n";
    echo "#sajax1 {
        width: 180px;
        float: left;
        margin-left: 5px;
        }\n";
    echo "#sajax2 {
        width: 180px;
        float: left;
        margin-left: 5px;
        }\n";
}
echo ".textbold {
    font-weight: bold;
    }\n";
echo ".texterror {
    font-weight: bold;
    color: #FF0000;
    font-size: large;
    }\n";
echo ".texterrorcenter {
    font-weight: bold;
    color: #FF0000;
    text-align: center;
    font-size: large;
    }\n";
echo ".nuketitle {
    font-weight: bold;
    text-align: center;
    font-size: x-large;
    }\n";
echo ".switchcontent{
    border-top-width: 0;
    }\n";
echo ".switchclosecontent{
    border-top-width: 0;
    display: none;
    }";
global $more_styles;
if (!empty($more_styles)) {
    echo $more_styles;
}
echo "</style>\n";
?>