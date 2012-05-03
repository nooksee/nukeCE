<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center">
    <tr>
        <td align="center" ><a href="../../../{U_FORUM_INDEX}" target="_parent"><img src="../templates/subSilver/images/logo_phpBB_med.gif" alt="phpBB" border="0" /></a></td>
    </tr>
    <tr>
        <td align="center" >
            <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
                <tr>
                    <th height="25" class="thHead"><strong>{L_ADMIN}</strong></th>
                </tr>
                <tr class="category exp">
                    <td height="28" class="catSides" style="cursor:pointer;cursor:hand;"><span class="cattitle">{L_QUICK_LINKS}</span></td>
                </tr>
                <tr class="menuCATS"> 
                    <td class="row1">
                        <div style="display:block;">
                            <table width="100%" cellpadding="4" cellspacing="1" border="0" class="bodyline">
                                <tr>
                                    <td class="row1">
                                        <div style="display:block;" class="genmed">
                                            <a href="{U_HOME_NUKE}" target="_parent" class="genmed">{L_HOME_NUKE}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="row1">
                                        <div style="display:block;" class="genmed">
                                            <a href="{U_ADMIN_NUKE}" target="_parent" class="genmed">{L_ADMIN_NUKE}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="row1">
                                        <div style="display:block;" class="genmed">
                                            <a href="{U_ADMIN_INDEX}" target="main" class="genmed">{L_ADMIN_INDEX}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="row1">
                                        <div style="display:block;" class="genmed">
                                            <a href="../../../{U_FORUM_INDEX}" target="_parent" class="genmed">{L_FORUM_INDEX}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="row1">
                                        <div style="display:block;" class="genmed">
                                            <a href="../../../{U_FORUM_PREINDEX}" target="main" class="genmed">{L_PREVIEW_FORUM}</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <!-- BEGIN catrow -->
                <tr class="category col">
                    <td height="28" class="catSides" style="cursor:pointer;cursor:hand;"><span class="cattitle">{catrow.ADMIN_CATEGORY}</span></td>
                </tr>
                <tr class="menuCATS"> 
                    <td class="row1">
                        <div id="menuCat_{catrow.MENU_CAT_ID}" style="display:block;">
                            <table width="100%" cellpadding="4" cellspacing="1" border="0" class="bodyline">
                                <!-- BEGIN modulerow -->
                                <tr>
                                    <td class="row1">
                                        <div id="menuCat_{catrow.MENU_CAT_ID}_{catrow.modulerow.ROW_COUNT}" style="display:block;" class="genmed">
                                            <a href="{catrow.modulerow.U_ADMIN_MODULE}"  target="main" class="genmed">{catrow.modulerow.ADMIN_MODULE}</a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- END modulerow -->
                            </table>
                        </div>
                    </td>
                </tr>
                <!-- END catrow -->
            </table>
        </td>
    </tr>
</table>
<div style="height: 12px; line-height: 12px;">&nbsp;</div>