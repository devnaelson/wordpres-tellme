<?php
/**
 * Created by PhpStorm.
 * User: cassiovidal
 * Date: 19/07/18
 * Time: 14:44
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="split_vendors_product_data" class="panel woocommerce_options_panel hidden">
<?php foreach ( $vendor_fields as $field_args ) : ?>
	<div class="options_group">
		<?php
		foreach ( $field_args as $field_arg ) {
			$type = $field_arg['type'];
			switch ( $type ) {
				case 'select':
					woocommerce_wp_select( $field_arg );
					break;
				case 'number':
				case 'text':
					woocommerce_wp_text_input( $field_arg );
					break;
			}
		}
		?>
	</div>
<?php endforeach; ?>
<?php wp_nonce_field( 'vendors_product_data_action', 'vendors_product_data_nonce' ); ?>
</div>

