<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network       */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

global $prefix, $nuke_config, $admin_file, $ab_config;
if(!$ab_config['page_delay'] OR $ab_config['page_delay'] < 1) { $ab_config['page_delay'] = 5; }
define('_AB_0OCTECT','Full IP (127.2.3.4)');
define('_AB_1OCTECT','1 Octet (127.2.3.*)');
define('_AB_2OCTECT','2 Octets (127.2.*.*)');
define('_AB_3OCTECT','3 Octets (127.*.*.*)');
define('_AB_ACCESSCHANGEDON','Your Access Changed on');
define('_AB_ACCESSFOR','Your Access for');
define('_AB_ACTIVATE','Activate');
define('_AB_ADDANOTHERIP','Add Another IP');
define('_AB_ADDBY','Added by');
define('_AB_ADDED','added');
define('_AB_ADDIP','Add Blocked IP');
define('_AB_ADDIPS','To add a subnet, use the format: <i>192.168.1.*</i>');
define('_AB_ADMIN','Admin');
define('_AB_ADMINAUTH','Admin Auth');
define('_AB_ADMINBLOCKER','ADMIN Blocker');
define('_AB_ADMINISTRATION','Administration');
define('_AB_ADMINLIST','Admin Contacts');
define('_AB_ADMINS','Admins Only');
define('_AB_AGENT','Agent');
define('_AB_AGENTIPTRACKING','Agent IP Tracking');
define('_AB_AGENTTRACKING','Agent Tracking');
define('_AB_ASC','Ascending');
define('_AB_AUTHLOGIN','Auth Login');
define('_AB_AUTHORBLOCKER','AUTHOR Blocker');
define('_AB_BLOCK','Block');
define('_AB_BLOCKEDFROM','Blocked abuse from');
define('_AB_BLOCKEDIP','Blocked IP');
define('_AB_BLOCKEDIPMENU','Blocked IP Menu');
define('_AB_BLOCKEDIPS','Blocked IP\'s');
define('_AB_BLOCKEDON','Blocked on');
define('_AB_BLOCKEDPAGE','Blocked IP Page Settings');
define('_AB_BLOCKEDREFERERS','Blocked Refering Domains');
define('_AB_BLOCKERCONFIG','Blocker Configuration');
define('_AB_BLOCKFORWARD','Block &amp; Forward');
define('_AB_BLOCKTEMPLATE','Block &amp; Default Page');
define('_AB_BLOCKTYPE','IP Block Type');
define('_AB_BUILDCGI','Build CGIAuth file');
define('_AB_BYNSN','by: NukeScripts.net');
define('_AB_BYTES','bytes');
define('_AB_C2CODE','C2 Code');
define('_AB_CGIAUTH','Admin CGIAuth');
define('_AB_CGIAUTHSETUP','CGIAuth Setup');
define('_AB_CHECKSECTION','Check section');
define('_AB_CIDRS','CIDRs');
define('_AB_CLEAREXPIRED','Clear Expired Blocked IP\'s');
define('_AB_CLEARIP','Clear Blocked IP\'s');
define('_AB_CLEARIPS','WARNING: Are you sure you wish to empty the blocked IP database?');
define('_AB_CLEAR','Clear');
define('_AB_CLEARTRACKEDS','WARNING: Are you sure you wish to empty the tracked IP database?');
define('_AB_CLIENT_IP','Client IP');
define('_AB_CLIKEBLOCKER','CLIKE Blocker');
define('_AB_CODE','Code');
define('_AB_CONFIGURATION','Configuration');
define('_AB_COPYRIGHT','Copyright');
define('_AB_COUNTRY','Country');
define('_AB_COUNTRYLISTING','Country Listing');
define('_AB_COVERS','covers');
define('_AB_CRYPTSALT','Crypt Salt');
define('_AB_DATETIME','Date &amp; Time');
define('_AB_DELETE','Delete');
define('_AB_DELETEIP','Delete IP');
define('_AB_DELETEIPS','Are you sure you want to delete the IP of');
define('_AB_DESC','Descending');
define('_AB_DISABLED','Disabled');
define('_AB_DISABLESWITCH','NukeSentinel(tm) Status');
define('_AB_DISPLAYLINK','Display Link');
define('_AB_DISPLAYREASON','Display Reason');
define('_AB_DURATION','Block Duration');
define('_AB_EDITADMINS','Edit Admin');
define('_AB_EDITIP','Edit IP Address');
define('_AB_EDITBLOCKEDIP','Edit Blocked IP');
define('_AB_EDITIPS','To edit to a subnet, use the format: <i>192.168.1.*</i>');
define('_AB_EMAILBLOCKFORWARD','Email, Block, &amp; Forward');
define('_AB_EMAILBLOCKTEMPLATE','Email, Block, &amp; Default Page');
define('_AB_EMAILFORWARD','Email &amp; Forward');
define('_AB_EMAILLOOKUP','Email IP lookup');
define('_AB_EMAILONLY','Email Admin');
define('_AB_EMAILTEMPLATE','Email &amp; Default Page');
define('_AB_ENABLED','Enabled');
define('_AB_EXPIRES','Expires');
define('_AB_EXPIRESIN','Expires in');
define('_AB_EXPIRESINS','Expressed in number of days.');
define('_AB_FALSEADMIN','You have attempted to use a False Admin cookie to access this site!');
define('_AB_FILTERBLOCKER','Filters Blocker');
define('_AB_FLAG','Flag');
define('_AB_FLOODBLOCKER','Flood Blocker');
define('_AB_FLOODBLOCKERFAILED','Flood Blocker can not be activated until you have set the ftaccess Path in admin.');
define('_AB_FLOODDELAY','Flood Delay');
define('_AB_FLOODERS','<b>Intentional</b> flood attackers:');
define('_AB_FLOODNOTE','Only if Flood Blocker is activated.');
define('_AB_FLOODNUM','Number of Attacks:');
define('_AB_FORCENUKEURL','Force Nuke URL');
define('_AB_FORWARD','Forward To');
define('_AB_FORWARDONLY','Forward');
define('_AB_FROM','from');
define('_AB_FTACCESSFAILED','You can not use .ftaccess writting until you have set the ftaccess path in <a href="'.$admin_file.'.php?op=ABMain">settings</a>.');
define('_AB_FTACCESSPATH','ftaccess Path');
define('_AB_FTWARNING','.ftaccess does not exist or is not correctly CHMODed.');
define('_AB_GENERALSETTINGS','General Settings');
define('_AB_GET','Get String');
define('_AB_GETOUT','Leave this site now!');
define('_AB_GO','GO');
define('_AB_HARVESTBLOCKER','Harvester Blocker');
define('_AB_HARVESTERLIST','Harvest List');
define('_AB_HARVESTERLISTADD','Harvest List Add');
define('_AB_HELPSYS','NukeSentinel(tm) Help');
define('_AB_HIERROR','The IP <b>MUST BE</b> numeric with each octet between 0 and 255!');
define('_AB_HITDATE','Date');
define('_AB_HITS','Hits');
define('_AB_HOSTNAME','Host Name');
define('_AB_HTACCESSFAILED','You can not use .htaccess writting until you have set the htaccess path in <a href="'.$admin_file.'.php?op=ABMain">settings</a>.');
define('_AB_HTACCESSPATH','htaccess Path');
define('_AB_HTTPAUTH','Admin HTTPAuth');
define('_AB_HTTPONLY','The below information pertains to the HTTPAuth system in NukeSentinel(tm) only! It does not affect your normal admin login information.');
define('_AB_HTWARNING','File does not exist or is not correctly CHMODed.');
define('_AB_HTWRITE','Write to htaccess');
define('_AB_HTWRITEX','');
define('_AB_IN','in');
define('_AB_INFOSYS','NukeSentinel(tm) Info');
define('_AB_INVALIDIP','You are using an Invalid IP to access this site!');
define('_AB_INVALIDMETHOD','You are using an Invalid Request Method to access this site!');
define('_AB_IPADDRESS','IP Address');
define('_AB_IPADDRESSES','IP Addresses');
define('_AB_IPBLOCKED','Blocked IP');
define('_AB_IPERROR','The IP <b>MUST BE</b> numeric with each octet between 0 and 255!');
define('_AB_IPFULL','Full IP (127.2.3.4)');
define('_AB_IPLOOKUPSITE','IP Lookup Site');
define('_AB_IPRANGE','IP Range (127.*.*.*)');
define('_AB_IPSPERPAGE','IPs per page');
define('_AB_REFEREDFROM','Refered From');
define('_AB_IPSTRACKED','IPs Tracked');
define('_AB_IPTRACKED','Tracked IP');
define('_AB_IPTRACKER','IP Tracking');
define('_AB_IPTRACKERSETTINGS','IP Tracker Settings');
define('_AB_IPTRACKING','IP Tracking');
define('_AB_IPTRACKINGINFO','IP Tracking Info');
define('_AB_ISCOVERED','is covered by');
define('_AB_ISPROTECTED','Is Protected');
define('_AB_KB','Kb');
define('_AB_LASTIP','Last IP');
define('_AB_LASTVIEWED','Last Viewed');
define('_AB_LISTHTTPAUTH','Admin Auth List');
define('_AB_LOERROR','The IP <b>MUST BE</b> numeric with each octet between 0 and 255!');
define('_AB_LOGIN','HTTPAuth Login');
define('_AB_MATCH','String Match');
define('_AB_MAXIMUMDAYS','Maximum Days');
define('_AB_MB','Mb');
define('_AB_MODULE','Sort by Module');
define('_AB_NO','No');
define('_AB_NOCOUNTRIES','There are currently no Countries in the database!');
define('_AB_NOIPS','There are currently no IP addresses in the database!');
define('_AB_NOMATCHES','There are currently no matches in the database!');
define('_AB_NONE','Not Shown');
define('_AB_NONOTES','No Notes');
define('_AB_NOPAGES','There are currently no Tracked pages in the database');
define('_AB_NORMALLY','Normally');
define('_AB_NOTAVAILABLE','Not Available');
define('_AB_NOTE','Note');
define('_AB_NOTES','Notes');
define('_AB_NOTIFY','Notify');
define('_AB_NOTINSERTED','was not inserted into');
define('_AB_NOTPROTECTED','Not Protected');
define('_AB_NOTSUPPORTED','This function requires Apache to work.');
define('_AB_NOUSERS','There are currently no Tracked users in the database');
define('_AB_NSDISABLED','NukeSentinel(tm) Deactivated');
define('_AB_SENTINEL','NukeSentinel(tm)');
define('_AB_NUKESENTINELICON','NukeSentinel');
define('_AB_OF','of');
define('_AB_OFF','Off');
define('_AB_ON','On');
define('_AB_ONLINE','Online');
define('_AB_OPTIMIZED','Optimized');
define('_AB_OVERHEAD','Overhead');
define('_AB_PAGEDELAY','Page Delay');
define('_AB_PAGES','Pages');
define('_AB_PAGETRACKING','Page Tracking');
define('_AB_PAGEVIEWED','Page Viewed');
define('_AB_PAGEVIEWINFO','Page View Info');
define('_AB_PASSWORD','Auth Password');
define('_AB_PERMENANT','Permanent');
define('_AB_PERPAGE','Per Page');
define('_AB_POST','Post String');
define('_AB_PREVENTDOS','DOS Protection');
define('_AB_ADMINISTRATIVE','Administrative Settings');
define('_AB_PRINT','Print');
define('_AB_PRINTPAGE','Print');
define('_AB_PRINTERFRIENDLY','Printer Friendly View');
define('_AB_PRINTBLOCKEDIPS','Print Blocked IP\'s');
define('_AB_PRINTIP','Print Blocked IP Details');
define('_AB_PRINTTRACKEDAGENTS','Print Tracked Agents');
define('_AB_PRINTTRACKEDIPS','Print Tracked IP\'s');
define('_AB_PRINTTRACKEDREFERS','Print Tracked Refers');
define('_AB_PRINTTRACKEDUSERS','Print Tracked Users');
define('_AB_PROTECTED','Protected');
define('_AB_PROXYBLOCKER','Block Proxies');
define('_AB_PROXYLITE','Lite Level');
define('_AB_PROXYMILD','Mild Level');
define('_AB_PROXYREASON','Reason Blocked');
define('_AB_PROXYSTRONG','Strong Level');
define('_AB_QUERY','Query String');
define('_AB_REASON','Reason');
define('_AB_REASONNO','No Reason');
define('_AB_RECHECKSECTION','Recheck section');
define('_AB_RECORDS','Records');
define('_AB_REFERERBLOCKER','Referer Blocker');
define('_AB_REFERERLIST','Referer List');
define('_AB_REFERERLISTADD','Referer List Add');
define('_AB_REFERERLISTDELETE','Referer List Delete');
define('_AB_REFERIPTRACKING','Referer IP Tracking');
define('_AB_REFERTRACKING','Referer Tracking');
define('_AB_REGDATE','Registered');
define('_AB_REGGLOBALS','HTTPAuth Requires \'register_globals\' to be ON');
define('_AB_REMOTE_ADDR','Remote Address');
define('_AB_REMOTE_PORT','Remote Port');
define('_AB_REQUESTBLOCKER','Request Method Blocker');
define('_AB_REQUESTLIST','Request List');
define('_AB_REQUESTLISTADD','Request List Add');
define('_AB_REQUESTLISTDELETE','Request List Delete');
define('_AB_REQUESTLISTS','Enter 1 Request Method string per line.<br />Strings will be matched against the Request Method.');
define('_AB_REQUEST_METHOD','Request Method');
define('_AB_RESEND','Resend');
define('_AB_SANTY','Possible Santy Worm Attack!');
define('_AB_SANTYPROTECTION','Santy Worm Protection');
define('_AB_SAVEIN','Save this in');
define('_AB_SCANADMINS','Scan for New Admins');
define('_AB_SCANADMINSDONE','Scan for New Admins has completed!');
define('_AB_SCRIPTBLOCKER','Scripting Blocker');
define('_AB_SEARCHBLOCKEDIPS','Blocked IP\'s');
define('_AB_SEARCHBLOCKIPS','Search Blocked IP Addresses');
define('_AB_SEARCHFOR','Search for');
define('_AB_SEARCHIN','Search in');
define('_AB_SEARCH','Search Addresses');
define('_AB_SEARCHIPS','Search IP Addresses');
define('_AB_SEARCHNOTE','Use % to search globally within an octet.<br />i.e. 127.0.0.%, 127.0.%.1, 127.%.0.1, or %.0.0.1');
define('_AB_SEARCHRANGENOTE','This must be a fully quailified range.');
define('_AB_SEARCHTRACKEDIPS','Tracked IP\'s');
define('_AB_SEARCHTRACKIPS','Search Tracked IP Addresses');
define('_AB_SEARCHUSERS','Search Users');
define('_AB_SECTION','Section');
define('_AB_SELECTCOUNTRY','Select Country');
define('_AB_SELECTONE','-- Select One --');
define('_AB_SELFEXPIRE','AutoClear Blocks');
define('_AB_SET','Set');
define('_AB_SITEDISABLED','Site Disabled');
define('_AB_SITEENABLED','Site Enabled');
define('_AB_SITENAME','Sitename');
define('_AB_SITESWITCH','Site Switch');
define('_AB_SITEURL','Site URL');
define('_AB_SIZE','Size');
define('_AB_SORT','Sort');
define('_AB_SORTCOLUMN','Sort Column');
define('_AB_SORTDIRECTION','Sort Direction');
define('_AB_STACCESSPATH','staccess Path');
define('_AB_STATUS','Status');
define('_AB_STRINGBLOCKER','String Blocker');
define('_AB_STRINGLIST','String List');
define('_AB_STRINGLISTADD','String List Add');
define('_AB_STRINGLISTDELETE','String List Delete');
define('_AB_STRINGLISTS','Enter 1 String per line.<br />Strings will be matched against the User Agent.');
define('_AB_STWARNING','File does not exist or is not correctly CHMODed.');
define('_AB_SUBMENU','<br /><img src="images/nukesentinel/Sentinel_Medium.png" height="47" width="102" alt="" title="" />');
define('_AB_TABLE','Table');
define('_AB_TABLES','Table(s)');
define('_AB_TEMPLATE','Default Page');
define('_AB_TEMPLATES','View Templates');
define('_AB_TEMPLATEONLY','Default Page');
define('_AB_TO','to');
define('_AB_TRACKEDAGENTS','Tracked Agents');
define('_AB_TRACKEDIPMENU','Tracked IP Menu');
define('_AB_TRACKEDIPS','Tracked IP\'s');
define('_AB_TRACKEDPAGE','Tracked IP Page Settings');
define('_AB_TRACKEDREFERS','Tracked Refers');
define('_AB_TRACKEDUSERS','Tracked Users');
define('_AB_TYPE','Type');
define('_AB_UNIONBLOCKER','UNION Blocker');
define('_AB_UNKNOWN','Unknown');
define('_AB_UNLIMITED','Unlimited');
define('_AB_UNSET','<i>Unset</i>');
define('_AB_URLS','URLs');
define('_AB_USER','User');
define('_AB_USERAGENT','User Agent');
define('_AB_USEREMAIL','User Email');
define('_AB_USERID','User ID');
define('_AB_USERIPTRACKING','User IP Tracking');
define('_AB_USERREFER','User Refer');
define('_AB_USERS','Users/Admins Only');
define('_AB_USERSDB','Users Last IP\'s');
define('_AB_USERTRACKING','User Tracking');
define('_AB_VIEW','View');
define('_AB_VIEWIP','View Blocked IP');
define('_AB_VIEWTEMPLATE','View Template');
define('_AB_VISITORS','All Visitors');
define('_AB_WASINSERTED','was inserted into');
define('_AB_WHOISFOR','Who-Is for IP');
define('_AB_X_FORWARDED','Forwarded For');
define('_AB_YES','Yes');
define('_AB_YOURAGNT','Your Agent');
define('_AB_YOURIP','Your IP');

?>