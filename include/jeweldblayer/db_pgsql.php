<?php

/*
 * Copyright (c) 2013-2015 Luna
 * License under MIT
 */

// Do we have support for PostgreSQL?
if (!function_exists('pg_connect'))
	exit('Your host does not support PostgreSQL. PostgreSQL support is required to use a PostgreSQL database to run Luna.');

class DBConnect {
	var $datatype_transforms = array(
		'%^BIGINT( )?(\\([0-9]+\\))?( )?(UNSIGNED)?$%i'						=>	'BIGINT',
		'%^DOUBLE( )?(\\([0-9,]+\\))?( )?(UNSIGNED)?$%i'					=>	'DOUBLE PRECISION',
		'%^(MEDIUM)?INT( )?(\\([0-9]+\\))?( )?(UNSIGNED)?$%i'				=>	'INTEGER',
		'%^FLOAT( )?(\\([0-9]+\\))?( )?(UNSIGNED)?$%i'						=>	'REAL',
		'%^(TINY|SMALL)INT( )?(\\([0-9]+\\))?( )?(UNSIGNED)?$%i'			=>	'SMALLINT',
		'%^(TINY|MEDIUM|LONG)?TEXT$%i'										=>	'TEXT',
	);

	var $error_message = 'Unknown';
	var $error_no = false;
	var $link_id;
	var $num_queries = 0;
	var $prefix;
	var $query_result;
	var $query_text = array();
	var $stored_queries = array();
	var $transaction_running = 0;

	function __construct($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect) {
		$this->prefix = $db_prefix;

		if ($db_host) {
			if (strpos($db_host, ':') !== false) {
				list($db_host, $dbport) = explode(':', $db_host);
				$connect_str[] = 'host='.$db_host.' port='.$dbport;
			} else
				$connect_str[] = 'host='.$db_host;
		}

		if ($db_name)
			$connect_str[] = 'dbname='.$db_name;

		if ($db_username)
			$connect_str[] = 'user='.$db_username;

		if ($db_password)
			$connect_str[] = 'password='.$db_password;

		if ($p_connect)
			$this->link_id = @pg_pconnect(implode(' ', $connect_str));
		else
			$this->link_id = @pg_connect(implode(' ', $connect_str));

		if (!$this->link_id)
			error('Unable to connect to PostgreSQL server', __FILE__, __LINE__);

		// Setup the client-server character set (UTF-8)
		if (!defined('FORUM_NO_SET_NAMES'))
			$this->set_names('utf8');

		return $this->link_id;
	}
	
	function DBLayer($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect) {  
		$this->__construct($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect);
	}

	function start_connection() {
		++$this->transaction_running;

		return (@pg_query($this->link_id, 'BEGIN')) ? true : false;
	}

	function end_connection() {
		--$this->transaction_running;

		if (@pg_query($this->link_id, 'COMMIT'))
			return true;
		else {
			@pg_query($this->link_id, 'ROLLBACK');
			return false;
		}
	}

	function query($sql, $unbuffered = false) { // $unbuffered is ignored since there is no pgsql_unbuffered_query()
		if (strrpos($sql, 'LIMIT') !== false)
			$sql = preg_replace('%LIMIT ([0-9]+),([ 0-9]+)%', 'LIMIT \\2 OFFSET \\1', $sql);

		if (defined('FORUM_SHOW_QUERIES'))
			$q_start = get_microtime();

		@pg_send_query($this->link_id, $sql);
		$this->query_result = @pg_get_result($this->link_id);

		if (pg_result_status($this->query_result) != PGSQL_FATAL_ERROR) {
			if (defined('FORUM_SHOW_QUERIES'))
				$this->stored_queries[] = array($sql, sprintf('%.5f', get_microtime() - $q_start));

			++$this->num_queries;

			$this->query_text[intval($this->query_result)] = $sql;

			return $this->query_result;
		} else {
			if (defined('FORUM_SHOW_QUERIES'))
				$this->stored_queries[] = array($sql, 0);

			$this->error_no = false;
			$this->error_message = @pg_result_error($this->query_result);

			if ($this->transaction_running)
				@pg_query($this->link_id, 'ROLLBACK');

			--$this->transaction_running;

			return false;
		}
	}

	function result($query_id = 0, $row = 0, $col = 0) {
		return ($query_id) ? @pg_fetch_result($query_id, $row, $col) : false;
	}

	function fetch_assoc($query_id = 0) {
		return ($query_id) ? @pg_fetch_assoc($query_id) : false;
	}

	function fetch_row($query_id = 0) {
		return ($query_id) ? @pg_fetch_row($query_id) : false;
	}

