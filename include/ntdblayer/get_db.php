<?php

/*
 * Copyright (C) 2013-2015 Luna
 * License: http://opensource.org/licenses/MIT MIT
 */

// Load the database layer we need
switch ($db_type) {
	case 'mysqli':
		require_once FORUM_ROOT.'include/ntdblayer/db_mysqli.php';
		break;

	default:
		error('We can\'t find a \''.$db_type.'\'-based database. Please check the settings given in config.php.', __FILE__, __LINE__);
		break;
}

// Connect to the database
$db = new DBConnect($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect);