<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Clean_Commerce
 */

?><?php
	/**
	 * Hook - clean_commerce_action_doctype.
	 *
	 * @hooked clean_commerce_doctype - 10
	 */
	do_action( 'clean_commerce_action_doctype' );
?>
<head>
	<?php
	/**
	 * Hook - clean_commerce_action_head.
	 *
	 * @hooked clean_commerce_head - 10
	 */
	do_action( 'clean_commerce_action_head' );
	?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Hook - clean_commerce_action_before.
	 *
	 * @hooked clean_commerce_page_start - 10
	 * @hooked clean_commerce_skip_to_content - 15
	 */
	do_action( 'clean_commerce_action_before' );
	?>

    <?php
	  /**
	   * Hook - clean_commerce_action_before_header.
	   *
	   * @hooked clean_commerce_header_start - 10
	   */
	  do_action( 'clean_commerce_action_before_header' );
	?>
		<?php
		/**
		 * Hook - clean_commerce_action_header.
		 *
		 * @hooked clean_commerce_site_branding - 10
		 */
		do_action( 'clean_commerce_action_header' );
		?>
    <?php
	  /**
	   * Hook - clean_commerce_action_after_header.
	   *
	   * @hooked clean_commerce_header_end - 10
	   */
	  do_action( 'clean_commerce_action_after_header' );
	?>

	<?php
	/**
	 * Hook - clean_commerce_action_before_content.
	 *
	 * @hooked clean_commerce_add_breadcrumb - 7
	 * @hooked clean_commerce_content_start - 10
	 */
	do_action( 'clean_commerce_action_before_content' );
	?>
    <?php
	  /**
	   * Hook - clean_commerce_action_content.
	   */
	  do_action( 'clean_commerce_action_content' );
	?>
