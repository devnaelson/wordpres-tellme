<?php
/**
 * Pagarme-split Helper Functions to work with Pagar.me API
 *
 * Currently using composer suport to load pagarme-php Classes
 *
 * PHP version 5
 *
 * @category API
 * @package  Pagarme-split/pagarme_api
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get singleton instance of PagarMe instance.
 *
 * @return object $pagarme_api_instance Current instance of \PagarMe\Sdk\PagarMe
 */
function pagarme_api() {
	global $pagarme_api_instance;

	$composer_autoload_path = plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';
	include_once $composer_autoload_path;

	$chave_aux = get_option( 'woocommerce_pagarme-banking-ticket_settings', '' );
	if ( '' === $chave_aux ) {
		$chave_aux = get_option( 'woocommerce_pagarme-credit-card_settings', '' );
	}
	$api_key = $chave_aux['api_key'];
	$api_url = $chave_aux['api_new_url'];
	$timeout = null;

	if ( ! isset( $pagarme_api_instance ) ) {
		$pagarme_api_instance = new \PagarMe\Sdk\PagarMe( $api_key, $timeout, $api_url );
	}
	return $pagarme_api_instance;
}

/**
 * Finds or creates a recipient.
 *
 * @param array $bank_data Bank information about recipient.
 * @param array $recipient_data Data about recipient to find or create.
 */
function find_or_create_recipient( $bank_data, $recipient_data ) {
	$api_pagarme = pagarme_api();
	$bank_id = null;
	$recipient_id = null;

	$is_valid_recipient = is_valid_recipient_id( $recipient_data['id'] );
	if ( $is_valid_recipient ) {
		try {
			$recipient = $api_pagarme->recipient()->get( $recipient_data['id'] );
		} catch (PagarMe\Sdk\ClientException $e) {
			$recipient = null;
			write_log( 'PagarMe\Sdk\ClientException: ' . $e->getMessage() );
		}
		if ( $recipient instanceof PagarMe\Sdk\Recipient\Recipient ) {
			$recipient_id = $recipient->getId();
			$bank_account = $recipient->getBankAccount();
			$bank_id = $bank_account->getId();
			write_log( "Found recipient_id: ${recipient_id} with bank_id: ${bank_id}" );
		}
	}

	// create new recipient.
	if ( ! isset( $recipient ) ) {
		write_log( 'Recipient not found yet' );

		$bank_id = find_or_create_bank_account( $bank_data );
		write_log( "Recipient find_or_create bank_id: ${bank_id}" );

		if ( empty( $bank_id ) ) {
			$recipient = null;
		} else {
			$recipients = $api_pagarme->recipient()->getList();
			$match_recipient = array();
			foreach ( $recipients as $rcpt ) {
				if ( $bank_id === $rcpt->getBankAccount()->getId() ) {
					$match_recipient[] = $rcpt;
				}
			}
			if ( count( $match_recipient ) > 0 ) {
				$recipient = $match_recipient[0];
				$bank_account = $recipient->getBankAccount();
			}
			write_log( 'Total recipients: ' . count( $recipients ) );
			write_log( 'Total match_recipient: ' . count( $match_recipient ) );

			if ( ! isset( $recipient ) ) {
				write_log( 'Creating new recipient' );
				write_log( $recipient_data );
				$recipient_data['bank_account_id'] = $bank_id;
				$bank_account = $api_pagarme->bankAccount()->get( $bank_id );
				$transfer_enabled = 'enabled' === $recipient_data['transfer_enabled'] ? true : false;
				$transfer_interval = $recipient_data['transfer_interval'];
				$transfer_day = $recipient_data['transfer_day'];
				$transfer_antecipation = $recipient_data['automatic_anticipation_enabled'];
				$transfer_antecipation_percent = $recipient_data['anticipatable_volume_percentage'];
				$recipient = $api_pagarme->recipient()->create(
					$bank_account,
					$transfer_interval,
					$transfer_day,
					$transfer_enabled,
					$transfer_antecipation,
					$transfer_antecipation_percent
				);
			}
			update_remote_recipient( $recipient, $recipient_data, $bank_account );
		}
	}

	write_log( '## Return recipient ##' );
	write_log( $recipient );

	return $recipient;
}

