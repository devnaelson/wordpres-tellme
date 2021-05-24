
<?php
/**
 * Plugin Name:       Aquila
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

/**
 * Create admin Page to list unsubscribed emails.
 */
// Hook for adding admin menus
add_action('admin_menu', 'wpdocs_unsub_add_pages');

// action function for above hook

/**
 * Adds a new top-level page to the administration menu.
 */
function wpdocs_unsub_add_pages()
{
    add_menu_page(
        __('Planilhas', 'textdomain'),
        __('Planilha - UP', 'textdomain'),
        'manage_options',
        'wpdocs-unsub-email-list',
        'wpdocs_unsub_page_callback',
        '',
        50
    );
}

/**
 * Disply callback for the Unsub page.
 */
function wpdocs_unsub_page_callback()
{
    echo 'Unsubscribe Email List';
}
