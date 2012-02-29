<?php

/********************************************************/
/* HTTP Authentication Using PHP CGI and Apache         */
/* CGIAdminAuth.php                                     */
/* By: Raven PHP Scripts                                */
/* http://www.ravenphpscripts.com                       */
/* Copyright (c) 2004 by Raven PHP Scripts              */
/********************************************************/

$pagetitle = _AB_SENTINEL.": "._AB_CGIAUTHSETUP;
include(NUKE_BASE_DIR."header.php");
sentinel_header();
OpenTable();
$ip_sets = abget_configs();
$rp = strtolower(str_replace('index.php', '', realpath('index.php')));
$staccess = str_replace($rp, "", $ip_sets['staccess_path']);
echo '
      <script language="JavaScript" type="text/javascript">
          function select_text() {
              var content=eval("document.form1.code");
              content.focus();
              content.select();
          }
      </script>
     ';
echo "
      <div align=\"center\">
          "._AB_SAVEIN." : 
          <strong>
              ".$ip_sets['htaccess_path']."
          </strong>
          <br />
          <br />
      </div>
      <table align='center' border='0' cellpadding='2' cellspacing='2'>
          <tr>
              <td>
                  <form action=\"\" name=\"form1\" method=\"post\">
                      <textarea name=\"code\" cols=\"44\" rows=\"19\" style=\"font-family:Courier New;\" readonly=\"readonly\" onClick=\"select_text();\">";
                          echo '#-------------------------------------------'."\n";
                          echo '# Start of Sentinel '.$admin_file.'.php Auth'."\n";
                          echo '#-------------------------------------------'."\n";
                          echo '&lt;Files '.$staccess.'&gt;'."\n";
                          echo '  deny from all'."\n";
                          echo '&lt;/Files&gt;'."\n";
                          echo "\n";
                          echo '&lt;Files '.$admin_file.'.php&gt;'."\n";
                          echo '  &lt;Limit GET POST PUT&gt;'."\n";
                          echo '    require valid-user'."\n";
                          echo '  &lt;/Limit&gt;'."\n";
                          echo '  AuthName "Restricted by Sentinel"'."\n";
                          echo '  AuthType Basic'."\n";
                          echo '  AuthUserFile '.$ab_config['staccess_path']."\n";
                          echo '&lt;/Files&gt;'."\n";
                          echo '#-------------------------------------------'."\n";
                          echo '# End of Sentinel '.$admin_file.'.php Auth'."\n";
                          echo '#-------------------------------------------</textarea>'."\n";
echo "    
                  </form>
              </td>
          </tr>
      </table>
     ";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>