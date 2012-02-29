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

if (!defined('NUKE_CE')) {
    die("You can't access this file directly...");
}

global $db, $prefix, $cache;

##################################################
# Load dynamic meta tags from database           #
##################################################

/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
if(($metatags = $cache->load('metatags', 'config')) === false) {
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
  $metatags = array();
  $sql = 'SELECT meta_property, meta_content FROM '.$prefix.'_meta';
  $result = $db->sql_query($sql, true);
  $i=0;
  while(list($meta_property, $meta_content) = $db->sql_fetchrow($result, SQL_NUM)) {
      $metatags[$i] = array();
      $metatags[$i]['meta_property'] = $meta_property;
      $metatags[$i]['meta_content'] = $meta_content;
      $i++;
  }
  unset($i);
  $db->sql_freeresult($result);
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
  $cache->save('metatags', 'config', $metatags);
}
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/

##################################################
# Finally output the meta tags                   #
##################################################

$metastring = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">\n";
$metastring .= "<meta http-equiv=\"Content-Language\" content=\""._LANGCODE."\">\n";

for($i=0,$j=count($metatags);$i<$j;$i++) {
	$metatag = $metatags[$i];
    $metastring .= "<meta property=\"".$metatag['meta_property']."\" content=\"".$metatag['meta_content']."\">\n";
}

###############################################
# DO NOT REMOVE THE FOLLOWING COPYRIGHT LINE! #
# YOU'RE NOT ALLOWED TO REMOVE NOR EDIT THIS. #
###############################################

// IF YOU REALLY NEED TO REMOVE IT AND HAVE MY WRITTEN AUTHORIZATION CHECK: http://phpnuke.org/modules.php?name=Commercial_License
// PLAY FAIR AND SUPPORT THE DEVELOPMENT, PLEASE!
$metastring .= "<meta property=\"generator\" content=\"PHP-Nuke Copyright (c) 2006 by Francisco Burzi. This is free software, and you may redistribute it under the GPL (http://phpnuke.org/files/gpl.txt). PHP-Nuke comes with absolutely no warranty, for details, see the license (http://phpnuke.org/files/gpl.txt). Powered by nukeCE (http://www.nukece.com).\">\n";

echo $metastring;

?>