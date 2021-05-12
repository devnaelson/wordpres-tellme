<?php
/*
 ** Header Navigation 
 ** @package aquila
*/

$menu_class = \AQUILA_THEME\Inc\Menu::get_instance();
$menu_class->get_menu_id('naelson-header-menu');
//print_r(wp_get_nav_menu_items(2));

wp_nav_menu([
    'theme_location' => 'naelson-header-menu',
    'container_class' => 'custom-menu-class'
]);
