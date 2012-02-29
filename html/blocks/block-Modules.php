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

function moduleblock_get_active() {
    global $db, $prefix, $cache;

    $out = array();
    if(!($result = $db->sql_query("SELECT * FROM `".$prefix."_modules` WHERE `active`='1' AND `inmenu`='1' AND `cat_id`<>0 ORDER BY `cat_id`, `pos` ASC"))) {
        return '';
    }
    while ($row = $db->sql_fetchrow($result)) {
        $out[$row['cat_id']][] = $row;
    }
    $db->sql_freeresult($result);
    return $out;
}

function moduleblock_get_cats() {
    global $db, $prefix, $cache;
    static $cats;
    $use = (isset($_POST['save']) || (isset($_GET['area']) && $_GET['area'] == 'block')) ? 0 : 1;
    if (isset($cats) && is_array($cats) && $use) return $cats;

    if((($cats = $cache->load('module_cats', 'config')) === false) || !isset($cats) || !$use) {
        $cats = array();
        if(!($result = $db->sql_query("SELECT * FROM `".$prefix."_modules_cat` ORDER BY `pos` ASC"))) {
            return '';
        }
        while ($row = $db->sql_fetchrow($result)) {
            $cats[] = $row;
        }
        $db->sql_freeresult($result);
        $cache->save('module_cats', 'config', $cats);
    }

    return $cats;
}

function moduleblock_display() {
    global $moduleblock_active, $moduleblock_cats, $content, $userinfo;

    if(!is_array($moduleblock_active) || !is_array($moduleblock_cats)) return;

    //Home
    $content .= "
                 &nbsp;
                 <span style=\"font-weight: bold;\">
                     "._HOME."
                 </span>
                 <br />
                 &nbsp;
                 <strong>
                     <big>
                         &middot;
                     </big>
                 </strong>
                 <a href=\"index.php\">
                     "._HOME."
                 </a>
                 <br />
                ";

    foreach ($moduleblock_cats as $cat) {
        if(isset($cat['cid']) && is_integer(intval($cat['cid']))) {
            if (!isset($moduleblock_active[intval($cat['cid'])])) continue;
            $mod_array = $moduleblock_active[intval($cat['cid'])];
            if(is_array($mod_array)) {
                $content .= "
                             &nbsp;
                             <span style=\"font-weight: bold;\">
                                 ".$cat['name']."
                             </span>
                             <br />
                            ";
                foreach ($mod_array as $module) {
                    if ($module['view'] >= 2 && !is_mod_admin($module['name'])) {
                        if ($module['view'] == 2 && is_user()) {
                            continue;
                        } elseif ($module['view'] == 3 && !is_user()) {
                            continue;
                        } elseif ($module['view'] == 4) {
                            continue;
                        } elseif ($module['view'] == 6) {
                            $groups = (!empty($module['groups'])) ? $groups = explode('-', $module['groups']) : '';
                            $ingroup = false;
                            if(is_array($groups)){
                                foreach ($groups as $group) {
                                     if (isset($userinfo['groups'][$group])) {
                                         $ingroup = true;
                                     }
                                }
                                if (!$ingroup) continue;
                            }
                        }
                    }
                    if(substr($module['title'],0,3) == '~l~') {
                        $content .= "
                                     &nbsp;
                                     <strong>
                                         <big>
                                             &middot;
                                         </big>
                                     </strong>
                                     <a href=\"".$module['custom_title']."\">
                                         ".substr($module['title'],3)."
                                     </a>
                                     <br />";
                    } else {
                        $content .= "
                                     &nbsp;
                                     <strong>
                                         <big>
                                             &middot;
                                         </big>
                                     </strong>
                                     <a href=\"modules.php?name=".$module['title']."\">
                                         ".$module['custom_title']."
                                     </a>
                                     <br />
                                    ";
                    }
                }
            }
        }
    }
}

