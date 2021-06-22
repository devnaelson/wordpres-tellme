<?php
/**
 * Callback functions for active_callback.
 *
 * @package Clean_Commerce
 */

if ( ! function_exists( 'clean_commerce_is_featured_carousel_active' ) ) :

	/**
	 * Check if featured carousel is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function clean_commerce_is_featured_carousel_active( $control ) {

		if ( 'disabled' !== $control->manager->get_setting( 'featured_carousel_status' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'clean_commerce_is_featured_category_carousel_active' ) ) :

	/**
	 * Check if featured category carousel is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function clean_commerce_is_featured_category_carousel_active( $control ) {

		if ( 'featured-category' === $control->manager->get_setting( 'featured_carousel_type' )->value() && 'disabled' !== $control->manager->get_setting( 'featured_carousel_status' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'clean_commerce_is_featured_product_category_carousel_active' ) ) :

	/**
	 * Check if featured product category carousel is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function clean_commerce_is_featured_product_category_carousel_active( $control ) {

		if ( 'featured-product-category' === $control->manager->get_setting( 'featured_carousel_type' )->value() && 'disabled' !== $control->manager->get_setting( 'featured_carousel_status' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'clean_commerce_is_image_in_archive_active' ) ) :

	/**
	 * Check if image in archive is active.
	 *
	 * @since 1.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function clean_commerce_is_image_in_archive_active( $control ) {

		if ( 'disable' !== $control->manager->get_setting( 'archive_image' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'clean_commerce_is_image_in_single_active' ) ) :

	/**
	 * Check if image in single is active.
	 *
	 * @since 1.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function clean_commerce_is_image_in_single_active( $control ) {

		if ( 'disable' !== $control->manager->get_setting( 'single_image' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

