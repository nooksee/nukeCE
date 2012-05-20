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

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

echo '
      <!-- jQuery -->
      <script src="includes/libraries/jquery.js"></script>
      <!-- Sexy -->
      <script src="includes/libraries/jquery.sexy.js"></script>
      <!-- Resize -->
      <script src="includes/libraries/jquery.resize.js"></script>
     ';

########################################################
# Include for some common javascript/jquery functions  #
########################################################
echo '
      <script src="includes/js/resize.js"></script>
      <script type="text/javascript">
          Sexy.css("includes/css/styles.css");
          Sexy.bundle(
              "includes/js/onload.js",
              "includes/js/popup.js",
              "includes/js/collapse.js"
          );
      </script>
     ';

global $more_js;
if (!empty($more_js)) {
    echo $more_js;
}

?>