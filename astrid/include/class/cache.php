<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */

class Cache {
	// Generate the cache for the settings
	public function generate_config_cache() {
		global $db;
	
		// Fetch the configuration
		$result = $db->query( 'SELECT * FROM '.$db->prefix.'config', true ) or error( 'Unable to fetch forum config', __FILE__, __LINE__, $db->error() );
	
		$output = array();
		while ( $cur_con_item = $db->fetch_row( $result ) )
			$output[$cur_con_item[0]] = $cur_con_item[1];
	
		// Output config as PHP code
		$content = '<?php'."\n\n".'define( \'JEWEL_CONFIG_LOADED\', 1 );'."\n\n".'$luna_config = '.var_export( $output, true ).';'."\n\n".'?>';
		Cache::write_cache_file( 'jewel_config.php', $content );
	}

	// Create a cache file
	public function write_cache_file( $file, $content ) {
		$fh = @fopen( JEWEL_CACHE.$file, 'wb' );
		if ( !$fh )
			error( 'It\'s not possible to write the '.htmlspecialchars( $file ).'-file to the cache. Make sure we\'ve got writing permissions for \''.htmlspecialchars( JEWEL_CACHE ).'\'', __FILE__, __LINE__ );
	
		flock( $fh, LOCK_EX );
		ftruncate( $fh, 0 );
	
		fwrite( $fh, $content );
	
		flock( $fh, LOCK_UN );
		fclose( $fh );
	
		if ( function_exists( 'apc_delete_file' ) )
			@apc_delete_file( JEWEL_CACHE.$file );
	}
}