<?php

/**
  Plugin Name: Zoop Split Gateway
  Plugin URI: http://www.2wins.com.br
  Description: Plugin para cadastrar recebedores e regras de recebimento na zoop
  Version: 2.0
  Author: 2wins
  Author URI: http://www.2wins.com.br
  License: GPLv2
  Text Domain: cv_wc_Zoop
  Domain Path: /languages/
 */

/*
 *      Copyright 2018 Cassio Vidal <comercial@cassiovidal.com>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}



// Make sure is class is not active
if (!class_exists('cv_wc_Zoop')):
    // define constants
    define('CVZP_DIR_PATH', PLUGIN_DIR_PATH(__FILE__));
    define('CVZP_DIR_URL', PLUGIN_DIR_URL(__FILE__));

    define( 'VENDOR_MAX_SPLITS', 3 );
    define( 'VENDOR_EMAIL_SHOW', true );

    class cv_wc_Zoop {

        /**
         * Plugin version.
         *
         * @var string
         */
        const VERSION = '1.0';

        /**
         * Initialize the plugin public actions.
         *
         */
        private function __construct() {
            // Load plugin text domain.
            add_action( 'init', array ( $this, 'load_plugin_textdomain') );

            // Verification of prerequisites

            // Make sure WooCommerce is active
            if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                add_action(
                    'admin_notices',
                    array (
                        $this->admin_notice (
                            __('For the correct operation of the plugin WooCommerce - Zoop Split you need the plugin WooCommerce.', 'cv_wc_Zoop'
                            ),
                            'error'
                        )
                    )
                );
            }
            // Make sure WooCommerce Extra Checkout Fields for Brazil is active
            if (!in_array('woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                add_action(
                    'admin_notices',
                    array (
                        $this->admin_notice (
                            __('For the correct operation of the plugin WooCommerce - Zoop Split you need the plugin WooCommerce Extra Checkout Fields for Brazil.', 'cv_wc_Zoop'
                            ),
                            'error'
                        )
                    )
                );
            }
            // Make sure YITH_Vendors is active
            if ( ! class_exists( 'YITH_Vendors' ) ) {
                add_action(
                    'admin_notices',
                    array (
                        $this->admin_notice (
                            __('For the correct operation of the plugin WooCommerce - Zoop Split you need the plugin YITH_Vendors.', 'cv_wc_Zoop'
                            ),
                            'error'
                        )
                    )
                );
            }
            // Make sure curl extension is instaled
            if (!extension_loaded('curl')) {
                add_action(
                    'admin_notices',
                    array (
                        $this->admin_notice (
                            __('For the correct operation of the plugin WooCommerce - Zoop Split you need enable the extension PHP CURL.', 'cv_wc_Zoop'
                            ),
                            'error'
                        )
                    )
                );
            }

            // end verification of prerequisites

            // Add Zoop to payments
            add_filter('woocommerce_payment_gateways', array($this, 'add_to_gateways'));
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'gateway_plugin_links'));

        }

        /**
         * Load the plugin text domain for translation.
         *
         * @since 0.0.1
         */
        public function load_plugin_textdomain() {
            load_plugin_textdomain('cv_wc_Zoop', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        /**
         * Return an instance of this class.
         *
         * @return object A single instance of this class.
         */
        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            global $cv_WC_Zoop;
            if (is_null($cv_WC_Zoop)) {
                $cv_WC_Zoop = new cv_wc_Zoop();                
                try{
                    include_once dirname( __FILE__ ) . '/includes/cv_config.php';
                    include_once dirname(__FILE__) . '/includes/cv_vendor.php';
                    include_once dirname( __FILE__ ) . '/includes/cv_zoop_ws.php';
                    include_once dirname( __FILE__ ) . '/includes/cv_zoop_gateway.php';
                    if (class_exists('cv_zoop_ws')){
                        $cv_WC_Zoop->ws = new cv_zoop_ws();
                    } else {
                        $cv_WC_Zoop->logMsg('An error occurred on instance classes. Verify folder include cv_zoop_ws.php', 'error');
                        add_action(
                            'admin_notices',
                            array (
                                $cv_WC_Zoop->admin_notice (
                                    __('An error occurred on instance classes. Verify folder include.', 'cv_wc_Zoop'
                                    ),
                                    'error'
                                )
                            )
                        ); 
                    }
                    if (class_exists('cv_config')){
                        $cv_WC_Zoop->config = new cv_config();
                    } else {
                        $cv_WC_Zoop->logMsg('An error occurred on instance classes. Verify folder include cv_config.php', 'error');
                        add_action(
                            'admin_notices',
                            array (
                                $cv_WC_Zoop->admin_notice (
                                    __('An error occurred on instance classes. Verify folder include.', 'cv_wc_Zoop'
                                    ),
                                    'error'
                                )
                            )
                        ); 
                    }
                    if (class_exists('cv_vendor')){
                        $cv_WC_Zoop->vendor = new cv_vendor();
                    } else {
                        $cv_WC_Zoop->logMsg('An error occurred on instance classes. Verify folder include cv_config.php', 'error');
                        add_action(
                            'admin_notices',
                            array (
                                $cv_WC_Zoop->admin_notice (
                                    __('An error occurred on instance classes. Verify folder include.', 'cv_wc_Zoop'
                                    ),
                                    'error'
                                )
                            )
                        ); 
                    }
                    if (class_exists('cv_zoop_gateway')){
                        $cv_WC_Zoop->gateway = new cv_zoop_gateway();
                    } else {
                        $cv_WC_Zoop->logMsg('An error occurred on instance classes. Verify folder include cv_config.php', 'error');
                        add_action(
                            'admin_notices',
                            array (
                                $cv_WC_Zoop->admin_notice (
                                    __('An error occurred on instance classes. Verify folder include.', 'cv_wc_Zoop'
                                    ),
                                    'error'
                                )
                            )
                        ); 
                    }
                }
                catch(Exception $e){
                    $cv_WC_Zoop->logMsg('An error occurred on instance classes. Verify folder include', 'error');
                    add_action(
                        'admin_notices',
                        array (
                            $cv_WC_Zoop->admin_notice (
                                __('An error occurred on instance classes. Verify folder include.', 'cv_wc_Zoop'
                                ),
                                'error'
                            )
                        )
                    );
                }
                
            }
            return $cv_WC_Zoop;
        }

        /**
         * @param $msg
         * @param string $level
         * @param string $file
         *
         */
        public function logMsg($msg, $level = 'info', $file = 'cv_wc_Zoop.log') {
          if (!file_exists(CVZP_DIR_PATH ."log/")) {
            mkdir(CVZP_DIR_PATH ."log/");
          }
          $file = CVZP_DIR_PATH ."log/".$file;
          $levelStr = '';
          switch ($level) {
            case 'info':
              $levelStr = 'INFO';
            break;
            case 'warning':
              $levelStr = 'WARNING';
            break;
            case 'error':
              $levelStr = 'ERROR';
            break;
          case 'debug':
              $levelStr = 'DEBUG';
              break;
          default:
              $levelStr = $level;
          }
          $date = date('Y-m-d H:i:s');
          $msg = sprintf("[%s] [%s]: %s%s%s", $date, $levelStr, $msg, PHP_EOL, PHP_EOL);
          file_put_contents($file, $msg, FILE_APPEND);
        }

        public function admin_notice ( $msg="", $level = "warning" ) {
            echo "<div class=\"notice notice-$level is-dismissible\"><p>$msg</p></div>";
        }

        function wc_get_template( $filename, $args = array(), $section = '' ) {

            $ext           = strpos( $filename, '.php' ) === false ? '.php' : '';
            $template_name = $section . '/' . $filename . $ext;
            $template_path = WC()->template_path();
            $default_path  = TEMPLATE_PATH;

            wc_get_template( $template_name, $args, $template_path, $default_path );
        }

        /**
         * Add the gateway to WC Available Gateways
         *
         * @since 0.0.1
         * @param array $gateways all available WC gateways
         * @return array $gateways all WC gateways + paggi gateway
         */
        public function add_to_gateways($gateways) {
            $gateways[] = 'cv_zoop_gateway';
            return $gateways;
        }

        /**
         * Adds plugin page links
         *
         * @since 0.0.1
         * @param array $links all plugin links
         * @return array $links all plugin links + our custom links (i.e., "Settings")
         */
        public function gateway_plugin_links($links) {

            $plugin_links = array(
                '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=cv_zoop_gateway') . '">' . __('Configure', 'cv_wc_Zoop') . '</a>'
            );

            return array_merge($plugin_links, $links);
        }
    }

    //register_activation_hook(__FILE__, array('cv_wc_Zoop', 'install'));
    add_action ( 'plugins_loaded', array ( 'cv_wc_Zoop', 'get_instance' ) );
endif;
