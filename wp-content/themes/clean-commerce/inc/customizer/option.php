<?php
/**
 * Theme Options.
 *
 * @package Clean_Commerce
 */

$default = clean_commerce_get_default_theme_options();

// Add Panel.
$wp_customize->add_panel( 'theme_option_panel',
	array(
	'title'      => __( 'Theme Options', 'clean-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	)
);

// Header Section.
$wp_customize->add_section( 'section_header',
	array(
	'title'      => __( 'Header Options', 'clean-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting show_title.
$wp_customize->add_setting( 'show_title',
	array(
	'default'           => $default['show_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_title',
	array(
	'label'    => __( 'Show Site Title', 'clean-commerce' ),
	'section'  => 'section_header',
	'type'     => 'checkbox',
	'priority' => 100,
	)
);
// Setting show_tagline.
$wp_customize->add_setting( 'show_tagline',
	array(
	'default'           => $default['show_tagline'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_tagline',
	array(
	'label'    => __( 'Show Tagline', 'clean-commerce' ),
	'section'  => 'section_header',
	'type'     => 'checkbox',
	'priority' => 100,
	)
);

// Setting contact_number.
$wp_customize->add_setting( 'contact_number',
	array(
	'default'           => $default['contact_number'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'contact_number',
	array(
	'label'    => __( 'Contact Number', 'clean-commerce' ),
	'section'  => 'section_header',
	'type'     => 'text',
	'priority' => 100,
	)
);

// Setting contact_email.
$wp_customize->add_setting( 'contact_email',
	array(
	'default'           => $default['contact_email'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_email',
	)
);
$wp_customize->add_control( 'contact_email',
	array(
	'label'    => __( 'Contact Email', 'clean-commerce' ),
	'section'  => 'section_header',
	'type'     => 'text',
	'priority' => 100,
	)
);

// Setting show_social_in_header.
$wp_customize->add_setting( 'show_social_in_header',
	array(
	'default'           => $default['show_social_in_header'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_social_in_header',
	array(
	'label'    => __( 'Show Social Icons', 'clean-commerce' ),
	'section'  => 'section_header',
	'type'     => 'checkbox',
	'priority' => 100,
	)
);

$wp_customize->add_setting( 'search_in_header',
	array(
		'default'           => $default['search_in_header'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'clean_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'search_in_header',
	array(
		'label'    => __( 'Enable Search', 'clean-commerce' ),
		'section'  => 'section_header',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Layout Section.
$wp_customize->add_section( 'section_layout',
	array(
	'title'      => __( 'Layout Options', 'clean-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting global_layout.
$wp_customize->add_setting( 'global_layout',
	array(
	'default'           => $default['global_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'global_layout',
	array(
	'label'    => __( 'Global Layout', 'clean-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => clean_commerce_get_global_layout_options(),
	'priority' => 100,
	)
);
// Setting archive_layout.
$wp_customize->add_setting( 'archive_layout',
	array(
	'default'           => $default['archive_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'archive_layout',
	array(
	'label'    => __( 'Archive Layout', 'clean-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => clean_commerce_get_archive_layout_options(),
	'priority' => 100,
	)
);
// Setting archive_image.
$wp_customize->add_setting( 'archive_image',
	array(
	'default'           => $default['archive_image'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'archive_image',
	array(
	'label'    => __( 'Image in Archive', 'clean-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => clean_commerce_get_image_sizes_options( true, array( 'disable', 'thumbnail', 'large' ), false ),
	'priority' => 100,
	)
);
// Setting archive_image_alignment.
$wp_customize->add_setting( 'archive_image_alignment',
	array(
	'default'           => $default['archive_image_alignment'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'archive_image_alignment',
	array(
	'label'           => __( 'Image Alignment in Archive', 'clean-commerce' ),
	'section'         => 'section_layout',
	'type'            => 'select',
	'choices'         => clean_commerce_get_image_alignment_options(),
	'priority'        => 100,
	'active_callback' => 'clean_commerce_is_image_in_archive_active',
	)
);
// Setting single_image.
$wp_customize->add_setting( 'single_image',
	array(
	'default'           => $default['single_image'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_select',
	)
);
$wp_customize->add_control( 'single_image',
	array(
	'label'    => __( 'Image in Single Post/Page', 'clean-commerce' ),
	'section'  => 'section_layout',
	'type'     => 'select',
	'choices'  => clean_commerce_get_image_sizes_options( true, array( 'disable', 'large' ), false ),
	'priority' => 100,
	)
);

// Footer Section.
$wp_customize->add_section( 'section_footer',
	array(
	'title'      => __( 'Footer Options', 'clean-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting copyright_text.
$wp_customize->add_setting( 'copyright_text',
	array(
	'default'           => $default['copyright_text'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
	)
);
$wp_customize->add_control( 'copyright_text',
	array(
	'label'    => __( 'Copyright Text', 'clean-commerce' ),
	'section'  => 'section_footer',
	'type'     => 'text',
	'priority' => 100,
	)
);

// Setting show_social_in_footer.
$wp_customize->add_setting( 'show_social_in_footer',
	array(
		'default'           => $default['show_social_in_footer'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'clean_commerce_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_social_in_footer',
	array(
		'label'    => __( 'Show Social Icons', 'clean-commerce' ),
		'section'  => 'section_footer',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Blog Section.
$wp_customize->add_section( 'section_blog',
	array(
	'title'      => __( 'Blog Options', 'clean-commerce' ),
	'priority'   => 100,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting excerpt_length.
$wp_customize->add_setting( 'excerpt_length',
	array(
	'default'           => $default['excerpt_length'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'clean_commerce_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'excerpt_length',
	array(
	'label'       => __( 'Excerpt Length', 'clean-commerce' ),
	'description' => __( 'in words', 'clean-commerce' ),
	'section'     => 'section_blog',
	'type'        => 'number',
	'priority'    => 100,
	'input_attrs' => array( 'min' => 1, 'max' => 200, 'style' => 'width: 55px;' ),
	)
);

// Setting read_more_text.
$wp_customize->add_setting( 'read_more_text',
	array(
	'default'           => $default['read_more_text'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'read_more_text',
	array(
	'label'    => __( 'Read More Text', 'clean-commerce' ),
	'section'  => 'section_blog',
	'type'     => 'text',
	'priority' => 100,
	)
);

// Setting exclude_categories.
$wp_customize->add_setting( 'exclude_categories',
	array(
	'default'           => $default['exclude_categories'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'exclude_categories',
	array(
	'label'       => __( 'Exclude Categories in Blog', 'clean-commerce' ),
	'description' => __( 'Enter category ID to exclude in Blog Page. Separate with comma if more than one', 'clean-commerce' ),
	'section'     => 'section_blog',
	'type'        => 'text',
	'priority'    => 100,
	)
);
