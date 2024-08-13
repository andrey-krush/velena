<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#111111">
    <title>Velena</title>
    <script  src="https://code.jquery.com/jquery-3.6.4.min.js"  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="  crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>


    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/css/jquery-ui.min.css">
    <script src="<?=TEMPLATE_PATH?>/js/jquery-ui.min.js"></script>
    <script src="<?=TEMPLATE_PATH?>/js/jquery.ui.touch-punch.min.js"></script>

    <script src="//maps.googleapis.com/maps/api/js?key=<?php echo get_field('content',Contacts_Page::get_ID())['api_key']; ?>"></script>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/css/vendor.css">
    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/css/main.css">
</head>
<body class="page__body">
    <div class="site-container">
        <?php dynamic_sidebar('header') ?>