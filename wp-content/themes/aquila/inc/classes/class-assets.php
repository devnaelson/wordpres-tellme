<?php

/**
 * Bootstraps the Theme.
 *
 * @package Aquila
 */

namespace AQUILA_THEME\Inc;

use AQUILA_THEME\Inc\Traits\Singleton;

class Assets
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
        add_action('wp_enqueue_scripts', [$this, 'register_styles']);
        add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    }

    /**
     * Proper way to enqueue scripts and styles
     */
    public function register_scripts()
    {
        wp_enqueue_script('GlobalScripts', get_template_directory_uri() . '/assets/js/global.js', wp_get_theme()->get('Version'));
        wp_enqueue_script('BootstrapScript', get_template_directory_uri() . '/assets/js/bootstrap.js', wp_get_theme()->get('Version'));
    }

    public function register_styles()
    {
        //wp_enqueue_style('GlobalStyles', get_stylesheet_uri(), wp_get_theme()->get('Version'));
        wp_enqueue_style('GlobalStyles', get_template_directory_uri() . '/style.css', wp_get_theme()->get('Version'));
        wp_enqueue_style('BootstrapStyle', get_template_directory_uri() . '/assets/css/bootstrap.css', wp_get_theme()->get('Version'));
    }
};
