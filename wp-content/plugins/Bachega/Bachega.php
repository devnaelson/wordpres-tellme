<?php

/**
 * Plugin Name:       Bachega
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

// Hook for adding admin menus
add_action('admin_menu', 'menu_unsub_add_pages');

// action function for above hook

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


/**
 * Disply callback for the Unsub page.
 */
function menu_unsub_page_callback()
{
    require_once 'header.php';

    wp_nav_menu([
        'theme_location' => 'naelson-header-menu',
        'container_class' => 'custom-menu-class'
    ]);

    require_once 'footer.php';
}

/**
 * Disply callback for the Unsub page.
 */
function config_submenu_page_callback()
{
    require_once 'header.php';
    $error =  true;
    if (isset($_GET['create_post']) == true and $_GET['create_post'] == true) {
    } else {

    }
    
?>
    <div class="container">
        <div class="row text-center">
            <div class="col p-5">
                <div class="alert alert-info" role="alert">
                    Criar posts
                </div>
                <p><a class="btn btn-primary" href="<?php echo esc_url(add_query_arg(array('create_post' => true))); ?>" role="button">ACTION</a></p>
            </div>
            <div class="col p-5">
                NONE
            </div>
            <div class="col p-5">
                NONE
            </div>
        </div>
    </div>
<?php
    require_once 'footer.php';
}
