<?php
/***************************************************************************
 *                                 mysql.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: mysql.php,v 1.16 2002/03/19 01:07:36 psotfx Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if(!defined("SQL_LAYER"))
{

define("SQL_LAYER","mysql");
define('SQL_NUM', MYSQL_NUM);
define('SQL_BOTH', MYSQL_BOTH);
define('SQL_ASSOC', MYSQL_ASSOC);
define('END_TRANSACTION', 2);

class sql_db
{

	var $mysql_version;
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
    var $time;
    var $debug = 0;
    var $saved = '';
    var $connect_id;
	var $querylist = array();
	var $file;
	var $line;
	var $qtime;

	function _backtrace_log($query, $failed=false, $queryid=0)
	{
		global $debug;
		if (!is_bool($debug) && $debug == 'full') {
			$this->_backtrace();
			if ($failed) {
				$this->querylist[$this->file][] = '<span style="color: #FF0000; font-weight: bold;">FAILED LINE '.$this->line.':</span> '.htmlspecialchars($query);
			} else {
    			$this->querylist[$this->file][(int)$queryid] = '<span style="font-weight: bold;">LINE '.$this->line.':</span> '.htmlspecialchars($query);
    			if (isset($this->qtime[(int)$queryid])) {
        			$time = (isset($this->qtime[(int)$queryid])) ? ' QTIME:'.substr($this->qtime[(int)$queryid],0,5) : '';
        			$this->querylist[$this->file][(int)$queryid] .= $time;
    			}
			}
		}
	}
	function _backtrace()
	{
		$this->file = 'unknown';
		$this->line = 0;
		if (version_compare(phpversion(), '4.3.0', '>=')) {
			$tmp = debug_backtrace();
			for ($i=0; $i<count($tmp); ++$i) {
				if (!preg_match('#[\\\/]{1}includes[\\\/]{1}db[\\\/]{1}[a-z_]+.php$#', $tmp[$i]['file'])) {
					$this->file = $tmp[$i]['file'];
					$this->line = $tmp[$i]['line'];
					break;
				}
			}
		}
	}

	//
	// Constructor
	//
	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		if($this->persistency)
		{
			$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
		}
		else
		{
			$this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
		}

		if ($this->db_connect_id && $this->dbname != '')
		{
			if (@mysql_select_db($this->dbname))
			{
				// Determine what version we are using and if it natively supports UNICODE
				$this->mysql_version = mysql_get_server_info($this->db_connect_id);

				/*if (version_compare($this->mysql_version, '4.1.3', '>='))
				{
					mysql_query("SET NAMES 'utf8'", $this->db_connect_id);
					mysql_query("SET CHARACTER SET 'utf8'", $this->db_connect_id);
				}*/
				$this->connect_id = $this->db_connect_id;
				return $this->db_connect_id;
			}
		}

		return false;
	}

	//
	// Other base methods
	//
	function sql_close()
	{
		if($this->db_connect_id)
		{
			if($this->query_result)
			{
				@mysql_free_result($this->query_result);
			}
			$result = @mysql_close($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
    function check_query($query) {
        global $prefix, $cache;
        if(!$cache->valid) return;
        if (!stristr($query, "UPDATE") && !stristr($query, "INSERT") && !stristr($query, "DELETE")) { return; }
        $tables = array(
                      'nukeconfig' => $prefix . '_config',
                      'sysconfig' => $prefix . '_system',
                      'board_config' => $prefix . '_bbconfig',
                      'blocks' => $prefix . '_blocks',
                      'ya_config' => $prefix . '_users_config',
                      'block_modules' => $prefix . '_modules',
                       );
        while(list($file, $table) = each($tables)) {
            if (stristr($query, $table)) {
				$cache->delete($file, 'config');
            }
        }
        return;
    }
    function union_secure($query) {
        // check if it is a SELECT query
        if (strtoupper($query[0]) == 'S') {
            // SPLIT when theres 'UNION (ALL|DISTINT|SELECT)'
            $query_parts = preg_split('/(union)([\s\ \*\/]+)(all|distinct|select)/i', $query, -1, PREG_SPLIT_NO_EMPTY);
            // and then merge the query_parts:
            if (count($query_parts) > 1) {
                $query = '';
                foreach($query_parts AS $part) {
                    $query .= 'UNI0N SELECT'; // A Zero
                    $query .= $part;
                }
            }
        }
    }
	//
	// Base query method
	//
	function sql_query($query = "", $transaction = FALSE)
	{
	    // Get time before query
        $stime = get_microtime();
        $qtime = get_microtime();

		// Remove any pre-existing queries
		if (isset($this->query_result)) unset($this->query_result);
		if($query != '')
		{
            if(SQL_LAYER == 'mysql') {
                $this->union_secure($query);
            }
            if($this->debug) {
                $this->saved .= $query . "<br />";
            }
            $this->num_queries++;
			$this->query_result = @mysql_query($query, $this->db_connect_id);
		}
		if($this->query_result)
		{
		    //Check query to clear cache?
            $this->check_query($query);
            $this->time += (get_microtime()-$stime);
            $this->qtime[(int) $this->query_result] = (get_microtime()-$qtime);
			$this->_backtrace_log($query, false, (int)$this->query_result);
			if (isset($this->row[(int) $this->query_result])) unset($this->row[(int) $this->query_result]);
			if (isset($this->rowset[(int) $this->query_result])) unset($this->rowset[(int) $this->query_result]);
			return $this->query_result;
		}
		else
		{
            //Get the error array
            $sqlerror = $this->sql_error();

            $this->_backtrace();
            $logdata = array('File: '. $this->file,
                             'Line: '. $this->line,
                             'Message: ' . $sqlerror['message'],
                             'Code: ' . $sqlerror['code'],
                             'Query: ' . $query
                            );
            //Log error
            log_write('error', $logdata, 'SQL Error');

			// backtrace
			$this->_backtrace_log($query, true);
            //Calc runtime
            $this->time += (get_microtime()-$stime);

			return ( $transaction == END_TRANSACTION ) ? true : false;
		}
	}
    function sql_uquery($query)
    {
        return $this->sql_query($query, true);
    }

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_rows($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_affectedrows()
	{
		if($this->db_connect_id)
		{
			$result = @mysql_affected_rows($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_fields($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldname($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldtype($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrow($query_id = 0, $trash=0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
		    $stime = get_microtime();
			$this->row[(int) $query_id] = @mysql_fetch_array($query_id);
			$this->time += (get_microtime()-$stime);
			return $this->row[(int) $query_id];
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrowset($query_id = 0)
	{
	    $stime = get_microtime();
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if (isset($this->rowset[(int) $query_id])) unset($this->rowset[(int) $query_id]);
			if (isset($this->row[(int) $query_id])) unset($this->row[(int) $query_id]);
			$result = null;
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id))
			{
				$result[] = $this->rowset[(int) $query_id];
			}
			$this->time += (get_microtime()-$stime);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				$result = @mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if($this->rowset[$query_id])
					{
						$result = $this->rowset[$query_id][$field];
					}
					else if($this->row[$query_id])
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_nextid(){
		if($this->db_connect_id)
		{
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		}
	    return false;
	}
	function sql_freeresult($query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
		    if (isset($this->row[(int) $query_id])) unset($this->row[(int) $query_id]);
			if (isset($this->rowset[(int) $query_id])) unset($this->rowset[(int) $query_id]);

			@mysql_free_result($query_id);

			if (isset($this->querylist[$this->file][(int)$query_id])) {
			    $this->querylist[$this->file][(int)$query_id] .= '<span style="color: #0000FF; font-weight: bold;"> *</span>';
			}

			return true;
		}
		else
		{
			return false;
		}
	}
    function sql_escapestring($string)
    {
        return $this->sql_addq($string);
    }
    function sql_addq($string)
    {
        static $magic_quotes;
        if (!isset($magic_quotes)) $magic_quotes = get_magic_quotes_gpc();
        if ($magic_quotes) $string = stripslashes($string);
        return (version_compare(phpversion(), '4.3.0', '>=')) ? mysql_real_escape_string($string, $this->connect_id) : mysql_escape_string($string);
    }
    function sql_error($query_id = 0)
    {
        return array('message' => @mysql_error($this->db_connect_id), 'code' => @mysql_errno($this->db_connect_id));
    }
    function sql_ufetchrow($query = "", $type=SQL_BOTH)
    {
        $query_id = $this->sql_query($query, true);
        $result = $this->sql_fetchrow($query_id, $type);
        $this->sql_freeresult($query_id);
        return $result;
    }
    function sql_optimize($table_name="")
    {
        global $dbname;
        $error = false;
        if (empty($table_name)) {
            $nuke_tables = $this->sql_fetchtables($dbname, true);
            foreach($nuke_tables as $table) {
                if(!$result = $this->sql_query('OPTIMIZE TABLE ' . $table)) {
                    $error = true;
                }
                $this->sql_freeresult($result);
            }
        } else {
            if(!$result = $this->sql_query('OPTIMIZE TABLE ' . $table_name)) {
                $error = true;
            }
            $this->sql_freeresult($result);
        }
        $this->sql_freeresult($result);
		return ((!$error) ? true : false);
    }
    function sql_fetchtables($database="", $nuke_only=false)
    {
        global $prefix;
        $result = $this->sql_query(empty($database) ? 'SHOW TABLES' : 'SHOW TABLES FROM '.$database);
        $tables = array();
        while (list($name) = $this->sql_fetchrow($result)) {
            if ($nuke_only) {
                if(stristr($name, $prefix.'_')) {
                    $tables[$name] = $name;
                }
            } else {
                $tables[$name] = $name;
            }
        }
        $this->sql_freeresult($result);
        return $tables;
    }
    function sql_fetchdatabases()
    {
        $result = $this->sql_query('SHOW DATABASES');
        $databases = array();
        while (list($name) = $this->sql_fetchrow($result)) {
            $databases[$name] = $name;
        }
        $this->sql_freeresult($result);
        return $databases;
    }
    function sql_ufetchrowset($query = '', $type=SQL_BOTH)
    {
        $query_id = $this->sql_query($query, true);
        return $this->sql_fetchrowset($query_id, $type);
    }
    function print_debug() {
        if ($this->debug) {
            return $this->saved;
        }
        return '';
    }

} // class sql_db

} // if ... define

?>