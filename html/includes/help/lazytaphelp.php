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

echo '
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html>
          <head>
              <title>
                  Lazy Google Tap Help
              </title>
              <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
              <style type="text/css">
                  h1.myclass {font-size: 20pt; font-weight: bold; color: blue; text-align: center}
                  h1.myclass2 {font-size: 11pt; font-style: normal; text-align: left}
              </style>
          </head>
          <body>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass">
                              Lazy Google Tap Help
                          </h1>
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Before you begin: 
		          </h1>
                          All 
                          <font color="blue">
                              blue
                          </font> 
                          words are explained in more detail in the notes. 
                          All 
                          <font color="red">
                              red
                          </font> 
                          words are commands you need to do. 
                          All 
                          <font color="green">
                              green
                          </font>  
                          words are files you will be working with. 
                          Please read the notes before you ask questions on the forums. Many of your questions 
                          should be answered there.
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Description:
                          </h1>
                          What is Lazy Google Tap? 
                          <br />
                          <br />
                          Lazy Google Tap changes the links on your website from php to html. For example 
                          <br />
                          http://www.example.com/modules.php?name=Forums would become 
                          <br />
                          http://www.example.com/Nuke-Forums.html.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Why?:
                          </h1>
		          This allows search engine bots to better crawl your site.  This will allow more of your 
                          content to be searchable and should give you a higher ranking in certain search engines.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              How Do I Set It Up?:
                          </h1>
                          First make sure you have an .htaccess file on the root of your site.
                          <br />
                          <br />
                          If you do not, you can simply make one, name the file .htaccess.
                          <br />
                          <br />
                          If you created a blank one or your .htaccess file does not contain the following
                          RewriteEngine statements please insert it. Simply copy and paste to your .htaccess
                          file.
                          <br />
                          <br />
                          If you have the .htaccess file or you have these statements then skip the next
                          section.
                      </td>
                  </tr>
              </table>
              <br />
              <table border="1" width="100%">
                  <tr>
                      <td>
                          Options +FollowSymlinks
                          <br />
                          RewriteEngine on 
                          <br />
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-
                          <br />
                          _(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html#(.*)$ 
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9&$10=$11&$12=$13&$14=$15
                          <br />
                          #%16 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-
                          <br />
                          _(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9&$10=$11&$12=$13&$14=$15
                          <br />
                          [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-
                          <br />
                          _(.*)_-_(.*)_-_(.*)#(.*)\.html$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9&$10=$11&$12=$13#$14 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-
                          <br />
                          _(.*)_-_(.*)_-_(.*)\.html$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9&$10=$11&$12=$13 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-
                          <br />
                          _(.*)\.html#(.*)$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9&$10=$11#$12 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-
                          <br />
                          _(.*)\.html$ /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9&$10=$11 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html#(.*)$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9#$10 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7&$8=$9 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html#(.*)$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7#$8 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5&$6=$7 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html#(.*)$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5#$6 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)_-_(.*)_-_(.*)\.html$
                          <br />
                          /modules.php?name=$1&$2=$3&$4=$5 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)\.html#(.*)$ /modules.php?name=$1&$2=$3#$4 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)_-_(.*)_-_(.*)\.html$ /modules.php?name=$1&$2=$3 [L]
                          <br />
                          RewriteRule ^Nuke-index.html#(.*)$ /index.php#$1 [L]
                          <br />
                          RewriteRule ^Nuke-index.html$ /index.php [L]
                          <br />
                          RewriteRule ^Nuke-(.*)\.html#(.*)$ /modules.php?name=$1#$2 [L]
                          <br />
                          RewriteRule ^Nuke-(.*)\.html$ /modules.php?name=$1 [L]
                      </td>
                  </tr>
              </table>
              <br />
              <table border="0" width="100%">
                  <tr>
                      <td>
                          If you would like to use a prefix other than "Nuke-" for your page titles then you need to 
                          make a few changes.
                          <br />
                          <br />
                          <font color="red">
                              Open
                          </font> 
                          <font color="green">
                              includes/functions_tap.php
                          </font>                          
                          <br />
                          <br /> 
                          <font color="red">Find</font>: 
                          <br />
                          define("TAP_PREFIX", "Nuke-"); 
                          <br />
                          <br />
                          <font color="red">Replace</font>: 
                          <br />
                          Nuke- 
                          <br />
                          <br />
                          With what ever you want your prefix to be.  For example "Example-": 
                          <br />
                          define("TAP_PREFIX", "Example-"); 
                          <br />
                          <br />
                          <font color="red">Save</font>, 
                          <font color="red">
                              Close
                          </font> 
                          (and 
                          <font color="red">Upload</font>) 
                          <br />
                          <br />
                          <font color="red">
                              Open
                          </font> 
                          <font color="green">
                              .htaccess
                          </font>                          
                          <br />
                          <br />
                          <font color="red">Find ALL</font>: 
                          <br />
                          Nuke- 
                          <br />
                          <br />
                          <font color="red">Replace</font>: 
                          <br />
                          Your new prefix
                          <br />
                          <br />
                          Example:
                      </td>
                  </tr>
              </table>
              <table border="1" width="100%">
                  <tr>
                      <td>
                          RewriteRule ^ Example- (.*)\.html#(.*)$ /modules.php?name=$1#$2 [L] 
                          <br />
                          RewriteRule ^ Example- (.*)\.html$ /modules.php?name=$1 [L]
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <br />
                          <font color="red">Save</font>, 
                          <font color="red">
                              Close
                          </font> 
                          (and 
                          <font color="red">Upload</font>) 
                          <br />
                          <br />
                          Finally go into the Admin 
                          <br />
                          Site Admin->Preferences->Miscellaneous Options 
                          <br />
                          http://www.example.com/admin.php?op=Configure&sub=9 
                          <br />
                          <br />
                          Now choose how you want the Lazy Google Tap to work. 
                          <br />
                          <br />
                          <h1 class="myclass2">
                              Disabled 
		          </h1>                          
                          Lazy Google Tap is completely off 
                          <br />
                          <h1 class="myclass2">
                              Bots Only 
		          </h1>                             
                          Only when nukeCE detects the user is a crawler (ie Google, MSN, etc) will it 
                          <br />
                          activate the Lazy Google Tap 
                          <br />
                          <h1 class="myclass2">
                              Everyone 
		          </h1>                             
                          All users, administrators and bots will see the Lazy Google Tap 
                          <br />
                          <h1 class="myclass2">
                              Bots & Admins 
		          </h1>                             
                          Only bots and administrators will see the Lazy Google Tap 
                          <br />
                          <br />
                          Hit the Save Changes button.                          
                      </td>
                  </tr>
              </table>
          </body>
      </html>
     ';

?>