<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

class Version {
	// See http://getluna.org/docs/version.php for more info
	const LUNA_VERSION = '2.0 Preview';
	const LUNA_JEWEL_VERSION = '2.0.0.74';

	// The Luna Core code name
	const LUNA_CODE_NAME = 'denim';

	// The database version number, every change in the database requires this number to go one up
	const LUNA_DB_VERSION = '89.00';

	// The parser version number, every change to the parser requires this number to go one up
	const LUNA_PARSER_VERSION = '11.2.0';

	// The search index version number, every change to the search index requires this number to go one up
	const LUNA_SI_VERSION = '2.0';

	// Luna system requirements
	const MIN_PHP_VERSION = '5.3.0';
	const MIN_MYSQL_VERSION = '5.0.0';
	const MIN_PGSQL_VERSION = '8.0.0';
}
?>