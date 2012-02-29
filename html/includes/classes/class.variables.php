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

defined('NUKE_CE') || die('Direct access is not allowed');

define('REGEXP_TLD', '/(\.AC|\.AD|\.AE|\.AERO|\.AF|\.AG|\.AI|\.AL|\.AM|\.AN|\.AO|\.AQ|\.AR|\.ARPA|\.AS|\.AT|\.AU|\.AW|\.AX|\.AZ|\.BA|\.BB|\.BD|\.BE|\.BF|\.BG|\.BH|\.BI|\.BIZ|\.BJ|\.BM|\.BN|\.BO|\.BR|\.BS|\.BT|\.BV|\.BW|\.BY|\.BZ|\.CA|\.CAT|\.CC|\.CD|\.CF|\.CG|\.CH|\.CI|\.CK|\.CL|\.CM|\.CN|\.CO|\.COM|\.COOP|\.CR|\.CU|\.CV|\.CX|\.CY|\.CZ|\.DE|\.DJ|\.DK|\.DM|\.DO|\.DZ|\.EC|\.EDU|\.EE|\.EG|\.ER|\.ES|\.ET|\.EU|\.FI|\.FJ|\.FK|\.FM|\.FO|\.FR|\.GA|\.GB|\.GD|\.GE|\.GF|\.GG|\.GH|\.GI|\.GL|\.GM|\.GN|\.GOV|\.GP|\.GQ|\.GR|\.GS|\.GT|\.GU|\.GW|\.GY|\.HK|\.HM|\.HN|\.HR|\.HT|\.HU|\.ID|\.IE|\.IL|\.IM|\.IN|\.INFO|\.INT|\.IO|\.IQ|\.IR|\.IS|\.IT|\.JE|\.JM|\.JO|\.JOBS|\.JP|\.KE|\.KG|\.KH|\.KI|\.KM|\.KN|\.KR|\.KW|\.KY|\.KZ|\.LA|\.LB|\.LC|\.LI|\.LK|\.LR|\.LS|\.LT|\.LU|\.LV|\.LY|\.MA|\.MC|\.MD|\.MG|\.MH|\.MIL|\.MK|\.ML|\.MM|\.MN|\.MO|\.MOBI|\.MP|\.MQ|\.MR|\.MS|\.MT|\.MU|\.MUSEUM|\.MV|\.MW|\.MX|\.MY|\.MZ|\.NA|\.NAME|\.NC|\.NE|\.NET|\.NF|\.NG|\.NI|\.NL|\.NO|\.NP|\.NR|\.NU|\.NZ|\.OM|\.ORG|\.PA|\.PE|\.PF|\.PG|\.PH|\.PK|\.PL|\.PM|\.PN|\.PR|\.PRO|\.PS|\.PT|\.PW|\.PY|\.QA|\.RE|\.RO|\.RU|\.RW|\.SA|\.SB|\.SC|\.SD|\.SE|\.SG|\.SH|\.SI|\.SJ|\.SK|\.SL|\.SM|\.SN|\.SO|\.SR|\.ST|\.SU|\.SV|\.SY|\.SZ|\.TC|\.TD|\.TEL|\.TF|\.TG|\.TH|\.TJ|\.TK|\.TL|\.TM|\.TN|\.TO|\.TP|\.TR|\.TRAVEL|\.TT|\.TV|\.TW|\.TZ|\.UA|\.UG|\.UK|\.UM|\.US|\.UY|\.UZ|\.VA|\.VC|\.VE|\.VG|\.VI|\.VN|\.VU|\.WF|\.WS|\.YE|\.YT|\.YU|\.ZA|\.ZM|\.ZW)/i');

/**
 * Example:
 * <code>
 * <?php
 *     global $_GETVAR;
 *     //$variable = $_GETVAR->get($var, $loc, $type, $default, $minlen, $maxlen, $regex);
 *     $foo = $_GETVAR->get('foo', '_GET', 'string', null, 1, 10, '/^foo/i');
 *
 *     //Strip slashes for SQL
 *     $sql = 'INSERT INTO `foo` ("", "'.$_GETVAR->fixQuotes($foo).'")';
 * ?>
 * </code>
 */

/**
 * @package v3
 * @subpackage Variables
 */
class Variables
{
    /**
     * The raw or "dirty" variable
     * This should not be used normally
     * @var mixed
     */
    var $rawVariable   = null;
    /**
     * Failed variable
     * @var bool
     */
    var $failed         = false;
    /**
     * Reason for failure
     * @var string
     */
    var $reason        = '';

    /**
     * Constructor
     */
    function variables() { }

