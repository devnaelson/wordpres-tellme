<?php
/*
	login users
	make pages and posts
	add media
	create a blog
	let users reply
	delete content
	add categories and tags
	add widgets
	embed a video.

 * Bootstraps the Theme.
 *
 * @package Aquila
 */

namespace AQUILA_THEME\Inc;

use AQUILA_THEME\Inc\Traits\Singleton;

class AQUILA_THEME
{
	use Singleton;

	protected function __construct()
	{

		// load class.
		Assets::get_instance();
		$this->set_hooks();
	}

	protected function set_hooks()
	{
		// actions and filters
		add_action('after_setup_theme', [$this, 'setup_theme']);
	}

	public function setup_theme()
	{
		add_theme_support('title-tag');
		$defaults = array(
			'height'               => 100,
			'width'                => 400,
			'flex-height'          => true,
			'flex-width'           => true,
			'header-text'          => array('site-title', 'site-description'),
			'unlink-homepage-logo' => true,
		);
		add_theme_support('custom-logo', $defaults);

		$another_args = array(
			'default-color'      => '0000ff',
			//'default-image'      => get_template_directory_uri() . '/images/wapuu.jpg',
			'default-position-x' => 'right',
			'default-position-y' => 'top',
			'default-repeat'     => 'no-repeat',
		);
		add_theme_support('custom-background', $another_args);

		//https://developer.wordpress.org/reference/functions/add_theme_support/#post-thumbnails
		add_theme_support('post-thumbnails');
		add_theme_support('automatic-feed-links');
		add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
		add_editor_style();
		add_theme_support('wp-block-styles');
		add_theme_support('align-wide');
	}
}
