<?php

/**
 * Plugin Name:       test
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Plugin handle planilha.
 * Version:           0.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Naelson G
 * Author URI:        https://github.com/NaelsonBrasil/
 * License:           Private
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       DEV-PROGRAMMER-plugin
 * Domain Path:       /languages
 */

//require 'inc/hooks.php';
add_action('admin_menu', 'menu_unsub_add_pages');
function menu_unsub_add_pages()
{
    add_menu_page(
        __('TEST', 'textdomain'),
        __('TEST', 'textdomain'),
        'manage_options',
        'exc-main',
        'menu_unsub_page_callbackTEST',
        '',
        60
    );
}
function menu_unsub_page_callbackTEST()
{
    echo "INTO";
}


add_action( 'wp_ajax_my_action', 'calledFilePHP' );
function calledFilePHP( $page_template ) {
    $page_template = dirname( __FILE__ ) . '/ajax.php';
}

add_action( 'admin_enqueue_scripts', 'calledJS' );
function calledJS() {
	wp_enqueue_script( 'ajax-script', plugins_url( '/global.js', __FILE__ ), array(), null, true );
}
