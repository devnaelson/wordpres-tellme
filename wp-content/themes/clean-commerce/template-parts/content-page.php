<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Clean_Commerce
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

    <?php
	  /**
	   * Hook - clean_commerce_single_image.
	   *
	   * @hooked clean_commerce_add_image_in_single_display -  10
	   */
	  do_action( 'clean_commerce_single_image' );
	?>
	<div class="entry-content-wrapper">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clean-commerce' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div><!-- .entry-content-wrapper -->

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit', 'clean-commerce' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

