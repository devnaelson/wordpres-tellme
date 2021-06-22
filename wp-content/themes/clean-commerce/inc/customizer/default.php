<?php
/**
 * Default theme options.
 *
 * @package Clean_Commerce
 */

if ( ! function_exists( 'clean_commerce_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function clean_commerce_get_default_theme_options() {

		$defaults = array();

		// Header.
		$defaults['show_title']            = true;
		$defaults['show_tagline']          = true;
		$defaults['contact_number']        = '';
		$defaults['contact_email']         = '';
		$defaults['show_social_in_header'] = false;
		$defaults['search_in_header']      = true;

		// Layout.
		$defaults['global_layout']           = 'right-sidebar';
		$defaults['archive_layout']          = 'excerpt';
		$defaults['archive_image']           = 'large';
		$defaults['archive_image_alignment'] = 'center';
		$defaults['single_image']            = 'large';

		// Footer.
		$defaults['copyright_text']        = esc_html__( 'Copyright &copy; All rights reserved.', 'clean-commerce' );
		$defaults['show_social_in_footer'] = false;

		// Blog.
		$defaults['excerpt_length']     = 40;
		$defaults['read_more_text']     = esc_html__( 'READ MORE', 'clean-commerce' );
		$defaults['exclude_categories'] = '';

		// Carousel Options.
		$defaults['featured_carousel_status']           = 'disabled';
		$defaults['featured_carousel_type']             = 'featured-category';
		$defaults['featured_carousel_number']           = 5;
		$defaults['featured_carousel_category']         = 0;
		$defaults['featured_carousel_product_category'] = 0;
		$defaults['featured_carousel_enable_autoplay']  = false;
		$defaults['featured_carousel_transition_delay'] = 3;

		// Pass through filter.
		$defaults = apply_filters( 'clean_commerce_filter_default_theme_options', $defaults );
		return $defaults;
	}

endif;
