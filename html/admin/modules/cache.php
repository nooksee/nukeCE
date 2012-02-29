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

if (!defined('ADMIN_FILE')) {
   die ("Illegal File Access");
}

define('CACHE_ADMIN', true);
global $prefix, $db, $sysconfig;

function cache_header() {
    global $admin_file, $sysconfig, $usrclearcache, $cache;
    
    $enabled = ($cache->valid) ? "<font color=\"green\">" . _CACHE_ENABLED . "</font>" : "<font color=\"red\">" . _CACHE_DISABLED . "</font> (<a href=\"$admin_file.php?op=howto_enable_cache\">" . _CACHE_HOWTOENABLE . "</a>)";
    $enabled_img = ($cache->valid) ? "<img src='images/sys/ok.png' alt='' width='10' height='10' />" : "<img src='images/sys/bad.png' alt='' width='10' height='10' />";
    $cache_num_files = $cache->count_rows();
    $last_cleared_img = ((time() - $sysconfig['cache_last_cleared']) >= 604800) ? "<img src='images/sys/bad.png' alt='' width='10' height='10' />" : "<img src='images/sys/ok.png' alt='' width='10' height='10' />";
    $clear_needed = ((time() - $sysconfig['cache_last_cleared']) >= 604800) ? "(<a href=\"$admin_file.php?op=cache_clear\"><font color=\"red\">" . _CACHE_CLEARNOW . "</font></a>)" : "";
    $last_cleared = date('F j, Y, g:i a', $sysconfig['cache_last_cleared']);
    $user_can_clear = ($usrclearcache) ? "[ <strong>" . _CACHE_YES . "</strong> | <a href=\"$admin_file.php?op=usrclearcache&amp;opt=0\">" . _CACHE_NO . "</a> ]" : "[ <a href=\"$admin_file.php?op=usrclearcache&amp;opt=1\">" . _CACHE_YES . "</a> | <strong>" . _CACHE_NO . "</strong> ]";
    $cache_good = (is_writable(NUKE_CACHE_DIR) && !ini_get('safe_mode')) ? "<font color=\"green\">" . _CACHE_GOOD . "</font>" : "<font color=\"red\">" . _CACHE_BAD . "</font>";
    $cache_good_img = (is_writable(NUKE_CACHE_DIR) && !ini_get('safe_mode')) ? "<img src='images/sys/ok.png' alt='' width='10' height='10' />" : "<img src='images/sys/bad.png' alt='' width='10' height='10' />";
    $cache_good = (ini_get('safe_mode')) ? "<font color=red>" . _CACHESAFEMODE . "</font>" : $cache_good;
    $cache_type = ($cache->type == FILE_CACHE) ? _CACHE_FILEMODE : (($cache->type == SQL_CACHE) ? _CACHE_SQLMODE : _CACHE_DISABLED);
    
    $pagetitle = _CACHE_HEADER;
    GraphicAdmin();
    OpenTable();
    echo "
          <div align=\"center\">
              <font class=\"title\">
                  <a href=\"$admin_file.php?op=cache\">
                      " . _CACHE_HEADER . "
                  </a>
              </font>
          </div>
          <br />
          <table align='center' border='0' width='70%'>
          <caption align=\"bottom\" style=\"padding: 10; font-size: 10pt\">
              <span class=\"content\">
                  [
                  <a href=\"$admin_file.php?op=cache_clear\">
                      " . _CACHE_CLEAR . "
                  </a>
                  ]
              </span>
          </caption>
              <tr>
                  <td>
                      $enabled_img
                  </td>
                  <td>
                      <i>
                          " . _CACHE_STATUS . "
                      </i>
                  </td>
                  <td>
                      $enabled
                  </td>
              </tr>
              <tr>
                  <td>
                      $enabled_img
                  </td>
                  <td>
                      <i>
                          " . _CACHE_MODE . "
                      </i>
                  </td>
                  <td>
                      $cache_type
                  </td>
              </tr>
              <tr>
                  <td>
                      $cache_good_img
                  </td>
                  <td>
                      <i>
                          " . _CACHE_DIR_STATUS . "
                      </i>
                  </td>
                  <td>
                      $cache_good
                  </td>
              </tr>
              <tr>
                  <td>
                      <img src='images/sys/null.png' alt='' width='10' height='10' />
                  </td>
                  <td>
                      <i>
                          " . _CACHE_NUM_FILES . "
                      </i>
                  </td>
                  <td>
                      $cache_num_files
                  </td>
              </tr>
              <tr>
                  <td>
                      $last_cleared_img
                  </td>
                  <td>
                      <i>
                          " . _CACHE_LAST_CLEARED . "
                      </i>
                  </td>
                  <td>
                      $last_cleared $clear_needed
                  </td>
              </tr>
              <tr>
                  <td>
                      <img src='images/sys/null.png' alt='' width='10' height='10' />
                  </td>
                  <td>
                      <i>
                          " . _CACHE_USER_CAN_CLEAR . "
                      </i>
                  </td>
                  <td>
                      $user_can_clear
                  </td>
              </tr>
          </table>
         ";
    CloseTable();
    echo "
          <br />
         ";
}

