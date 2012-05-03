<?php

/********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ================================================================             */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion */
/********************************************************************************/

if(!defined('IN_YA')) {
    exit('Access Denied');
}

$pagetitle = _YA_COOKIEINFO;
    
function yacookiecheck(){
    global $ya_config;
    setcookie("YA_CE1","value1"); 
    setcookie("YA_CE2","value2",time()+3600); 
    setcookie("YA_CE3","value3",time()+3600,"/"); 
    setcookie("YA_CE4","value4",time()+3600,"$ya_config[cookiepath]"); 
}

function yacookiecheckresults(){
    global $ya_config,$module_name;
    $cookiedebug = "0";        // cookiedebug: set this to '1' if you want additional debug info
    if (($_COOKIE ['YA_CE3'] != "value3") OR ($cookiedebug == "1")){
        include_once(NUKE_BASE_DIR.'header.php');
        title(_USERREGLOGIN);
        OpenTable();
    }
    
    $debugcookie = "<table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"70%\">";
    if($_COOKIE ['YA_CE1'] == "value1") {
        $debugcookie = "
                        <tr>
                            <td>1: setcookie('YA_CE1','value1';)</td>
                            <td><font color=\"#009933\"><strong>"._YA_COOKIEOK."</strong></font></td>
                        </tr>
                       "; 
    } else {
        $debugcookie = "
                        <tr>
                            <td>1: setcookie('YA_CE1','value1';)</td>
                            <td><font color=\"#FF3333\"><strong>"._YA_COOKIEFAIL."</strong></font></td>
                        </tr>
                       "; 
    }
    if($_COOKIE ['YA_CE2'] == "value2") {
        $debugcookie = "
                        <tr>
                            <td>2: setcookie('YA_CE2','value2',time()+120)</td>
                            <td><font color=\"#009933\"><strong>"._YA_COOKIEOK."</strong></font></td>
                        </tr>
                       "; 
    } else {
        $debugcookie = "
                        <tr>
                            <td>2: setcookie('YA_CE2','value2',time()+120)</td>
                            <td><font color=\"#FF3333\"><strong>"._YA_COOKIEFAIL."</strong></font></td>
                        </tr>
                       "; 
    }
    if($_COOKIE ['YA_CE3'] == "value3") {
        $debugcookie = "
                        <tr>
                            <td>3: setcookie('YA_CE3','value3',time()+120,'/')</td>
                            <td><font color=\"#009933\"><strong>"._YA_COOKIEOK."</strong></font></td>
                        </tr>
                       "; 
    } else {
        $debugcookie = "
                        <tr>
                            <td>3: setcookie('YA_CE3','value3',time()+120,'/')</td>
                            <td><font color=\"#FF3333\"><strong>"._YA_COOKIEFAIL."</strong></font></td>
                        </tr>
                       "; 
    }
    if($_COOKIE ['YA_CE4'] == "value4") {
        $debugcookie = "
                        <tr>
                            <td>4: setcookie('YA_CE4','value4',time()+120,'$ya_config[cookiepath]')</td>
                            <td><font color=\"#009933\"><strong>"._YA_COOKIEOK."</strong></font></td>
                        </tr>
                       "; 
    } else {
        $debugcookie = "
                        <tr>
                            <td>
                                4: setcookie('YA_CE4','value4',time()+120,'$ya_config[cookiepath]')
                            </td>
                            <td>
                                <font color=\"#FF3333\">
                                    <strong>
                                        "._YA_COOKIEFAIL."
                                    </strong>
                                </font>
                            </td>
                        </tr>
                       "; 
    }
    $debugcookie = "
                            </td>
                        </tr>
                    </table>
                   ";

    if ($_COOKIE ['YA_CE3'] != "value3") {
        ErrorReturn("<font color=\"#FF3333\">"._YA_COOKIENO."</font>");
    if ($cookiedebug == "1") {
        OpenTable();
        echo $debugcookie;
        CloseTable();
    }
    } else if ($cookiedebug == "1") {
        echo "
              <form action=\"modules.php?name=$module_name\" method=\"post\">
                  <table align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"70%\">
                      <tr>
                          <td colspan=\"2\">
                              <div align=\"center\">
                                  <font color=\"#FF3333\">
                                      <em>
                                          "._YA_COOKIEYES."
                                      </em>
                                  </font>
                              </div>
                          </td>
                      </tr>
                      <tr>
                          <td valign=\"top\">
             ";
        if ($cookiedebug == "1") {
            OpenTable();
            echo $debugcookie;
            CloseTable();
        }
        echo "
                          </td>
                      <tr>
                          <td align=\"right\">
                              <input type=\"submit\" name=\"submit\" value='"._YA_CONTINUE."'>
                          </td>
                      </tr>
                  </table>
              </form>
             ";
    }
    
    setcookie("YA_CE1","expired1",time()-604800,"");
    setcookie("YA_CE2","expired2",time()-604800,"");
    setcookie("YA_CE3","expired3",time()-604800,"/");
    setcookie("YA_CE4","expired4",time()-604800,"$ya_config[cookiepath]");

    if (($_COOKIE ['YA_CE3'] != "value3") OR  ($cookiedebug == "1")){
        CloseTable();
        echo "
              <br />
             ";
        include_once(NUKE_BASE_DIR.'footer.php');
   }
}

