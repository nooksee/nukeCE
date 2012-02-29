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
                  Password Strength Help
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
                              Password Strength Help
                          </h1>
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              The purpose of this is to help you create a password that will be stronger and thus
                              harder for hackers to break.  You can of course choose to ignore this as it is only a
                              helpful tool and not a requirement.
                          </h1>
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              The password strength meter measures your password strength in the following 5 ways.
                          </h1>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Password contains a lowercase letter (a-z)
                          </h1>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Password contains a uppercase letter (A-Z)
                          </h1>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Password contains a digit (0-9)
                          </h1>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Password contains a character that is not a letter or digit (!@#$%^&amp;*)
                          </h1>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Password length is at least 10 characters long
                          </h1>
                      </td>
                  </tr>
              </table>
          </body>
      </html>
     ';

?>