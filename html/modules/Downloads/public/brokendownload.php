<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2012 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

if(!defined('IN_DOWNLOADS')) {
    exit('Access Denied');
}

$lid = intval($lid);
$pagetitle = _REPORTBROKEN;
include_once(NUKE_BASE_DIR.'header.php');
menu(1);
OpenTable();
echo "
      <div align='center'>
          <span class='option'>
              <strong>"._REPORTBROKEN."</strong>
          </span>
          <br /><br /><br />
          "._THANKSBROKEN."<br />
          "._SECURITYBROKEN."<br /><br />
          <form action='modules.php?name=$module_name' method='post'>
            <input type='hidden' name='lid' value='$lid'>
            <input type='hidden' name='op' value='brokendownloadS'>
            <input type='submit' value='"._REPORTBROKEN."'>
          </form>
       </div>
      ";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>