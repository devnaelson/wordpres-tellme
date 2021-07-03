<?php
add_action('admin_menu', 'menu_unsub_add_pages');
function menu_unsub_add_pages()
{
    add_menu_page(
        __('Pagame', 'textdomain'),
        __('Pagame', 'textdomain'),
        'manage_options',
        'exc-main',
        'menu_unsub_page_callback',
        '',
        60
    );
}

add_action( 'init', 'test_init');
function test_init(){
    add_action( 'admin_init', 'test_admin_init');
}
 
function test_admin_init() {
    echo "Admin Init Inside Init";
}

// add_action( 'wp_ajax_exe_ajax', 'exe_ajax' );
// function exe_ajax() {
//     echo "result";
//    wp_die(); // this is required to terminate immediately and return a proper response
// }

// add_action( 'admin_enqueue_scripts', 'calledJS' );
// function calledJS() {
// 	wp_enqueue_script( 'ajax-script', URL_PAGAME."/global.js", array(), null, true );
// }