    /**
     * Fixes quotes to help against SQL injections
     *
     * @access public
     * @param string $string
     * @return string escaped string
     */
    function fixQuotes($string) {
        global $db;
        //If it is not a number
        if (!is_numeric($string)) {
            $string = str_replace('%27', "'", $string);
            if (isset($db->connect_id)) {
                return mysql_real_escape_string($string, $db->connect_id);
            } else {
                return mysql_real_escape_string($string);
            }
        }
        return $string;
    }
    /**
     * Gets the variable and runs all the sub functions
     *
     * @access public
     *
     * @param string $var the variable to check
     * @param string $loc the location to retrieve the variable
     * @param string $type the type to check against the variable
     * @param string $default the default value to give the variable if there is a failure
     * @param string $minlen the min length to check against variable
     * @param string $maxlen the max length to check against variable
     * @param string $regex the regex to check against the variable
     *
     * @return mixed
     */
    function get($var, $loc, $type='string', $default=null, $minlen='', $maxlen='', $regex='') {
        //Restart
        $this->failed            = false;
        $this->reason            = '';
        $this->rawVariable       = null;

        //Undo magic quotes
        /*static $undo;
        if (!$undo) {
            $undo = true;
            $_POST = $this->_undoMagic($_POST);
            $_GET = $this->_undoMagic($_GET);
            $_REQUEST = $this->_undoMagic($_REQUEST);
        }*/

        //Check for errors
        if (empty($var) || empty($type) || empty($loc)) {
            die('_GETVAR error');
        }

        //Make sure the location is valid
        $loc = $this->_validLocation($loc);
        //Check to make sure the variable is there
        if (!$this->_checkLocation($loc, $var)) return $this->_failed('location', $default);
        //Set the variable and the set $var to the retrieved value
        $var = $this->rawVariable = $this->_getLocation($loc, $var);
        //Type check
        if (!$this->_checkType($var, $type)) return $this->_failed('type', $default);
        //If there is length
        if (!empty($minlen) || !empty($maxlen)) {
            //Check length
            if (!$this->_checkLength($var, $minlen, $maxlen)) return $this->_failed('length', $default);
        }
        //If there is regex
        if (!empty($regex)) {
            //Check the regex
            if (!$this->_checkMatch($var, $regex)) return $this->_failed('pattern', $default);
        }
        //Change the type if needed
        $var = $this->_changetype($var, $type);

        return $var;
    }

