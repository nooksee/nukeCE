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

if(!defined('IN_SETTINGS')) {
  exit('Access Denied');
}

global $sysconfig;

echo "
      <fieldset>
          <legend>
              <span class='option'>
                  " . _GFXOPT . "
                  &nbsp;
              </span>
          </legend>
          <table cellpadding=\"0\" cellspacing=\"8\" border=\"0\">
     ";
$ck0 = $ck1 = $ck2 = $ck3 = $ck4 = $ck5 = $ck6 = $ck7 = "";
if ($sysconfig['usegfxcheck']==0) {
   $ck0 = " selected";
} elseif ($sysconfig['usegfxcheck']==1) {
   $ck1 = " selected";
} elseif ($sysconfig['usegfxcheck']==2) {
   $ck2 = " selected";
} elseif ($sysconfig['usegfxcheck']==3) {
   $ck3 = " selected";
} elseif ($sysconfig['usegfxcheck']==4) {
   $ck4 = " selected";
} elseif ($sysconfig['usegfxcheck']==5) {
   $ck5 = " selected";
} elseif ($sysconfig['usegfxcheck']==6) {
   $ck6 = " selected";
} else {
   $ck7 = " selected";
}
echo '
      <tr>
          <td>
              '._USEGFXCHECK.':
          </td>
          <td colspan="3">
              <select name="xusegfxcheck">
                  <option value="0"'.$ck0.'>
                      '._GFX_NC.'
                  </option>
                  <option value="1"'.$ck1.'>
                      '._GFX_AC.'
                  </option>
                  <option value="2"'.$ck2.'>
                      '._GFX_LC.'
                  </option>
                  <option value="3"'.$ck3.'>
                      '._GFX_RC.'
                  </option>
                  <option value="4"'.$ck4.'>
                      '._GFX_CA.'
                  </option>
                  <option value="5"'.$ck5.'>
                      '._GFX_AUC.'
                  </option>
                  <option value="6"'.$ck6.'>
                      '._GFX_ANC.'
                  </option>
                  <option value="7"'.$ck7.'>
                      '._GFX_ALLC.'
                  </option>
              </select>
          </td>
      </tr>
     ';
if (GDSUPPORT) {
    if (!defined('CAPTCHA')) {
        echo "
              <tr>
                  <td>
                      "._GFX_USEIMAGE."
                  </td>
                  <td colspan=\"3\">
             ";
        echo yesno_option('xuseimage', $sysconfig['useimage']);
        echo "
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _GFX_CODEFONT . ":
                  </td>
                  <td colspan=\"3\">
                      <select name='xcodefont'>
             ";
        $handle = @opendir(NUKE_INCLUDE_DIR.'fonts/');
        while(false !== ($file = @readdir($handle))) {
            if(preg_match('/^(.*?)\.ttf$/i', $file, $font)) {
                $sel = ($sysconfig['codefont'] == $font[1]) ? ' selected' : '';
                echo '
                      <option value="'.$font[1].'"'.$sel.'>
                          '.$font[1].'
                      </option>
                     ';
            }
        }
        closedir($handle);
        echo "
                      </select> 
                      [ 
		      "._FONTUPLOAD." 
		      ]
                  </td>
              </tr>
              <tr>
                  <td>
                      " . _GFX_CODESIZE . ":
                  </td>
                  <td colspan=\"3\">
             ";
        for ($i=1; $i <= 9; $i++) {
            $options[] = $i;
        }
        echo select_option('xcodesize',$sysconfig['codesize'],$options);
        echo "
                  </td>
              </tr>
              <tr>
                  <td>
		      <b>
                          " . _GFX_DEMO . ":
		      </b>
                  </td>
                  <td colspan=\"3\">
             ";
        echo security_code(1,'demo',1);
        echo "
                  </td>
              </tr>
             ";
    } else {
        global $capfile;
        if ($dir = @opendir(NUKE_INCLUDE_DIR.'captcha/'))
        {
            echo "
                  <tr>
                      <td valign='bottom'>
                 ";
            echo _DEFAULT.':';
            echo "
                      </td>
                      <td colspan=\"3\">
                 ";
            if (empty($capfile)) {
                echo "
                          <input type='radio' name='xcapfile' value='' checked='checked'>
                     ";
            } else {
                echo "
                          <input type='radio' name='xcapfile' value='' >
                     ";
            }
            echo "
                          <img src='includes/captcha.php?size=normal&amp;file=default' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>
                      </td>
                  </tr>
                 ";
            while( $file = @readdir($dir) )
            {
                if( $file != '.' && $file != '..' )
                {
                    $file = str_replace('.jpg','',$file);
                    echo "
                          <tr>
                              <td valign='bottom'>
                         ";
                    echo $file.':';
                    echo "
                              </td>
                              <td colspan=\"3\">
                         ";
                    if ($capfile == $file) {
                        echo "
                                  <input type='radio' name='xcapfile' value='".$file."' checked='checked'>
                             ";
                    } else {
                        echo "
                                  <input type='radio' name='xcapfile' value='".$file."' >
                             ";
                    }
                    echo "
                                  <img src='includes/captcha.php?size=normal&amp;file=".$file."' border='0' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'>
                              </td>
                          </tr>
                         ";
                }
            }
            @closedir($dir);
        }
    }
    echo "
              </table>
          </fieldset>
          <br />
         ";
}

?>