/**
 * Update the recipient in remote Pagarme api.
 *
 * @param object $recipient      Api object recipient to update.
 * @param array  $recipient_data Recipient data about recipient to update.
 * @param array  $bank_account   Bank information about recipient to update.
 */
function update_remote_recipient( $recipient, $recipient_data, $bank_account ) {
	if ( $recipient instanceof PagarMe\Sdk\Recipient\Recipient ) {
		if ( 'enabled' === $recipient_data['transfer_enabled'] ) {
			$recipient->setTransferEnabled( true );
		} else {
			$recipient->setTransferEnabled( false );
		}
		if ( $recipient->getTransferInterval() !== $recipient_data['transfer_interval'] ) {
			$recipient->setTransferInterval( $recipient_data['transfer_interval'] );
		}
		if ( 'daily' === $recipient_data['transfer_interval'] ) {
			$recipient_data['transfer_day'] = 0;
		}
		if ( $recipient->getTransferDay() !== $recipient_data['transfer_day'] ) {
			$recipient->setTransferDay( $recipient_data['transfer_day'] );
		}
		if ( 'enabled' === $recipient_data['automatic_anticipation_enabled'] ) {
			$recipient->setAutomaticAnticipationEnabled( true );
		} else {
			$recipient->setAutomaticAnticipationEnabled( false );
		}
		if ( $recipient->getAnticipatableVolumePercentage() !== $recipient_data['anticipatable_volume_percentage'] ) {
			$recipient->setAnticipatableVolumePercentage( $recipient_data['anticipatable_volume_percentage'] );
		}
	}
}

/**
 * Finds or creates a bank account on PagarMe.
 *
 * @param array $bank_data        Bank information about recipient.
 *
 * @return int  $bank_account_id  Return bank id found or created.
 */
function find_or_create_bank_account( $bank_data ) {
	$api_pagarme = pagarme_api();
	$bank_account = $api_pagarme->BankAccount();
	$valid_bank = is_bank_valid( $bank_data );
	if ( $valid_bank ) {
		$banks = $bank_account->getList();
		$match_bank = array();
		foreach ( $banks as $bank ) {
			if (
				$bank_data['account_document'] === $bank->getDocumentNumber()
				&& $bank_data['account_name'] === $bank->getLegalName()
				&& $bank_data['cod'] === $bank->getBankCode()
				&& $bank_data['agency'] === $bank->getAgencia()
				&& $bank_data['account'] === $bank->getConta()
				&& $bank_data['account_digit'] === $bank->getContaDv()
				&& $bank_data['agency_digit'] === $bank->getAgenciaDv()
			) {
				$match_bank[] = $bank;
			}
		}
		write_log( 'Total banks: ' . count( $banks ) );
		write_log( 'Match banks: ' . count( $match_bank ) );
		if ( count( $match_bank ) > 0 ) {
			$bank_account_id = $match_bank[0]->getId();
		}
		if ( empty( $bank_account_id ) ) {
			write_log( 'No bank found, create new bank' );
			if ( is_numeric( $bank_data['agency_digit'] ) ) {
				$bank = $bank_account->create(
					$bank_data['cod'],
					$bank_data['agency'],
					$bank_data['account'],
					$bank_data['account_digit'],
					$bank_data['account_document'],
					$bank_data['account_name'],
					$bank_data['agency_digit']
				);
			} else {
				$bank = $bank_account->create(
					$bank_data['cod'],
					$bank_data['agency'],
					$bank_data['account'],
					$bank_data['account_digit'],
					$bank_data['account_document'],
					$bank_data['account_name']
				);
			}
			$bank_account_id = $bank->getId();
			write_log( "Final bank id: ${bank_account_id}" );
		}
	} else {
		write_log( 'Bank data is not valid' );
		write_log( $bank_data );
		$bank_account_id = null;
	}

	return $bank_account_id;
}

/**
 * Checks if is valid bank data
 *
 * @param array $bank Bank data to check if valid.
 *
 * @return boolean $valid_bank Return true if valid bank.
 **/
