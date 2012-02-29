<body style="background-color:#e5e5e5;">
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
            <td>
                <table style="background-color: #FFFFFF; border-color: #006699; border-style: solid; border-width: 1px;" width="100%" cellpadding="4" cellspacing="1" border="0">
                    <tr>
                        <th style="background-color: #006699; background-image: url(modules/Forums/templates/subSilver/images/cellpic3.gif); color: #FFA34F; font-size: 11px; font-weight: bold; height: 25px;" align="center">{L_RULES_TITLE}</th>
                    </tr>
                    <!-- BEGIN switch_nothing -->
                    <tr>
                        <td style="background-color: #EFEFEF; color: inherit;" width="100%" align="center"><span class="gen">{L_EMPTY_GROUP_PERMS}</span></td>
                    </tr>
                    <!-- END switch_nothing -->
                    <!-- BEGIN group_row -->
                    <tr>
                        <td style="background-color: #EFEFEF; color: inherit;" width="100%" align="center">
                            <table style="background-color: #FFFFFF; border-color: #006699; border-style: solid; border-width: 1px;" width="100%" cellpadding="4" cellspacing="1" border="0">
                                <tr>
                                    <th style="background-color: #006699; background-image: url(modules/Forums/templates/subSilver/images/cellpic3.gif); color: #FFA34F; font-size: 11px; font-weight: bold; height: 25px;" align="center">{group_row.GROUP_RULE_HEADER}</th>
                                </tr>
                                <tr>
                                    <td style="background-color: #EFEFEF; color: inherit;" align="center"><span class="gen">
                                    <!-- BEGIN extension_row -->
                                    {group_row.extension_row.EXTENSION}&nbsp;
                                    <!-- END extension_row -->
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- END group_row -->
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
        <tr>
            <td align="center"><span class="genmed"><a href="javascript:window.close();" style="BACKGROUND: none; COLOR: #000000; FONT-SIZE: 11px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none;">{L_CLOSE_WINDOW}</a></span></td>
        </tr>
    </table>