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

require 'inc/hooks.php';
require 'inc/Libaries.php';
define("URL_BACHEGA", plugins_url());


/**
 * Disply callback for the Unsub page.
 */
function menu_unsub_page_callback()
{

    require_once 'header.php';
    global $current_user;

    
    // if (is_user_logged_in()) {

    //     echo '<span class="ciao">HELLO ' . $current_user->user_level . '</span>\n';
    //     echo '<a href="' . wp_logout_url(home_url()) . '" title="Logout">Logout</a>';

    // } else { echo '<a href="' . wp_login_url(get_permalink()) . '" title="Login">Login</a>'; }
    
    // $locations = get_nav_menu_locations();
    // $menu_id = $locations['ngsp-nav-menu-loc'];
    // $page = get_post(get_page_by_title('spreadsheet_req')->ID); // etc

    // wp_update_nav_menu_item($menu_id, 0, array(
    //     'menu-item-title' => $page->post_title,
    //     'menu-item-object-id' => $page->ID,
    //     'menu-item-object' => 'page',
    //     'menu-item-status' => 'publish',
    //     'menu-item-type' => 'post_type',
    // ));
    

    // wp_nav_menu([
    // 'theme_location' => 'ngsp-menu-a',
    // 'container_class' => 'custom-menu-class'
    // ]);
    
    require_once 'footer.php';
}

/**
 * Disply callback for the Unsub page.
 */
function config_submenu_page_callback()
{
    require_once 'header.php';

    $error = null;
    $error = (isset($_GET['create_post']) == true and $_GET['create_post'] == true) ? require 'inc/set-configs.php' : 0;
    if (!$error == 0 || $error == null) {
        $post_porcent = (get_page_by_title("spreadsheet_req") != NULL) ? 100 : 0;

?>
<div class="container">
    <div class="row text-center">
        <div class="col p-5">
            <div class="progress m-1" style="height: 32px;">
                <div class="progress-bar <?php echo ((($post_porcent == 0) ? 'bg-danger' : ($post_porcent > 0 and $post_porcent > 100)) ? 'bg-warning' : 'bg-success'); ?>"
                    role="progressbar" style="<?php echo 'width: ' . $post_porcent . '%; color: black;' ?>"
                    aria-valuenow="<?php echo  $post_porcent ?>" aria-valuemin="0" aria-valuemax="100">Creating
                    Post:&ensp;<?php echo  $post_porcent; ?>%</div>
            </div>
            <?php if ($post_porcent < 100) { ?><p><a class="btn btn-primary"
                    href="<?php echo esc_url(add_query_arg(array('create_post' => true))); ?>" role="button">ACTION</a>
            </p> <?php } ?>
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
    } else { require_once '404.php'; }
    require_once 'footer.php';
}