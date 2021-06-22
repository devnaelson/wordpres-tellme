<?php
/**
 * WooCommerce support.
 *
 * @package Clean_Commerce
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Wrapper.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'clean_commerce_woo_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'clean_commerce_woo_wrapper_end', 10 );

/**
 * Woocommerce content wrapper start.
 *
 * @since 1.0.0
 */
function clean_commerce_woo_wrapper_start() {
	echo '<div id="primary">';
	echo '<main role="main" class="site-main" id="main">';
}

/**
 * Woocommerce content wrapper end.
 *
 * @since 1.0.0
 */
function clean_commerce_woo_wrapper_end() {
	echo '</main><!-- #main -->';
	echo '</div><!-- #primary -->';
}

add_filter( 'woocommerce_breadcrumb_defaults', 'clean_commerce_woo_breadcrumb_defaults' );

/**
 * Woocommerce breadcrumb defaults.
 *
 * @since 1.0.0
 *
 * @param array $defaults Breadcrumb defaults.
 * @return array Modified breadcrumb defaults.
 */
function clean_commerce_woo_breadcrumb_defaults( $defaults ) {
	$defaults['delimiter']   = '';
	$defaults['wrap_before'] = '<div id="breadcrumb" itemprop="breadcrumb"><div class="container"><div class="woo-breadcrumbs breadcrumbs"><ul>';
	$defaults['wrap_after']  = '</ul></div></div></div>';
	$defaults['before']      = '<li>';
	$defaults['after']       = '</li>';
	return $defaults;
}

add_action( 'wp', 'clean_commerce_woo_hooking' );

/**
 * Hooking Woocommerce.
 *
 * @since 1.0.0
 */
function clean_commerce_woo_hooking() {

	// Fixing breadcrumb.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	if ( ! is_shop() ) {
		add_action( 'clean_commerce_action_before_content', 'woocommerce_breadcrumb', 7 );
	}
	remove_action( 'clean_commerce_action_before_content', 'clean_commerce_add_breadcrumb', 7 );

	// Fixing primary sidebar.
	$global_layout = clean_commerce_get_option( 'global_layout' );
	$global_layout = apply_filters( 'clean_commerce_filter_theme_global_layout', $global_layout );
	if ( in_array( $global_layout, array( 'no-sidebar' ) ) ) {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}

}

/**
 * Loop columns.
 *
 * @since 1.0.0
 */
function clean_commerce_woo_loop_columns() {
	return 3;
}

add_filter( 'loop_shop_columns', 'clean_commerce_woo_loop_columns' );

/**
 * Product archive image size.
 *
 * @since 1.0.0
 *
 * @param string $input Image size.
 * @return string Modified image size.
 */
function clean_commerce_woo_archive_image_size( $input ) {

	if ( ! is_single() ) {
		$input = 'clean-commerce-carousel';
	}
	return $input;
}

add_filter( 'single_product_archive_thumbnail_size', 'clean_commerce_woo_archive_image_size' );

/**
 * Modify global layout.
 *
 * @since 1.0.0
 *
 * @param string $input Global layout.
 * @return string Modified global layout.
 */
function clean_commerce_woo_modify_global_layout( $input ) {

	if ( is_shop() ) {
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		if ( $shop_page_id ) {
			$post_options = get_post_meta( $shop_page_id, 'clean_commerce_theme_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$layout = $post_options['post_layout'];

				if ( 'default' !== $layout ) {
					$input = esc_attr( $layout );
				}
			}
		}
	}

	return $input;
}

add_filter( 'clean_commerce_filter_theme_global_layout', 'clean_commerce_woo_modify_global_layout', 15 );

/**
 * Columns in related products.
 *
 * @since 1.0.6
 *
 * @param string $input Number.
 * @return string Modified number.
 */
function clean_commerce_woo_related_product_columns( $input ) {

	return 3;
}

add_filter( 'woocommerce_related_products_columns', 'clean_commerce_woo_related_product_columns' );
