<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

if ( !function_exists( 'optionsframework_option_name' ) ) {
	function optionsframework_option_name() {

		// This gets the theme name from the stylesheet (lowercase and without spaces)
		$themename = get_option( 'stylesheet' );
		$themename = preg_replace("/\W/", "_", strtolower($themename) );

		$optionsframework_settings = get_option('optionsframework');
		$optionsframework_settings['id'] = $themename;
		update_option('optionsframework', $optionsframework_settings);

		// echo $themename;
	}
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

if ( !function_exists( 'optionsframework_options' ) ) {
	function optionsframework_options() {

		// Test data
		$test_array = array();
		for($i=1;$i<=20;$i++){
			$test_array[$i] = $i;
		}

		$default_perpage =  get_option( 'posts_per_page' );

		$options = array();

		$options[] = array(
			'name' => __('Theme Settings', 'options_check'),
			'type' => 'heading');

		$options[] = array(
			'name' => __('Uploader Logo', 'options_check'),
			'desc' => __('This creates a full size uploader that previews the image.', 'options_check'),
			'id' => 'example_uploader',
			'type' => 'upload');

		$options[] = array(
			'name' => __( 'Select post per page', 'theme-textdomain' ),
			'desc' => __( 'Choose post per page.', 'theme-textdomain' ),
			'id' => 'perpage_select',
			'std' => $default_perpage,
			'type' => 'select',
			'class' => 'mini', //mini, tiny, small
			'options' => $test_array
		);

		$options[] = array(
			'name' => __('Sidebar Checkbox', 'options_check'),
			'desc' => __('Show sidebar on frontpage', 'options_check'),
			'id' => 'sidebar_checkbox',
			'std' => 1,
			'type' => 'checkbox');

		return $options;
	}
}