<?php

/*
 * Copyright (c) 2013-2015 Luna
 * License under MIT
 */

// Do we have support for SQLite 3?
if (!class_exists('SQLite3'))
	exit('Your host does not support SQLite 3. SQLite 3 support is required to use a SQLite 3 database to run Luna.');

class DBLayer {
	var $datatype_transforms = array(
		'%^SERIAL$%'															=>	'INTEGER',
		'%^(TINY|SMALL|MEDIUM|BIG)?INT( )?(\\([0-9]+\\))?( )?(UNSIGNED)?$%i'	=>	'INTEGER',
		'%^(TINY|MEDIUM|LONG)?TEXT$%i'											=>	'TEXT'
	);

	var $error_message = 'Unknown';
	var $error_no = false;
	var $last_query;
	var $link_id;
	var $num_queries = 0;
	var $prefix;
	var $query_result;
	var $stored_queries = array();
	var $transaction_running = 0;

	function __construct($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect) {
		// Prepend $db_name with the path to the forum root directory
		$db_name = FORUM_ROOT.$db_name;

		$this->prefix = $db_prefix;

		if (!file_exists($db_name)) {
			@touch($db_name);
			@chmod($db_name, 0666);
			if (!file_exists($db_name))
				error('Unable to create new database \''.$db_name.'\'. Permission denied', __FILE__, __LINE__);
		}

		if (!is_readable($db_name))
			error('Unable to open database \''.$db_name.'\' for reading. Permission denied', __FILE__, __LINE__);

		if (!forum_is_writable($db_name))
			error('Unable to open database \''.$db_name.'\' for writing. Permission denied', __FILE__, __LINE__);

		@$this->link_id = new SQLite3($db_name, SQLITE3_OPEN_READWRITE);

		if (!$this->link_id)
			error('Unable to open database \''.$db_name.'\'.', __FILE__, __LINE__);
		else
			return $this->link_id;
	}
	
	
	function DBLayer($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect) {  
		$this->__construct($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect);
	}

	function start_connection() {
		++$this->transaction_running;

		return ($this->link_id->exec('BEGIN TRANSACTION')) ? true : false;
	}

	function end_connection() {
		--$this->transaction_running;

		if ($this->link_id->exec('COMMIT'))
			return true;
		else {
			$this->link_id->exec('ROLLBACK');
			return false;
		}
	}

	function query($sql, $unbuffered = false) {
		if (strlen($sql) > 140000)
			exit('Insane query. Aborting.');

		$this->last_query = $sql;

		if (defined('FORUM_SHOW_QUERIES'))
			$q_start = get_microtime();

		$this->query_result = $this->link_id->query($sql);

		if ($this->query_result) {
			if (defined('FORUM_SHOW_QUERIES'))
				$this->stored_queries[] = array($sql, sprintf('%.5f', get_microtime() - $q_start));

			++$this->num_queries;

			return $this->query_result;
		}
		else {
			if (defined('FORUM_SHOW_QUERIES'))
				$this->stored_queries[] = array($sql, 0);

			$this->error_no = $this->link_id->lastErrorCode();
			$this->error_message = $this->link_id->lastErrorMsg();

			if ($this->transaction_running)
				$this->link_id->exec('ROLLBACK');

			--$this->transaction_running;

			return false;
		}
	}

	function result($query_id = 0, $row = 0, $col = 0) {
		if ($query_id) {
			$result_rows = array();
			while ($cur_result_row = @$query_id->fetchArray(SQLITE3_NUM))
				$result_rows[] = $cur_result_row;

			$cur_row = $result_rows[$row];  
			if (!empty($result_rows) && array_key_exists($row, $result_rows))  
				$cur_row = $result_rows[$row];  
    
			return $cur_row[$col];  

			if (isset($cur_row))  
				return $cur_row[$col];  
			else  
				return false;  
		}
		else
			return false;
	}

