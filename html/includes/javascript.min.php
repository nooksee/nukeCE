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

//Note due to all the windows.onload use womAdd('function_name()'); instead

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

########################################################
# Include for some common javascript/jquery functions  #
########################################################

echo "<script type=\"text/javascript\" src=\"includes/js/onload.js\"></script>\n";

echo "<script type=\"text/javascript\" src=\"includes/js/jquery.js\"></script>\n";

/*****[BEGIN]******************************************
 [ Mod:     Open Pop-up Window                v.1.1.0 ]
 ******************************************************/
echo "
      <script type=\"text/javascript\">
          var windowSizeArray = [ 
                                 \"width=200,height=200\",
                                 \"width=350,height=150,scrollbars=yes\",
                                 \"width=300,height=400,scrollbars=yes\",
                                 \"width=300,height=300,scrollbars=yes\",
                                 \"width=400,height=200,scrollbars=yes\",
                                 \"width=1024,height=768,scrollbars=yes\",
                                 \"width=500,height=300\",
                                 \"width=700,height=450\",
                                 \"width=600,height=400,scrollbars=yes\"
                                ];
          $(document).ready(function(){
              $('.newWindow').click(function (event){
                  var url = $(this).attr(\"href\");
                  var windowName = \"popUp\";//$(this).attr(\"name\");
                  var windowSize = windowSizeArray[  $(this).attr(\"rel\")  ];
                  window.open(url, windowName, windowSize);
                  event.preventDefault();
              });
          });
      </script>
     ";
/*****[END]********************************************
 [ Mod:     Open Pop-up Window                v.1.1.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Base:    Switch Content Script              v3.0.0 ]
 ******************************************************/
global $collapse;
if ($collapse) {
    echo "              
          <script type=\"text/javascript\">
              $(function() {
                  $('tr.parent')
                  .css(\"cursor\",\"pointer\")
                  .attr(\"title\",\"Click to expand/collapse\")
                  .click(function(){
                      $(this).siblings('.child-'+this.id).toggle();
                  });
                  $('tr[@class^=child-]').hide().children('td');
              });
          </script>
         ";
}
/*****[END]********************************************
 [ Base:    Switch Content Script              v3.0.0 ]
 ******************************************************/

//DO NOT PUT ANYTHING AFTER THIS LINE
echo "<!--[if IE]><script type=\"text/javascript\">womOn();</script><![endif]-->\n";
?>