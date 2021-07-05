<?php
/**
 * User: cassiovidal
 * Date: 09/07/18
 * Time: 13:12
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class cv_config
 */

class cv_config {
    /**
     * cv_config constructor.
     */
    public function __construct(){
        add_filter( 'woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_demo', array($this, 'settings_tab' ));
        add_action( 'woocommerce_update_options_settings_tab_demo', array($this, 'update_settings' ));
    }

    /**
     * @param $settings_tabs
     * @return mixed
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'Integration Config - Zoop', 'cv_wc_Zoop' );
        return $settings_tabs;
    }

    /**
     *
     */
    function settings_tab() {
        woocommerce_admin_fields( $this->get_settings() );
    }

    /**
     * @return mixed
     */
    function get_settings() {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'API Keys', 'cv_wc_Zoop' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'cv_wc_Zoop_section_title'
            ),
            'cvWCZoopMarketPlaceID' => array(
                'name' => __( 'MarketPlace ID', 'cv_wc_Zoop' ),
                'type' => 'text',
                'id'   => 'cv_wc_Zoop_MarketPlaceID'
            ),
            'cvWCZoopPublishableKey' => array(
                'name' => __( 'Publishable key', 'cv_wc_Zoop' ),
                'type' => 'text',
                'id'   => 'cv_wc_Zoop_PublishableKey'
            ),
            'cvWCZoopSellerID' => array(
                'name' => __( 'Seller ID', 'cv_wc_Zoop' ),
                'type' => 'text',
                'id'   => 'cv_wc_Zoop_SellerID'
            ),            
            'cvWCZoopBankDue' => array(
                'name' => __( 'Days due', 'cv_wc_Zoop' ),
                'type' => 'number',
                'id'   => 'cvWCZoopBankDue'
            ),            
            'cvWCZoopBankLateFee' => array(
                'name' => __( 'Late fee %', 'cv_wc_Zoop' ),
                'type' => 'text',
                'id'   => 'cvWCZoopBankLateFee',
                'desc' => __('Greater than 0.1', 'cv_wc_Zoop' ),
            ),            
            'cvWCZoopBankInterest' => array(
                'name' => __( 'Daily Interest %', 'cv_wc_Zoop' ),
                'type' => 'text',
                'id'   => 'cvWCZoopBankInterest',
                'desc' => __('Greater than 0.1', 'cv_wc_Zoop' )
            ),
            'cvWCZoopDebug' => array(
                'name' => __( 'Debug', 'cv_wc_Zoop' ),
                'type' => 'checkbox',
                'id'   => 'cv_wc_Zoop_Debug'
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_demo_section_end'
            )
        );
        return apply_filters( 'wc_settings_tab_demo_settings', $settings );
    }

    /**
     *
     */
    function update_settings() {
        global $cv_WC_Zoop;
        global $wpdb;
        woocommerce_update_options( $this->get_settings() );
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $wpdb->usermeta WHERE "
                ."meta_key like '%zoop_vendor_id%' or "
                ."meta_key like '%zoop_customer_id%' "
                , null
            )
        );
        $cv_WC_Zoop->ws->setWebhook();
    }

}