	function num_rows($query_id = 0) {
		return ($query_id) ? @pg_num_rows($query_id) : false;
	}

	function affected_rows() {
		return ($this->query_result) ? @pg_affected_rows($this->query_result) : false;
	}

	function insert_id() {
		$query_id = $this->query_result;

		if ($query_id && $this->query_text[intval($query_id)] != '') {
			if (preg_match('%^INSERT INTO ([a-z0-9\_\-]+)%is', $this->query_text[intval($query_id)], $table_name)) {
				// Hack (don't ask)
				if (substr($table_name[1], -6) == 'groups')
					$table_name[1] .= '_g';

				$temp_q_id = @pg_query($this->link_id, 'SELECT currval(\''.$table_name[1].'_id_seq\')');
				return ($temp_q_id) ? intval(@pg_fetch_result($temp_q_id, 0)) : false;
			}
		}

		return false;
	}

	function get_num_queries() {
		return $this->num_queries;
	}

	function get_stored_queries() {
		return $this->stored_queries;
	}

	function free_result($query_id = false) {
		if (!$query_id)
			$query_id = $this->query_result;

		return ($query_id) ? @pg_free_result($query_id) : false;
	}

	function escape($string) {
		return is_array($string) ? '' : pg_escape_string($string);
	}

	function error() {
		$result['error_sql'] = @current(@end($this->stored_queries));
		$result['error_no'] = $this->error_no;
		$result['error_message'] = $this->error_message;

		return $result;
	}

	function close() {
		if ($this->link_id) {
			if ($this->transaction_running) {
				if (defined('FORUM_SHOW_QUERIES'))
					$this->stored_queries[] = array('COMMIT', 0);

				@pg_query($this->link_id, 'COMMIT');
			}

			if ($this->query_result)
				@pg_free_result($this->query_result);

			return @pg_close($this->link_id);
		} else
			return false;
	}

	function get_names() {
		$result = $this->query('SHOW client_encoding');
		return strtolower($this->result($result)); // MySQL returns lowercase so lets be consistent
	}

	function set_names($names) {
		return $this->query('SET NAMES \''.$this->escape($names).'\'');
	}

	function get_version() {
		$result = $this->query('SELECT VERSION()');

		return array(
			'name'		=> 'PostgreSQL',
			'version'	=> preg_replace('%^[^0-9]+([^\s,-]+).*$%', '\\1', $this->result($result))
		);
	}

	function exists_table($table_name) {
		$result = $this->query('SELECT 1 FROM pg_class WHERE relname = \''.$this->prefix.$this->escape($table_name).'\'');
		return $this->num_rows($result) > 0;
	}

	function exists_field($table_name, $field_name) {
		$result = $this->query('SELECT 1 FROM pg_class c INNER JOIN pg_attribute a ON a.attrelid = c.oid WHERE c.relname = \''.$this->prefix.$this->escape($table_name).'\' AND a.attname = \''.$this->escape($field_name).'\'');
		return $this->num_rows($result) > 0;
	}

	function exists_index($table_name, $index_name) {
		$result = $this->query('SELECT 1 FROM pg_index i INNER JOIN pg_class c1 ON c1.oid = i.indrelid INNER JOIN pg_class c2 ON c2.oid = i.indexrelid WHERE c1.relname = \''.$this->prefix.$this->escape($table_name).'\' AND c2.relname = \''.$this->prefix.$this->escape($table_name).'_'.$this->escape($index_name).'\'');
		return $this->num_rows($result) > 0;
	}

	function add_table($table_name, $schema) {
		if ($this->exists_table($table_name))
			return true;

		$query = 'CREATE TABLE '.$this->prefix.$table_name." (\n";

		// Go through every schema element and add it to the query
		foreach ($schema['FIELDS'] as $field_name => $field_data) {
			$field_data['datatype'] = preg_replace(array_keys($this->datatype_transforms), array_values($this->datatype_transforms), $field_data['datatype']);

			$query .= $field_name.' '.$field_data['datatype'];

			// The SERIAL datatype is a special case where we don't need to say not null
			if (!$field_data['allow_null'] && $field_data['datatype'] != 'SERIAL')
				$query .= ' NOT NULL';

			if (isset($field_data['default']))
				$query .= ' DEFAULT '.$field_data['default'];

			$query .= ",\n";
		}

		// If we have a primary key, add it
		if (isset($schema['PRIMARY KEY']))
			$query .= 'PRIMARY KEY ('.implode(',', $schema['PRIMARY KEY']).'),'."\n";

		// Add unique keys
		if (isset($schema['UNIQUE KEYS'])) {
			foreach ($schema['UNIQUE KEYS'] as $key_name => $key_fields)
				$query .= 'UNIQUE ('.implode(',', $key_fields).'),'."\n";
		}

		// We remove the last two characters (a newline and a comma) and add on the ending
		$query = substr($query, 0, strlen($query) - 2)."\n".')';

		$result = $this->query($query) ? true : false;

		// Add indexes
		if (isset($schema['INDEXES'])) {
			foreach ($schema['INDEXES'] as $index_name => $index_fields)
				$result &= $this->add_index($table_name, $index_name, $index_fields, false);
		}

		return $result;
	}

