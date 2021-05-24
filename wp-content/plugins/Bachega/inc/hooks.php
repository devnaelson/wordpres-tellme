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