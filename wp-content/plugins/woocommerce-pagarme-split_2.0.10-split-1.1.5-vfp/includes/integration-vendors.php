<?php
/**
 * Pagarme-split Integrations for product multi vendor functions
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

add_filter( 'woocommerce_product_data_tabs', 'add_split_vendors_product_data_tab', 60, 1 );
/**
 * Add vendors to product tabs
 *
 * @param array $tabs Product tabs array.
 *
 * @return array $tabs Return the modified tabs array.
 */
function add_split_vendors_product_data_tab( $tabs ) {
		$tabs['split_vendors'] = array(
				'label'    => __( 'Vendors Split', 'woocommerce-pagarme' ),
				'target' => 'split_vendors_product_data',
		);
		return $tabs;
}

add_action( 'woocommerce_product_data_panels', 'split_vendors_product_commission_content' );
/**
 * Add commission tab in single product "Product Data" section
 */
function split_vendors_product_commission_content() {
	if ( current_user_can( 'manage_woocommerce' ) ) {
		$vendor_fields = vendors_product_commissions_data();
		$args = array(
			'fields_args' => $vendor_fields,
		);
		pgm_wc_get_template( 'product-data-vendor-commissions', $args , 'woocommerce/admin' );
	}
}

/**
 * Build the structure to handle vendors data
 *
 * @return array $vendor_fields Return array with vendor data
 */
function vendors_product_commissions_data( $post_id = false ) {
	if ( false === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$v_prefix = '_vendor';
	$vendor_fields = array();
	for ( $i = 0; $i < VENDOR_MAX_SPLITS; $i++ ) {
		$keys = array(
			"${v_prefix}_${i}_id" => array(
				'label' => __( "Product Vendor ${i}", 'woocommerce-pagarme-split' ),
				'type' => 'select',
				'description' => __( "Select the Vendor that will receive extra commision.\nDefault will not include commission on sale", 'woocommerce-pagarme-split' ),
			),
			"${v_prefix}_${i}_commission" => array(
				'label' => __( "Commission for Vendor ${i}", 'woocommerce-pagarme-split' ),
				'type' => 'number',
				'description' => __( 'Commission value to calculate and send to selected Vendor', 'woocommerce-pagarme-split' ),
			),
		);

		$vendor_field = array();
		foreach ( $keys as $key => $props ) {
			$meta_value = get_post_meta( $post_id, $key, true );
			$vendor = array(
				'id' 				=> $key,
				'label'             => $props['label'],
				'desc_tip'          => 'true',
				'description'       => $props['description'],
				'value'             => $meta_value ? $meta_value : '',
				'type'              => $props['type'],
			);
			if ( 'select' == $props['type'] ) {
				$vendor['options'] = get_options_for_vendor_users();
			}
			array_push( $vendor_field, $vendor );
		}
		array_push( $vendor_fields, $vendor_field );
	}
	return $vendor_fields;
}


add_action( 'woocommerce_process_product_meta', 'split_vendors_product_commissions_meta_save' );
/**
 * Save product meta for vendors splits data
 *
 * @param int $post_id Current post to save data.
 */
function split_vendors_product_commissions_meta_save( $post_id ) {
	if ( current_user_can( 'manage_woocommerce' ) ) {
		if ( ! empty( $_POST ) && check_admin_referer( 'vendors_product_data_action', 'vendors_product_data_nonce' ) ) {
			$vendor_fields = vendors_product_commissions_data();
			foreach ( $vendor_fields as $vendor_field ) {
				foreach ( $vendor_field as $field ) {
					$field_id = $field['id'];
					$field_type = $field['type'];
					$post_value = $_POST[ $field_id ];

					switch ( $field_type ) {
						case 'select':
							if ( '0' === $post_value ) {
								delete_post_meta( $post_id, $field_id );
							} else {
								update_post_meta( $post_id, $field_id, $post_value );
							}
							break;

						case 'number':
							if ( empty( $post_value ) ) {
								delete_post_meta( $post_id, $field_id );
							} else {
								update_post_meta( $post_id, $field_id, $post_value );
							}
					}
				}
			}
		}
	}
}