function moduleblock_get_inactive() {
    global $db, $prefix, $cache;

    if(!($result = $db->sql_query("SELECT * FROM `".$prefix."_modules` WHERE (`active`='0' OR `inmenu`='0' OR `cat_id`='0') AND `title` NOT LIKE '~l~%' ORDER BY `custom_title` ASC"))) {
        return '';
    }
    while ($row = $db->sql_fetchrow($result)) {
        $out[] = $row;
    }
    $db->sql_freeresult($result);
    return $out;
}

function moduleblock_get_inactive_links() {
    global $db, $prefix, $cache;
    static $links;
    $use = (isset($_POST['save']) || (isset($_GET['area']) && $_GET['area'] == 'block')) ? 0 : 1;
    if (isset($links) && is_array($links) && $use) return $links;

    if ((($links = $cache->load('module_links', 'config')) === false) || !isset($links) || !$use) {
        $links = '';
        if(!($result = $db->sql_query("SELECT * FROM `".$prefix."_modules` WHERE (`active`=0 OR `cat_id`='0') AND `title` LIKE '~l~%' ORDER BY `title` ASC"))) {
            return '';
        }
        while ($row = $db->sql_fetchrow($result)) {
            $links[] = $row;
        }
        $db->sql_freeresult($result);
        if(!empty($links) && is_array($links)) {
            $cache->save('module_links', 'config', $links);
        } else {
            $cache->delete('module_links', 'config');
        }
    }
    return $links;
}

function moduleblock_display_inactive() {
    global $moduleblock_invisible, $moduleblock_invisible_links, $content;

    $content .= "
                 <hr />
                 <div align=\"center\">
                     <select name=\"name\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">
                         <option value=''>
                             "._MORE."
                         </option>
                         <optgroup label=\""._INVISIBLEMODULES."\">
                ";
    if(is_array($moduleblock_invisible)) {
        foreach ($moduleblock_invisible as $module) {
            if ($module['active']) {
                $one = 1;
                $content .= "
                             <option value=\"modules.php?name=".$module['title']."\">
                                 ".$module['custom_title']."
                             </option>
                            ";
            } else {
                $moduleblock_inactive[] = $module;
            }
        }
        if(!$one) $content .= "
                               <option value=''>
                                   "._NONE."
                               </option>
                              ";
    } else {
        $content .= "
                     <option value=''>
                         "._NONE."
                     </option>
                    ";
    }
    $content .= "</optgroup>";

    $content .= "<optgroup label=\""._NOACTIVEMODULES."\">";
    if(is_array($moduleblock_inactive)) {
        foreach ($moduleblock_inactive as $module) {
            $content .= "
                         <option value=\"modules.php?name=".$module['title']."\">
                             ".$module['custom_title']."
                         </option>
                        ";
        }
    } else {
        $content .= "<option value=''>"._NONE."</option>";
    }
    $content .= "</optgroup>";

    $content .= "<optgroup label=\""._INACTIVE_LINKS."\">";
    if(is_array($moduleblock_invisible_links)) {
        foreach ($moduleblock_invisible_links as $link) {
            $content .= "
                         <option value=\"".$link['custom_title']."\" target=\"_blank\">
                             ".substr($link['title'],3)."
                         </option>
                        ";
        }
    } else {
        $content .= "<option value=''>"._NONE."</option>";
    }
    $content .= "</optgroup>";
    $content .= "</select>";
    $content .= "</div>";
}

global $prefix, $db, $language, $currentlang, $nukeurl, $content, $moduleblock_active, $moduleblock_cats;

$content = '';
$main_module = main_module();

$moduleblock_active = moduleblock_get_active();
$moduleblock_cats = moduleblock_get_cats();
moduleblock_display();

if(is_admin()) {
    global $moduleblock_invisible, $moduleblock_invisible_links;
    $moduleblock_invisible = moduleblock_get_inactive();
    $moduleblock_invisible_links = moduleblock_get_inactive_links();
    moduleblock_display_inactive();
}

?>