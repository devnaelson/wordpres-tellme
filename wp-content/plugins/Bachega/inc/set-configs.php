<?php
function auto_config()
{

    $sheetRequest = array('post_title' => 'sheet-request', 'post_content'  => '', 'post_status'   => 'publish', 'post_author'   => 1, 'post_category' => array(1), 'post_type'     => 'page');
    $flagSheetRequest = wp_insert_post($sheetRequest);
    if ($flagSheetRequest > 0)
         echo '<div class="alert m-2 alert-success" role="alert">Successfull page ID=' . $flagSheetRequest . '</div>';
    else 
         echo '<div class="alert m-2 alert-danger" role="alert">Error pageID=' . $flagSheetRequest . '</div>';
         
}
auto_config();
