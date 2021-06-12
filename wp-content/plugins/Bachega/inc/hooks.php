<?php

// Hook for adding admin menus
add_action('admin_menu', 'menu_unsub_add_pages');


/**
 * Adds a new top-level page to the administration menu.
 */
function menu_unsub_add_pages()
{
    add_menu_page(
        __('Planilhas', 'textdomain'),
        __('Planilhas', 'textdomain'),
        'manage_options',
        'planilha',
        'menu_unsub_page_callback',
        '',
        60
    );

    add_submenu_page(
        'planilha',
        'Config',
        'Config',
        'manage_options',
        'configs',
        'config_submenu_page_callback',
    );
}

//Widget Show
function ngspMenuA()
{
    register_nav_menu('ngsp-nav-menu-loc', __('MenuNV Excel'));
}
add_action('init', 'ngspMenuA');

// wp_update_nav_menu_item($menu_id, 0, array(
//     'menu-item-title' => 'My Link',
//     'menu-item-url' => 'http://example.com/',
//     'menu-item-status' => 'publish',
//     'menu-item-type' => 'custom', // optional
// ));


// global $wp_rewrite;
// if(!page_exists_by_slug('twizo-verification')){
//     $newPage = array(
//         'post_title' => '2FA Settings',
//         'post_name' => 'twizo-verification',
//         'post_type' => 'page',
//         'post_status' => 'publish'
//     );

//     wp_insert_post($newPage);
// }

//  function page_exists_by_slug($page_slug) {

// $page = get_page_by_path( $page_slug , OBJECT );

// if ( isset($page) )
//    return true;
// else
//    return false;
// }