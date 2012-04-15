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

global $prefix, $db, $admdata, $dbname, $cache;

if (is_mod_admin()) {
  
    $crlf = "\n";
    $filename = $dbname.'_'.date('d-m-Y').'.sql';
    $tablelist = (isset($_POST['tablelist'])) ? $_POST['tablelist'] : $db->sql_fetchtables($dbname);
    @set_time_limit(0);

    switch ($op) {

        case 'BackupDB':
            if (empty($tablelist)) { echo('No tables found'); }
            require_once(NUKE_CLASSES_DIR.'class.database.php');
            DB::backup($dbname, $tablelist, $filename, isset($_POST['dbstruct']), isset($_POST['dbdata']), isset($_POST['drop']), isset($_POST['gzip']));
        break;

        case 'OptimizeDB':
        case 'optimize':
        case 'CheckDB':
        case 'AnalyzeDB':
        case 'RepairDB':
            if($op == 'optimize') $op = 'OptimizeDB';
            $type = strtoupper(substr($op,0,-2));
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            OpenTable();
	    echo "
                  <div align=\"center\">
                      <font class=\"title\">
                          <a href=\"$admin_file.php?op=database\">
                              " . _DATABASE_ADMIN_HEADER . "
                          </a>
                      </font>
                  </div>
                 ";
	    CloseTable();
	    echo "<br />";
            OpenTable();
            if (count($tablelist)) {
                if ($type == 'STATUS') {
                    $query = 'SHOW TABLE STATUS FROM '.$dbname;
                } else {
                   $query = "$type TABLE $dbname.".implode(", $dbname.", $tablelist);
                }
            $result = $db->sql_query($query);
            $numfields = $db->sql_numfields($result);
            echo '
                  <table align="center" width="96%" cellpadding="3" cellspacing="1" border="0" class="forumline">
                      <caption style="padding: 10; font-size: 10pt">
                          <div align="center">
                              <span class="gen">
                                  <strong>
                                      <em>
                                          '._ACTIONRESULTS.' '.strtolower($type).'!
                                      </em>
                                  </strong>
                              </span>
                          </div>
                      <br />
                      </caption>
                      <tr>
                          <td class="catHead" colspan="4" height="28" align="center">
                              <span class="cattitle">
                                  <strong>'._DATABASE.'</strong>: '.$dbname.'
                              </span>
                          </td>
                      </tr>
                      <tr>
                 ';
              for ($j=0; $j<$numfields; $j++) {
                  echo '
                        <th colspan="1" align="left" height="25" class="thTop" nowrap="nowrap" width="100%">
                            <strong>
                                '.$db->sql_fieldname($j, $result).'
                            </strong>
                        </th>
                       ';
              }
              echo '
                    </tr>
                   ';
              while ($row = $db->sql_fetchrow($result)) {
                  $row_class = ($c++%2==1) ? 'row2' : 'row1';
                  echo '
                        <tr>
                       ';
                  for($j=0; $j<$numfields; $j++) {
                      echo '
                            <td class='.$row_class.' align="left" nowrap="nowrap">
                                '.$row[$j].'
                            </td>
                           ';
                  }
                  echo '
                        </tr>
                       ';
              }
              echo '
                    </table>
                    <span class="gen">
                    <br />
                    </span>
                   ';
            }
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        break;

        case 'RestoreDB':
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            OpenTable();
	    echo "
                  <div align=\"center\">
                      <font class=\"title\">
                          <a href=\"$admin_file.php?op=database\">
                              " . _DATABASE_ADMIN_HEADER . "
                          </a>
                      </font>
                  </div>
                 ";
	    CloseTable();
	    echo "<br />";
            require_once(NUKE_CLASSES_DIR.'class.database.php');
            $cache->clear();
            OpenTable();
            echo '
                  <div align="center">
                      <b>
                         '._DATABASE.':
                      </b> 
                      '.$dbname.'
                  <br />
                  <br />
                 ';
            if (!DB::query_file($_FILES['sqlfile'], $error)) { 
                echo($error); 
            } else {
                echo ''.sprintf(_IMPORTSUCCESS, $_FILES['sqlfile']['name']);
            }
            echo '
                  </div>
                 ';
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        break;

        case 'backup':
        case 'database':
            include_once(NUKE_BASE_DIR.'header.php');
            GraphicAdmin();
            OpenTable();
	    echo "
                  <div align=\"center\">
                      <font class=\"title\">
                          <a href=\"$admin_file.php?op=database\">
                              " . _DATABASE_ADMIN_HEADER . "
                          </a>
                      </font>
                  </div>
                 ";
	    CloseTable();
	    echo "<br />";
            OpenTable();
            echo '
                  <form method="post" name="backup" action="'.$admin_file.'.php" enctype="multipart/form-data">
                 ';
            echo "
                  <table align=\"center\" cellpadding=\"2\" cellspacing=\"10\" border=\"0\">
                      <tr>
                          <td>
                              <select name=\"tablelist[]\" size=\"20\" multiple=\"multiple\">
                 ";
            if (is_array($tablelist)) {
                foreach($tablelist as $table) {
                    echo '
                          <option value="'.$table.'">
                              '.$table.'
                          </option>
                         ';
                }
            }
            echo '
                  </select>
                  <br />
                  <br />
                  <div align="center">
                 ';
            echo "
                            <a href=\"javascript:void(0);\" onclick=\"setSelectOptions('backup', 'tablelist[]', true); return false;\">
                                <strong>
                                    "._CHECKALL."
                                </strong>
                            </a>
                            &nbsp;|&nbsp;
                            <a href=\"javascript:void(0);\" onclick=\"setSelectOptions('backup', 'tablelist[]', false); return false;\">
                                <strong>
                                    "._UNCHECKALL."
                                </strong>
                            </a>
                      </div>
                  </td>
                 ";
            echo '
                  <td nowrap="nowrap" valign="middle">
                      <label for="op">
                          <div>
                              <strong>
                                  <i>
                                      '._DBACTION.'
                                  </i>
                                  &nbsp;
                              </strong>
                          </div>
                      </label>
                      <select name="op" id="op" onchange="dbback=document.getElementById(\'backuptasks\');dbback.style.display=(this.options[this.selectedIndex].value==\'BackupDB\') ? \'\' : \'none\';">
                          <option value="AnalyzeDB">
                              '._ANALYZEDATABASE.'
                          </option>
                          <option value="BackupDB" selected="selected">
                              '._SAVEDATABASE.'
                          </option>
                          <option value="CheckDB">
                              '._CHECKDATABASE.'
                          </option>
                          <option value="OptimizeDB">
                              '._OPTIMIZEDATABASE.'
                          </option>
                          <option value="RepairDB">
                              '._REPAIRDATABASE.'
                          </option>
                      </select>
                      &nbsp;
                      <input type="submit" value="'._GO.'" />
                      <br />
                      <br />
                      <div id="backuptasks" style="float: center;">
                          <strong>
                              '._BACKUPTASKS.':&nbsp;
                          </strong>
                      <br />
                      <input type="hidden" value="1" name="dbdata" />
                      <input type="checkbox" value="1" name="dbstruct" checked="checked" />
                          '.sprintf(_INCLUDESTATEMENT, 'CREATE').'
                      <br />
                      <input type="checkbox" value="1" name="drop" checked="checked" />
                          '.sprintf(_INCLUDESTATEMENT, 'DROP').'
                      <br />
                 ';
            if (GZIPSUPPORT) {
                echo '  
                      <input type="checkbox" value="1" name="gzip" checked="checked" />
                     ' ._GZIPCOMPRESS;
            }
            echo '
                  </td>
                  <td>
                      <select name="status" size="20">
                 ';
                      $res = $db->sql_query("SHOW STATUS");
                      while ($row = $db->sql_fetchrow($res, SQL_NUM)) {
                          echo '
                                <option>
                                    '.$row[0].'&nbsp;=&nbsp;'.$row[1].'
                                </option>
                               ';
                      }
            echo "
                                  </select>
                                  <br />
                                  <br />
                                  <div align=\"center\">
                                      <strong>
                                          "._EXTENDEDSTATUS."&nbsp;
                                      </strong>
                                      <a href=\"includes/help/extendedhelp.php\" rel='4' class='newWindow'>
                                          <img src=\"images/icon_help.gif\" alt=\""._EXTENDEDHELP."\" title=\""._EXTENDEDHELP."\" border=\"0\" width=\"13\" height=\"13\">
                                      </a>
                                  </div>
                              </td>
                          </tr>
                      </table>
                  </form>
                 ";
            CloseTable();
            echo "<br />";
            OpenTable();
            echo "
                  <fieldset>
                      <legend>
                          <span class='option'>
                              "._IMPORTFILE."&nbsp;
                          </span>
                      </legend>
                      <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
                      <form method=\"post\" action=\"".$admin_file.".php\" name=\"restore\" enctype=\"multipart/form-data\">
                          <tr>
                              <td>
                              <td colspan=\"3\">
                                  <input type=\"file\" name=\"sqlfile\" size=\"80\" />
                                  <input type=\"hidden\" name=\"op\" value=\"RestoreDB\" />
                              </span>
                              </td>
                          </tr>
                          </tr>
                      </td>
                      </table>
                  </fieldset>
                  <br />
                      <div align=\"center\">
                          <input type=\"submit\" value=\"" . _IMPORTSQL . "\">
                      </div>
                      </form>
                  </td>
                 ";
            CloseTable();
            include_once(NUKE_BASE_DIR.'footer.php');
        break;
    }

} else {
    echo "Access Denied";
}

?>