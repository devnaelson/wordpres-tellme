<?php
/*

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
				'naelson-footer-menu' => esc_html__('Page Footer', 'aquila')
			]
		);
	}

	public function get_menu_id($location)
	{
		$locations = get_nav_menu_locations();

		print_r($locations);

		// echo "<pre>";
		// print_r(wp_get_nav_menu_items(3));
		// echo "</pre>";
	}
}
