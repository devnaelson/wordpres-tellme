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

    add_submenu_page(
        'exc-main',
        'Config',
        'Config',
        'manage_options',
        'configs',
        'config_submenu_page_callback',
    );
}

add_action( 'wp_ajax_my_action', 'calledFilePHP' );
function calledFilePHP( $page_template ) {
    $page_template = dirname( __FILE__ ) . '/ajax.php';
}

add_action( 'admin_enqueue_scripts', 'calledJS' );
function calledJS() {
	wp_enqueue_script( 'ajax-script', plugins_url( '/global.js', __FILE__ ), array(), null, true );
}
