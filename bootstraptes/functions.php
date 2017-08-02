<?php

if (!function_exists( 'bootstraptes_enqueue_styles' )) {
    function bootstraptes_enqueue_styles() {
        wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css' );
        $dependencies = array('bootstrap');
    	wp_enqueue_style( 'bootstraptes-style', get_stylesheet_uri(), $dependencies ); 
    }

    add_action( 'wp_enqueue_scripts', 'bootstraptes_enqueue_styles' );
}

if (!function_exists( 'bootstraptes_enqueue_scripts' )) {
    function bootstraptes_enqueue_scripts() {
        $dependencies = array('jquery');
        wp_enqueue_script('bootstrap', get_template_directory_uri().'/bootstrap/js/bootstrap.min.js', $dependencies, '', true );
    }

    add_action( 'wp_enqueue_scripts', 'bootstraptes_enqueue_scripts' );
}

if (!function_exists( 'bootstraptes_wp_setup' )) {
    function bootstraptes_wp_setup() {
        add_theme_support( 'title-tag' );
    }

    add_action( 'after_setup_theme', 'bootstraptes_wp_setup' );
}

if (!function_exists( 'bootstraptes_register_menu' )) {
    function bootstraptes_register_menu() {
        register_nav_menu('header-menu', __( 'Header Menu' ));
        register_nav_menu('footer-menu', __( 'Footer Menu' ));
    }

    add_action( 'init', 'bootstraptes_register_menu' );
}

if (!function_exists( 'bootstraptes_widgets_init' )) {
    function bootstraptes_widgets_init() {

        register_sidebar( array(
            'name'          => 'Footer - Copyright Text',
            'id'            => 'footer-copyright-text',
            'before_widget' => '<div class="footer-copyright-text">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        ) );
        
        register_sidebar( array(
            'name'          => 'Sidebar - Inset',
            'id'            => 'sidebar-1',
            'before_widget' => '<div class="sidebar-module sidebar-module-inset">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        ) );
        
        register_sidebar( array(
            'name'          => 'Sidebar - Default',
            'id'            => 'sidebar-2',
            'before_widget' => '<div class="sidebar-module">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        ) );

    }
    add_action( 'widgets_init', 'bootstraptes_widgets_init' );
}

/*
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';
// Loads options.php from child or parent theme
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

if ( !function_exists( 'of_get_option' ) ) {
    function of_get_option($name, $default = false) {
        
        $optionsframework_settings = get_option('optionsframework');
        
        // Gets the unique option id
        $option_name = $optionsframework_settings['id'];
        
        if ( get_option($option_name) ) {
            $options = get_option($option_name);
        }
            
        if ( isset($options[$name]) ) {
            return $options[$name];
        } else {
            return $default;
        }
    }
}
?>