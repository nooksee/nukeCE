<style type="text/css">
<!--
-->
</style>
<!-- BEGIN attach -->
<div style="height: 18px; line-height: 18px;">&nbsp;</div>
<!-- BEGIN denyrow -->
<hr style="height: 2px; width: 100%; margin-left: 0px; margin-right: auto;">
<table style="text-align: left; width: 100%;" class="forumline" border="0" cellpadding="3" cellspacing="1">
    <tr>
        <th class="thHead" height="25"></th>
    </tr>
    <tr> 
        <td class="row1">
            <table width="100%" cellspacing="0" cellpadding="1" border="0">
                <tr> 
                    <td>&nbsp;</td>
                </tr>
                <tr> 
                    <td align="center"><span class="gen">{postrow.attach.denyrow.L_DENIED}</span></td>
                </tr>
                <tr> 
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- END denyrow -->
<!-- BEGIN cat_stream -->
<hr style="height: 2px; width: 100%; margin-left: 0px; margin-right: auto;">
<table style="text-align: left; width: 100%;" class="forumline" border="0" cellpadding="3" cellspacing="1">
    <tbody>
        <tr>
            <th colspan="3" class="thHead" align="center" width="100%">{postrow.attach.cat_stream.DOWNLOAD_NAME}</th>
        </tr>    
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_DESCRIPTION}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_stream.COMMENT}</span></td>
            <td class="row3" align="center" rowspan="4" width="12%"><a href="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}" {postrow.attach.cat_stream.TARGET_BLANK} class="genmed">{postrow.attach.cat_stream.S_UPLOAD_IMAGE}<br /><strong>{L_DOWNLOAD}</strong></a></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_FILESIZE}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_stream.FILESIZE} {postrow.attach.cat_stream.SIZE_VAR}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{postrow.attach.cat_stream.L_DOWNLOADED_VIEWED}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_stream.L_DOWNLOAD_COUNT}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_PREVIEW}:</span></td>
            <td class="row2" align="center" width="76%">
                <span class="genmed">
                    <object id="wmp" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"> 
                        <param name="FileName" value="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}"> 
                        <param name="ShowControls" value="1"> 
                        <param name="ShowDisplay" value="0"> 
                        <param name="ShowStatusBar" value="1"> 
                        <param name="AutoSize" value="1"> 
                        <param name="AutoStart" value="0"> 
                        <param name="Visible" value="1"> 
                        <param name="AnimationStart" value="0"> 
                        <param name="Loop" value="0"> 
                        <embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autosize=1 autostart=0 visible=1 animationatstart=0 loop=0></embed>
                    </object>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<!-- END cat_stream -->
<!-- BEGIN cat_swf -->
<hr style="height: 2px; width: 100%; margin-left: 0px; margin-right: auto;">
<table style="text-align: left; width: 100%;" class="forumline" border="0" cellpadding="3" cellspacing="1">
    <tbody>
        <tr>
            <th colspan="3" class="thHead" align="center" width="100%">{postrow.attach.cat_swf.DOWNLOAD_NAME}</th>
        </tr>    
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_DESCRIPTION}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_swf.COMMENT}</span></td>
            <td class="row3" align="center" rowspan="4" width="12%"><a href="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}" {postrow.attach.cat_swf.TARGET_BLANK} class="genmed">{postrow.attach.cat_swf.S_UPLOAD_IMAGE}<br /><strong>{L_DOWNLOAD}</strong></a></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_FILESIZE}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_swf.FILESIZE} {postrow.attach.cat_swf.SIZE_VAR}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{postrow.attach.cat_swf.L_DOWNLOADED_VIEWED}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_swf.L_DOWNLOAD_COUNT}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_PREVIEW}:</span></td>
            <td class="row2" align="center" width="76%">
                <span class="genmed">
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="100%" height="100%"> 
                        <param name=movie value="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}">
                        <param name="AllowScriptAccess" value="never">
                        <param name=loop value=1> 
                        <param name=quality value=high> 
                        <param name=scale VALUE=default> 
                        <param name=wmode value=transparent> 
                        <param name=bgcolor value=#000000> 
                        <embed src="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}" loop=1 AllowScriptAccess="never" quality=high scale=default wmode=transparent bgcolor=#000000  width="100%" height="100%" type="application/x-shockwave-flash" pluginspace="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
                    </object>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<!-- END cat_swf -->
