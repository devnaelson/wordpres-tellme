<?php
/**
 * Core functions.
 *
 * @package Clean_Commerce
 */

if ( ! function_exists( 'clean_commerce_get_option' ) ) :

	/**
	 * Get theme option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function clean_commerce_get_option( $key ) {

		if ( empty( $key ) ) {
			return;
		}

		$value = '';

		$default = clean_commerce_get_default_theme_options();
		$default_value = null;

		if ( is_array( $default ) && isset( $default[ $key ] ) ) {
			$default_value = $default[ $key ];
		}

		if ( null !== $default_value ) {
			$value = get_theme_mod( $key, $default_value );
		}
		else {
			$value = get_theme_mod( $key );
		}

		return $value;
	}

endif;

if( ! function_exists( 'clean_commerce_exclude_category_in_blog_page' ) ) :

  /**
   * Exclude category in blog page.
   *
   * @since 1.0
   */
  function clean_commerce_exclude_category_in_blog_page( $query ) {

    if( $query->is_home && $query->is_main_query()   ) {
      $exclude_categories = clean_commerce_get_option( 'exclude_categories' );
      if ( ! empty( $exclude_categories ) ) {
        $cats = explode( ',', $exclude_categories );
        $cats = array_filter( $cats, 'is_numeric' );
        $string_exclude = '';
        if ( ! empty( $cats ) ) {
          $string_exclude = '-' . implode( ',-', $cats);
          $query->set( 'cat', $string_exclude );
        }
      }
    }
    return $query;
  }

endif;

add_filter( 'pre_get_posts', 'clean_commerce_exclude_category_in_blog_page' );
