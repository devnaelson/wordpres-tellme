<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Aquila
 * @since Twenty Twenty one
 */
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo wp_upload_dir("2021/04")['url'] . "/Time-Share.jpeg"; ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


    <div>
        <header id="masthead" class="site-header" role="banner">
            <?php get_template_part('template-parts/header/nav'); ?>
        </header>
    </div>
    <?php


    if (function_exists('the_custom_logo')) {
        the_custom_logo();
        the_title();
    }
    ?>