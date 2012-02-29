<span class="gen"><br /></span>
<table width="{GLANCE_TABLE_WIDTH}" cellpadding="4" cellspacing="1" border="0" class="forumline">
    <tr>
        <th colspan="2" align="center" height="25" class="thCornerL" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
        <th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
        <th width="100" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</th>
        <th width="50" align="center" class="thTop" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
        <th align="center" class="thCornerR" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
    </tr>
    <!-- BEGIN switch_glance_news -->
    <tr> 
        <td class="catHead" colspan="6" height="28"><span class="cattitle">{NEWS_HEADING}</span></td>
    </tr>
    <!-- BEGIN switch_news_on -->
    <tbody id="phpbbGlance_news" style="display: ;">
    <!-- END switch_news_on -->
    <!-- BEGIN switch_news_off -->
    <tbody id="phpbbGlance_news" style="display: none;">
    <!-- END switch_news_off -->  

    <!-- END switch_glance_news -->
    <!-- BEGIN news -->
    <!-- BEGIN divider -->
    <tr> 
        <td class="catHead" colspan="6" height="28"><span class="cattitle">{topicrow.divider.L_DIV_HEADERS}</span></td>
    </tr>
    <!-- END divider -->
    <tr> 
        <td class="row1" align="center" valign="middle" width="20"><a href="{news.TOPIC_LINK}" class="topictitle">{news.BULLET}</a></td>
        <td class="row1" width="100%"><span class="topictitle"><a href="{news.TOPIC_LINK}" class="topictitle">{news.TOPIC_TITLE}</a></span><span class="gensmall"><br /></span></td>
        <td valign="middle" class="row2" nowrap="nowrap" align="center"><span class="genmed"><a href="{news.FORUM_LINK}" class="genmed">{news.FORUM_TITLE}</a></span></td>
        <td valign="middle" class="row3" nowrap="nowrap" align="center"><span class="genmed">{news.TOPIC_POSTER}</span></td>
        <td valign="middle" class="row2" nowrap="nowrap" align="center"><span class="genmed">{news.TOPIC_REPLIES}</span></td>
        <td valign="middle" class="row3" nowrap="nowrap" align="center"><span class="genmed">{news.TOPIC_TIME}<br />{news.LAST_POSTER}</span></td>
    </tr>
    <!-- END news -->

    <!-- BEGIN switch_glance_recent -->
    <tr> 
        <td class="catHead" colspan="6" height="28"><span class="cattitle">{RECENT_HEADING}</span></td>
    </tr>
    <!-- BEGIN switch_recent_on -->
    <tbody id="phpbbGlance_recent" style="display: ;">
    <!-- END switch_recent_on -->
    <!-- BEGIN switch_recent_off -->
    <tbody id="phpbbGlance_recent" style="display: none;">
    <!-- END switch_recent_off -->  

    <!-- END switch_glance_recent -->

    <!-- BEGIN recent -->
    <tr>
        <td class="row1" align="center" valign="middle" width="20"><a href="{recent.TOPIC_LINK}" class="topictitle">{recent.BULLET}</a></td>
        <td class="row1" width="100%"><span class="topictitle"><a href="{recent.TOPIC_LINK}" class="topictitle">{recent.TOPIC_TITLE}</a></span><span class="gensmall"><br /></span></td>
        <td valign="middle" class="row2" nowrap="nowrap" align="center"><span class="genmed"><a href="{recent.FORUM_LINK}" class="genmed">{recent.FORUM_TITLE}</a></span></td>
        <td valign="middle" class="row3" nowrap="nowrap" align="center"><span class="genmed">{recent.TOPIC_POSTER}</span></td>
        <td valign="middle" class="row2" nowrap="nowrap" align="center"><span class="genmed">{recent.TOPIC_REPLIES}</span></td>
        <td valign="middle" class="row3" nowrap="nowrap" align="center"><span class="genmed">{recent.LAST_POST_TIME}<br />{recent.LAST_POSTER}</span></td>        
    </tr>
    <!-- END recent -->
</table>
<span class="gen"><br /></span>