function display_main() {
   global $admin_file, $cache, $bgcolor1;

   $open = "<img src=\"images/sys/folder_open.gif\" alt=\"\" border=\"0\" name=\"folder\">";
   $closed = "<img src=\"images/sys/folder_closed.gif\" alt=\"\" border=\"0\" name=\"folder\">";

   echo "
         <script type=\"text/javascript\">
         
             var folder_closed = new Image();
             folder_closed.src = \"images/sys/folder_closed.gif\";
             var folder_open = new Image();
             folder_open.src = \"images/sys/folder_open.gif\";

             function show(name, count) {
                 i=1;
                 while(i<=count){
                     if(document.getElementById(name + i).style.display == \"none\") {
                         document.getElementById(name + i).style.display = \"\";
                     } else {
                         document.getElementById(name + i).style.display = \"none\";
                     }
                 i++;
                 }

                 var img = document['folder-' + name].src;
                 if (img == folder_open.src) {
                     document['folder-' + name].src = folder_closed.src;
                 } else {
                     document['folder-' + name].src = folder_open.src;
                 }
             }
         </script>
        ";

    OpenTable();
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table align=\"center\" width=\"96%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
              <tr>
                  <th colspan=\"1\" align=\"left\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\" width=\"85%\">
                      <strong>
                          " . _CACHE_FILENAME . "
                      </strong>
                  </th>
                  <th colspan=\"1\" align=\"center\" width=\"15%\" class=\"thCornerR\" nowrap=\"nowrap\">
                      <strong>
                          " . _CACHE_OPTIONS . "
                      </strong>
                  </th>
              </tr>
         ";
    
    $all_cache = $cache->saved;
    $total = count($all_cache);
    $cat_names = array_keys($all_cache);
    
    if(is_array($cat_names)) {
        foreach($cat_names as $file) {
            $img = "open";
            $num_files = $cache->count_rows($file);
            echo  "
                   <tr bgcolor=\"".$bgcolor1."\" valign=\"middle\">
                       <td width='40%' align='left' colspan=\"1\">
                           <a id=\"$file\" href=\"javascript:show('$file', '$num_files');\">
                               <img name='folder-$file' src='images/sys/folder_$img.gif' alt='' border='0'>
                           </a>
                           <strong>
                               " . $file . " ($num_files)
                           </strong>
                       </td>
                       <td width='15%' align='center' colspan=\"1\">
                           <span class=\"content\">
                               <a href=\"$admin_file.php?op=cache_delete&amp;name=$file\">
                                   <img src=\"images/delete.gif\" alt=\""._CACHE_DELETE."\" title=\""._CACHE_DELETE."\" border=\"0\" width=\"17\" height=\"17\">
                               </a> 
                               <img src=\"images/sys/forbidden.png\" border=\"0\" width=\"16\" height=\"16\">
                           </span>
                       </td>
                   </tr>
                  ";
            $subNames = array_keys($all_cache[$file]);
            $id = 1;
            foreach($subNames as $subFile) {
                echo  "
                       <tr bgcolor=\"".$bgcolor1."\" valign=\"middle\" id=\"$file$id\">
                           <td width='40%' align='left'>
                               <a href=\"$admin_file.php?op=cache_view&amp;file=$subFile&amp;name=$file\">
                                   &nbsp;&nbsp;&nbsp;
                                   <img src='images/sys/php.png' alt=\""._CACHE_VIEW."\" border='0'>
                               </a>
                               $subFile
                           </td>
                           <td width='15%' align='center'>
                               <span class=\"content\">
                                   <a href=\"$admin_file.php?op=cache_delete&amp;file=$subFile&amp;name=$file\">
                                       <img src=\"images/delete.gif\" alt=\""._CACHE_DELETE."\" title=\""._CACHE_DELETE."\" border=\"0\" width=\"17\" height=\"17\">
                                   </a> 
                                   <a href=\"$admin_file.php?op=cache_view&amp;file=$subFile&amp;name=$file\">
                                       <img src=\"images/view.gif\" alt=\""._CACHE_VIEW."\" title=\""._CACHE_VIEW."\" border=\"0\" width=\"17\" height=\"17\">
                                   </a>
                               </span>
                           </td>
                       </tr>
                      ";
                $id++;
            }
        }
    }
    echo  "
           </table>
           <span class=\"gen\">
           <br />
           </span>
          ";
    CloseTable();
}

