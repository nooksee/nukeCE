<html dir="{S_CONTENT_DIRECTION}">
    <head>
        <title>phpBB Administration</title>
        <meta http-equiv="Content-Type" content="text/html;">
        <!-- jQuery -->
        <script src="../../../includes/libraries/jquery.js"></script>
        <!-- Sexy -->
        <script src="../../../includes/libraries/jquery.sexy.js"></script>
        <!-- jQuery UI -->
        <script src="../../../includes/libraries/jquery-ui.js"></script>
        <!-- Layout -->
        <script src="../../../includes/libraries/jquery.layout.js"></script>
        <script type="text/javascript">
            Sexy.css("../includes/css/layout.css");
            Sexy.js("../includes/js/layout.js");
        </script>
    </head>
    <body>
        <div class="ui-layout-west">
            <!-- IFRAME as menu-pane -->
            <iframe class="ui-layout-content" src="{S_FRAME_NAV}" name="nav" longdesc="" width="100%" height="100" frameborder="0" scrolling="auto"></iframe>
        </div>
        <!-- IFRAME as layout-pane -->
        <iframe id="main" name="main" class="ui-layout-center" width="100%" height="600" frameborder="0" scrolling="auto" src="{S_FRAME_MAIN}"></iframe>
    </body>
</html>