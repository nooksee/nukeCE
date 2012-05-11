<?php

/************************************************************************/
/* PHP-NUKE EVOLVED: Web Portal System                                  */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2008 by Kevin Atwood                                   */
/* http://www.nuke-evolved.com                                          */
/*                                                                      */
/* All PHP-Nuke code is released under the GNU General Public License.  */
/* See COPYRIGHT.txt and LICENSE.txt.                                   */
/************************************************************************/

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$mod_name = basename(dirname(__FILE__));
get_lang($mod_name);
    /* User Settings */
    $debug = "false";

    /* Change Smiles Path Here */
    $jsmiles = (file_exists("themes/$ThemeSel/images/smiles/icon_smile.gif")) ? "themes/$ThemeSel/images/smiles/" : 'images/emoticons/';

    function ADVT_stripslashes($text ) {
        if (get_magic_quotes_gpc() == 1 ) {
            return(stripslashes($text ));
        }
        return($text );
    }
    function journalfoot() {
        include_once(NUKE_BASE_DIR.'footer.php');
    }
    function startjournal($sitename, $user) {
        global $mod_name;
        $user = check_html($user, "nohtml");
        $sitename = check_html($sitename, "nohtml");
        if (is_user()) {
            $j_user1 = "<center>[ <a href=\"modules.php?name=$mod_name\">"._JOURNALDIR."</a> | <a href=\"modules.php?name=$mod_name&amp;file=edit\">"._YOURJOURNAL."</a> ]</center>";
            $j_user2 = "";
        } else {
            $j_user1 = "<center>[ <a href=\"modules.php?name=$mod_name\">"._JOURNALDIR."</a> | <a href=\"modules.php?name=Your_Account&amp;op=new_user\">"._CREATEACCOUNT."</a> ]</center>";
            $j_user2 = "<br /><center><span class=\"tiny\">"._MEMBERSCAN."</span></center>";
        }
        title($sitename.': '._USERSJOURNAL);
        if (is_user()) {
            include(NUKE_MODULES_DIR.'Your_Account/navbar.php');
            OpenTable();
            nav();
            CloseTable();
            echo "<br />";
        }
        OpenTable();
        echo "<center><img src=\"modules/$mod_name/images/bgimage.gif\" alt=\""._USERSJOURNAL."\" /><br /><span class=title><strong>"._USERSJOURNAL."</strong></span></center>";
        echo $j_user1;
        echo $j_user2;
        CloseTable();
    }

?>