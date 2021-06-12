<?php
function auto_config()
{

    $autoC_post = array('post_title' => 'spreadsheet_req', 'post_content'  => '', 'post_status'   => 'publish', 'post_author'   => 1, 'post_category' => array(1), 'post_type'     => 'page');
    $flag = wp_insert_post($autoC_post);

    if ($flag > 0) echo '<div class="alert m-2 alert-success" role="alert">Successfull pageID=' . $flag . '</div>';
     else echo '<div class="alert m-2 alert-danger" role="alert">Error pageID=' . $flag . '</div>';
    
    if ($flag > 0) {

        // Check if the menu exists
        $menu_name   = 'ngsp-menu-a';
        $menu_exists = wp_get_nav_menu_object($menu_name);

        // If it doesn't exist, let's create it.
        if (!$menu_exists) {

            $menu_id = wp_create_nav_menu($menu_name); //create name menu

            if (!has_nav_menu('ngsp-nav-menu-loc')) {
                $locations = get_theme_mod('nav_menu_locations');
                $locations['ngsp-nav-menu-loc'] = $menu_id;//add to menu
                set_theme_mod('nav_menu_locations', $locations);
            }

        }
    }
}

auto_config();
