<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="error">
	<p>
        <strong>
            <?php
            esc_html_e( 'WooCommerce zoop with splits', 'cv_wc_Zoop' );
            ?>
        </strong>
        <?php
        esc_html_e( ' is enabled but not effective. It requires YITH WooCommerce Multi Vendor in order to work.', 'cv_wc_Zoopt' );
        ?>
    </p>
</div>
