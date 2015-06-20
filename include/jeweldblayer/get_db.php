<?php

/*
 * Copyright (c) 2013-2015 Luna
 * License under MIT
 */

// Load the database layer we need
switch ($db_type) {
	case 'mysql':
		require_once JEWEL_ROOT.'include/jeweldblayer/db_mysql.php';
		break;

	case 'mysql_innodb':
		require_once JEWEL_ROOT.'include/jeweldblayer/db_mysql_innodb.php';
		break;

	case 'mysqli':
		require_once JEWEL_ROOT.'include/jeweldblayer/db_mysqli.php';
		break;

	case 'mysqli_innodb':
		require_once JEWEL_ROOT.'include/jeweldblayer/db_mysqli_innodb.php';
		break;

	case 'sqlite':
		require_once JEWEL_ROOT.'include/jeweldblayer/db_sqlite.php';
		break;

	case 'pgsql':
		require_once JEWEL_ROOT.'include/jeweldblayer/db_pgsql.php';
		break;

	default:
		error('We can\'t find a \''.$db_type.'\'-based database. Please check the settings given in config.php.', __FILE__, __LINE__);
		break;
}

// Connect to the database
$db = new DBConnect($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect);