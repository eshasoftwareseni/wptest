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
    <nav class="nav" role="navigation">
        <ul>
            <li>X</li>            
            <li><a href="<?php home_url() ?>">home</a></li>
            <li><a href="javascript:page1();">sample page 1</a></li>
            <li><a href="javascript:page2();">sample page 2</a></li>
        </ul>
    </nav>
    </div>
</div>

<div class="container">

    <div class="row">