<!-- BEGIN cat_images -->
<hr style="height: 2px; width: 100%; margin-left: 0px; margin-right: auto;">
<table style="text-align: left; width: 100%;" class="forumline" border="0" cellpadding="3" cellspacing="1">
    <tbody>
        <tr>
            <th colspan="3" class="thHead" align="center" width="100%">{postrow.attach.cat_images.DOWNLOAD_NAME}</th>
        </tr>    
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_DESCRIPTION}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_images.COMMENT}</span></td>
            <td class="row3" align="center" rowspan="4" width="12%"><a href="{postrow.attach.cat_images.IMG_SRC}" {postrow.attach.cat_images.TARGET_BLANK} class="genmed">{postrow.attach.cat_images.S_UPLOAD_IMAGE}<br /><strong>{L_DOWNLOAD}</strong></a></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_FILESIZE}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_images.FILESIZE} {postrow.attach.cat_images.SIZE_VAR}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{postrow.attach.cat_images.L_DOWNLOADED_VIEWED}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_images.L_DOWNLOAD_COUNT}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_PREVIEW}:</span></td>
            <td class="row2" align="center" width="76%"><span class="genmed"><img class="resize" src="{postrow.attach.cat_images.IMG_SRC}" alt="{postrow.attach.cat_images.DOWNLOAD_NAME}" border="0" /></span></td>
        </tr>
    </tbody>
</table>
<!-- END cat_images -->
<!-- BEGIN cat_thumb_images -->
<hr style="height: 2px; width: 100%; margin-left: 0px; margin-right: auto;">
<table style="text-align: left; width: 100%;" class="forumline" border="0" cellpadding="3" cellspacing="1">
    <tbody>
        <tr>
            <th colspan="3" class="thHead" align="center" width="100%">{postrow.attach.cat_thumb_images.DOWNLOAD_NAME}</th>
        </tr>    
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_DESCRIPTION}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_thumb_images.COMMENT}</span></td>
            <td class="row3" align="center" rowspan="4" width="12%"><a href="{postrow.attach.cat_thumb_images.U_DOWNLOAD_LINK}" {postrow.attach.cat_thumb_images.TARGET_BLANK} class="genmed">{postrow.attach.cat_thumb_images.S_UPLOAD_IMAGE}<br /><strong>{L_DOWNLOAD}</strong></a></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_FILESIZE}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_thumb_images.FILESIZE} {postrow.attach.cat_thumb_images.SIZE_VAR}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{postrow.attach.cat_thumb_images.L_DOWNLOADED_VIEWED}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.cat_thumb_images.L_DOWNLOAD_COUNT}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_PREVIEW}:</span></td>
            <td class="row2" align="center" width="76%"><span class="genmed"><a href="{postrow.attach.cat_thumb_images.IMG_SRC}" class="fullsize"><img src="{postrow.attach.cat_thumb_images.IMG_THUMB_SRC}" alt="{postrow.attach.cat_thumb_images.DOWNLOAD_NAME}" border="0" /></a></span></td>
        </tr>
    </tbody>
</table>
<!-- END cat_thumb_images -->
<!-- BEGIN attachrow -->
<hr style="height: 2px; width: 100%; margin-left: 0px; margin-right: auto;">
<table style="text-align: left; width: 100%;" class="forumline" border="0" cellpadding="3" cellspacing="1">
    <tbody>
        <tr>
            <th colspan="3" class="thHead" align="center" width="100%">{postrow.attach.attachrow.DOWNLOAD_NAME}</th>
        </tr>    
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_DESCRIPTION}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.attachrow.COMMENT}</span></td>
            <td class="row3" align="center" rowspan="4" width="12%"><a href="{postrow.attach.attachrow.U_DOWNLOAD_LINK}" {postrow.attach.attachrow.TARGET_BLANK} class="genmed">{postrow.attach.attachrow.S_UPLOAD_IMAGE}<br /><strong>{L_DOWNLOAD}</strong></a></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_FILENAME}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.attachrow.DOWNLOAD_NAME}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{L_FILESIZE}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.attachrow.FILESIZE} {postrow.attach.attachrow.SIZE_VAR}</span></td>
        </tr>
        <tr>
            <td class="row1" width="12%"><span class="genmed">{postrow.attach.attachrow.L_DOWNLOADED_VIEWED}:</span></td>
            <td class="row2" width="76%"><span class="genmed">{postrow.attach.attachrow.L_DOWNLOAD_COUNT}</span></td>
        </tr>
    </tbody>
</table>
<!-- END attachrow -->
<div style="height: 18px; line-height: 18px;">&nbsp;</div>        
<!-- END attach -->