	function delete_table($table_name) {
		if (!$this->exists_table($table_name))
			return true;

		return $this->query('DROP TABLE '.$this->prefix.$table_name) ? true : false;
	}

	function rename_table($old_table, $added_table) {
		// If the new table exists and the old one doesn't, then we're happy
		if ($this->exists_table($added_table) && !$this->exists_table($old_table))
			return true;

		return $this->query('ALTER TABLE '.$this->prefix.$old_table.' RENAME TO '.$this->prefix.$added_table) ? true : false;
	}

	function add_field($table_name, $field_name, $field_type, $allow_null, $default_value = null, $after_field = null) {
		if ($this->exists_field($table_name, $field_name))
			return true;

		$field_type = preg_replace(array_keys($this->datatype_transforms), array_values($this->datatype_transforms), $field_type);

		$result = $this->query('ALTER TABLE '.$this->prefix.$table_name.' ADD '.$field_name.' '.$field_type) ? true : false;

		if (!is_null($default_value)) {
			if (!is_int($default_value) && !is_float($default_value))
				$default_value = '\''.$this->escape($default_value).'\'';

			$result &= $this->query('ALTER TABLE '.$this->prefix.$table_name.' ALTER '.$field_name.' SET DEFAULT '.$default_value) ? true : false;
			$result &= $this->query('UPDATE '.$this->prefix.$table_name.' SET '.$field_name.'='.$default_value) ? true : false;
		}

		if (!$allow_null)
			$result &= $this->query('ALTER TABLE '.$this->prefix.$table_name.' ALTER '.$field_name.' SET NOT NULL') ? true : false;

		return $result;
	}

	function change_field($table_name, $field_name, $field_type, $allow_null, $default_value = null, $after_field = null) {
		if (!$this->exists_field($table_name, $field_name))
			return true;

		$field_type = preg_replace(array_keys($this->datatype_transforms), array_values($this->datatype_transforms), $field_type);

		$result = $this->add_field($table_name, 'tmp_'.$field_name, $field_type, $allow_null, $default_value, $after_field);
		$result &= $this->query('UPDATE '.$this->prefix.$table_name.' SET tmp_'.$field_name.' = '.$field_name) ? true : false;
		$result &= $this->delete_field($table_name, $field_name);
		$result &= $this->query('ALTER TABLE '.$this->prefix.$table_name.' RENAME COLUMN tmp_'.$field_name.' TO '.$field_name) ? true : false;

		return $result;
	}

	function delete_field($table_name, $field_name) {
		if (!$this->exists_field($table_name, $field_name))
			return true;

		return $this->query('ALTER TABLE '.$this->prefix.$table_name.' DROP '.$field_name) ? true : false;
	}

	function add_index($table_name, $index_name, $index_fields, $unique = false) {
		if ($this->exists_index($table_name, $index_name))
			return true;

		return $this->query('CREATE '.($unique ? 'UNIQUE ' : '').'INDEX '.$this->prefix.$table_name.'_'.$index_name.' ON '.$this->prefix.$table_name.'('.implode(',', $index_fields).')') ? true : false;
	}

	function delete_index($table_name, $index_name) {
		if (!$this->exists_index($table_name, $index_name))
			return true;

		return $this->query('DROP INDEX '.$this->prefix.$table_name.'_'.$index_name) ? true : false;
	}

	function add_config($config_name, $config_value) {
		if (!array_key_exists($config_name, $luna_config))
			return $this->query('INSERT INTO '.$this->prefix.'config (conf_name, conf_value) VALUES (\''.$config_name.'\', \''.$config_value.'\')') or error('Unable to insert config value \''.$config_name.'\'', __FILE__, __LINE__, $db->error());
	}

	function delete_config($config_name) {
		if (!array_key_exists($config_name, $luna_config))
			return $this->query('DELETE FROM '.$this->prefix.'config WHERE conf_name = \''.$config_name.'\'') or error('Unable to remove config value \''.$config_name.'\'', __FILE__, __LINE__, $db->error());
	}

	function truncate_table($table_name) {
		return $this->query('DELETE FROM '.$this->prefix.$table_name) ? true : false;
	}
}
