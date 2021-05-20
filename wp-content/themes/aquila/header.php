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

    $the_post_id   = get_the_ID();
    $hide_title    = get_post_meta($the_post_id, '_hide_page_title', true);
    $heading_class = (!empty($hide_title) && 'yes' === $hide_title) ? 'hide d-none' : '';

    $has_post_thumbnail = get_the_post_thumbnail($the_post_id);

    
	// Title, enable, disable box in post
    // hooks in aquila theme
    // add _hide_page_title postmeta

	if ( is_single() || is_page() ) {
		printf(
			'<h1 class="page-title text-dark %1$s">%2$s</h1>',
			esc_attr( $heading_class ),
			wp_kses_post( get_the_title() )
		);
	} else {
		printf(
			'<h2 class="entry-title mb-3"><a class="text-dark" href="%1$s">%2$s</a></h2>',
			esc_url( get_the_permalink() ),
			wp_kses_post( get_the_title() )
		);
	}
    //end

    if (have_posts()) :
        while (have_posts()) : the_post();
            single_post_title();
            the_content();
        endwhile;
    else :
        _e('Sorry, no posts matched your criteria.', 'textdomain');
    endif;

    ?>

    <?php
    if (function_exists('the_custom_logo')) {
        the_custom_logo();
        the_title();
    }
    ?>