function delete_cache($file, $name) {
    global $admin_file, $cache;
    OpenTable();
    if (!empty($file) && !empty($name)) {
        if ($cache->delete($file, $name)) {
            echo "
                  <div align\"center\">
                      <strong>
                          " . _CACHE_FILE_DELETE_SUCC . "
                      </strong>
                      <br />
                      <br />
                 ";
            redirect("$admin_file.php?op=cache");
            echo "
                  </div>
                 ";
        } else {
            echo "
                  <div align\"center\">
                      <strong>
                          " . _CACHE_FILE_DELETE_FAIL . "
                      </strong>
                      <br />
                      <br />
                 ";
            redirect("$admin_file.php?op=cache");
            echo "
                  </div>
                 ";
        }
    } elseif (empty($file) && (!empty($name))) {
        if ($cache->delete('', $name)) {
            echo "
                  <div align\"center\">
                      <strong>
                          " . _CACHE_CAT_DELETE_SUCC . "
                      </strong>
                      <br />
                      <br />
                 ";
            redirect("$admin_file.php?op=cache");
            echo "
                  </div>
                 ";
        } else {
            echo "
                  <div align\"center\">
                      <strong>
                          " . _CACHE_CAT_DELETE_FAIL . "
                      </strong>
                      <br />
                      <br />
                 ";
            redirect("$admin_file.php?op=cache");
            echo "
                  </div>
                 ";
        }
        } else {
            echo "
                  <div align\"center\">
                      <strong>
                          " . _CACHE_INVALID . "
                      </strong>
                      <br />
                      <br />
                 ";
            redirect("$admin_file.php?op=cache");
            echo "
                  </div>
                 ";
        }
    CloseTable();
}

function cache_view($file, $name) {
    global $admin_file, $cache;
    OpenTable();
    echo "
          <span class=\"gen\">
          <br />
          </span>
          <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"96%\">
          <caption align=\"bottom\" style=\"padding: 10; font-size: 10pt\">
              <span class=\"content\">
                  [
                  <a href=\"$admin_file.php?op=cache_delete&amp;file=$file&amp;name=$name\">
                      " . _CACHE_DELETE . "
                  </a>
                  ]
              </span>
          </caption>
              <tr>
                  <td style=\"width: auto; word-break: break-all;\" width='100%' align='left'>
                      <div style=\"word-wrap: break-word; width: 695px;\">
         ";
    if(is_array($cache->saved[$name][$file])) {
        $file = "<?php\n\n\$$file = array(\n".$cache->array_parse($cache->saved[$name][$file]).");\n\n?>";
    } else {
        $file = "<?php\n\n\$$file = \"" . $cache->saved[$name][$file] . "\";\n\n?>";
    }
    @highlight_string($file);
    echo "
                      </td>
                  </div>
              </tr>
          </table>
          <span class=\"gen\">
          <br />
          </span>
         ";
    CloseTable();
}

function clear_cache() {
    global $admin_file, $cache;
    OpenTable();
    if($cache->clear()) {
        echo "
              <div align\"center\">
                  <strong>
                      " . _CACHE_CLEARED_SUCC . "
                  </strong>
                  <br />
                  <br />
             ";
        redirect("$admin_file.php?op=cache");
        echo "
              </div>
             ";
    } else {
        echo "
              <div align\"center\">
                  <strong>
                      " . _CACHE_CLEARED_FAIL . "
                  </strong>
                  <br />
                  <br />
             ";
        redirect("$admin_file.php?op=cache");
        echo "
              </div>
             ";
    }
    CloseTable();
}

function usrclearcache($opt) {
    global $prefix, $db, $admin_file, $cache;
    $opt = intval($opt);
    if($opt == 1 || $opt == 0) {
        $db->sql_query("UPDATE ".$prefix."_system SET sys_value='" . $opt . "' WHERE sys_field='usrclearcache'");
        $cache->delete('sysconfig');
        OpenTable();
        echo "
              <div align\"center\">
                  <strong>
                      " . _CACHE_PREF_UPDATED_SUCC . "
                  </strong>
                  <br />
                  <br />
             ";
        redirect("$admin_file.php?op=cache");
        echo "</div>";
        CloseTable();
    } else {
        OpenTable();
        echo "
              <div align\"center\">
                  <strong>
                      " . _CACHE_INVALID . "
                  </strong>
                  <br />
                  <br />
             ";
        redirect("$admin_file.php?op=cache");
        echo "
              </div>
             ";
        CloseTable();
    }
}

function howto_enable_cache() {
    global $admin_file;
    OpenTable();
    echo "
          <div align\"center\">
              <strong>
                  " . _CACHE_ENABLE_HOW . "
              </strong>
              <br />
              <br />
         ";
    redirect("$admin_file.php?op=cache");
    echo "
          </div>
         ";
    CloseTable();
}

if (is_mod_admin()) {
    include_once(NUKE_BASE_DIR.'header.php');
    cache_header();
    
    switch ($op) {
        case 'cache_delete':
            delete_cache($_GET['file'], $_GET['name']);
        break;
    
        case 'cache_view':
            cache_view($_GET['file'], $_GET['name']);
        break;
    
        case 'cache_clear':
            clear_cache();
        break;
    
        case 'usrclearcache':
            usrclearcache($_GET['opt']);
        break;
    
        case 'howto_enable_cache':
            howto_enable_cache();
        break;
    
        default:
            display_main();
        break;
    }
    include_once(NUKE_BASE_DIR.'footer.php');
    
} else {
    echo "Access Denied";
}

?>