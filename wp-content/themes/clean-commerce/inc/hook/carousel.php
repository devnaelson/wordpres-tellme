<?php
/**
 * Implementation of carousel feature.
 *
 * @package Clean_Commerce
 */

// Check carousel status.
add_filter( 'clean_commerce_filter_carousel_status', 'clean_commerce_check_carousel_status' );

// Add carousel to the theme.
add_action( 'clean_commerce_action_before_content', 'clean_commerce_add_featured_carousel', 5 );

// Carousel details.
add_filter( 'clean_commerce_filter_carousel_details', 'clean_commerce_get_carousel_details' );

if ( ! function_exists( 'clean_commerce_get_carousel_details' ) ) :

	/**
	 * Carousel details.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Carousel details.
	 */
	function clean_commerce_get_carousel_details( $input ) {

		$featured_carousel_type   = clean_commerce_get_option( 'featured_carousel_type' );
		$featured_carousel_number = clean_commerce_get_option( 'featured_carousel_number' );

		switch ( $featured_carousel_type ) {

			case 'featured-category':

				$featured_carousel_category = clean_commerce_get_option( 'featured_carousel_category' );

				$qargs = array(
					'posts_per_page' => absint( $featured_carousel_number ),
					'no_found_rows'  => true,
					'post_type'      => 'post',
					'meta_query'     => array(
						array( 'key' => '_thumbnail_id' ),
					),
				);
				if ( absint( $featured_carousel_category ) > 0 ) {
					$qargs['cat'] = absint( $featured_carousel_category );
				}

				// Fetch posts.
				$all_posts = get_posts( $qargs );
				$carousels = array();

				if ( ! empty( $all_posts ) ) {

					$cnt = 0;
					foreach ( $all_posts as $key => $post ) {

						if ( has_post_thumbnail( $post->ID ) ) {
							$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'clean-commerce-carousel' );
							$carousels[ $cnt ]['images'] = $image_array;
							$carousels[ $cnt ]['title']  = $post->post_title;
							$carousels[ $cnt ]['url']    = get_permalink( $post->ID );

							$cnt++;
						}
					}
				}
				if ( ! empty( $carousels ) ) {
					$input = $carousels;
				}

			break;

			case 'featured-product-category':

				$featured_carousel_product_category = clean_commerce_get_option( 'featured_carousel_product_category' );

				$qargs = array(
					'posts_per_page' => esc_attr( $featured_carousel_number ),
					'no_found_rows'  => true,
					'post_type'      => 'product',
					'meta_query'     => array(
						array( 'key' => '_thumbnail_id' ),
					),
				);

				if ( absint( $featured_carousel_product_category ) > 0 ) {
					$tax = array(
							'taxonomy' => 'product_cat',
							'terms'    => esc_attr( $featured_carousel_product_category ),
						);
					$qargs['tax_query'] = array ( $tax );
				}

				// Fetch posts.
				$all_posts = get_posts( $qargs );
				$carousels = array();

				if ( ! empty( $all_posts ) ) {

					$cnt = 0;
					foreach ( $all_posts as $key => $post ) {

						if ( has_post_thumbnail( $post->ID ) ) {
							$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'clean-commerce-carousel' );
							$carousels[ $cnt ]['images'] = $image_array;
							$carousels[ $cnt ]['title']  = $post->post_title;
							$carousels[ $cnt ]['url']    = get_permalink( $post->ID );

							$cnt++;
						}
					}
				}
				if ( ! empty( $carousels ) ) {
					$input = $carousels;
				}

			break;

			default:
			break;
		}
		return $input;

	}
endif;

