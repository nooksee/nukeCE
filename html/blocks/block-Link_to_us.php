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

//Change to your image
//The image should be around 88x31
$img_url = 'images/powered/minilogo.gif';

global $nukeurl, $sitename;

if(substr($img_url,0,4) == "http") {
    $img_link = $img_url;
} else if(substr($img_url,0,1) == "/"){
    $img_link = $nukeurl . $img_url;
} else {
    $img_link = $nukeurl ."/". $img_url;
}

echo '
      <script language="JavaScript" type="text/javascript">
          function select_text()
          {
              var content=eval("document.form1.linktous");
              content.focus();
              content.select();
          }
      </script>
     ';

$content = "
            <br />
            <div align=\"center\">
                <a href=\"".$nukeurl."\">
                    <img src=\"".$img_url."\" alt=\"".$sitename."\" title=\"".$sitename."\" border=\"0\" />
                </a>
                <br />
                <br />
                <form action=\"\" name=\"form1\" method=\"post\">
                    <textarea name=\"linktous\" cols=\"15\" rows=\"4\" readonly=\"readonly\" onClick=\"select_text();\">&lt;a href=&quot;".$nukeurl."&quot;&gt;&lt;img src=&quot;".$img_link."&quot; alt=&quot;".$sitename."&quot; title=&quot;".$sitename."&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;</textarea>
                </form>
               <font class=\"content\">
                   "._CLICKON."
               </font>
            </div>
           ";

?>