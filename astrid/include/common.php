<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

include_once 'class/version.php';
include_once 'class/astrid.php';
include_once 'class/cache.php';
define( 'JEWEL_CACHE', JEWEL_ROOT.'cache/' );

// Load the config file so we can work with the database
if ( file_exists( JEWEL_ROOT.'/config.php' ) )
	require JEWEL_ROOT.'/config.php';

// In case PUN, FORUM or LUNA are defined, we're happy to, but define JEWEL anyway
if ( defined( 'PUN' ) || defined( 'FORUM' ) || defined( 'LUNA' ) )
	define('JEWEL', 1);

// If we can't find JEWEL to be defined, there is something wrong
if ( !defined( 'JEWEL' ) ) {
	header( 'Location: '.JEWEL_ROOT.'/install/index.php' );
	exit;
}

require JEWEL_ROOT.'/astrid/include/general_functions.php';

// Load the datbase layer,  and connect
require JEWEL_ROOT.'/include/jeweldblayer/get_db.php';

// Connect to the database
$db->start_connection();

// Load the configuration
if ( file_exists( JEWEL_CACHE.'jewel_config.php' ) )
	include JEWEL_CACHE.'jewel_config.php';

if ( !defined( 'JEWEL_CONFIG_LOADED' ) ) {
	Cache::generate_config_cache();
	require JEWEL_CACHE.'jewel_config.php';
}