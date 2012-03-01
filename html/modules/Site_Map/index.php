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

if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$module_name = basename(dirname(__FILE__));
$subject = $sitename." "._SMAP;

global $prefix, $db, $sitename, $currentlang, $admin, $multilingual, $module_name, $admin_file, $user_prefix;

$result = $db->sql_query("SELECT * FROM `".$prefix."_smap`");
while ($row=$db->sql_fetchrow($result))
{
    $nametask = $row['name'];
    $value = $row['value'];
    $conf[$nametask]=$value;
}
$db->sql_freeresult($result);
$xml = $conf['xml'];
$ndown = $conf['ndown'];
$nnews = $conf['nnews'];
$nrev = $conf['nrev'];
$ntopics = $conf['ntopics'];
$nuser = $conf['nuser'];

//---------------------- XML BEGIN -----------------
if($xml)
{
	//OPEN FILE
	$var=@fopen(NUKE_BASE_DIR."sitemap.xml","w+");
	// HEADER
	@fwrite($var, "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
	<!--Google Site Map File Generated by Site_Map http://www.nukece.com -->
	<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n");
} else {
    //DELETE CONTENT
    $var=@fopen(NUKE_BASE_DIR."sitemap.xml","w+");
    @fclose($var);
}
//---------------------- XML END -----------------


if (file_exists(NUKE_MODULES_DIR.$module_name.'/language/lang-'.$currentlang.'.php')) {
	include_once(NUKE_MODULES_DIR.$module_name.'/language/lang-'.$currentlang.'.php');
} else {
	include_once(NUKE_MODULES_DIR.$module_name.'/language/lang-english.php');
}

function downloads_subs($cid, $spaces, $xml) {
    $result4 = $db->sql_query("SELECT cid, title FROM " . $prefix . "_downloads_categories WHERE active=1 AND parentid=$cid1 ORDER BY title");
}

include_once(NUKE_BASE_DIR.'header.php');
title($sitename.' '._SMAP);
OpenTable();
echo"<table align=\"center\" border=\"0\">";
echo"<tr><td><img src=\"modules/Site_Map/images/cath.gif\" alt=\"cath\"></td><td><a href=\"$nukeurl\">Homepage</a></td></tr>\n";
$result2 = $db->sql_query("SELECT `title`, `custom_title`, `view`, `groups` FROM `" . $prefix . "_modules` WHERE `active`=1 ORDER BY `custom_title`");
while ($row2 = $db->sql_fetchrow($result2)) {
	$titolomodulo = $row2['custom_title'];
	$link = $row2['title'];
	$permesso = $row2['view'];
	$groups = $row2['groups'];
	echo"<tr><td>";
	if ($permesso < 3) {
		echo "<img src=\"modules/Site_Map/images/cat1.gif\" alt=\"cat1\">";
	} else if ($permesso == 4 && is_admin()) {
	    echo "<img src=\"modules/Site_Map/images/cat1.gif\" alt=\"cat1\">";
	} else if ($permesso == 6 && !empty($groups) && is_array($groups)) {
	    $ingroup = false;
	    global $userinfo;
	    foreach ($groups as $group) {
		     if (isset($userinfo['groups'][$group])) {
		         $ingroup = true;
		     }
	    }
	    if (!$ingroup) {
	        echo "<img src=\"modules/Site_Map/images/cat1.gif\" alt=\"cat1\">";
	    } else {
	        echo"<img src=\"modules/Site_Map/images/deny.gif\" alt=\"deny\">";
	    }
	} else {
		echo"<img src=\"modules/Site_Map/images/deny.gif\" alt=\"deny\">";
	}
	echo "</td>\n";
if(substr($row2['title'],0,3) == '~l~') {
	echo "<td>                                     <a href=\"".$row2['custom_title']."\">
                                         ".substr($row2['title'],3)."
                                     </a></td></tr>\n";
        } else {
	echo "<td><a href=\"modules.php?name=$link\">$titolomodulo</a></td></tr>\n";
        }
	switch($link) {
		case 'Downloads':
			$result3 = $db->sql_query("SELECT `cid`, `title` FROM `" . $prefix . "_downloads_categories` WHERE `active`=1 AND `parentid`=0 ORDER BY `title`");
			while ($row3 = $db->sql_fetchrow($result3)) {
				$titolodown = $row3['title'];
				$cid1 = $row3['cid'];
				echo"<tr><td>&nbsp;</td><td><img src=\"modules/Site_Map/images/cat3.gif\" alt=\"cat3\"> <a href=\"modules.php?name=Downloads&amp;cid=$cid1\">$titolodown</a></td>";
				if($xml)
                {
                    //XML
                    @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Downloads&amp;cid=$cid1</loc></url>\n");
                }
                $result4 = $db->sql_query("SELECT `cid`, `title` FROM `" . $prefix . "_downloads_categories` WHERE `active`=1 AND `parentid`=$cid1 ORDER BY `title`");
				while ($row4 = $db->sql_fetchrow($result4)) {
					$titolodown2 = $row4['title'];
					$cid2 = $row4['cid'];
					echo"<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\"> <a href=\"modules.php?name=Downloads&amp;cid=$cid2\">$titolodown2</a></td>";
					if($xml)
                    {
                            //XML
                            @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Downloads&amp;cid=$cid2</loc></url>\n");
                   }
                   $result4b = $db->sql_query("SELECT `cid`, `lid`, `title` FROM `" . $prefix . "_downloads_downloads` WHERE `active`=1 AND `cid`=$cid2 ORDER BY `hits` LIMIT 0,".$ndown);
                    while ($row4b = $db->sql_fetchrow($result4b)) {
        				$titolodown3=$row4b['title'];
        				$cid3=$row4b['lid'];
        				echo"<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/catt.gif\" alt=\"cat\"> <a href=\"modules.php?name=Downloads&amp;op=getit&amp;lid=$cid3\">$titolodown3</a></td>";
        				if($xml)
                        {
                                //XML
                                //@fwrite($var, "<url><loc>$nukeurl/modules.php?name=Downloads&amp;op=getit&amp;lid=$cid3</loc></url>\n");
                        }
                    }
                    $db->sql_freeresult($result4b);
                }
                $db->sql_freeresult($result4);
			}
            $db->sql_freeresult($result3);
		break;
		case '~l~Photo Album':
			$result5 = $db->sql_query("SELECT `cat_id`, `cat_title` FROM `" . $prefix . "_bbalbum_cat` ORDER BY `cat_order`");
			while ($row5 = $db->sql_fetchrow($result5)) {
				$titolocatf = $row5['cat_title'];
				$cat_id = $row5['cat_id'];

				//Check to make sure its not a blank category
				$number_of_cats = $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_bbalbum_cat WHERE cat_id=$cat_id ORDER BY cat_order"));
				if ($number_of_cats <= 0) continue;

				echo"<tr><td>&nbsp;</td><td><img src=\"modules/Site_Map/images/cat3.gif\" alt=\"cat3\"> <a href=\"modules.php?name=Forums&amp;file=album_cat&amp;cat_id=$cat_id\">$titolocatf</a></td>";
		        if($xml)
                {
                    //XML
                    @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Forums&amp;file=album_cat&amp;cat_id=$cat_id</loc></url>\n");
                }
                }
			$db->sql_freeresult($result5);
		break;

		case 'Forums':
			$result5 = $db->sql_query("SELECT `cat_id`, `cat_title` FROM `" . $prefix . "_bbcategories` ORDER BY `cat_order`");
			while ($row5 = $db->sql_fetchrow($result5)) {
				$titolocatf = $row5['cat_title'];
				$cat_id = $row5['cat_id'];

				//Check to make sure its not a blank category
				$number_of_forums = $db->sql_numrows($db->sql_query("SELECT * FROM " . $prefix . "_bbforums WHERE cat_id=$cat_id AND auth_view<2 AND auth_read<2 ORDER BY forum_order"));
				if ($number_of_forums <= 0) continue;

				echo"<tr><td>&nbsp;</td><td><img src=\"modules/Site_Map/images/cat3.gif\" alt=\"cat3\"> <a href=\"modules.php?name=Forums&amp;file=index&amp;c=$cat_id\">$titolocatf</a></td>";
		        if($xml)
                {
                    //XML
                    @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Forums&amp;file=index&amp;c=$cat_id</loc></url>\n");
                }
                $result6 = $db->sql_query("SELECT `forum_name`, `forum_id`, `auth_view`, `auth_read` FROM `" . $prefix . "_bbforums` WHERE `cat_id`=$cat_id AND `auth_view`<2 AND `auth_read`<2 ORDER BY `forum_order`");
				while ($row6 = $db->sql_fetchrow($result6)) {
					$titoloforum = $row6['forum_name'];
					$fid = $row6['forum_id'];
					$auth_view = $row6['auth_view'];
					$auth_read = $row6['auth_read'];
					echo"<tr><td>&nbsp;</td><td>";
					if ($auth_view && !is_user()) {
						echo"&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/deny.gif\" alt=\"deny\">";
						echo"$titoloforum</td></tr>";
					} else {
						echo"&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\">";
						echo" <a href=\"modules.php?name=Forums&amp;file=viewforum&amp;f=$fid\">$titoloforum</a></td></tr>";
    			        if($xml)
                        {
                            //XML
                            @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Forums&amp;file=viewforum&amp;f=$fid</loc><changefreq>daily</changefreq></url>\n");
                        }
                        $resultT = $db->sql_query("SELECT topic_title, topic_id FROM " . $prefix . "_bbtopics WHERE forum_id=$fid ORDER BY topic_id DESC LIMIT 0,".$ntopics);
						while($rowT = $db->sql_fetchrow($resultT)) {
						    echo"<tr><td>&nbsp;</td><td>";
							echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/catt.gif\" alt=\"cat\">";
							echo" <a href=\"modules.php?name=Forums&amp;file=viewtopic&amp;t=$rowT[topic_id]\">" . $rowT['topic_title'] . "</a></td>";
							if($xml)
							{
                                //XML
                                @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Forums&amp;file=viewtopic&amp;t=$rowT[topic_id]</loc><changefreq>daily</changefreq></url>\n");
                            }
                       }
                       $db->sql_freeresult($resultT);
					}
				}
				$db->sql_freeresult($result6);
			}
			$db->sql_freeresult($result5);
		break;

		case 'Web_Links':
            $result8 = $db->sql_query("SELECT `cid`, `title` from `".$prefix."_links_categories` where `parentid`='$cid' order by `title`");
			while ($row8 = $db->sql_fetchrow($result8)) {
				$titololink = $row8['title'];
				$cid1 = $row8['cid'];
				echo"<tr><td>&nbsp;</td><td><img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\"> <a href=\"modules.php?name=Web_Links&amp;l_op=viewlink&amp;cid=$cid1\">$titololink</a></td>";
				if($xml)
				{
                     //XML
			         @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Web_Links&amp;l_op=viewlink&amp;cid=$cid1</loc></url>\n");
			    }
            }
            $db->sql_freeresult($result8);
		break;

		case 'Topics':
			$result9 = $db->sql_query("SELECT topictext,topicid FROM ".$prefix."_topics ORDER BY topictext");
			while ($row9 = $db->sql_fetchrow($result9)) {
				$topiclink=$row9['topictext'];
				$cidtopic=$row9['topicid'];
				echo"<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\"> <a href=\"modules.php?name=Topics&amp;cid=$cidtopic\">$topiclink</a></td>";
				if($xml)
		        {
                    //XML
			        @fwrite($var, "<url><loc>$nukeurl/modules.php?name=Topics&amp;cid=$cidtopic</loc></url>\n");
		        }
            }
            $db->sql_freeresult($result9);
		break;

		case 'News':
			$result10 = $db->sql_query("SELECT `title`, `sid` FROM `".$prefix."_stories` ORDER BY `sid` DESC LIMIT 0,".$nnews);
			while ($row10 = $db->sql_fetchrow($result8)) {
				$newslink = $row10['title'];
				$cidnews = $row10['sid'];

				echo"<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\"> <a href=\"modules.php?name=News&amp;file=article&amp;sid=$cidnews\">$newslink</a></td>";
				if($xml)
		        {
                                //XML
			    @fwrite($var, "<url><loc>$nukeurl/modules.php?name=News&amp;file=article&amp;sid=$cidnews</loc></url>\n");
		        }
            }
            $db->sql_freeresult($result10);
		break;

		case 'Members_List':
			$result11 = $db->sql_query("SELECT `username`, `user_id` FROM `".$user_prefix."_users` ORDER BY `user_id` DESC LIMIT 0,".$nuser);
			while ($row11 = $db->sql_fetchrow($result11)) {
				$user=$row11['username'];
				$ciduser=$row11['user_id'];

				echo"<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\"> <a href=\"modules.php?name=Profile&amp;mode=viewprofile&amp;u=$ciduser\">$user</a></td>";
            }
            $db->sql_freeresult($result11);
		break;

		case 'Reviews':
			$result12 = $db->sql_query("SELECT `title`, `id` FROM `".$prefix."_reviews` ORDER BY `id` DESC LIMIT 0,".$nrev);
			while ($row12 = $db->sql_fetchrow($result12)) {
				$titrev=$row12['title'];
				$cidrev=$row12['id'];

				echo"<tr><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"modules/Site_Map/images/cat2.gif\" alt=\"cat2\"> <a href=\"modules.php?name=Reviews&amp;rop=showcontent&amp;id=$cidrev\">$titrev</a></td>";
				if($xml)
		        {
                                //XML
			    //@fwrite($var, "<url><loc>$nukeurl/modules.php?name=Reviews&amp;rop=showcontent&amp;id=$cidrev</loc></url>\n");
		        }
            }
            $db->sql_freeresult($result12);
		break;
	}
}
$db->sql_freeresult($result2);
echo"</table>";
CloseTable();

if($xml)
{
    // FOOTER XML
    @fwrite($var, '</urlset>');
}
// FOOTER GRAPHIC
include_once(NUKE_BASE_DIR.'footer.php');

?>