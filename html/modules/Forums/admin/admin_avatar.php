<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ===========================                                            */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
        $file = basename(__FILE__);
        $module['General']['Avatar_Management'] = "$file";
        return;
}

$root_path = "./../../../";
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

// Any mode passed?
if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
        $mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
    $target = ( isset($HTTP_GET_VARS['target']) ) ? $HTTP_GET_VARS['target'] : $HTTP_POST_VARS['target'];
}
else
{
        $mode = "";
}

// Select all avatars and usernames that have an uploaded avatar currently
$sql = "SELECT user_id, username, user_avatar FROM " . USERS_TABLE . "
    WHERE user_avatar_type = " . USER_AVATAR_UPLOAD . " AND user_avatar IS NOT NULL";

if(!$result = $db->sql_query($sql))
{
    $error = $db->sql_error();
    die("Could not get avatar information! $error[code] : $error[message]");
}

// Create a hash to keep track of all the user that is using the uploaded avatar
while ($avatar_rowset = $db->sql_fetchrow($result))
{
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    $avatar_usage[$avatar_rowset[user_avatar]] = UsernameColor($avatar_rowset[username]);
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/  
}

// This is the variable that points to the path of the avatars
// You may need to ajust this to meet your needs ;)
$real_avatar_dir = $phpbb_root_path . '../../' . $board_config['avatar_path'];

echo '<h1>Avatar Management</h1>

<p>The table below shows all currently stored uploaded avatars.  These are only the avatars which were selected to be
uploaded by users, this will not show avatars currently in the gallery. If any of the listed avatars are no longer used
by any user an option to delete it will appear.  This is a permanent delete, once you have selected to delete an
avatar it will be removed from the server and no longer be available.  You will be prompted for confirmation (requires
Javascript).</p>';

switch( $mode )
{
        case "delete":
        echo '<table cellpadding=4 cellspacing=1 border=0 class=forumline>';
        if ( unlink($real_avatar_dir.'/'.$target) )
        {
            print "<tr><td>Success, $target deleted!</td></tr><tr><td><a href=\"./admin_avatar.php\">Continue</a></td></tr></table>";
        }
        else
        {
            print "<tr><td>FAILED to delete $target!</td></tr><tr><td><a href=javascript:history.go(-1)>Go Back</a></td></tr></table>";
        }
        break;

    default:
        echo '<style>
        <!--
        td.avatar_listing    { font-family: Verdana; font-size: xx-small; text-align: center; }
        th.avatar_listing    { font-family: Verdana; font-size: x-small; text-align: center; font-weight: bold;
                      background-color: #cccccc; }
        -->
        </style>

        <table cellpadding=4 cellspacing=1 border=0 class=forumline>
        <tr>
          <th class=avatar_listing width=40%>Avatar</th>
          <th class=avatar_listing width=20%>Size</th>
          <th class=avatar_listing width=20%>Usage</th>
          <th class=avatar_listing width=20%>Edit user</th>
        </tr>';

        $alt1 = '#CCCCFF';
        $alt2 = '#EEEEEE';
        $alter = $alt2;

        // This is where we go through the avatar directory and report whether they are not
        // used or if they are used, by who.
        if ($avatar_dir = @opendir($real_avatar_dir))
        {
            while( $file = @readdir($avatar_dir) )
            {
                // This is where the script will filter out any file that doesn't match the patterns
                if( $file != "." && $file != ".." && ereg("\.(gif|jpg|jpeg|png)$",$file) )
                {
                    $stats = stat($real_avatar_dir.'/'.$file);

                    // Alternating row colows code
                    if     ($alter == $alt1) { $alter = $alt2; }
                        elseif ($alter == $alt2) { $alter = $alt1; }
                    if (isset($avatar_usage[$file]) )
                    {
                        // Since we need to supply a link with a valid sid later in html, let's build it now
                        $av_id = $avatar_usage[$file];
                        $sql = "SELECT user_id FROM " . USERS_TABLE . "
                            WHERE username = '$av_id'";
                        if(!$result = $db->sql_query($sql))
                        {
                            $error = $db->sql_error();
                            die("Could not get user information! $error[code] : $error[message]");
                        }
                        $av_uid = $db->sql_fetchrow($result);
                        $avatar_uid = $av_uid['user_id'];
                        $edit_url = append_sid("./admin_users.php?mode=edit&u=$avatar_uid");
                        // Bingo, someone is using this avatar
                        print "<tr><td class=avatar_listing bgcolor=$alter><img src=$real_avatar_dir/$file><br />$file</td>
                               <td class=avatar_listing bgcolor=$alter>$stats[7] Bytes</td>
                               <td class=avatar_listing bgcolor=$alter>$avatar_usage[$file]</td>
                               <td class=avatar_listing bgcolor=$alter>
                                                           <a href=\"$edit_url\"> Edit $avatar_usage[$file]</a></td></tr>\n";

                    }
                    else
                    {
                        // Not used, safe to display delete link for admin
                        $delete_html = append_sid("./admin_avatar.php?mode=delete&target=$file");
                        print "<tr><td class=avatar_listing bgcolor=$alter><img src=$real_avatar_dir/$file><br />$file</td>
                                                           <td class=avatar_listing bgcolor=$alter>$stats[7] Bytes</td>
                               <td class=avatar_listing bgcolor=$alter>Not Used<br /><a href=$delete_html onClick=\"if(confirm('Are you sure you want to delete: $file ?')) return true; else return false;\">Delete</a></td>
                               <td class=avatar_listing bgcolor=$alter>&nbsp;</td>
                               </tr>\n";
                    }
                }
            }
        }
        else
        {
            // If we made it to this else there was a problem trying to read the avatar directory
            // If you see this error message check this variable:
            //     $real_avatar_dir -> This may be set incorrectly for your site.
            print "Avatar directory unavailable!";
        }

        echo '</table>';
        break;
}

?>