function ShowCookiesRedirect() {
    global $ya_config,$module_name;
    setcookie("YA_CE1","1",time()-604800,"");
    setcookie("YA_CE2","2",time()-604800,"");
    setcookie("YA_CE3","3",time()-604800,"/");
    setcookie("YA_CE4","4",time()-604800,"$ya_config[cookiepath]");
    redirect("modules.php?name=$module_name&op=ShowCookies");
}

function ShowCookies() {
    global $ya_config, $module_name;
    include_once(NUKE_BASE_DIR.'header.php');
    title(_YA_COOKIEINFO);
    if ($ya_config['cookiecleaner']==0) {
        DisplayErrorReturn(_ACTDISABLED, 1);
    } else {
        info_box("tip", _YA_DELCOOKIEINFO1);
        OpenTable();
        $CookieArray = $HTTP_COOKIE_VARS;
        if (!is_array($HTTP_COOKIE_VARS)) {
            $CookieArray = $_COOKIE;
        }
        if (is_array($CookieArray) && !empty($CookieArray)) {
            echo "
                  <span class=\"gen\">
                  <br />
                  </span>
                  <table align=\"center\" width=\"80%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                      <caption align=\"bottom\" style=\"padding: 10; font-size: 10pt\">
                          <form action=\"modules.php?name=$module_name&amp;op=DeleteCookies\" method=\"post\">
                              <div align=\"center\">
                                  <span class=\"content\">
                                      <input type=\"submit\" name=\"submit\" value=\"" ._YA_COOKIEDELALL . "\">
                                  </span>
                              </div>
                          </form>
                      </caption>
                      <tr>
                          <th colspan=\"1\" align=\"left\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\" width=\"25%\">
                              &nbsp;"._YA_COOKIENAME."&nbsp;
                          </th>
                          <th colspan=\"1\" align=\"left\" class=\"thCornerR\" nowrap=\"nowrap\">
                              &nbsp;"._YA_COOKIEVAL."&nbsp;
                          </th>
                      </tr>
                 ";
            while(list($cName,$cValue) = each($CookieArray)) {
                $row_class = ($c++%2==1) ? 'row2' : 'row1';
                $cName = str_replace(" ","",$cName);
                if (empty($cValue)) $cValue = "(empty)";
                $cMore = substr("$cValue", 36, 1);
                if (!empty($cMore)) 
                $cValue = substr("$cValue", 0, 35)." ( . . . )";
                echo "
                      <tr>
                          <td class=".$row_class." align=\"left\" width=\"50%\" nowrap=\"nowrap\">
                              $cName
                          </td>
                          <td class=".$row_class." align=\"left\" width=\"50%\" nowrap=\"nowrap\">
                              $cValue
                          </td>
                      </tr>
                     ";
            }
            echo "
                  </table>
                  <span class=\"gen\">
                  <br />
                  </span>
                  <div align=\"center\">
                      [ 
                      <a href='modules.php?name=$module_name'>
                          "._USERLOGIN."
                      </a> 
                      | 
                      <a href='modules.php?name=$module_name&amp;op=pass_lost'>
                          "._PASSWORDLOST."
                      </a> 
                      ]
                  </div>
                 ";    
        } else {
            echo "
                  <div align=\"center\">
                      <span class=\"option\">
                          <b>
                              <em>
                                  "._NOCOOKIESSET."
                              </em>
                          </b>
                      </span>        
                      <br />
                      <br />
                      [
                      <a href=\"javascript:location.reload(true);\" target=\"_self\">
                          Refresh
                      </a>
                      ]
                  </div>
                 ";
        }
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
        $CookieArray = "";
   }
}

