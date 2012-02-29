<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>
<link rel="stylesheet" href="modules/Forums/templates/subSilver/subSilver.css" type="text/css">
<!-- start mod : Resize Posted Images Based on Max Width -->
<script type="text/javascript">
//<![CDATA[
<!--

var rmw_max_width = {IMAGE_RESIZE_WIDTH}; // you can change this number, this is the max width in pixels for posted images
var rmw_border_1 = '1px solid {T_BODY_LINK}';
var rmw_border_2 = '2px dotted {T_BODY_LINK}';
var rmw_image_title = '{L_RMW_IMAGE_TITLE}';

//-->
//]]>
</script>
<script type="text/javascript" src="{U_RMW_JSLIB}"></script>
<!-- fin mod : Resize Posted Images Based on Max Width -->
<!-- BEGIN switch_enable_pm_popup -->
<script type="text/javascript">
<!--
    if ( {PRIVATE_MESSAGE_NEW_FLAG} )
    {
        window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
    }
//-->
</script>
<!-- END switch_enable_pm_popup -->
<!-- Start add - Advanced time management MOD -->
<!-- BEGIN switch_send_pc_dateTime -->
<script type="text/javascript">
<!-- Start Replace - window.onload = send_pc_dateTime -->
send_pc_dateTime();
<!-- End Replace - window.onload = send_pc_dateTime -->

function send_pc_dateTime() {
    var pc_dateTime = new Date()
    pc_timezoneOffset = pc_dateTime.getTimezoneOffset()*(-60);
    pc_date = pc_dateTime.getFullYear()*10000 + (pc_dateTime.getMonth()+1)*100 + pc_dateTime.getDate();
    pc_time = pc_dateTime.getHours()*3600 + pc_dateTime.getMinutes()*60 + pc_dateTime.getSeconds();

    for ( i = 0; document.links.length > i; i++ ) {
        with ( document.links[i] ) {
            if ( href.indexOf('{U_SELF}') == 0 ) {
                textLink = '' + document.links[i].firstChild.data
                if ( textLink.indexOf('http://') != 0 && textLink.indexOf('www.') != 0 && (textLink.indexOf('@') == -1 || textLink.indexOf('@') == 0 || textLink.indexOf('@') == textLink.length-1 )) {
                    if ( href.indexOf('?') == -1 ) {
                        pc_data = '?pc_tzo=' + pc_timezoneOffset + '&pc_d=' + pc_date + '&pc_t=' + pc_time;
                    } else {
                        pc_data = '&pc_tzo=' + pc_timezoneOffset + '&pc_d=' + pc_date + '&pc_t=' + pc_time;
                    }
                    if ( href.indexOf('#') == -1 ) {
                        href += pc_data;
                    } else {
                        href = href.substring(0, href.indexOf('#')-1) + pc_data + href.substring(href.indexOf('#'), href.length-1);
                    }
                }
            }
        }
    }
}
</script>
<!-- END switch_send_pc_dateTime -->
<!-- End add - Advanced time management MOD -->
</head>
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">

<a name="top"></a>

<table width="100%" cellspacing="0" cellpadding="10" border="0" align="center"> 
    <tr> 
        <td class="bodyline"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr> 
                <table cellspacing="0" cellpadding="2" border="0" align="center">
                    <tr>
                        <td align="center" valign="top" nowrap="nowrap"><center><span class="mainmenu">&nbsp;<a href="{U_FAQ}" class="mainmenu">{I_MINI_FAQ}{L_FAQ}</a>&nbsp;&nbsp;<a href="{U_SEARCH}" class="mainmenu">{I_MINI_SEARCH}{L_SEARCH}</a>&nbsp;&nbsp;<a href="{U_GROUP_CP}" class="mainmenu">{I_MINI_USERGROUPS}{L_USERGROUPS}</a>&nbsp;&nbsp;<a href="{U_PROFILE}" class="mainmenu">{I_MINI_PROFILE}{L_PROFILE}</a>&nbsp;&nbsp;<a href="{U_PRIVATEMSGS}" class="mainmenu">{I_MINI_PRIVATEMSGS}{PRIVATE_MESSAGE_INFO}</a>&nbsp;&nbsp;<a href="{U_LOGIN_LOGOUT}" class="mainmenu">{I_MINI_LOGIN_LOGOUT}{L_LOGIN_LOGOUT}</a>&nbsp;</span></center></td>
                    </tr>
                </table></td>
            </tr>
        </table>

<!-- Quick Search -->
<!-- BEGIN switch_quick_search -->
<br /><script type="text/javascript">
<!--
function checkSearch()
{
    {switch_quick_search.CHECKSEARCH}
    else
    {
        return true;
    }
}
//-->
</script>
<center>
<table width="100%" cellpadding="2" cellspacing="1" border="0"><form name="search_block" method="post" action="{U_SEARCH}" onSubmit="return 
checkSearch()">
  <tr>            
    <td align="center"><span class="gensmall" style="line-height=150%">
    {switch_quick_search.L_QUICK_SEARCH_FOR} <input class="post" type="text" name="search_keywords" size="15"> 
{switch_quick_search.L_QUICK_SEARCH_AT} <select class="post" name="site_search">{switch_quick_search.SEARCHLIST}</select>
    <input class="mainoption" type="submit" value="{L_SEARCH}"></span></td>
  </tr>
  <tr>
    <td align="center"><a href="{U_SEARCH}" class="gensmall">{switch_quick_search.L_ADVANCED_FORUM_SEARCH}</a></td>
  </tr>
<input type="hidden" name="search_fields" value="all">
<input type="hidden" name="show_results" value="topics"></form>
</table>
</center>
<!-- END switch_quick_search -->

<!-- BEGIN boarddisabled -->
  <br /><div align="center"><span class="gen"><strong>{L_BOARD_CURRENTLY_DISABLED}</strong></span></div><br />
<!-- END boarddisabled -->

        <br />