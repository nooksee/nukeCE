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

if (!defined('IN_PHPBB'))
{
    die('Hacking attempt');
}

// Get provided Languages from an Module
function get_module_languages($short_name)
{
    global $phpbb_root_path;

    $language_directory = $phpbb_root_path . 'modules/language';
    $languages = array();

    if (!file_exists($language_directory))
    {
        message_die(GENERAL_ERROR, 'Unable to find Language Directory');
    }

    if( $dir = @opendir($language_directory) )
    {
        while( $sub_dir = @readdir($dir) )
        {
            if( !is_file($language_directory . '/' . $sub_dir) && !is_link($language_directory . '/' . $sub_dir) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
            {
                if (strstr($sub_dir, 'lang_'))
                {
                    $languages[] = trim($sub_dir);
                }
            }
        }
        
        closedir($dir);
    }

    $found_languages = array();

    // Ok, go through all Languages and generate the Language Array
    for ($i = 0; $i < count($languages); $i++)
    {
        $language_file = $phpbb_root_path . 'modules/language/' . $languages[$i] . '/lang_modules.php';
        $file_content = implode('', file($language_file));
        if (trim($file_content) != '')
        {
            // Get Content and find out if this Module is there
            if ((preg_match("/.*?\/\/[ ]\[" . preg_quote($short_name) . "\]./si", $file_content)) && (preg_match("/.*?\/\/[ ]\[\/" . preg_quote($short_name) . "\]./si", $file_content)) )
            {
                $found_languages[] = str_replace('lang_', '', $languages[$i]);
            }
        }
    }

    return ($found_languages);
}

// Get Languages available on this system
function get_all_installed_languages()
{
    global $phpbb_root_path;

    $language_directory = $phpbb_root_path . 'modules/language';
    $languages = array();

    if (!file_exists($language_directory))
    {
        message_die(GENERAL_ERROR, 'Unable to find Language Directory');
    }

    if( $dir = @opendir($language_directory) )
    {
        while( $sub_dir = @readdir($dir) )
        {
            if( !is_file($language_directory . '/' . $sub_dir) && !is_link($language_directory . '/' . $sub_dir) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
            {
                if (strstr($sub_dir, 'lang_'))
                {
                    $languages[] = trim($sub_dir);
                }
            }
        }
        
        closedir($dir);
    }

    $found_languages = array();

    // Ok, go through all Languages and generate the Language Array
    for ($i = 0; $i < count($languages); $i++)
    {
        $language_file = $phpbb_root_path . 'modules/language/' . $languages[$i] . '/lang_modules.php';
        if (file_exists($language_file))
        {
            $found_languages[] = $languages[$i]; 
        }
    }

    return ($found_languages);
}

?>