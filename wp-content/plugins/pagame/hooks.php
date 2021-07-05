<?php
add_action('admin_menu', 'menu_unsub_add_pages');
function menu_unsub_add_pages()
{
    add_menu_page(
        __('Pagame', 'textdomain'),
        __('Pagame', 'textdomain'),
        'manage_options',
        'exc-main',
        'menu_unsub_page_callback',
        '',
        60
    );
}


add_action( 'init', function() {
    if ( empty( $_POST['doing_form'] ) ) {
        return; // We didn't submit the form
    }
    // We did! Do the form handling
    echo "ASASFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF";
});


function my_theme_function(){
    echo "Plugin func";
}

function my_theme_is_loaded() {
    // Bootstrap your plugin here
    // OR
    // try to run your function this way:
    echo "asasddddddddddddddddddddd";
    if ( function_exists( 'my_theme_function' ) ) {
        my_theme_function();
    }
}

//with this you can call in your theme. 

// You can also try replace the `after_setup_theme` with the
// `init`. I guess it could work in both ways, but whilw your
// plugin rely on the theme code, the following is best option.
add_action( 'after_setup_theme', 'my_theme_is_loaded' );
    
// add_action( 'init', 'test_init');
// function test_init(){
//     add_action( 'admin_init', 'test_admin_init');
// }
 
// function test_admin_init() {
//     echo "Admin Init Inside Init";
// }

// add_action( 'wp_ajax_exe_ajax', 'exe_ajax' );
// function exe_ajax() {
//     echo "result";
//    wp_die(); // this is required to terminate immediately and return a proper response
// }

add_action( 'admin_enqueue_scripts', 'calledJS' );
function calledJS() {
	wp_register_script( 'ajax-script', URL_PAGAME."/global.js", array(), null, true );
    wp_enqueue_script('ajax-script');
}


// add_action( 'after_setup_theme', 'wpdocs_i_am_a_function' );
// function wpdocs_i_am_a_function() {
//     add_theme_support( 'title-tag' );
//     add_theme_support( 'post-thumbnails' );
//     add_theme_support( 'custom-header' );
// }

