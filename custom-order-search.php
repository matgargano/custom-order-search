<?php

/*
Plugin Name: Custom Order Search
Plugin URI: http://www.matgargano.com/
Description: Adds a drag and drop settings page that lets you customize how you want to order your search results
Author: mat gargano
Author URI: http://www.matgargano.com/
Version: 0.1.2
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


use cos\Search;
use cos\Settings;


$base_namespace = 'cos';

spl_autoload_register( function ( $class ) use ( $base_namespace ) {
	$base = explode( '\\', $class );
	if ( $base_namespace === $base[0] ) {
		$file = __DIR__ . '/' . strtolower( str_replace( [ '\\', '_' ], [
					DIRECTORY_SEPARATOR,
					'-'
				], $class ) . '.php' );
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			die( sprintf( 'File %s not found', $file ) );
		}
	}

} );

$settings = new Settings();
$settings->init();

$search = new Search();
$search->init();