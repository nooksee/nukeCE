<?php

class Sajax {
    var $sajax_version;    
    var $sajax_debug_mode;
    var $sajax_export_array;
    var $sajax_export_list;
    var $sajax_request_type;
    var $sajax_remote_uri;
    var $sajax_failure_redirect;
    var $sajax_js_has_been_shown;
    var $sajax_added_scripts = '';

    function Sajax() {
        $this->sajax_version = '0.13';    
        $this->sajax_debug_mode = false;
        $this->sajax_export_array = array();
        $this->sajax_export_list = array();
        $this->sajax_remote_uri = $_SERVER["REQUEST_URI"];
        $this->sajax_failure_redirect = '';
        $this->sajax_request_type = 'GET';
        $this->sajax_js_has_been_shown = false;
    }

    function sajax_handle_client_request() {
        if (empty($_GET["rs"]) && empty($_POST["rs"]))
        return;

        ob_start();

        if (!empty($_GET["rs"])) {
            // Always call server
            header ("Cache-Control: max-age=0, must-revalidate"); // HTTP/1.1
            header ("Pragma: no-cache"); // HTTP/1.0
            $func_name = $_GET["rs"];
            if (! empty($_GET["rsargs"]))
            $args = $_GET["rsargs"];
        } else {
            $func_name = $_POST["rs"];
            if (! empty($_POST["rsargs"]))
            $args = $_POST["rsargs"];
        }

        if(! empty($args)) {
            if(get_magic_quotes_gpc())
            $args = stripslashes($args);

            $args = json_decode($args, true);

            if(get_magic_quotes_gpc()) {
                function array_addslashes($value) {
                    if(is_array($value))
                    return array_map("array_addslashes", $value);
                    else
                    return addslashes($value);
                }

                $args = array_map("array_addslashes", $args);
            }
        } else {
            $args = array();
        }

        global $sajax_export_list;

        if (! in_array($func_name, $sajax_export_list)) {
            $error = $func_name." not callable";
        } else {
            $result = call_user_func_array($func_name, $args);

            $error = ob_get_contents();
            ob_end_clean();
        }

        header('Content-Type: text/plain; charset=UTF-8');
        if(!empty($error)) {
            echo '-:'.$error;
        } else {
            echo "+:".json_encode($result);
        }
        exit;
    }

    function sajax_show_javascript() {
        global $sajax_js_has_been_shown;
        global $sajax_debug_mode;
        global $sajax_failure_redirect;

        if (! $sajax_js_has_been_shown) {

            ?>
            sajax_debug_mode = <?php echo($sajax_debug_mode ? "true" : "false"); ?>;
            sajax_failure_redirect = "<?php echo($sajax_failure_redirect); ?>";
            <?php
            
            global $sajax_export_array;
            foreach($sajax_export_array as $function) {
                ?>
                function x_<?php echo($function["name"]); ?>() {
                    return sajax_do_call("<?php echo($function["name"]); ?>", arguments, "<?php echo($function["method"]); ?>", <?php echo($function["asynchronous"] ? 'true' : 'false'); ?>, "<?php echo($function["uri"]); ?>");
                }
                <?php
            }
            $sajax_js_has_been_shown = true;
        }
        echo $this->sajax_added_scripts;
    }

    function sajax_export() {
        global $sajax_export_array;
        global $sajax_export_list;
        global $sajax_request_type;
        global $sajax_remote_uri;

        $num = func_num_args();
        for ($i=0; $i<$num; $i++) {
            $function = func_get_arg($i);

            if(!is_array($function))
            $function = array("name" => $function);

            if(!isset($function["method"]))
            $function["method"] = $sajax_request_type;

            if(!isset($function["asynchronous"]))
            $function["asynchronous"] = true;

            if(!isset($function["uri"]))
            $function["uri"] = $sajax_remote_uri;

            $key = array_search($function["name"], $sajax_export_list);
            if ($key === false) { 
                $sajax_export_array[] = $function;
                $sajax_export_list[] = $function["name"];
            } else {
                //Overwrite old function
                $sajax_export_array[$key] = $function;
                $sajax_export_list[$key] = $function["name"];
            }
        }
    }

    function sajax_add_script($scr) {
        $this->sajax_added_scripts .= $scr;
    }
}

?>