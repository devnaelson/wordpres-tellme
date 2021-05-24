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

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

        <header class="page-header">
            <h1 class="page-title"><?php _e('Not Found', 'twentythirteen'); ?></h1>
        </header>

        <div class="page-wrapper">
            <div class="page-content">
                <h2><?php _e('This is somewhat embarrassing, isn’t it?', 'twentythirteen'); ?></h2>
                <p><?php _e('It looks like nothing was found at this location. Maybe try a search?', 'twentythirteen'); ?></p>

                <?php get_search_form(); ?>
            </div><!-- .page-content -->
        </div><!-- .page-wrapper -->

    </div><!-- #content -->
</div><!-- #primary -->