<?php
/**
 * Theme Options related to carousel.
 *
 * @package Clean_Commerce
 */

$default = clean_commerce_get_default_theme_options();

// Add Panel.
$wp_customize->add_panel( 'theme_carousel_panel', array(
	'title'    => __( 'Featured Carousel', 'clean-commerce' ),
	'priority' => 100,
) );

// Carousel Type Section.
$wp_customize->add_section( 'section_theme_carousel_type', array(
	'title'    => __( 'Carousel Type', 'clean-commerce' ),
	'priority' => 100,
	'panel'    => 'theme_carousel_panel',
) );

// Setting featured_carousel_status.
$wp_customize->add_setting( 'featured_carousel_status', array(
	'default'           => $default['featured_carousel_status'],
	'sanitize_callback' => 'clean_commerce_sanitize_select',
) );
$wp_customize->add_control( 'featured_carousel_status', array(
	'label'    => __( 'Enable Carousel On', 'clean-commerce' ),
	'section'  => 'section_theme_carousel_type',
	'type'     => 'select',
	'priority' => 100,
	'choices'  => clean_commerce_get_featured_carousel_content_options(),
) );

// Setting featured_carousel_type.
$wp_customize->add_setting( 'featured_carousel_type', array(
	'default'           => $default['featured_carousel_type'],
	'sanitize_callback' => 'clean_commerce_sanitize_select',
) );
$wp_customize->add_control( 'featured_carousel_type', array(
	'label'           => __( 'Select Carousel Type', 'clean-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'select',
	'priority'        => 100,
	'choices'         => clean_commerce_get_featured_carousel_type(),
	'active_callback' => 'clean_commerce_is_featured_carousel_active',
) );

// Setting featured_carousel_number.
$wp_customize->add_setting( 'featured_carousel_number', array(
	'default'           => $default['featured_carousel_number'],
	'sanitize_callback' => 'clean_commerce_sanitize_number_range',
) );
$wp_customize->add_control( 'featured_carousel_number', array(
	'label'           => __( 'No of Carousel Items', 'clean-commerce' ),
	'description'     => __( 'Enter number between 1 and 20.', 'clean-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'type'            => 'number',
	'priority'        => 100,
	'active_callback' => 'clean_commerce_is_featured_carousel_active',
	'input_attrs'     => array( 'min' => 1, 'max' => 20, 'step' => 1, 'style' => 'width: 55px;' ),
) );

// Setting featured_carousel_category.
$wp_customize->add_setting( 'featured_carousel_category', array(
	'default'           => $default['featured_carousel_category'],
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( new Clean_Commerce_Dropdown_Taxonomies_Control( $wp_customize, 'featured_carousel_category', array(
	'label'           => __( 'Select Category', 'clean-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'settings'        => 'featured_carousel_category',
	'priority'        => 100,
	'active_callback' => 'clean_commerce_is_featured_category_carousel_active',
) ) );

// Setting featured_carousel_product_category.
$wp_customize->add_setting( 'featured_carousel_product_category', array(
	'default'           => $default['featured_carousel_product_category'],
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( new Clean_Commerce_Dropdown_Taxonomies_Control( $wp_customize, 'featured_carousel_product_category', array(
	'label'           => __( 'Select Product Category', 'clean-commerce' ),
	'section'         => 'section_theme_carousel_type',
	'settings'        => 'featured_carousel_product_category',
	'priority'        => 100,
	'taxonomy'        => 'product_cat',
	'active_callback' => 'clean_commerce_is_featured_product_category_carousel_active',
) ) );

// Carousel Options Section.
$wp_customize->add_section( 'section_theme_carousel_options', array(
	'title'      => __( 'Carousel Options', 'clean-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_carousel_panel',
) );

// Setting featured_carousel_enable_autoplay.
$wp_customize->add_setting( 'featured_carousel_enable_autoplay', array(
	'default'           => $default['featured_carousel_enable_autoplay'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_checkbox',
) );
$wp_customize->add_control( 'featured_carousel_enable_autoplay', array(
	'label'    => __( 'Enable Autoplay', 'clean-commerce' ),
	'section'  => 'section_theme_carousel_options',
	'type'     => 'checkbox',
	'priority' => 100,
) );

// Setting featured_carousel_transition_delay.
$wp_customize->add_setting( 'featured_carousel_transition_delay', array(
	'default'           => $default['featured_carousel_transition_delay'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_number_range',
) );
$wp_customize->add_control( 'featured_carousel_transition_delay', array(
	'label'       => __( 'Transition Delay', 'clean-commerce' ),
	'description' => __( 'in seconds', 'clean-commerce' ),
	'section'     => 'section_theme_carousel_options',
	'type'        => 'number',
	'priority'    => 100,
	'input_attrs' => array( 'min' => 1, 'max' => 10, 'step' => 1, 'style' => 'width: 55px;' ),
) );