function DeleteCookies() {
    global $ya_config, $module_name, $prefix, $user, $username, $CookieArray, $cookie;
    include_once(NUKE_BASE_DIR.'header.php');
    title(_YA_COOKIEINFO);
    if ($ya_config['cookiecleaner']==0) {
        DisplayErrorReturn(_ACTDISABLED, 1);
    } else {
        info_box("important", _YA_COOKIEDEL1);
        OpenTable();
        $r_uid = $cookie[0];
        $r_username = $cookie[1];
        echo $r_username;
        echo $r_uid;
        echo $username;
        $CookieArray = $_COOKIE;
        $db->sql_query("DELETE FROM ".$prefix."_session WHERE uname='$r_username'");
        $db->sql_query("OPTIMIZE TABLE ".$prefix."_session");
        echo "
              <span class=\"gen\">
              <br />
              </span>
              <table align=\"center\" width=\"80%\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" class=\"forumline\">
                  <caption align=\"bottom\" style=\"padding: 10; font-size: 10pt\">
                      <form action=\"modules.php?name=$module_name&amp;op=ShowCookies\" method=\"post\">
                          <div align=\"center\">
                              <span class=\"content\">
                                  <input type=\"submit\" name=\"submit\" value=\"" ._YA_COOKIESHOWALL . "\">
                              </span>
                          </div>
                      </form>
                  </caption>
                  <tr>
                      <th colspan=\"1\" align=\"left\" height=\"25\" class=\"thCornerL\" nowrap=\"nowrap\" width=\"25%\">
                          &nbsp;"._YA_COOKIENAME."&nbsp;
                      </th>
                      <th colspan=\"1\" align=\"left\" class=\"thCornerR\" nowrap=\"nowrap\">
                          &nbsp;"._YA_COOKIESTAT."&nbsp;
                      </th>
                  </tr>
             ";
        if (is_array($CookieArray) && !empty($CookieArray)) {
            while(list($cName,$cValue) = each($CookieArray)) {
                $row_class = ($c++%2==1) ? 'row2' : 'row1';
                $cName = str_replace(" ","",$cName);
                // Multiple cookie paths used to expire cookies that are no longer in use as well.
                setcookie("$cName","1",time()-604800,""); // Directory only path
                setcookie("$cName","2",time()-604800,"/"); // Site wide path
                setcookie("$cName","3",time()-604800,"$ya_config[cookiepath]"); // Configured path
                echo "
                      <tr>
                          <td class=".$row_class." align=\"left\" width=\"50%\" nowrap=\"nowrap\">
                              $cName
                          </td>
                          <td class=".$row_class." align=\"left\" width=\"50%\" nowrap=\"nowrap\">
                              "._YA_COOKIEDEL2."
                     ";
                unset($cName);
            }
            echo "
                          </td>
                      </tr>
                  </table>
                  <span class=\"gen\">
                  <br />
                  </span>
                  <div align=\"center\">
                      [ 
                      <a href='modules.php?name=$module_name'>
                          "._USERLOGIN."
                      </a> 
                      | 
                      <a href='modules.php?name=$module_name&amp;op=pass_lost'>
                          "._PASSWORDLOST."
                      </a> 
                      ]
                  </div>
                 ";
        }

        // menelaos: these lines need some more study: which are usefull, which are not
        unset($user);
        unset($cookie);
        $user="";
        if(isset($_SESSION)){@session_unset();}
        if(isset($_SESSION)){@session_destroy();} 
        if( isset($_COOKIE[session_name()]))
        unset( $_COOKIE[session_name()] );
        // menelaos: these lines need some more study: which are usefull, which are not

        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
   }
}

?>