<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="blog-masthead">
    <div class="container">
        <?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'menu_class' => 'blog-nav list-inline'  ) ); ?>
    </div>
</div>

<div class="container">

    <div class="blog-header">
        <?php if ( of_get_option( 'example_uploader' ) ) { ?>
            <div id="header-image">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                   <img src="<?php echo of_get_option( 'example_uploader' ); ?>" alt="Logo" width="150" height="150" />
                </a>
            </div>
        <?php } ?>
        <h1 class="blog-title"><?php bloginfo( 'name' ); ?></h1>
        <?php
            $description_text = of_get_option( 'example_text', 'no entry' ); 
            $description = (!empty($description_text)) ? $description_text : get_bloginfo( 'description', 'display' );
            if($description) { ?><p class="lead blog-description"><?php echo $description ?></p><?php } ?>
    </div>

    <div class="row">