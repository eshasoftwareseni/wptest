<?php
/**
 * Modify filter assesment_3_timezones
 */
if (!function_exists('example_callback')) {
	function example_callback( $example ) {
	    $example = array();
	    $timestamp = time();
	    foreach(timezone_identifiers_list() as $key => $zone) {
	        date_default_timezone_set($zone);
	        $time = 'UTC/GMT ' . date('P', $timestamp);
	        $example[$time] = $zone;
	    } 
	    return $example;
	}

	add_filter( 'assesment_3_timezones', 'example_callback' );
}

/**
* Modify action assesment_3_after_render
*/
if (!function_exists('example_action')) {
	function example_action() {
	  echo "Silahkan pilih timezone Anda: ";
	}

add_action( 'assesment_3_after_render', 'example_action' );
}