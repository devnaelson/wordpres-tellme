<?php

function auto_config()
{
    $autoC_post = array('post_title' => 'spreadsheet_req', 'post_content'  => '', 'post_status'   => 'publish', 'post_author'   => 1, 'post_category' => array(1), 'post_type'     => 'page');
    $flag = wp_insert_post($autoC_post);
    if ($flag > 0) {
        echo '<div class="alert m-2 alert-success" role="alert">Successfull PageID=' . $flag . '</div>';
    } else {
        echo '<div class="alert m-2 alert-danger" role="alert">Error PageID=' . $flag . '</div>';
    }
}

auto_config();