if ( ! function_exists( 'clean_commerce_add_featured_carousel' ) ) :

	/**
	 * Add featured carousel.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_add_featured_carousel() {

		$flag_apply_carousel = apply_filters( 'clean_commerce_filter_carousel_status', false );

		if ( true !== $flag_apply_carousel ) {
			return false;
		}

		$carousel_details = array();
		$carousel_details = apply_filters( 'clean_commerce_filter_carousel_details', $carousel_details );

		if ( empty( $carousel_details ) ) {
			return;
		}

		// Render carousel now.
		clean_commerce_render_featured_carousel( $carousel_details );

	}
endif;

if ( ! function_exists( 'clean_commerce_render_featured_carousel' ) ) :

	/**
	 * Render featured carousel.
	 *
	 * @since 1.0.0
	 *
	 * @param array $carousel_details Details of carousel content.
	 */
	function clean_commerce_render_featured_carousel( $carousel_details = array() ) {

		if ( empty( $carousel_details ) ) {
			return;
		}

		$featured_carousel_enable_autoplay = clean_commerce_get_option( 'featured_carousel_enable_autoplay' );
		$featured_carousel_transition_delay = clean_commerce_get_option( 'featured_carousel_transition_delay' );

		$carousel_args = array(
			'slidesToShow'   => 3,
			'slidesToScroll' => 1,
			'dots'           => false,
			'prevArrow'      => '<span data-role="none" class="slick-prev" tabindex="0"><i class="fa fa-angle-left" aria-hidden="true"></i></span>',
			'nextArrow'      => '<span data-role="none" class="slick-next" tabindex="0"><i class="fa fa-angle-right" aria-hidden="true"></i></span>',
			'responsive'     => array(
				array(
					'breakpoint' => 1024,
					'settings'   => array(
						'slidesToShow' => 3,
						),
					),
				array(
					'breakpoint' => 768,
					'settings'   => array(
						'slidesToShow' => 2,
						),
					),
				array(
					'breakpoint' => 480,
					'settings'   => array(
						'slidesToShow' => 1,
						),
					),
				),
			);

		if ( true === $featured_carousel_enable_autoplay ) {
			$carousel_args['autoplay']      = true;
			$carousel_args['autoplaySpeed'] = 1000 * absint( $featured_carousel_transition_delay );
		}

		$carousel_args_encoded = wp_json_encode( $carousel_args );
		?>

		<div id="featured-carousel">
			<div class="container">
				<div class="featured-product-carousel-wrapper" data-slick='<?php echo $carousel_args_encoded; ?>'>
					<?php foreach ( $carousel_details as $item ) : ?>

						<div class="featured-carousel-item">
							<div class="featured-carousel-item-thumb">
								<a href="<?php echo esc_url( $item['url'] ); ?>">
									<?php if ( ! empty( $item['images'] ) ) : ?>
										<div class="product-thumb">
											<img src="<?php echo esc_url( $item['images'][0] ); ?>" alt="" />
										</div><!-- .product-thumb -->
									<?php endif; ?>
									<h3 class="featured-product-title"><span><?php echo esc_html( $item['title'] ); ?></span></h3>
								</a>
							</div><!-- .featured-carousel-item-thumb -->
						</div><!-- .featured-carousel-item -->

					<?php endforeach; ?>

				</div><!-- .featured-product-carousel-wrapper -->
			</div><!-- .container -->
		</div><!-- #featured-carousel -->
	    <?php
	}
endif;

if( ! function_exists( 'clean_commerce_check_carousel_status' ) ) :

	/**
	 * Check status of carousel.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_check_carousel_status( $input ) {

		// Carousel status.
		$featured_carousel_status = clean_commerce_get_option( 'featured_carousel_status' );

		// Get Page ID outside Loop.
		$page_id = null;
		$queried_object = get_queried_object();
		if ( is_object( $queried_object ) && 'WP_Post' === get_class( $queried_object ) ) {
			$page_id = get_queried_object_id();
		}

		// Front page displays in Reading Settings.
		$page_on_front  = absint( get_option( 'page_on_front' ) );
		$page_for_posts = absint( get_option( 'page_for_posts' ) );

		switch ( $featured_carousel_status ) {

			case 'disabled':
				$input = false;
				break;

			case 'home-page':
			    if ( function_exists( 'is_shop' ) && is_shop() ) {
					$input = true;
			    }
			    else if ( $page_on_front === $page_id && $page_on_front > 0 ) {
					$input = true;
			    }
				break;

			default:
				break;
		}

		return $input;
	}

endif;
