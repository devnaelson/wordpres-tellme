<?php
/**
 * Theme supports.
 *
 * @package Clean_Commerce
 */

// Load Footer Widget Support.
require_if_theme_supports( 'footer-widgets', get_template_directory() . '/inc/support/footer-widgets.php' );

// WooCommerce support.
if ( class_exists( 'WooCommerce' ) ) {
	require_once trailingslashit( get_template_directory() ) . 'inc/support/woocommerce.php';
}
