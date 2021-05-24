<?php 

/**
 * Create admin Page to list unsubscribed emails.
 */
 // Hook for adding admin menus
 add_action('admin_menu', 'wpdocs_unsub_add_pages');
 
 // action function for above hook
 
/**
 * Adds a new top-level page to the administration menu.
 */
function wpdocs_unsub_add_pages() {
     add_menu_page(
        __( 'Unsub List', 'textdomain' ),
        __( 'Unsub Emails','textdomain' ),
        'manage_options',
        'wpdocs-unsub-email-list',
        'wpdocs_unsub_page_callback',
        ''
    );
}
 
/**
 * Disply callback for the Unsub page.
 */
 function wpdocs_unsub_page_callback() {
     echo 'Unsubscribe Email List';
 }