	function fetch_assoc($query_id = 0) {
		if ($query_id) {
			$cur_row = @$query_id->fetchArray(SQLITE3_ASSOC);
			if ($cur_row) {
				// Horrible hack to get rid of table names and table aliases from the array keys
				foreach ($cur_row as $key => $value) {
					$dot_spot = strpos($key, '.');
					if ($dot_spot !== false) {
						unset($cur_row[$key]);
						$key = substr($key, $dot_spot+1);
						$cur_row[$key] = $value;
					}
				}
			}

			return $cur_row;
		}
		else
			return false;
	}

	function fetch_row($query_id = 0) {
		return ($query_id) ? @$query_id->fetchArray(SQLITE3_NUM) : false;
	}

	function num_rows($query_id = 0) {
		if ($query_id && preg_match ('/\bSELECT\b/i', $this->last_query)) {
			$num_rows_query = preg_replace ('/\bSELECT\b(.*)\bFROM\b/imsU', 'SELECT COUNT(*) FROM', $this->last_query);
			$result = $this->query($num_rows_query);

			return intval($this->result($result));
		}
		else
			return false;
	}

	function affected_rows() {
		return ($this->query_result) ? $this->link_id->changes() : false;
	}

	function insert_id() {
		return ($this->link_id) ? $this->link_id->lastInsertRowID() : false;
	}

	function get_num_queries() {
		return $this->num_queries;
	}

	function get_stored_queries() {
		return $this->stored_queries;
	}

	function free_result($query_id = false) {
		if ($query_id) {
			@$query_id->finalize();
		}

		return true;
	}

	function escape($str) {
		return is_array($str) ? '' : $this->link_id->escapeString($str);
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

				$this->link_id->exec('COMMIT');
			}

