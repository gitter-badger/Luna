<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

include_once 'class/version.php';
include_once 'class/astrid.php';

// Load the config file so we can work with the database
if ( file_exists( '../config.php' ) )
	require '../config.php';

// In case PUN, FORUM or LUNA are defined, we're happy to, but define JEWEL anyway
if ( defined( 'PUN' ) || defined( 'FORUM' ) || defined( 'LUNA' ) )
	define('JEWEL', 1);

// If we can't find JEWEL to be defined, there is something wrong
if ( !defined( 'JEWEL' ) ) {
	header( 'Location: /install/index.php' );
	exit;
}

?>