<?php
/**
 * Pagarme-split INIT Integrations Functions
 *
 * PHP version 5
 *
 * @category Base
 * @package  Pagarme-split/init
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

! defined( 'PGM_PATH' )            && define( 'PGM_PATH', plugin_dir_path( __FILE__ ) . '../' );
! defined( 'PGM_TEMPLATE_PATH' )   && define( 'PGM_TEMPLATE_PATH', PGM_PATH . 'templates/' );
! defined( 'VENDOR_MAX_SPLITS' )   && define( 'VENDOR_MAX_SPLITS', 3 );
! defined( 'VENDOR_EMAIL_SHOW' )   && define( 'VENDOR_EMAIL_SHOW', true );

include_once dirname( __FILE__ ) . '/wp-http_helpers.php';
include_once dirname( __FILE__ ) . '/integration-helpers.php';
include_once dirname( __FILE__ ) . '/integration-legacy.php';
include_once dirname( __FILE__ ) . '/integration-pagarme-api.php';
include_once dirname( __FILE__ ) . '/integration-splits.php';
include_once dirname( __FILE__ ) . '/integration-views-profile.php';
include_once dirname( __FILE__ ) . '/integration-vendors.php';