			return @$this->link_id->close();
		}
		else
			return false;
	}

	function get_names() {
		return '';
	}

	function set_names($names) {
		return true;
	}

	function get_version() {
		$info = SQLite3::version();

		return array(
			'name'		=> 'SQLite3',
			'version'	=> $info['versionString']
		);
	}

	function exists_table($table_name) {
		$result = $this->query('SELECT COUNT(type) FROM sqlite_master WHERE name = \''.$this->prefix.$this->escape($table_name).'\' AND type=\'table\'');
		$exists_field = (intval($this->result($result)) > 0);

		// Free results for DROP
		if ($result instanceof Sqlite3Result) {
			$this->free_result($result);
		}

		return $exists_field;
	}

	function exists_field($table_name, $field_name) {
		$result = $this->query('SELECT sql FROM sqlite_master WHERE name = \''.$this->prefix.$this->escape($table_name).'\' AND type=\'table\'');
		$sql = $this->result($result);

		if (is_null($sql) || $sql === false)
			return false;

		return (preg_match('%[\r\n]'.preg_quote($field_name).' %', $sql) === 1);
	}

	function exists_index($table_name, $index_name) {
		$result = $this->query('SELECT COUNT(type) FROM sqlite_master WHERE tbl_name = \''.$this->prefix.$this->escape($table_name).'\' AND name = \''.$this->prefix.$this->escape($table_name).'_'.$this->escape($index_name).'\' AND type=\'index\'');
		$exists_index = (intval($this->result($result)) > 0);

		// Free results for DROP
		if ($result instanceof Sqlite3Result) {
			$this->free_result($result);
		}

		return $exists_index;
	}

	function add_table($table_name, $schema) {
		if ($this->exists_field($table_name))
			return true;

		$query = 'CREATE TABLE '.$this->prefix.$table_name." (\n";

		// Go through every schema element and add it to the query
		foreach ($schema['FIELDS'] as $field_name => $field_data) {
			$field_data['datatype'] = preg_replace(array_keys($this->datatype_transforms), array_values($this->datatype_transforms), $field_data['datatype']);

			$query .= $field_name.' '.$field_data['datatype'];

			if (!$field_data['allow_null'])
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
		if (!$this->exists_field($table_name))
			return true;

		return $this->query('DROP TABLE '.$this->prefix.$this->escape($table_name)) ? true : false;
	}

	function rename_table($old_table, $new_table) {
		// If the old table does not exist
		if (!$this->exists_field($old_table))
			return false;
		// If the table names are the same
		else if ($old_table == $new_table)
			return true;
		// If the new table already exists
		else if ($this->exists_field($new_table))
			return false;

		$table = $this->get_table_info($old_table);

		// Create new table
		$query = str_replace('CREATE TABLE '.$this->prefix.$this->escape($old_table).' (', 'CREATE TABLE '.$this->prefix.$this->escape($new_table).' (', $table['sql']);
		$result = $this->query($query) ? true : false;

		// Recreate indexes
		if (!empty($table['indices'])) {
			foreach ($table['indices'] as $cur_index) {
				$query = str_replace('CREATE INDEX '.$this->prefix.$this->escape($old_table), 'CREATE INDEX '.$this->prefix.$this->escape($new_table), $cur_index);
				$query = str_replace('ON '.$this->prefix.$this->escape($old_table), 'ON '.$this->prefix.$this->escape($new_table), $query);
				$result &= $this->query($query) ? true : false;
			}
		}

		// Copy content across
		$result &= $this->query('INSERT INTO '.$this->prefix.$this->escape($new_table).' SELECT * FROM '.$this->prefix.$this->escape($old_table)) ? true : false;

		// Drop the old table if the new one exists
		if ($this->exists_field($new_table))
			$result &= $this->delete_table($old_table);

		return $result;
	}

	function get_table_info($table_name) {
		// Grab table info
		$result = $this->query('SELECT sql FROM sqlite_master WHERE tbl_name = \''.$this->prefix.$this->escape($table_name).'\' ORDER BY type DESC') or error('Unable to fetch table information', __FILE__, __LINE__, $this->error());

		$table = array();
		$table['indices'] = array();
		$num_rows = 0;

		while ($cur_index = $this->fetch_assoc($result)) {
			if (empty($cur_index['sql']))
				continue;

			if (!isset($table['sql']))
				$table['sql'] = $cur_index['sql'];
			else
				$table['indices'][] = $cur_index['sql'];

			++$num_rows;
		}

		// Check for empty
		if ($num_rows < 1)
			return;


		// Work out the columns in the table currently
		$table_lines = explode("\n", $table['sql']);
		$table['columns'] = array();
		foreach ($table_lines as $table_line) {
			$table_line = trim($table_line, " \t\n\r,"); // trim spaces, tabs, newlines, and commas
			if (substr($table_line, 0, 12) == 'CREATE TABLE')
				continue;
			else if (substr($table_line, 0, 11) == 'PRIMARY KEY')
				$table['primary_key'] = $table_line;
			else if (substr($table_line, 0, 6) == 'UNIQUE')
				$table['unique'] = $table_line;
			else if (substr($table_line, 0, strpos($table_line, ' ')) != '')
				$table['columns'][substr($table_line, 0, strpos($table_line, ' '))] = trim(substr($table_line, strpos($table_line, ' ')));
		}

		return $table;
	}

	function add_field($table_name, $field_name, $field_type, $allow_null, $default_value = null, $after_field = null) {
		if ($this->exists_field($table_name, $field_name))
			return true;

		$table = $this->get_table_info($table_name);

		// Create temp table
		$now = time();
		$tmptable = str_replace('CREATE TABLE '.$this->prefix.$this->escape($table_name).' (', 'CREATE TABLE '.$this->prefix.$this->escape($table_name).'_t'.$now.' (', $table['sql']);
		$result = $this->query($tmptable) ? true : false;
		$result &= $this->query('INSERT INTO '.$this->prefix.$this->escape($table_name).'_t'.$now.' SELECT * FROM '.$this->prefix.$this->escape($table_name)) ? true : false;

		// Create new table sql
		$field_type = preg_replace(array_keys($this->datatype_transforms), array_values($this->datatype_transforms), $field_type);
		$query = $field_type;

		if (!$allow_null)
			$query .= ' NOT NULL';
		
		if ($default_value === '')
			$default_value = '\'\'';

		if (!is_null($default_value))
			$query .= ' DEFAULT '.$default_value;

		$old_columns = array_keys($table['columns']);

		// Determine the proper offset
		if (!is_null($after_field))
			$offset = array_search($after_field, array_keys($table['columns']), true) + 1;
		else
			$offset = count($table['columns']);

		// Out of bounds checks
		if ($offset > count($table['columns']))
			$offset = count($table['columns']);
		else if ($offset < 0)
			$offset = 0;

		if (!is_null($field_name) && $field_name !== '')
			$table['columns'] = array_merge(array_slice($table['columns'], 0, $offset), array($field_name => $query), array_slice($table['columns'], $offset));

		$new_table = 'CREATE TABLE '.$this->prefix.$this->escape($table_name).' (';

		foreach ($table['columns'] as $cur_column => $column_details)
			$new_table .= "\n".$cur_column.' '.$column_details.',';

		if (isset($table['unique']))
			$new_table .= "\n".$table['unique'].',';

		if (isset($table['primary_key']))
			$new_table .= "\n".$table['primary_key'].',';

		$new_table = trim($new_table, ',')."\n".');';

		// Drop old table
		$result &= $this->delete_table($table_name);

		// Create new table
		$result &= $this->query($new_table) ? true : false;

		// Recreate indexes
		if (!empty($table['indices'])) {
			foreach ($table['indices'] as $cur_index)
				$result &= $this->query($cur_index) ? true : false;
		}

		// Copy content back
		$result &= $this->query('INSERT INTO '.$this->prefix.$this->escape($table_name).' ('.implode(', ', $old_columns).') SELECT * FROM '.$this->prefix.$this->escape($table_name).'_t'.$now) ? true : false;

		// Drop temp table
		$result &= $this->delete_table($table_name.'_t'.$now);

		return $result;
	}

	function change_field($table_name, $field_name, $field_type, $allow_null, $default_value = null, $after_field = null) {
		// Unneeded for SQLite
		return true;
	}

	function delete_field($table_name, $field_name) {
		if (!$this->exists_field($table_name, $field_name))
			return true;

		$table = $this->get_table_info($table_name);

		// Create temp table
		$now = time();
		$tmptable = str_replace('CREATE TABLE '.$this->prefix.$this->escape($table_name).' (', 'CREATE TABLE '.$this->prefix.$this->escape($table_name).'_t'.$now.' (', $table['sql']);
		$result = $this->query($tmptable) ? true : false;
		$result &= $this->query('INSERT INTO '.$this->prefix.$this->escape($table_name).'_t'.$now.' SELECT * FROM '.$this->prefix.$this->escape($table_name)) ? true : false;

		// Work out the columns we need to keep and the sql for the new table
		unset($table['columns'][$field_name]);
		$new_columns = array_keys($table['columns']);

		$new_table = 'CREATE TABLE '.$this->prefix.$this->escape($table_name).' (';

		foreach ($table['columns'] as $cur_column => $column_details)
			$new_table .= "\n".$cur_column.' '.$column_details.',';

		if (isset($table['unique']))
			$new_table .= "\n".$table['unique'].',';

		if (isset($table['primary_key']))
			$new_table .= "\n".$table['primary_key'].',';

		$new_table = trim($new_table, ',')."\n".');';

		// Drop old table
		$result &= $this->delete_table($table_name);

		// Create new table
		$result &= $this->query($new_table) ? true : false;

		// Recreate indexes
		if (!empty($table['indices'])) {
			foreach ($table['indices'] as $cur_index)
				if (!preg_match('%\('.preg_quote($field_name, '%').'\)%', $cur_index))
					$result &= $this->query($cur_index) ? true : false;
		}

		// Copy content back
		$result &= $this->query('INSERT INTO '.$this->prefix.$this->escape($table_name).' SELECT '.implode(', ', $new_columns).' FROM '.$this->prefix.$this->escape($table_name).'_t'.$now) ? true : false;

		// Drop temp table
		$result &= $this->delete_table($table_name.'_t'.$now);

		return $result;
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
