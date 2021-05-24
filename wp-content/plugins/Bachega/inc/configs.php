<?php

function auto_posts()
{

    $autoC_post = array(
        'post_title'    => 'spreadsheet_req',
        //'post_content'  => 'Default',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_category' => array(1),
        'post_type'     => 'page'
    );

    // Insert the post into the database
    wp_insert_post($autoC_post);
}
