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
   die ('Illegal File Access');
}

global $prefix, $db, $admdata;

$log = ($_GET['log']) ? $_GET['log'] : die("Invalid Operation");

if (is_mod_admin()) {

    @include_once(NUKE_ADMIN_DIR.'language/custom/lang-'.$currentlang.'.php');

    function view_log($file) {
        global $admin_file, $bgcolor2;
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php\">
                          "._ADMIN_LOG."
                      </a>
                  </font>
              </div>
              <br />
             ";


        $filename = NUKE_INCLUDE_DIR."log/" . $file . ".log";
        
        if(!is_file($filename)) {
            ErrorReturn(_ADMIN_LOG_ERRFND);
            return;
        }
        if(filesize($filename) == 0) {
            ErrorReturn(_TRACKER_EMPTY);
            return;
        }
        if($handle = @fopen($filename,"r")) {
            $content = @fread($handle, filesize($filename));
            @fclose($handle);
        }
        
        $content = nl2br($content);
        
        echo "
              <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
                  <tr bgcolor=\"".$bgcolor2."\">
                  <tr>
                      <td bgcolor=\"".$bgcolor2."\" width=\"100%\" align=\"center\">
                          <span class=\"content\">
                              <a href=\"".$admin_file.".php?op=adminlog_clear&log=" . $file . "\">
                                  " . _TRACKER_CLEAR . "
                              </a>
                          </span>
                      </td>
                  </tr>
              </table>
              <br />
              <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"90%\">
                  <tr>
                      <td style=\"width: auto; word-break: break-all;\" width=\"100%\" align=\"left\">
                          $content
                      </td>
                  </tr>
              </table>
             ";
    }
    
    function log_ack($file) {
        global $db, $prefix, $admin_file, $cache;
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php\">
                          "._ADMIN_LOG."
                      </a>
                  </font>
              </div>
              <br />
             ";
        
        $filename = NUKE_INCLUDE_DIR."log/" . $file . ".log";
        
        if(!is_file($filename)) {
            echo "
                  <br />
                 ";
            ErrorReturn(_ADMIN_LOG_ERRFND);
        } else {
            if(!$handle = @fopen($filename,"r")) {
                echo _TRACKER_ERR_OPEN;
            } else {
                $content = @fread($handle, filesize($filename));
                @fclose($handle);
                $file_num = substr_count($content, "\n");
                $sql_log = "UPDATE ".$prefix."_config SET " . $file . "_log_lines='".$file_num."'";
                if($db->sql_query($sql_log)) {
                    echo _TRACKER_UP;
                } else {
                    echo _TRACKER_ERR_UP;
                }
                $cache->delete('nukeconfig');
            }
        }
        redirect($admin_file . '.php');
        return;
    }

    function log_clear($file) {
        global $db, $prefix, $admin_file, $cache;
        echo "
              <div align=\"center\">
                  <font class=\"title\">
                      <a href=\"$admin_file.php\">
                          "._ADMIN_LOG."
                      </a>
                  </font>
              </div>
              <br />
              <div align=\"center\">
                  <span class=\"option\">
                      <b>
                          <em>
                              "._TRACKER_CLEARED."
                          </em>
                      </b>
                  </span>
             ";
        
        $filename = NUKE_INCLUDE_DIR."log/" . $file . ".log";
        
        if(!is_file($filename)) {
            echo "
                  <br />
                  <div align=\"center\">
                      <span class='option' style='color:red'>
                          <b>
                              <em>
                                  "._ADMIN_LOG_ERRFND."
                              </em>
                          </b>
                      </span>
                 ";
        } else {
            if(!$handle = fopen($filename,"w")) {
                echo _TRACKER_ERR_OPEN;
            } else {
                fwrite($handle, "");
                fclose($handle);
                $sql_log = "UPDATE ".$prefix."_config SET " . $file . "_log_lines='0'";
                if(!$db->sql_query($sql_log)) {
                   die(mysql_error());
                }
                $cache->delete('nukeconfig');
            }
        }
        echo "
                  <br />
                  <br />
                  "._GOBACK."
              </div>
             ";
    }

    if ($admdata['radminsuper'] == 1) {
        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
        switch ($op) {
            case "viewadminlog":
                view_log($log);
            break;
        
            case "adminlog_ack":
                log_ack($log);
            break;
        
            case "adminlog_clear":
                log_clear($log);
            break;
        }
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

} else {
    echo 'Access Denied';
}

?>