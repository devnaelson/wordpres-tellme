<?php
/**
 * Recommended plugins.
 *
 * @package Clean_Commerce
 */

add_action( 'tgmpa_register', 'clean_commerce_activate_recommended_plugins' );

/**
 * Register recommended plugins.
 *
 * @since 1.0.0
 */
function clean_commerce_activate_recommended_plugins() {

	$plugins = array(
		array(
			'name'     => esc_html__( 'WooCommerce', 'clean-commerce' ),
			'slug'     => 'woocommerce',
			'required' => false,
		),
	);

	$config = array();

	tgmpa( $plugins, $config );

}
