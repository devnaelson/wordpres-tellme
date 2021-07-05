<?php
/**
 * Pagarme-split New Integrations Functions
 *
 * PHP version 5
 *
 * @category Integration
 * @package  Pagarme-split/Functions
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'write_log' ) ) {
	/**
	 * Write debug information to debug.log file.
	 *
	 * @param mixed $log What to write to debug.log file.
	 */
	function write_log( $log ) {
		if ( defined( 'DEBUG' ) && DEBUG === false ) {
			return;
		}
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}

/**
 * Return the id of master recipient
 *
 * @return int Returns pgm_recipient_id for master recipient
 */
function get_master_recipient_id() {
	$query_users = array(
		'meta_key' => 'pgm_recipient_master',
		'meta_value' => 'enabled',
	);
	$users = get_users( $query_users );
	if ( count( $users ) > 1 ) {
		write_log( 'Found more than one recipient master' );
	}
	foreach ( $users as $user ) {
		return get_user_meta( $user->ID, 'pgm_recipient_id', true );
	}
}

/**
 * Update master recipient user.
 *
 * @param int    $user_id  User id of current master recipient.
 * @param string $value    Value to update master recipient (enabled or disabled).
 * @param string $enabled  Default enabled value to compare.
 * @param string $disabled Default disabled value to compare.
 */
function update_master_user( $user_id, $value, $enabled = 'enabled', $disabled = 'disabled' ) {
	$query_users = array(
		'exclude' => array( $user_id ),
		'meta_key' => 'pgm_recipient_master',
		'meta_value' => $enabled,
	);
	$users = get_users( $query_users );
	foreach ( $users as $user ) {
		$uid = $user->ID;
		update_user_meta( $uid, 'pgm_recipient_master', $disabled );
	}
	write_log( 'Total users: ' . count( $users ) );
	write_log( "Value: {$value} Enable: {$enabled} Disabled: {$disabled}" );
	if ( $value === $enabled ) {
		update_user_meta( $user_id, 'pgm_recipient_master', $enabled );
	} else {
		update_user_meta( $user_id, 'pgm_recipient_master', $disabled );
	}
}

/**
 * Checked value for checkbox
 *
 * @param string $value Value to check with $check parameter.
 * @param string $check Default value to check.
 *
 * @return string $checked Return checked value or empty.
 */
function checked_value( $value, $check = 'enabled' ) {
	$checked = '';
	if ( $value === $check ) {
		$checked = 'checked="checked"';
	}
	return $checked;
}

/**
 * Selected value for select html input
 *
 * @param string $value Value to check with $select parameter.
 * @param string $select Default value to check.
 *
 * @return string $selected Return selected output to html.
 */
function selected_value( $value, $select = '' ) {
	$selected = '';
	if ( $value === $select ) {
		$selected = 'selected="selected"';
	}
	return $selected;
}

if ( ! function_exists( 'pgm_wc_get_template' ) ) {
	/**
	 * Get Pagarme-split Plugin Template
	 *
	 * It's possible to overwrite the template from theme.
	 * Put your custom template in woocommerce/product-vendors folder
	 *
	 * @param string $filename Template filename.
	 * @param array  $args     Arguments for template.
	 * @param string $section  Section to find template.
	 *
	 * @use   wc_get_template()
	 * @since 1.0
	 * @return void
	 */
	function pgm_wc_get_template( $filename, $args = array(), $section = '' ) {

		$ext           = strpos( $filename, '.php' ) === false ? '.php' : '';
		$template_name = $section . '/' . $filename . $ext;
		$template_path = WC()->template_path();
		$default_path  = PGM_TEMPLATE_PATH;

		wc_get_template( $template_name, $args, $template_path, $default_path );
	}
}

/**
 * Return array of options to use with vendor select
 *
 * @param bool $cache Use cached valued.
 *
 *	@return array $vendors_product_users Array of options for vendors users.
 */
function get_options_for_vendor_users( $cache = true ) {
	global $vendors_product_users;
	$args = array(
		'meta_key'     => 'pgm_recipient_id',
		'meta_value'   => '',
		'meta_compare' => '!=',
	);
	if ( ! $cache ) {
		$vendors_product_users = array();
	}
	if ( ! isset( $vendors_product_users ) ) {
		$vendors_users = get_users( $args );
		$vendors_product_users = array();
		$vendors_product_users[] = 'Default';
		foreach ( $vendors_users as $user ) {
			$data = $user->data;
			$opt_id = $data->ID;
			$display = $data->display_name;
			$email = $data->user_email;
			$rcpt_id = get_user_meta( $opt_id , 'pgm_recipient_id', true );
			$opt_title = vendor_users_opt_title( $display, $rcpt_id, $email, VENDOR_EMAIL_SHOW );
			$vendors_product_users[ $opt_id ] = $opt_title;
		}
	}
	return $vendors_product_users;
}

/**
 * Build title to vendor user options.
 *
 * @param string  $display    Title to display.
 * @param string  $rcpt_id    Recipient id to display.
 * @param string  $email      Email to show in title.
 * @param boolean $show_email Should show email in title.
 *
 * @return string  $full_title Title builded for option.
 **/
function vendor_users_opt_title( $display, $rcpt_id, $email, $show_email ) {
	$title = '( ';
	$title .= $rcpt_id;
	if ( $show_email ) {
		$title .= ' / ' . $email;
	}
	$title .= ' )';

	$full_title = "${display} ${title}";
	return $full_title;
}
