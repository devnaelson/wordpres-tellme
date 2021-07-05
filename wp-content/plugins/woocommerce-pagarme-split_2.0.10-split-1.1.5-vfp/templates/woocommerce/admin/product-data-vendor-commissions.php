<?php
/**
 * Pagarme-split Integrations template for product multi vendor
 *
 * PHP version 5
 *
 * @category Integration
 * @package  Pagarme-split/Integration
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="split_vendors_product_data" class="panel woocommerce_options_panel hidden">
<?php foreach ( $fields_args as $field_args ) : ?>
	<div class="options_group">
		<?php
		foreach ( $field_args as $field_arg ) {
			$type = $field_arg['type'];
			switch ( $type ) {
				case 'select':
					woocommerce_wp_select( $field_arg );
					break;
				case 'number':
					woocommerce_wp_text_input( $field_arg );
					break;
			}
		}
		?>
	</div>
<?php endforeach; ?>
<?php wp_nonce_field( 'vendors_product_data_action', 'vendors_product_data_nonce' ); ?>
</div>
