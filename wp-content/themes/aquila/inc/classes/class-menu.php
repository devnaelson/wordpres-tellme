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

class Menu
{
	use Singleton;

	protected function __construct()
	{

		// load class.
		$this->set_hooks();
	}

	protected function set_hooks()
	{
		// actions and filters
		add_action('init', [$this, 'register_my_menus']);
	}

	public function register_my_menus()
	{

		//avoid conflits with plugin
		register_nav_menus(
			[
				'naelson-header-menu' => esc_html__('Header Menu', 'aquila'),
				'naelson-footer-menu' => esc_html__('Header Fooder', 'aquila')
			]
		);
	}
}
