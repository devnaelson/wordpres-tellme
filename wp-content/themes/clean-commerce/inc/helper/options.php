<?php
/**
 * Helper functions related to customizer and options.
 *
 * @package Clean_Commerce
 */

if ( ! function_exists( 'clean_commerce_get_global_layout_options' ) ) :

	/**
	 * Returns global layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function clean_commerce_get_global_layout_options() {

		$choices = array(
			'left-sidebar'  => esc_html__( 'Primary Sidebar - Content', 'clean-commerce' ),
			'right-sidebar' => esc_html__( 'Content - Primary Sidebar', 'clean-commerce' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'clean-commerce' ),
		);
		$output = apply_filters( 'clean_commerce_filter_layout_options', $choices );
		return $output;

	}

endif;

if ( ! function_exists( 'clean_commerce_get_archive_layout_options' ) ) :

	/**
	 * Returns archive layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function clean_commerce_get_archive_layout_options() {

		$choices = array(
			'full'    => esc_html__( 'Full Post', 'clean-commerce' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'clean-commerce' ),
		);
		$output = apply_filters( 'clean_commerce_filter_archive_layout_options', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'clean_commerce_get_image_sizes_options' ) ) :

	/**
	 * Returns image sizes options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $add_disable True for adding No Image option.
	 * @param array $allowed Allowed image size options.
	 * @return array Image size options.
	 */
	function clean_commerce_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {

		global $_wp_additional_image_sizes;
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
		$choices = array();
		if ( true === $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'clean-commerce' );
		}
		$choices['thumbnail'] = esc_html__( 'Thumbnail', 'clean-commerce' );
		$choices['medium']    = esc_html__( 'Medium', 'clean-commerce' );
		$choices['large']     = esc_html__( 'Large', 'clean-commerce' );
		$choices['full']      = esc_html__( 'Full (original)', 'clean-commerce' );

		if ( true === $show_dimension ) {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ){
					$choices[ $key ] .= ' ('. $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;

	}

endif;


if ( ! function_exists( 'clean_commerce_get_image_alignment_options' ) ) :

	/**
	 * Returns image options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function clean_commerce_get_image_alignment_options() {

		$choices = array(
			'none'   => _x( 'None', 'Alignment', 'clean-commerce' ),
			'left'   => _x( 'Left', 'Alignment', 'clean-commerce' ),
			'center' => _x( 'Center', 'Alignment', 'clean-commerce' ),
			'right'  => _x( 'Right', 'Alignment', 'clean-commerce' ),
		);
		return $choices;

	}

endif;

if ( ! function_exists( 'clean_commerce_get_featured_carousel_content_options' ) ) :

	/**
	 * Returns the featured carousel content options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function clean_commerce_get_featured_carousel_content_options() {

		$choices = array(
			'disabled'  => esc_html__( 'Disabled', 'clean-commerce' ),
			'home-page' => esc_html__( 'Static Front Page and Shop Page', 'clean-commerce' ),
		);

		$output = apply_filters( 'clean_commerce_filter_featured_carousel_content_options', $choices );

		return $output;

	}

endif;

if ( ! function_exists( 'clean_commerce_get_featured_carousel_type' ) ) :

	/**
	 * Returns the featured carousel type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function clean_commerce_get_featured_carousel_type() {

		$choices = array(
			'featured-category'         => __( 'Featured Category', 'clean-commerce' ),
			'featured-product-category' => __( 'Featured Product Category', 'clean-commerce' ),
		);
		$output = apply_filters( 'clean_commerce_filter_featured_carousel_type', $choices );

		if ( ! empty( $output ) ) {
			ksort( $output );
		}

		return $output;

	}

endif;

if ( ! function_exists( 'clean_commerce_get_numbers_dropdown_options' ) ) :

	/**
	 * Returns numbers dropdown options.
	 *
	 * @since 1.0.0
	 *
	 * @param int $min Min.
	 * @param int $max Max.
	 *
	 * @return array Options array.
	 */
	function clean_commerce_get_numbers_dropdown_options( $min = 1, $max = 4 ) {

		$output = array();

		if ( $min <= $max ) {
			for ( $i = $min; $i <= $max; $i++ ) {
				$output[ $i ] = $i;
			}
		}

		return $output;

	}

endif;
