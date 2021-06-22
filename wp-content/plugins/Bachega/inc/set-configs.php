<?php
function auto_config()
{

    $sheetRequest = array('post_title' => 'sheet-request', 'post_content'  => '', 'post_status'   => 'publish', 'post_author'   => 1, 'post_category' => array(1), 'post_type'     => 'page');
    $sheetUpload = array('post_title' => 'sheet-upload', 'post_content'  => '', 'post_status'   => 'publish', 'post_author'   => 1, 'post_category' => array(1), 'post_type'     => 'page');

    $flagSheetRequest = wp_insert_post($sheetRequest);
    $flagSheetUpload = wp_insert_post($sheetUpload);

    if ($flagSheetRequest > 0)
        echo '<div class="alert m-2 alert-success" role="alert">Successfull page ID=' . $flagSheetRequest . '</div>';
    else echo '<div class="alert m-2 alert-danger" role="alert">Error pageID=' . $flagSheetRequest . '</div>';

    if ($flagSheetUpload > 0)
        echo '<div class="alert m-2 alert-success" role="alert">Successfull page ID=' . $flagSheetUpload . '</div>';
    else echo '<div class="alert m-2 alert-danger" role="alert">Error pageID=' . $flagSheetUpload . '</div>';

/*
    // Check if the menu exists
    $menu_name   = 'ngsp-menu-a';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    // If it doesn't exist, let's create it.
    if (!$menu_exists) {

        $menu_id = wp_create_nav_menu($menu_name); //create name menu
        if (!has_nav_menu('ngsp-nav-menu-loc')) {
            $locations = get_theme_mod('nav_menu_locations');
            $locations['ngsp-nav-menu-loc'] = $menu_id; //add to menu
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    if (count(wp_get_nav_menu_items('ngsp-menu-a')) == 0) {

        $locations = get_nav_menu_locations();
        $menu_id = $locations['ngsp-nav-menu-loc'];
        $post_upload_id = get_post(get_page_by_title('sheet-upload')->ID);

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => $post_upload_id->post_title,
            'menu-item-object-id' => $post_upload_id->ID,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
        ));
    }
    */
}


auto_config();
