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
?>