function is_bank_valid( $bank ) {
	$is_valid_bank = true;
	$bank_fields = array(
		'cod' => array(
			'required' => true,
			'max'    => 3,
			'number'   => true,
		),
		'agency' => array(
			'required' => true,
			'max'    => 5,
			'number'   => true,
		),
		'agency_digit' => array(
			'max'    => 1,
			'number'   => true,
		),
		'account' => array(
			'required' => true,
			'max'    => 13,
			'number'   => true,
		),
		'account_digit' => array(
			'required' => true,
			'max'    => 2,
			'alphanum' => true,
		),
		'account_name' => array(
			'required' => true,
		),
		'account_document' => array(
			'required' => true,
			'number'   => true,
			'max'      => 14,
			'min'      => 11,
			'document' => true,
		),
	);
	write_log( 'Checking for valid bank' );
	write_log( $bank );
	foreach ( $bank_fields as $field_key => $field_params ) {
		$field_value = $bank[ $field_key ];

		write_log( "### Checking field_key: ${field_key}" );
		write_log( "field_value: ${field_value}" );
		write_log( $field_params );

		if ( check_param( 'required', $field_params, $is_valid_bank ) ) {
			write_log( 'checking for required' );
			if ( array_key_exists( $field_key, $bank ) ) {
				$is_valid_bank = ( strlen( $field_value ) === 0 ) ? false : true;
			}
		}

		if ( check_param( 'max', $field_params, $is_valid_bank, $field_value, 'number' ) ) {
			write_log( 'checking for max' );
			$is_valid_bank = $field_params['max'] >= strlen( $field_value );
		}

		if ( check_param( 'min', $field_params, $is_valid_bank, $field_value, 'number' ) ) {
			write_log( 'checking for min' );
			$is_valid_bank = $field_params['min'] <= strlen( $field_value );
		}

		if ( check_param( 'number', $field_params, $is_valid_bank, $field_value ) ) {
			write_log( 'checking for number' );
			$is_valid_bank = is_numeric( $field_value );
		}

		if ( check_param( 'alphanum', $field_params, $is_valid_bank, $field_value ) ) {
			write_log( 'checking for alphanum' );
			if ( count( preg_match( '/^[a-zA-Z0-9]+$/', $field_value ) ) === 0 ) {
				$is_valid_bank = false;
			}
		}

		if ( check_param( 'document', $field_params, $is_valid_bank, $field_value ) ) {
			write_log( 'checking for document ' );
			if ( class_exists( 'Extra_Checkout_Fields_For_Brazil_Formatting' ) ) {
				$is_valid_bank = (
					Extra_Checkout_Fields_For_Brazil_Formatting::is_cnpj( $field_value ) ||
					Extra_Checkout_Fields_For_Brazil_Formatting::is_cpf( $field_value )
				);
			}
		}

		if ( false === $is_valid_bank ) {
			write_log( "Not valid bank, field: ${field_key}" );
			return false;
		}
	}

	write_log( 'Is valid bank data' );
	return true;
}

/**
 * Tests if will needs to check param of field_key.
 *
 * Helper function to check for field_params.
 *
 * @param string  $field_key    Field key name to check in field_params.
 * @param array   $field_params Paramentes of field.
 * @param boolean $is_valid     Current value of $is_bank_valid, need to valid to check value.
 * @param boolean $field_value  The field value to check if empty, default value is true.
 * @param string  $check_type      Check type of value number or boolean for now.
 *
 * @return boolean $check       Return true if needs to check field_value validations.
 **/
function check_param( $field_key, $field_params, $is_valid, $field_value = true, $check_type = 'boolean' ) {
	$check = (
		! empty( $field_value ) &&
		array_key_exists( $field_key, $field_params ) &&
		$is_valid
	);
	switch ( $check_type ) {
		case 'boolean':
			$check = (
				$check &&
				true === $field_params[ $field_key ]
			);
			break;

		case 'number':
			$check = (
				$check &&
				0 < $field_params[ $field_key ]
			);
			break;
	}
	return $check;
}

/**
 * Validates recipient id.
 *
 * @param string $recipient_id Recipient id to validate.
 *
 * @return boolean
 **/
function is_valid_recipient_id( $recipient_id ) {
	return preg_match( '/^re_[a-z0-9]{25}$/', $recipient_id );
}
