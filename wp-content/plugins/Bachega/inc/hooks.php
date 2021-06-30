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

add_filter( 'page_template', 'templateJ1' );
function templateJ1( $page_template ) {
    if ( is_page( 'sheet-request' ) ) {
        $page_template = dirname( __FILE__ ) . '/ajax.php';
    }
    return $page_template;
}