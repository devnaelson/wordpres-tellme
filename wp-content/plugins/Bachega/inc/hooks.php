<?php
add_action('admin_menu', 'menu_unsub_add_pages');
function menu_unsub_add_pages()
{
    add_menu_page(
        __('Planilhas', 'textdomain'),
        __('Planilhas', 'textdomain'),
        'manage_options',
        'exc-main',
        'menu_unsub_page_callback',
        '',
        60
    );
}

add_action( 'wp_ajax_exe_ajax', 'exe_ajax' );
function exe_ajax() {
   require 'ajax.php';
   wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'admin_enqueue_scripts', 'calledJS' );
function calledJS() {
	wp_enqueue_script( 'ajax-script', URL_BACHEGA."/global.js", array(), null, true );
}