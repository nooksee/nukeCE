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

if(!defined('NUKE_CE')) exit;

global $db, $user_prefix, $userinfo;

if (is_user()) {
    $newsletter = $userinfo['newsletter'];
    $user_id = $userinfo['user_id'];
    if ($newsletter) {
        $message = _NEWSLETTERBLOCKSUBSCRIBED;
        $action = '
                   <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                       <input type="submit" name="nb_unsubscribe" value="'._NEWSLETTERBLOCKUNSUBSCRIBE.'" />
                   </form>
                  ';
        if (isset($_POST['nb_unsubscribe'])) {
            $db->sql_query("UPDATE ".$user_prefix."_users SET newsletter='0' WHERE user_id='$user_id'");
            redirect($_SERVER['REQUEST_URI']);
        }
    } else {
        $message = _NEWSLETTERBLOCKNOTSUBSCRIBED;
        $action = '
                   <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                       <input type="submit" name="nb_subscribe" value="'._NEWSLETTERBLOCKSUBSCRIBE.'" />
                   </form>
                  ';
        if (isset($_POST['nb_subscribe'])) {
            $db->sql_query("UPDATE ".$user_prefix."_users SET newsletter='1' WHERE user_id='$user_id'");
            redirect($_SERVER['REQUEST_URI']);
        }
    }
} else {
    $message = _NEWSLETTERBLOCKREGISTER;
    $action = '
               <a href="modules.php?name=Your_Account&amp;op=new_user" title="'._NEWSLETTERBLOCKREGISTERNOW.'">
                   '._NEWSLETTERBLOCKREGISTERNOW.'
               </a>
              ';
}

$content = '
            <br />
            <div align="center">
            <img src="images/blocks/newsletter.png" alt="'._NEWSLETTER.'" title="'._NEWSLETTER.'" />
                <br />
                <br />
                '.$message.'
                <br />
                <br />
                '.$action.'
            </div>
           ';

?>