<?php
/**
 * Custom theme functions.
 *
 * This file contains hook functions attached to theme hooks.
 *
 * @package Clean_Commerce
 */

if ( ! function_exists( 'clean_commerce_skip_to_content' ) ) :
	/**
	 * Add Skip to content.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_skip_to_content() {
		?><a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'clean-commerce' ); ?></a><?php
	}
endif;

add_action( 'clean_commerce_action_before', 'clean_commerce_skip_to_content', 15 );


if ( ! function_exists( 'clean_commerce_site_branding' ) ) :

	/**
	 * Site branding.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_site_branding() {

		?>
	    <div class="site-branding">

			<?php clean_commerce_the_custom_logo(); ?>

			<?php $show_title = clean_commerce_get_option( 'show_title' ); ?>
			<?php $show_tagline = clean_commerce_get_option( 'show_tagline' ); ?>
			<?php if ( true === $show_title || true === $show_tagline ) :  ?>
				<div id="site-identity">
					<?php if ( true === $show_title ) : ?>
						<?php if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( true === $show_tagline ) : ?>
						<p class="site-description"><?php bloginfo( 'description' ); ?></p>
					<?php endif; ?>
				</div><!-- #site-identity -->
			<?php endif; ?>
	    </div><!-- .site-branding -->
	    <div id="right-header">
		    <?php if ( has_nav_menu( 'header' ) ) : ?>
		    	<?php
		    	wp_nav_menu( array(
					'theme_location' => 'header',
					'container'      => 'nav',
					'container_id'   => 'header-nav',
					'depth'          => 1,
		    	) );
		    	?>
		    <?php endif; ?>

	    	<?php if ( clean_commerce_is_woocommerce_active() ) : ?>
		    	<div id="cart-section">
		    		<ul>
		    			<li class="account-login">
			    			<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><?php echo is_user_logged_in() ? esc_html__( 'My Account', 'clean-commerce' ) : esc_html__( 'Login / Register', 'clean-commerce' ) ; ?></a>
			    			</li>
		    			<li class="cart-price"><a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><strong><?php esc_html_e( 'Shopping Cart', 'clean-commerce' ) ?></strong>&nbsp;/&nbsp;<span class="amount"><?php echo WC()->cart->get_cart_total(); ?></span></a></li>
	    				<li class="cart-icon"><strong><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></strong><span class="cart-icon-handle"></span></li>
	    			</ul>
	    		</div> <!-- .cart-section -->
	    	<?php endif; ?>
    	</div> <!-- #right-header -->
	    <?php
	}

endif;

add_action( 'clean_commerce_action_header', 'clean_commerce_site_branding' );

if ( ! function_exists( 'clean_commerce_add_primary_navigation' ) ) :

	/**
	 * Primary navigation.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_add_primary_navigation() {
		?>
	    <div id="main-nav" class="clear-fix">
	        <div class="container">
		        <nav id="site-navigation" class="main-navigation" role="navigation">
		            <div class="wrap-menu-content">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'fallback_cb'    => 'clean_commerce_primary_navigation_fallback',
						) );
						?>
		            </div><!-- .menu-content -->
		        </nav><!-- #site-navigation -->

				<?php $search_in_header = clean_commerce_get_option( 'search_in_header' ); ?>
				<?php if ( true === $search_in_header ) : ?>
			        <div class="header-search-box">
			        	<a href="#" class="search-icon"><i class="fa fa-search"></i></a>
			        	<div class="search-box-wrap">
				        	<?php get_search_form(); ?>
		        		</div><!-- .search-box-wrap -->
		        	</div><!-- .header-search-box -->
				<?php endif; ?>

	       </div> <!-- .container -->
	    </div> <!-- #main-nav -->
    <?php
	}

endif;

add_action( 'clean_commerce_action_after_header', 'clean_commerce_add_primary_navigation', 20 );

if ( ! function_exists( 'clean_commerce_mobile_navigation' ) ) :

	/**
	 * Mobile navigation.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_mobile_navigation() {
		?>
		<div class="mobile-nav-wrap">
			<a id="mobile-trigger" href="#mob-menu"><i class="fa fa-bars"></i></a>
			<div id="mob-menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => '',
					'fallback_cb'    => 'clean_commerce_primary_navigation_fallback',
					) );
				?>
			</div><!-- #mob-menu -->
			<?php if ( has_nav_menu( 'header' ) ) : ?>
				<a id="mobile-trigger2" href="#mob-menu2"><i class="fa fa-bars"></i></a>
				<div id="mob-menu2">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'header',
						'container'      => '',
						) );
					?>
				</div><!-- #mob-menu2 -->
			<?php endif; ?>
		</div><!-- .mobile-nav-wrap -->
		<?php

	}

endif;
add_action( 'clean_commerce_action_before', 'clean_commerce_mobile_navigation', 20 );

if ( ! function_exists( 'clean_commerce_footer_copyright' ) ) :

	/**
	 * Footer copyright.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_footer_copyright() {

		// Check if footer is disabled.
		$footer_status = apply_filters( 'clean_commerce_filter_footer_status', true );
		if ( true !== $footer_status ) {
			return;
		}

		// Copyright content.
		$copyright_text = clean_commerce_get_option( 'copyright_text' );
		$copyright_text = apply_filters( 'clean_commerce_filter_copyright_text', $copyright_text );
		if ( ! empty( $copyright_text ) ) {
			$copyright_text = wp_kses_data( $copyright_text );
		}

		// Powered by content.
		$powered_by_text = sprintf( esc_html__( 'Clean Commerce by %s', 'clean-commerce' ), '<a target="_blank" rel="designer" href="https://wenthemes.com/">' . esc_html__( 'WEN Themes', 'clean-commerce' ) . '</a>' );

		$show_social_in_footer = clean_commerce_get_option( 'show_social_in_footer' );

		$column_count = 0;

		if ( $copyright_text ) {
			$column_count++;
		}
		if ( $powered_by_text ) {
			$column_count++;
		}
		if ( true === $show_social_in_footer && has_nav_menu( 'social' ) ) {
			$column_count++;
		}
		?>

		<div class="colophon-inner colophon-grid-<?php echo esc_attr( $column_count ); ?>">

		    <?php if ( ! empty( $copyright_text ) ) : ?>
			    <div class="colophon-column">
			    	<div class="copyright">
			    		<?php echo $copyright_text; ?>
			    	</div><!-- .copyright -->
			    </div><!-- .colophon-column -->
		    <?php endif; ?>

		    <?php if ( true === $show_social_in_footer && has_nav_menu( 'social' ) ) : ?>
			    <div class="colophon-column">
			    	<div class="footer-social">
			    		<?php the_widget( 'Clean_Commerce_Social_Widget' ); ?>
			    	</div><!-- .footer-social -->
			    </div><!-- .colophon-column -->
		    <?php endif; ?>

		    <?php if ( ! empty( $powered_by_text ) ) : ?>
			    <div class="colophon-column">
			    	<div class="site-info">
			    		<?php echo $powered_by_text; ?>
			    	</div><!-- .site-info -->
			    </div><!-- .colophon-column -->
		    <?php endif; ?>

		</div><!-- .colophon-inner -->

	    <?php
	}

endif;

add_action( 'clean_commerce_action_footer', 'clean_commerce_footer_copyright', 10 );


if ( ! function_exists( 'clean_commerce_add_sidebar' ) ) :

	/**
	 * Add sidebar.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_add_sidebar() {

		global $post;

		$global_layout = clean_commerce_get_option( 'global_layout' );
		$global_layout = apply_filters( 'clean_commerce_filter_theme_global_layout', $global_layout );

		// Check if single.
		if ( $post && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'clean_commerce_theme_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}

		// Include primary sidebar.
		if ( 'no-sidebar' !== $global_layout ) {
			get_sidebar();
		}

	}

endif;

add_action( 'clean_commerce_action_sidebar', 'clean_commerce_add_sidebar' );

if ( ! function_exists( 'clean_commerce_custom_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_custom_posts_navigation() {

		the_posts_pagination();

	}
endif;

add_action( 'clean_commerce_action_posts_navigation', 'clean_commerce_custom_posts_navigation' );


if ( ! function_exists( 'clean_commerce_add_image_in_single_display' ) ) :

	/**
	 * Add image in single post.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_add_image_in_single_display() {

		global $post;

		if ( has_post_thumbnail() ) {

			$values = get_post_meta( $post->ID, 'clean_commerce_theme_settings', true );
			$clean_commerce_theme_settings_single_image = isset( $values['single_image'] ) ? esc_attr( $values['single_image'] ) : '';

			if ( ! $clean_commerce_theme_settings_single_image ) {
				$clean_commerce_theme_settings_single_image = clean_commerce_get_option( 'single_image' );
			}

			if ( 'disable' !== $clean_commerce_theme_settings_single_image ) {
				$args = array(
					'class' => 'aligncenter',
				);
				the_post_thumbnail( esc_attr( $clean_commerce_theme_settings_single_image ), $args );
			}
		}

	}

endif;

add_action( 'clean_commerce_single_image', 'clean_commerce_add_image_in_single_display' );

if ( ! function_exists( 'clean_commerce_add_breadcrumb' ) ) :

	/**
	 * Add breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_add_breadcrumb() {

		// Bail if Home Page.
		if ( is_front_page() || is_home() ) {
			return;
		}

		echo '<div id="breadcrumb"><div class="container">';
		clean_commerce_simple_breadcrumb();
		echo '</div><!-- .container --></div><!-- #breadcrumb -->';
	}

endif;

add_action( 'clean_commerce_action_before_content', 'clean_commerce_add_breadcrumb' , 7 );


if ( ! function_exists( 'clean_commerce_footer_goto_top' ) ) :

	/**
	 * Go to top.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_footer_goto_top() {

		echo '<a href="#page" class="scrollup" id="btn-scrollup"><i class="fa fa-angle-up"></i></a>';

	}

endif;

add_action( 'clean_commerce_action_after', 'clean_commerce_footer_goto_top', 20 );

if ( ! function_exists( 'clean_commerce_header_top_content' ) ) :

	/**
	 * Header Top.
	 *
	 * @since 1.0.0
	 */
	function clean_commerce_header_top_content() {
		$contact_number        = clean_commerce_get_option( 'contact_number' );
		$contact_email         = clean_commerce_get_option( 'contact_email' );
		$show_social_in_header = clean_commerce_get_option( 'show_social_in_header' );

		if ( empty( $contact_number ) && empty( $contact_email ) ) {
			$contact_status = false;
		}
		else {
			$contact_status = true;
		}

		if ( false === $contact_status && ( false === clean_commerce_get_option( 'show_social_in_header' ) || false === has_nav_menu( 'social' ) ) ) {
			return;
		}
		?>
		<div id="tophead">
			<div class="container">
				<div id="quick-contact">
					<ul>
						<?php if ( ! empty( $contact_number ) ) :
							$cnumber_clean = preg_replace( '/\D+/', '', esc_attr( $contact_number ) ); ?>
							<li class="quick-call">
								<a href="<?php echo esc_url( 'tel:' . $cnumber_clean ); ?>"><?php echo esc_html( $contact_number ); ?></a>
							</li>
						<?php endif; ?>
						<?php if ( ! empty( $contact_email ) ) : ?>
							<li class="quick-email">
							<a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php echo esc_html( antispambot( $contact_email ) ); ?></a>
							</li>
						<?php endif; ?>
					</ul>
				</div> <!-- #quick-contact -->

				<?php if ( true === $show_social_in_header && has_nav_menu( 'social' ) ) : ?>
					<div class="header-social-wrapper">
						<?php the_widget( 'Clean_Commerce_Social_Widget' ); ?>
					</div><!-- .header-social-wrapper -->
				<?php endif; ?>

			</div> <!-- .container -->
		</div><!--  #tophead -->
		<?php
	}

endif;

add_action( 'clean_commerce_action_before_header', 'clean_commerce_header_top_content', 5 );
