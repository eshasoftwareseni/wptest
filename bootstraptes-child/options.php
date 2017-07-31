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

		$default_description = get_bloginfo( 'description', 'display' );

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
			'name' => __('Blog Description', 'options_check'),
			'desc' => __('A text input field.', 'options_check'),
			'id' => 'example_text',
			'std' => $default_description,
			'type' => 'text');

		/**
		 * For $settings options see:
		 * http://codex.wordpress.org/Function_Reference/wp_editor
		 *
		 * 'media_buttons' are not supported as there is no post to attach items to
		 * 'textarea_name' is set by the 'id' you choose
		 */

		$wp_editor_settings = array(
			'wpautop' => true, // Default
			'textarea_rows' => 5,
			'tinymce' => array( 'plugins' => 'wordpress,wplink' )
		);

		$options[] = array(
			'name' => __('Footer Copyright', 'options_check'),
			'desc' => __( 'You can write your footer copyright here.', 'options_check' ),
			'id' => 'example_editor',
			'type' => 'editor',
			'settings' => $wp_editor_settings );

		return $options;
	}
}