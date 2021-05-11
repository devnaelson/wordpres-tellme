<?php

/**
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

		Assets::get_instance();
		// load class.
		$this->set_hooks();
	}

	protected function set_hooks()
	{
		// actions and filters
	}
}
