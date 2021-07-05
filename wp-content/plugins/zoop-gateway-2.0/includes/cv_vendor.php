<?php
/**
 * Created by PhpStorm.
 * User: cassiovidal
 * Date: 18/07/18
 * Time: 19:07
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class cv_vendor
{
    /**
     * cv_vendor constructor.
     */
    function __construct()
    {
        add_filter( 'woocommerce_product_data_tabs', array($this, 'add_split_vendors_product_data_tab'), 60, 1);
        add_action( 'woocommerce_product_data_panels', array($this, 'split_vendors_product_commission_content'));
        add_action( 'woocommerce_process_product_meta', array($this, 'split_vendors_product_commissions_meta_save'));

        add_action( 'show_user_profile', array($this, 'show_bank_profile_fields'));
        add_action( 'edit_user_profile', array($this, 'show_bank_profile_fields'));
        add_action( 'personal_options_update', array($this, 'save_bank_profile_fields' ) );
        add_action( 'edit_user_profile_update', array($this, 'save_bank_profile_fields' ) );

        add_action( 'user_profile_update_errors', array($this,'validateProfileFields'), 10, 3 );

        add_action('user_edit_form_tag', array($this, 'make_form_accept_uploads'));

    }

    /**
     * @param $errors
     * @param $update
     * @param $user
     * @return mixed
     */
    function validateProfileFields( $errors, $update, $user ) {
        if (isset($_POST['vendor_id']['error'])){
            $errors->add( '', "<strong>ERROR</strong>: " . $_POST['vendor_id']['error']['message'] . "." );
        }
        return $errors;
    }

    /**
     * Add vendors to product tabs
     *
     * @param array $tabs Product tabs array.
     *
     * @return array $tabs Return the modified tabs array.
     */
    function add_split_vendors_product_data_tab($tabs)
    {
        $tabs['split_vendors'] = array(
            'label' => __('Vendors Split', 'cv_wc_Zoop'),
            'target' => 'split_vendors_product_data',
        );
        return $tabs;
    }

    /**
     * Add commission tab in single product "Product Data" section
     */
    function split_vendors_product_commission_content()
    {
        if (current_user_can('manage_woocommerce')) {
            $vendor_fields = $this->vendors_product_commissions_data();
            include_once CVZP_DIR_PATH . 'includes/frontend/product_vendor_commissions.php';
        }
    }

    /**
     * Build the structure to handle vendors data
     *
     * @return array $vendor_fields Return array with vendor data
     */
    function vendors_product_commissions_data($post_id = false)
    {
        if (false === $post_id) {
            global $post;
            $post_id = $post->ID;
        }
        $v_prefix = '_vendor';
        $vendor_fields = array();
        for ($i = 0; $i < VENDOR_MAX_SPLITS; $i++) {
            $keys = array(
                "${v_prefix}_${i}_id" => array(
                    'label' => __("Product Vendor ${i}", 'cv_wc_Zoop'),
                    'type' => 'select',
                    'description' => __("Select the Vendor that will receive extra commision.
                        Default will not include commission on sale", 'cv_wc_Zoop'),
                ),
                "${v_prefix}_${i}_commission" => array(
                    'label' => __("Commission for Vendor ${i}", 'cv_wc_Zoop'),
                    'type' => 'text',
                    'description' => __('Commission value to calculate and send to selected Vendor', 'cv_wc_Zoop'),
                ),
            );
            $vendor_field = array();
            foreach ($keys as $key => $props) {
                $meta_value = get_post_meta($post_id, $key, true);
                $vendor = array(
                    'id' => $key,
                    'label' => $props['label'],
                    'desc_tip' => 'true',
                    'description' => $props['description'],
                    'value' => $meta_value ? $meta_value : '',
                    'type' => $props['type'],
                );
                if ('select' == $props['type']) {
                    $vendor['options'] = $this->get_options_for_vendor_users();
                }
                array_push($vendor_field, $vendor);
            }
            array_push($vendor_fields, $vendor_field);
        }
        return $vendor_fields;
    }

    /**
     * Save product meta for vendors splits data
     *
     * @param int $post_id Current post to save data.
     */
    function split_vendors_product_commissions_meta_save($post_id)
    {
        if (current_user_can('manage_woocommerce')) {
            if (!empty($_POST) && check_admin_referer('vendors_product_data_action', 'vendors_product_data_nonce')) {
                $vendor_fields = $this->vendors_product_commissions_data();
                foreach ($vendor_fields as $vendor_field) {
                    foreach ($vendor_field as $field) {
                        $field_id = $field['id'];
                        $field_type = $field['type'];
                        $post_value = $_POST[$field_id];

                        switch ($field_type) {
                            case 'select':
                                if ('0' === $post_value) {
                                    delete_post_meta($post_id, $field_id);
                                } else {
                                    update_post_meta($post_id, $field_id, $post_value);
                                }
                                break;

                            case 'text':
                            case 'number':
                                if (empty($post_value)) {
                                    delete_post_meta($post_id, $field_id);
                                } else {
                                    update_post_meta($post_id, $field_id, $post_value);
                                }
                        }
                    }
                }
            }
        }
    }

    /**
     * Fields to show on bank user profile.
     *
     * @param WP_User $user User object to build form fields.
     */
    function show_bank_profile_fields($user)
    {
        global $cv_WC_Zoop;
        $user_id = $user->ID;
        if (!in_array('yith_vendor', $user->roles)) {
            return false;
        }
        $mcc = $cv_WC_Zoop->ws->getMCC();
        $recipient_master = get_the_author_meta( 'zoop_recipient_master', $user_id );
        $cv_WC_Zoop->logMsg("recipient_master: {$recipient_master}", "info", "vendor.log" );

        wp_register_script('jquery-maskjs', CVZP_DIR_URL . 'assets/js/jquery.mask/jquery.mask.min.js', array('jquery'));
        wp_enqueue_script('jquery-maskjs');
        wp_register_script('jquery-cpfcnpjjs', CVZP_DIR_URL . 'assets/js/jquery.cpfcnpj/jquery.cpfcnpj.js', array('jquery'));
        wp_enqueue_script('jquery-cpfcnpjjs');

        $data = array();
        $meta = get_user_meta($user_id);
        foreach ($meta as $key => $value){
            if (strpos($key, 'zoop_') !== false ){
                $data[$key] = $value[0];
            }
        }
        include_once CVZP_DIR_PATH . 'includes/frontend/adm_profile.php';
    }

    /**
     * @param $user_id
     * @return bool
     */
    function save_bank_profile_fields( $user_id ) {
        global $cv_WC_Zoop;
        if ( ! current_user_can( 'administrator', $user_id ) )
        {
            return false;
        }
        if ($_POST['cpf'] === '')
        {
            return false;
        }
		if ($_POST['zoop_vendor_id'] != ''){
			$vendor_id = $_POST['zoop_vendor_id'];
		} else {
			$vendor_id = get_user_meta($user_id, 'zoop_vendor_id', true);
		}
        $bank_id = get_user_meta($user_id, 'zoop_bank_id', true);
        $meta = get_user_meta($user_id);
        /*foreach ($meta as $key => $value)
        {
            if (strpos($key, 'zoop_') !== false )
            {
                delete_user_meta($user_id, $key);
            }
        }*/
        $vendor = array();
        $file = array();
        foreach ($_FILES as $key => $value){
            $r = wp_handle_upload( $value ,array('test_form' => FALSE) );
            update_user_meta( $user_id, 'zoop_' . $key, $r, get_user_meta( $user_id, 'zoop_' . $key, true ) );
            $file[] = $r['file'];
        }
        // TODO remover comentário para envio do email
      /*  if (!empty($file)){
            $mailer = WC()->mailer();
            $mailer->send(
                array('casenvi@icloud.com', 'casenvi@gmail.com'),
                'Documentos Credenciamento EC ' . $_POST['cpf'],
                "Segue em anexo documentos para liberação de EC",
                null,
                $file
            );
        }
        */if ($_POST['type'] === "individual"){ //dados de pessoa fisica
            $vendor['type'] = "individual";
            $vendor['taxpayer_id'] = preg_replace('/[^0-9]/', '', $_POST['cpf']);
            $vendor['first_name'] = $_POST['name'];
            $vendor['email'] = $_POST['owner_email'];
            $vendor['phone_number'] = $_POST['phone'];
            $vendor['mcc'] = $_POST['mcc'];
            $vendor['birthdate'] = $_POST['birthdate'];
            $vendor['statement_descriptor'] = $_POST['statement_descriptor'];
            $vendor['address'] = array(
                'line1' => $_POST['line1'],
                'line2' => $_POST['line2'],
                'neighborhood' => $_POST['neighborhood'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'postal_code' => $_POST['postal_code'],
                'country_code' => 'BR'
            );
            $vendor['business_name'] = "";
            $vendor['business_phone'] = "";
            $vendor['business_email'] = "";
            $vendor['business_description'] = "";
            $vendor['business_opening_date'] = "";
            $vendor['statement_descriptor'] = "";
            $vendor['business_address'] = array(
                'line1' => "",
                'line2' => "",
                'neighborhood' => "",
                'city' => "",
                'state' => "",
                'postal_code' => "",
                'country_code' => 'BR'
            );
            $vendor['owner']['first_name'] = "";
            $vendor['owner']['email'] = "";
            $vendor['owner']['phone_number'] = "";
            $vendor['owner']['birthdate'] = "";
            $vendor['owner']['address'] = array(
                'line1' =>"",
                'line2' => "",
                'neighborhood' => "",
                'city' => "",
                'state' => "",
                'postal_code' => "",
                'country_code' => ""
            );
        }
        else
        { // dados de empresa
            $vendor['type'] = "business";
            $vendor['ein'] = $_POST['cpf'];
            $vendor['business_name'] = $_POST['business_name'];
            $vendor['business_phone'] = $_POST['business_phone'];
            $vendor['business_email'] = $_POST['business_email'];
            $vendor['business_description'] = $_POST['business_description'];
            $vendor['business_opening_date'] = $_POST['business_opening_date'];
            $vendor['statement_descriptor'] = $_POST['statement_descriptor'];
            $vendor['mcc'] = $_POST['mcc'];
            $vendor['business_address'] = array(
                'line1' => $_POST['business_address_line1'],
                'line2' => $_POST['business_address_line2'],
                'neighborhood' => $_POST['business_address_neighborhood'],
                'city' => $_POST['business_address_city'],
                'state' => $_POST['business_address_state'],
                'postal_code' => $_POST['business_address_postal_code'],
                'country_code' => 'BR'
            );
            // dados do proprietário da empresa
            $vendor['owner']['first_name'] = $_POST['name'];
            $vendor['owner']['email'] = $_POST['owner_email'];
            $vendor['owner']['phone_number'] = $_POST['phone'];
            $vendor['owner']['birthdate'] = $_POST['birthdate'];
            $vendor['owner']['address'] = array(
                'line1' => $_POST['line1'],
                'line2' => $_POST['line2'],
                'neighborhood' => $_POST['neighborhood'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'postal_code' => $_POST['postal_code'],
                'country_code' => 'BR'
            );
            // clear others
            $vendor['first_name'] = "";
            $vendor['email'] = "";
            $vendor['phone_number'] = "";
            //$vendor['mcc'] = "";
            $vendor['birthdate'] = "";
            //$vendor['statement_descriptor'] = "";
            $vendor['address'] = array(
                'line1' => "",
                'line2' => "",
                'neighborhood' => "",
                'city' => "",
                'state' => "",
                'postal_code' => "",
                'country_code' => "",
            );
        }
        $bank = array(
            'bank_code'         => $_POST['bank_cod'],
            'routing_number'    => $_POST['bank_agency'],
            'account_number'    => $_POST['bank_account'],
            'holder_name'       => $_POST['holder_name'],
            'taxpayer_id'       => $_POST['bank_taxpayer_id'],
        );
        // transfer
        $transfer = array(
            'interval'  => $_POST['recipient_transfer_interval'],
            'day'       => $_POST['recipient_transfer_day'],
            'enabled'   => $_POST['recipient_transfer_enabled'],
            'value'     => $_POST['minimum_transfer_value'],
        );
        if ( 'daily' !== $transfer['interval'] ) {
            if ( '0' === $transfer['day'] ) {
                $transfer['day'] = '1';
            }
            if ( 'weekly' === $transfer['interval'] ) {
                if ( $transfer['day'] > '7' ) {
                    $transfer['day'] = '5';
                }
            }
        }
        else
            {
                $transfer['day'] = '0';
        }
        foreach ($vendor as $key => $value){
            if (is_array($value)){
                foreach ($value as $key_1 => $value_1){
                    if (is_array($value_1)) {
                        foreach ($value_1 as $key_2 => $value_2){
                            update_user_meta ( $user_id, 'zoop_'. $key . '_' . $key_1 . '_' . $key_2, $value_2 );
                        }
                    }
                    else
                        {
                        update_user_meta ( $user_id, 'zoop_'. $key . '_' . $key_1, $value_1 );
                    }

                }
            }
            else
                {
                update_user_meta ( $user_id, 'zoop_'. $key, $value );
            }
        }
        foreach ($bank as $key => $value){
            update_user_meta ( $user_id, 'zoop_bank_'. $key, $value );
        }
        foreach ($transfer as $key => $value){
            update_user_meta ( $user_id, 'zoop_transfer_'. $key, $value );
        }
        // update or create remote
        $vendor = $cv_WC_Zoop->ws->setVendor($vendor['type'], $_POST['cpf'], $_POST['name'], $_POST['owner_email'],
            $_POST['phone'], $_POST['mcc'], $_POST['birthdate'], $_POST['statement_descriptor'], $_POST['line1'],
            $_POST['line2'], $_POST['neighborhood'], $_POST['city'], $_POST['state'], $_POST['postal_code'],
            $_POST['business_name'], $_POST['business_phone'], $_POST['business_email'], $_POST['business_description'],
            $_POST['business_opening_date'], $_POST['business_address_line1'], $_POST['business_address_line2'],
            $_POST['business_address_neighborhood'], $_POST['business_address_city'], $_POST['business_address_state'],
            $_POST['business_address_postal_code)'], $vendor_id);
        if (isset($vendor['id'])){
            update_user_meta ( $user_id, 'zoop_vendor_id', $vendor['id'] );
            $vendor_id = $vendor['id'];
            // add bank account
            $account = $cv_WC_Zoop->ws->setBank($_POST['type'], $bank['holder_name'], $bank['bank_code'],
                $bank['routing_number'], $bank['account_number'], $bank['taxpayer_id']);
            if (isset($account['bank_account']['id'])){
                update_user_meta ( $user_id, 'zoop_account_id', $account['bank_account']['id'] );
                update_user_meta ( $user_id, 'zoop_token_account', $account['id'] );
                //vinculação conta
                $bank = $cv_WC_Zoop->ws->bank_to_vendor($account['id'], $vendor_id);
                if (isset($bank['id'])){
                    //enable automatic transfer
                    $receiveID = $cv_WC_Zoop->ws->receivingPolicy($vendor_id, $transfer['interval'], $transfer['day'],
                        $transfer['enabled'], $transfer['value']);
                    if (isset($receiveID['id'])){
                        update_user_meta ( $user_id, 'zoop_receive_id', $bank['id'] );
                    }
                }
            }
        }else{
            update_user_meta ( $user_id, 'zoop_vendor_id', '' );
            $cv_WC_Zoop->logMsg(json_encode($vendor), "error", "Vendor.log");
        }


        return $vendor_id;
    }

    /**
     * Return array of options to use with vendor select
     *
     * @param bool $cache Use cached valued.
     *
     *	@return array $vendors_product_users Array of options for vendors users.
     */
    function get_options_for_vendor_users( $cache = false ) {
        global $vendors_product_users;
        $args = array(
            'meta_key'     => 'zoop_vendor_id',
            'meta_value'   => '',
            'meta_compare' => '!=',
        );
        if ( ! $cache ) {
            unset($vendors_product_users);
            $vendors_product_users = array();
        }
        if ( empty( $vendors_product_users ) ) {
            $vendors_users = get_users( $args );
            $vendors_product_users = array();
            $vendors_product_users[] = 'Default';
            foreach ( $vendors_users as $user ) {
                $data = $user->data;
                $opt_id = $data->ID;
                $display = $data->display_name;
                $email = $data->user_email;
                $rcpt_id = get_user_meta( $opt_id , 'zoop_vendor_id', true );
                $opt_title = $this->vendor_users_opt_title( $display, $rcpt_id, $email, VENDOR_EMAIL_SHOW );
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

    function make_form_accept_uploads() {
        echo ' enctype="multipart/form-data"';
    }

}