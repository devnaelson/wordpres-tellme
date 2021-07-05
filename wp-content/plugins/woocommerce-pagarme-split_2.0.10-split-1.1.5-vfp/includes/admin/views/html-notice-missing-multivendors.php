<?php
/**
 * Notice: Missing WooCommerce.
 *
 * @package WooCommerce_Pagarme/Admin/Notices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="error">
	<p><strong><?php esc_html_e( 'WooCommerce Pagar.me with splits', 'woocommerce-pagarme-split' ); ?></strong> <?php esc_html_e( ' is enabled but not effective. It requires YITH WooCommerce Multi Vendor in order to work.', 'woocommerce-pagarme-split' ); ?></p>
</div>
