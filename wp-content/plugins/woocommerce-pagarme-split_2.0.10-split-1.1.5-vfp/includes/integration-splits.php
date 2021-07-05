<?php
/**
 * Pagarme-split INIT Integrations Functions
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
	exit;
}

add_filter( 'wc_pagarme_transaction_data', 'generate_transaction_split_rules', 11, 2 );
/**
 * Generate transaction split data
 *
 * @param array $data  Current transaction data.
 * @param int   $order Current Order number.
 *
 * @returns $data array Modified transaction data
 */
function generate_transaction_split_rules( $data, $order ) {
	$line_items = $order->get_items( 'line_item' );
	$item_meta_key = wp_get_post_parent_id( $order->id ) ? '_commission_id' : '_child__commission_id';
	write_log( 'Total items: ' . count( $line_items ) );
	$order_total = $order->get_total();
	$total_commissions = 0;
	$split_rules = array();
	$splits = array();
	$added_ship = array();


	foreach ( $line_items as $line_item_id => $line_item ) {
		/**
		 * Process splits commission and shipping for product vendor.
		 */
		$commission_id   = wc_get_order_item_meta( $line_item_id, $item_meta_key );
		$commission      = YITH_Commission( $commission_id );
		$commission_amount = 0;

		if ( $commission->exists() ) {
			$vendor = $commission->get_vendor();
			$user = $commission->get_user();
			$commission_amount = $commission->get_amount();
			$shipping_id     = wc_get_order_item_meta( $line_item_id, '_shipping_line_id' );
			$shipping_cost   = wc_get_order_item_meta( $shipping_id, 'cost' );
			if ( ! in_array( $shipping_id, $added_ship, true ) ) {
				$added_ship[] = $shipping_id;
				$commission_amount += $shipping_cost;
			}
		}
		// Write Receivers to log!
		$wlog = "item_id: {$line_item_id} item_total: {$line_item['line_total']}";
		$wlog .= " commission: {$commission_amount} ";
		if ( isset( $user ) ) {
			$owner_id = $user->ID;
			$wlog .= " user_owner_id: {$owner_id}";
		}
		if ( isset( $vendor ) ) {
			$owner_id = $vendor->get_owner();
			$wlog .= " vendor_owner_id: {$owner_id}";
		}
		write_log( $wlog );
		unset( $recipient_id, $recebedor_id );
		if ( isset( $owner_id )
			&& is_numeric( $owner_id )
		) {
			$recipient_id = get_user_meta( $owner_id, 'pgm_recipient_id', true );
			$splits = get_splits_with_receiver( $recipient_id, $commission_amount, $splits );
			$recebedor_id = get_the_author_meta( 'user_recebedor', $owner_id );
		}
		if ( ! isset( $recipient_id ) && isset( $recebedor_id ) ) {
			$recipient_id = get_post_meta( $recebedor_id, 'vm50_recebedor_id', true );
			$splits = get_splits_with_receiver( $recipient_id, $commission_amount, $splits );
		}
		/**
		 * Process splits for product multi vendors
		 */
		$splits = split_multivendor_product_commissions( $line_item, $commission_amount, $splits );
	}
	// Get total of commission from splits.
	foreach ( $splits as $split ) {
		$total_commissions += $split['amount'];
	}
	$residual_amount = $order_total - $total_commissions;
	// Calculate interest_amount to increment residual_amount when interest payment occurs.
	$interest_amount = sprintf( '%.2f', $data['amount'] / 100 ) - $order_total;
	$residual_amount += $interest_amount;
	if ( $residual_amount > 0 ) {
		$receiver_master_id = get_master_recipient_id();
		if ( ! isset( $receiver_master_id ) ) {
			$receiver_master_id = encontra_lojista();
		}
		$splits = get_splits_with_receiver( $receiver_master_id, $residual_amount, $splits, true );
	}
	// last pass and create split_rules.
	foreach ( $splits as $split ) {
		$split_amount = (float) $split['amount'];
		$split['amount'] = number_format( $split_amount, 2, '', '' );
		write_log( "recpt_id: {$split['recipient_id']} split_amount: {$split_amount} split: {$split['amount']}" );
		$split_rules[] = $split;
	}

	write_log( "Total commissions: {$total_commissions}" );
	write_log( "Total order: {$order_total}" );
	write_log( "Residual: {$residual_amount}" );
	write_log( "Interest of order: {$interest_amount}" );
	write_log( 'Total rules: ' . count( $split_rules ) );

	// Change data and return.
	$data['split_rules'] = $split_rules;
	return    $data;
}

/**
 * Modify the Generate transaction split data
 *
 * @param array $recipient_id      Recipient to add commission value.
 * @param float $commission_amount Commission value to recipient.
 * @param array $splits            Current splits array to modify.
 * @param boolean $master          Receiver is master.
 *
 * @returns $data array Modified transaction data
 */
function get_splits_with_receiver( $recipient_id, $commission_amount, $splits, $master = false ) {
	if ( empty( $recipient_id ) ) {
		return $splits;
	}
	$receiver_factory = array(
		'recipient_id' => '',
		'charge_processing_fee' => false,
		'liable' => true,
		'percentage' => null,
		'amount' => 0,
	);
	$receiver = $receiver_factory;
	$receiver['recipient_id'] = $recipient_id;
	$receiver['master'] = $master ? 'yes' : 'no';
	$receiver['charge_processing_fee'] = $master;
	$receiver['amount'] = (float) wc_format_decimal( $commission_amount, 2 );
	if ( isset( $splits[ $recipient_id ]['amount'] )
		&& is_numeric( $splits[ $recipient_id ]['amount'] )
	) {
		$receiver['amount'] += $splits[ $recipient_id ]['amount'];
	}
	$splits[ $recipient_id ] = $receiver;
	return $splits;
}

/**
 * Calculate splits values for multi vendors.
 *
 * @param object $line_item Order line_item to help calculate splits.
 * @param array  $splits    Current array of splits to modify.
 *
 * @return array $splits    Returns modified splits array.
 */
function split_multivendor_product_commissions( $line_item, $vendor_commission, $splits ) {
	$product_id = $line_item['product_id'];
	$line_item_name = $line_item['name'];
	$line_total = (float) $line_item['line_total'];
	$total_ammount = $line_total; // - $vendor_commission;
	$vendors_data = vendors_product_commissions_data( $product_id );
	$total_commissions = 0;

	write_log( "Processing multivendor for line_item: ${line_item_name}." );
	foreach ( $vendors_data as $fields ) {
		foreach ( $fields as $key => $field ) {
			$type = $field['type'];
			switch ( $type ) {
				case 'select':
					$vendor_id = $field['value'];
					break;

				case 'number':
					$vendor_value = (float) $field['value'] / 100;
					break;
			}
		}
		$recipient_id = get_user_meta( $vendor_id, 'pgm_recipient_id', true );
		if ( ! empty( $recipient_id ) ) {
			$commission = $total_ammount * $vendor_value;
			$total_commissions += $commission;
			$splits = get_splits_with_receiver( $recipient_id, $commission, $splits );
			write_log( "Product commission to: ${recipient_id} ammount: ${commission}" );
		}
	}

	write_log( "Total product commissions: ${total_commissions}" );
	write_log( "Finished multivendor processing for line_item: ${line_item_name}." );
	return $splits;
}