    /**
     * Unset everything
     *
     * @access public
     * @param bool $force force the unset
     */
    function unsetVariables($force=false) {
        static $run;

        if ($run) return ;

        $run = true;

        //If registered globals is on
        if ((@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on') || $force) {
            //Code from phpbb v3
        	$not_unset = array(
        		'GLOBALS'	=> true,
        		'_GET'		=> true,
        		'_POST'		=> true,
        		'_COOKIE'	=> true,
        		'_REQUEST'	=> true,
        		'_SERVER'	=> true,
        		'_SESSION'	=> true,
        		'_ENV'		=> true,
        		'_FILES'	=> true,
        		'phpEx'		=> true,
        		'phpbb_root_path'	=> true,
        		'HTTP_POST_VARS' => true,
        		'HTTP_GET_VARS' => true,
        		'HTTP_SERVER_VARS' => true,
        		'HTTP_COOKIE_VARS' => true,
        		'HTTP_ENV_VARS' => true,
        		'HTTP_POST_FILES' => true,
        		'HTTP_SESSION_VARS' => true
        	);

        	// Not only will array_merge and array_keys give a warning if
        	// a parameter is not an array, array_merge will actually fail.
        	// So we check if _SESSION has been initialised.
        	if (!isset($_SESSION) || !is_array($_SESSION))
        	{
        		$_SESSION = array();
        	}

        	// Merge all into one extremely huge array; unset this later
        	$input = array_merge(
        		array_keys($_GET),
        		array_keys($_POST),
        		array_keys($_COOKIE),
        		array_keys($_SERVER),
        		array_keys($_SESSION),
        		array_keys($_ENV),
        		array_keys($_FILES)
        	);

        	foreach ($input as $varname)
        	{
        		if (isset($not_unset[$varname]))
        		{
        			// Hacking attempt. No point in continuing.
        			exit;
        		}

        		unset($GLOBALS[$varname]);
        	}

        	unset($input);
        }
    }
    /**
     * Changes the type to int or float
     *
     * @param mixed $var
     * @param string $type
     * @return mixed
     */
    function _changetype($var, $type) {
        switch (strtolower($type)) {
            case 'int':
            case 'integer': return (is_string($var)) ? (int)$var : $var;
            //
            case 'double':
            case 'float':   return (is_string($var)) ? (float)$var : $var;
            default: return $var;
        }
    }
   /**
     * Checks variable length of a string or the size of a number
     *
     * @access private
     *
     * @param mixed $var variable to check
     * @param string $minlen the min length to check against variable
     * @param string $maxlen the max length to check against variable
     *
     * @return bool
     */
    function _checkLength($var, $minlen, $maxlen) {
        if (empty($var)) return false;
        if (is_numeric($var)) {
            if (empty($minlen)) $minlen = 0;
            if (empty($maxlen)) return $var >= $minlen;
            return ($var >= $minlen && $var <= $maxlen);
        } else if ($this->_checkType($var, 'string')){
            if (!empty($maxlen) && !empty($minlen) && $this->_checkType($maxlen, 'int') && $this->_checkType($minlen, 'int')) {
                return ((strlen($var) >= ((int) $minlen)) && (strlen($var) <= ((int) $maxlen))) ? true : false;
            } else if (!empty($maxlen) && $this->_checkType($maxlen, 'int')) {
                return (strlen($var) <= ((int) $maxlen)) ? true : false;
            } else if ($this->_checkType($minlen, 'int')) {
                return (strlen($var) >= ((int) $minlen)) ? true : false;
            }
        }
        return true;
    }
    /**
     * Checks to make sure the location has valid data
     *
     * @access private
     *
     * @param string $loc the location to check for the variable
     * @param string $var the variable name to check for
     *
     * @return mixed
     */
    function _checkLocation($loc, $var) {
        switch ($loc) {
            case '_GET':
                return isset($_GET[$var]);
            case '_POST':
                return isset($_POST[$var]);
            case '_COOKIE':
                return isset($_COOKIE[$var]);
            case '_SESSION':
                return isset($_SESSION[$var]);
            case '_REQUEST':
                return isset($_REQUEST[$var]);
        }
        return false;
    }
    /**
     * Type checks variable against preg_match pattern
     *
     * @access private
     *
     * @param mixed $var the variable to type check
     * @param string $pattern the pattern to check against variable
     *
     * @return bool
     */
    function _checkMatch($var, $pattern) {
        return (preg_match($pattern, $var)) ? true : false;
    }
    /**
     * Type checks variable type
     *
     * @access private
     *
     * @param mixed $var variable to type check
     * @param string $type what to type check against variable
     *
     * @return bool
     */
    function _checkType($var, $type) {
        switch (strtolower($type)) {
            case 'str':
            case 'string':  return is_string($var);
            //
            case 'int':
            case 'integer': {
                if (is_string($var)) {
                    if (!is_numeric($var)) {
                        return false;
                    } else {
                        return true;
                    }
                }
                return is_int($var);
            }
            //
            case 'double':
            case 'float': {
                if (is_string($var)) {
                    if (!is_numeric($var)) {
                        return false;
                    } else {
                        return true;
                    }
                }
                return is_float($var);
            }
            //
            case 'array':   return is_array($var);
            case 'object':  return is_object($var);
            case 'numeric': return is_numeric($var);
            //
            case 'url': return preg_match(REGEXP_URL, $var);
            //
            case 'email':
                var_dump($var);
                //Test to make sure there is a valid domain
                if (!preg_match(REGEXP_TLD, $var)) return false;
                //Test for @ and if the structure is correct
                return (preg_match('/.*@.*\..*/', $var) && preg_match(REGEXP_EMAIL, $var));
            break;
        }
        return false;
    }
    /**
     * Sets up the failure
     *
     * @access private
     * @param string $reason the reason for the failure
     * @param string $default the default value to send back
     * @return null
     */
    function _failed($reason, $default=null) {
        $this->failed = true;
        $this->reason = $reason;
        return $default;
    }
    /**
     * Gets the data from the location
     *
     * @access private
     * @param string $loc the location to retrieve from
     * @param string $var the variable name to retrieve
     * @return mixed
     */
    function _getLocation($loc, $var) {
        switch ($loc) {
            case '_GET':
                return $_GET[$var];
            case '_POST':
                return $_POST[$var];
            case '_COOKIE':
                return $_COOKIE[$var];
            case '_SESSION':
                return $_SESSION[$var];
            case '_REQUEST':
                return $_REQUEST[$var];
        }
        return null;
    }
    /**
     * Strip slashes from an array of strings
     *
     * @access private
     * @param array $data the data array to strip slashes from
     * @return array escaped string array
     */
    function _stripSlashesArray($data) {
        return is_array($data) ? array_map('stripslashes', $data) : stripslashes($data);
    }
    /**
     * Undoes magic quotes if on
     *
     * @access private
     * @param mixed $data the data to strip
     * @return mixed stripped variable
     */
    function _undoMagic($data) {
        if (empty($data)) return null;
        return (STRIP) ? $this->_stripSlashesArray($data) : $data;
    }
    /**
     * Checks to make sure the location passed in was valid
     *
     * @access private
     * @param string $loc the location to validate
     * @return string a valid location
     */
    function _validLocation($loc) {
        switch (strtolower($loc)) {
            case 'get':
            case '$_get':
            case '_get':        return '_GET';
            //
            case 'post':
            case '$_post':
            case '_post':       return '_POST';
            //
            case 'cookie':
            case '$_cookie':
            case '_cookie':     return '_COOKIE';
            //
            case 'session':
            case '$_session':
            case '_session':    return '_SESSION';
            //
            case 'request':
            case '$_request':
            case '_request':
            default:            return '_REQUEST';
        }
    }
}

global $_GETVAR;
$_GETVAR =& new Variables();

/**
 * @todo
 * Type can be an array
 */
?>