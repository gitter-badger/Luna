<?php

/*
 * Copyright (c) 2013-2015 Luna
 * Licensed under MIT
 */
 
// Give the current timestamp with microseconds as a float
function get_microtime() {
	list( $usec, $sec ) = explode( ' ', microtime () );
	return( ( float ) $usec + ( float ) $sec );
}

?>