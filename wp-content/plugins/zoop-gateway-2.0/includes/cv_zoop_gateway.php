<?php
/**
 * Created by PhpStorm.
 * User: cassiovidal
 * Date: 20/07/18
 * Time: 14:54
 */

class cv_zoop_gateway extends WC_Payment_Gateway {

    public $id;
    public $has_fields;
    public $method_title;
    public $method_description;
    public $order_button_text;
    public $title;
    public $description;
    public $instructions;
    public $token;
    public $sandbox;
    public $debug;
    public $risk;
    public $max_installment;
    public $smallest_installment;
    public $interest_rate;
    public $free_installments;
    protected $taxpay;

    /**
     * Constructor for the gateway.
     *
     * @since 0.0.1
     */
    public function __construct() {

        $this->id = 'cv_zoop_gateway';
        $this->has_fields = false;
        $this->method_title = __('Zoop', 'cv_wc_Zoop');
        $this->method_description = __('Accept payments by credit card using the Zoop.', 'cv_wc_Zoop');
        $this->order_button_text = __('Proceed to payment', 'cv_wc_Zoop');

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title = $this->get_option('zoop_title', 'Zoop');
        $this->description = $this->get_option('zoop_description');
        $this->instructions = $this->get_option('zoop_instructions', $this->description);
        $this->token = $this->get_option('zoop_token');
        $this->sandbox = $this->get_option('zoop_sandbox', 'no');
        $this->debug = $this->get_option('zoop_debug');
        $this->risk = $this->get_option('zoop_risk', 'yes');
        $this->max_installment = $this->get_option('max_installment');
        $this->smallest_installment = $this->get_option('smallest_installment');
        $this->interest_rate = $this->get_option('interest_rate', '0');
        $this->free_installments = $this->get_option('free_installments', '1');
        $this->taxpay = ( get_option('cv_wc_Zoop_Taxpay') ? true : false);
        $this->marketPlaceID = get_option('cv_wc_Zoop_MarketPlaceID');


        // Active logs.
        if ('yes' == $this->debug) {
            if (function_exists('wc_get_logger')) {
                $this->log = wc_get_logger();
            } else {
                $this->log = new WC_Logger();
            }
        }

        // Actions
        add_action('init', array($this, 'init'));

        // Create account default in checkout
        add_filter('woocommerce_create_account_default_checked', function( $isChecked) {
            return true;
        });
        add_filter('woocommerce_thankyou_order_received_text', array($this, 'woo_change_order_received_text'), 10, 2);

    }

    /**
     * Init
     *
     * @since 0.0.1
     */
    public function init() {
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'))

        ;
        // update customers informations
        add_action('woocommerce_save_account_details', array($this, 'save_account_form'));
        add_action('woocommerce_customer_save_address', array($this, 'save_account_form'));

        // add menu in myaccount
        add_filter('woocommerce_account_menu_items'
            , array($this, 'zoopcards_account_menu_items'), 10, 1);

        add_rewrite_endpoint('zoopcards', EP_PAGES);
        add_filter(
            'the_title', array($this, 'endpoint_title'));
        add_action('woocommerce_account_zoopcards_endpoint', array($this, 'zoopcards_endpoint_content'));

        // add cancel transaction in order page
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'))

        ;
    }

    /**
     * Initialize Gateway Settings Form Fields
     *
     * @since 0.0.1
     */
    public function init_form_fields() {

        $this->form_fields = apply_filters('wc_zoop_form_fields', array(
            'general' => array(
                'title' => __('General', 'cv_wc_Zoop'),
                'type' => 'title',
                'description' => '',
            ),
            'enabled' => array(
                'title' => __('Enable/Disable', 'cv_wc_Zoop'),
                'type' => 'checkbox',
                'label' => __('Enable Zoop Payment', 'cv_wc_Zoop'),
                'default' => 'yes'
            ),
            'zoop_title' => array(
                'title' => __('Title', 'cv_wc_Zoop'),
                'type' => 'text',
                'description' => __('This controls the title for the payment method the customer sees during checkout.', 'cv_wc_Zoop'),
                'default' => __('Zoop Payment', 'cv_wc_Zoop'),
                'desc_tip' => true,
            ),
            'zoop_description' => array(
                'title' => __('Description', 'cv_wc_Zoop'),
                'type' => 'textarea',
                'description' => __('Payment method description that the customer will see on your checkout.', 'cv_wc_Zoop'),
                'default' => __('Please remit payment to Store Name upon pickup or delivery.', 'cv_wc_Zoop'),
                'desc_tip' => true,
            ),
            'zoop_instructions' => array(
                'title' => __('Instructions', 'cv_wc_Zoop'),
                'type' => 'textarea',
                'description' => __('Instructions that will be added to the thank you page and emails.', 'cv_wc_Zoop'),
                'default' => '',
                'desc_tip' => true,
            ),
            'installments' => array(
                'title' => __('Installments', 'cv_wc_Zoop'),
                'type' => 'title',
                'description' => '',
            ),
            'max_installment' => array(
                'title' => __('Number of Installment', 'cv_wc_Zoop'),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'default' => '12',
                'description' => __('Maximum number of installments possible with payments by credit card.', 'cv_wc_Zoop'),
                'desc_tip' => true,
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ),
            ),
            'smallest_installment' => array(
                'title' => __('Smallest Installment', 'cv_wc_Zoop'),
                'type' => 'text',
                'description' => __('Please enter with the value of smallest installment.', 'cv_wc_Zoop'),
                'desc_tip' => true,
                'default' => '5',
            ),
            'interest_rate' => array(
                'title' => __('Interest rate', 'cv_wc_Zoop'),
                'type' => 'text',
                'description' => __('Please enter with the interest rate amount. Note: use 0 to not charge interest.', 'cv_wc_Zoop'),
                'desc_tip' => true,
                'default' => '0',
            ),
            'free_installments' => array(
                'title' => __('Free Installments', 'cv_wc_Zoop'),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'default' => '1',
                'description' => __('Number of installments with interest free.', 'cv_wc_Zoop'),
                'desc_tip' => true,
                'options' => array(
                    '0' => _x('None', 'no free installments', 'cv_wc_Zoop'),
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ),
            ),
            'zoop_debug' => array(
                'title' => __('Debug Log', 'cv_wc_Zoop'),
                'type' => 'checkbox',
                'label' => __('Enable logging', 'cv_wc_Zoop'),
                'default' => 'yes',
                'description' => sprintf(__('Log Zoop events, such as API requests, inside %s', 'cv_wc_Zoop'), $this->get_log_view()),
            )
        ));
    }

    /**
     * Payment fields.
     *
     * @since 0.0.1
     */
    public function payment_fields() {
        if ($description = $this->get_description()) {
            echo wp_kses_post(wpautop(wptexturize($description)));
        }
        $cart_total = number_format((float) $this->get_order_total(), 2, '.', '');
        if (file_exists(CVZP_DIR_PATH . 'assets/js/jquery.mask/jquery.mask.min.js')) { // jquery-mask
            wp_register_script('jquery-mask', CVZP_DIR_URL . 'assets/js/jquery.mask/jquery.mask.min.js');
            wp_enqueue_script('jquery-mask');
        }
        if (file_exists(CVZP_DIR_PATH . 'assets/js/card-master/card.js')) { // card-master
            wp_register_script('card-master', CVZP_DIR_URL . 'assets/js/card-master/card.js');
            wp_enqueue_script('card-master');
        }
        if (file_exists(CVZP_DIR_PATH . 'assets/js/jquery.creditCardValidator/jquery.creditCardValidator.js')) {
            wp_register_script('jquery.creditCardValidator', CVZP_DIR_URL . 'assets/js/jquery.creditCardValidator/jquery.creditCardValidator.js');
            wp_enqueue_script('jquery.creditCardValidator');
            wp_localize_script('jquery.creditCardValidator ', 'wpurl ', array('siteurl' => get_option('siteurl')));
        }
        if (file_exists(CVZP_DIR_PATH . 'assets/css/zoop.css')) {
            wp_register_style('zoopcss', CVZP_DIR_URL . 'assets/css/zoop.css');
            wp_enqueue_style('zoopcss');
        }
        $zoop_customer_id = wp_get_current_user()->zoop_id;
        $cards = NULL;
        if ($zoop_customer_id) {
            $customer = $this->api->list_customer($zoop_customer_id);
            foreach ($customer['cards'] as $key => $value) {
                $cards[$key]['id'] = $value['id'];
                $cards[$key]['name'] = $value['name'];
                $cards[$key]['expires'] = $value['month'] . "/" . $value['year'];
                $cards[$key]['brand'] = $value['brand'];
                $cards[$key]['last4'] = $value['last4'];
            }
        }

        $installments = $this->get_installments($cart_total, null);
        wc_get_template('credit-card/payment-form.php', array(
            'cart_total' => $cart_total,
            'cards' => $cards,
            'columns' => array(
                '1' => __('Name', 'cv_wc_Zoop'),
                '2' => __('Expires', 'cv_wc_Zoop'),
                '3' => __('Brand', 'cv_wc_Zoop'),
                '4' => __('Last four', 'cv_wc_Zoop'),
                '5' => __('Action', 'cv_wc_Zoop')
            ),
            'installments' => $installments,
            'freeInstallments' => $this->free_installments,
            'tax' => $this->interest_rate
        ), 'woocommerce/zoop/', CVZP_DIR_PATH . 'templates/');
    }

    /**
     *
     *
     * @since 0.0.1
     * @param type $cart_total
     * @param type $installment
     * @return type
     */
    public function get_installments($cart_total, $installment) {
        global $cv_WC_Zoop;
        if (isset($installment)) {
            if ($installment <= $this->free_installments) {
                $return = number_format((float) round($cart_total/$installment, 2, PHP_ROUND_HALF_DOWN), 2, '.', '');
            } else {
                $return = number_format((float) ((floatval($cart_total) + (floatval($cart_total) * ($this->interest_rate * $installment) / 100)) / $installment), 2, '.', '');
            }
        } else {
            $return = array(
                '1' => $cart_total
            );
            $installments = $cart_total / $this->smallest_installment;
            if ($installments > $this->max_installment) {
                $installments = $this->max_installment;
            }
            for ($i = 2; $i <= $installments; $i++) {
                if ($i <= $this->free_installments) {
                    $return[$i] = number_format((float) round($cart_total/$i, 2, PHP_ROUND_HALF_DOWN), 2, '.', '');
                } else {
                    $return[$i] = number_format((float) (($cart_total + ($cart_total * ($this->interest_rate * $i) / 100)) / $i), 2, '.', '');
                }
            }
        }
        if ('yes' === $this->debug) {
            $cv_WC_Zoop->logMsg("get_installments($cart_total, $installment) = " . json_encode($return), "info", "getInstallmentsGateway.log");
        }
        return $return;
    }

    /**
     * Process the payment and return the result
     *
     * @since 0.0.1
     * @param int $order_id
     * @return array
     */
    public function process_payment($order_id) {
        global $cv_WC_Zoop;
        $cv_WC_Zoop->logMsg("payment_method _{". $_POST['payment_method'] ."} V1.0", "info", "processPaymentGateway.log");
        $cv_WC_Zoop->logMsg("order_id _{$order_id}_", "info", "processPaymentGateway.log");
        if (isset($_POST['payment_method']) && "cv_zoop_gateway" === $_POST['payment_method']) {
            $error = '';
            $total = $_POST['tot'];
            $order = new WC_Order($order_id);
            $zoop_customer_id = wp_get_current_user()->zoop_id;
            if (!$zoop_customer_id) {
                $zoop_customer_id = $order->get_meta('zoop_customer_id');
                $order_userID = $order->get_user_id();
            }
            $meta_data = get_metadata('post', $order_id);
            $cv_WC_Zoop->logMsg("ZOOP CUSTOMER ID _{$zoop_customer_id}_", "debug", "processPaymentGateway.log");
            $cv_WC_Zoop->logMsg("order {$order_id}= " . json_encode($order->get_data()), "debug", "processPaymentGateway.log");
            $cv_WC_Zoop->logMsg("order {$order_id}= " . json_encode($meta_data), "debug", "processPaymentGateway.log");
            // not registered - guest
            /*if (!$zoop_customer_id || $zoop_customer_id == "") {
                $zoop_customer_id = get_user_meta($order_userID, 'zoop_customer_id', true);
                if (!$zoop_customer_id  || $zoop_customer_id == '') {*/
            $name = $order->get_billing_first_name();
            $last_name = $order->get_billing_last_name();
            $email = $order->get_billing_email();
            if ($meta_data['_billing_persontype'][0] == '1') {
                $document = $meta_data['_billing_cpf'][0];
            } else {
                $document = $meta_data['_billing_cnpj'][0];
            }
            if ( $document === null || $document === ''){
                $cv_WC_Zoop->logMsg("#### CPF NÃO INFORMADO ####", "error", "processPaymentGateway.log");
                $cv_WC_Zoop->logMsg("user_info " . json_encode($user_info), "debug", "processPaymentGateway.log");
                $cv_WC_Zoop->logMsg("order " . json_encode($order->get_data()), "debug", "processPaymentGateway.log");
                $cv_WC_Zoop->logMsg("order meta_data " . json_encode($meta_data), "debug", "processPaymentGateway.log");
                $cv_WC_Zoop->logMsg("order meta_data['_billing_cpf'] " . json_encode($meta_data['_billing_cpf'][0]), "debug", "processPaymentGateway.log");
                $cv_WC_Zoop->logMsg("_REQUEST " . json_encode($_REQUEST), "debug", "processPaymentGateway.log");
                $cv_WC_Zoop->logMsg("####                  ####", "error", "processPaymentGateway.log");
                $document = $_REQUEST['billing_cpf'];                        
            }
            $phone = $order->get_billing_phone();
            $street_1 = $order->get_billing_address_1();
            $street_2 = $order->get_billing_address_2() . ' ' . $meta_data['_billing_number'][0];
            $district =$meta_data['_billing_neighborhood'][0];
            $birthdate = $meta_data['_billing_birthdate'][0];
            $city = $order->get_billing_city();
            $state = $order->get_billing_state();
            $zip = $order->get_billing_postcode();
            $result = $cv_WC_Zoop->ws->set_customer($name, $last_name, $email, $document, $phone, $birthdate, $street_1, $street_2, $district, $city, $state, $zip);
            $cv_WC_Zoop->logMsg("ZOOP CUSTOMER REGISTER " . json_encode($result), "info", "processPaymentGateway.log");
            if (isset($result['id'])) {
                $zoop_customer_id = $result['id'];
                update_post_meta($order_id, 'zoop_customer_id', $zoop_customer_id);
                update_user_meta($order_userID, 'zoop_customer_id', $zoop_customer_id);
            } else {
                if (isset($result['error'])) {
                    $error .= '<b>' . $result['error'][0]['param'] . "</b> " . $result['error'][0]['message'] . '<br/>';
                } else {
                    $error .= $result['errors'][0]['message'] . '<br/>';
                }
            }
                /*}
            }*/
            // card register
            if ($_POST['zoop_option'] == 'card') {
                if ('' === $error) {
                    if (isset($zoop_customer_id) && !isset($_POST['card_id']) && isset($_POST['cc_number'])) {
                        $result = $cv_WC_Zoop->ws->set_card($zoop_customer_id, $_POST['cc_name'], $_POST['cc_number'], $_POST['cc_expiry'], $_POST['cc_cvc']);
                        $cv_WC_Zoop->logMsg("ZOOP SETCARD " . json_encode($result), "debug", "processPaymentGateway.log");
                        if (isset($result['id'])) {
                            $card_id = $result['id'];
                        } else {
                            if (isset($result['error'])) {
                                $error .= '<b>' . $result['error'][0]['param'] . "</b> " . $result['error'][0]['message'] . '<br/>';
                                
                            } else {
                                $error .= $result['errors'][0]['message'] . '<br/>';
                            }
                        }
                    } else {
                        $card_id = $_POST['card_id'];
                    }
                }
                //parcelas
                if ('' === $error) {
                    $tax = 1;
                    if ($_POST['installments'] != '1') {
                        $total = $this->get_installments($total, $_POST['installments']);
                        if ($total != $_POST['tot']) {
                            $tax = ($total * $_POST['installments'] / $_POST['tot']);
                        }
                    }
                }
            }
            // frete
            $tot_ship = $order->get_total_shipping();
            $tot_split = $total * $_POST['installments'] - $tot_ship;
            // Split
            $split = array();
            $vendors = array();
            $item_meta_key = wp_get_post_parent_id($order->id) ? '_commission_id' : '_child__commission_id';
            foreach ($order->get_items() as $line_item_id => $item_data) {
                // marketplace
                $commission_id = wc_get_order_item_meta($line_item_id, $item_meta_key);
                $itemship = 0;
                $shipping_line_id = wc_get_order_item_meta($line_item_id, '_shipping_line_id');
                foreach ($order->get_shipping_methods() as $shipping_method) {
                    if ($shipping_line_id == $shipping_method->get_id()){
                        $itemship = $shipping_method->get_total();
                        break;
                    }
                }
                $commission = YITH_Commission($commission_id);
                if ($commission->exists()) {
                    $user = $commission->get_user();
                    $zoop_vendor_id_m = get_user_meta($user->id, 'zoop_vendor_id', true);
                    if (!isset($zoop_vendor_id_m) || $zoop_vendor_id_m === "") { // vendedor não cadastrado
                        $zoop_vendor_id_m = $this->setVendor($user->id);
                    }
                    $commission_rate = $commission->get_rate();
                    $commission_vendor = $item_data->get_total() * $commission_rate;
                    $comission_market = $item_data->get_total() - $commission_vendor;
                    $cv_WC_Zoop->logMsg("ZOOP split commission_rate = " . $commission_rate , "info", "processPaymentGateway.log");
                    // sub-vendor
                    $product_id = $item_data->get_product_id();
                    for ($i = 0; $i < VENDOR_MAX_SPLITS; $i++) {
                        $commission = get_post_meta($product_id, "_vendor_${i}_commission", true);
                        $cv_WC_Zoop->logMsg("ZOOP split commission = " . $commission , "info", "processPaymentGateway.log");
                        if (isset($commission) && $commission) {
                            $vendor_id = get_post_meta($product_id, "_vendor_${i}_id", true);
                            $zoop_vendor_id = get_user_meta($vendor_id, 'zoop_vendor_id', true);
                            $commission = str_replace(',', '.', $commission);
                            if (!isset($zoop_vendor_id) || $zoop_vendor_id == "" || !$zoop_vendor_id) { // vendedor não cadastrado
                                $zoop_vendor_id = $this->setVendor($vendor_id);
                            }
                            if ($zoop_vendor_id) {
                                $commission_value = number_format($commission_vendor * ($commission / 100), 2, '.', '');
                                $commission_vendor -= $commission_value;
                                $split[] = array(
                                    'recipient' => $zoop_vendor_id,
                                    'liable' => '1',
                                    'charge_processing_fee' => false,
                                    'amount' => number_format($commission_value * 100, 0, "", "")
                                );
                            }
                        }
                        unset ($zoop_vendor_id);
                    }
                    //marketplace
                    $idx = array_search($zoop_vendor_id_m, $vendors);
                    if ($idx === false || $idx === null){
                        $split[] = array(
                            'recipient' => $zoop_vendor_id_m,
                            'liable' => '1',
                            'charge_processing_fee' => $this->taxpay,
                            'amount' => number_format(($commission_vendor + $itemship) * 100, 0, "", "")// frete para o lojista
                            //'amount' => number_format($commission_vendor * 100, 0, "", "")// frete para o site
                        );  
                    } else {
                            $split[$idx]['amount'] += number_format(($commission_vendor) * 100, 0, "", ""); // frete para o lojista
                            //$split[$idx]['amount'] += number_format($commission_vendor * 100, 0, "", "")// frete para o site
                    }
                }
                $vendors[] = $zoop_vendor_id_m;
            }
            $idx = '';
            $idx = array_search( $this->marketPlaceID, $vendors);
            if ($idx != '' ){
                unset($split[$idx]);
            }
            $cv_WC_Zoop->logMsg("ZOOP split $order_id = " . json_encode($split), "info", "processPaymentGateway.log");
            if ($_POST['zoop_option'] == 'card') {
                foreach($split as $key => $value){
                    $split[$key]['amount'] = (int)$split[$key]['amount'];
                }
                $result = $cv_WC_Zoop->ws->process_regular_payment($total, $zoop_customer_id, $card_id, $_POST['installments'], $split, $order_id);
            } else {
                $result = $cv_WC_Zoop->ws->process_boleto_payment($total, $zoop_customer_id, $split, $order_id);
            }
            $cv_WC_Zoop->logMsg("ZOOP after process payment " . json_encode($result), "info", "processPaymentGateway.log");
            if (isset($result['id'])) {
                $transaction_id = $result['id'];
                $order = wc_get_order($order_id);
                if (isset($result['payment_method']['url'])) {
                    $this->bankslip_url = $result['payment_method']['url'];
                    $order->update_meta_data( 'zoop_bankslip_url', $this->bankslip_url );
                }
                $this->process_order_status($order, $result['status'], $transaction_id, $this->bankslip_url);

                // Reduce stock levels
                $order->reduce_order_stock();

                // Update order total

                if ($result['status'] === 'succeeded' && $tax > 1){
                    $feeamount = $_POST['tot'] * ($tax - 1);
                    $item = new \WC_Order_Item_Fee();
                    $item->set_props(array(
                        'name' => __('Tax by payment', 'cv_wc_Zoop'),
                        'tax_class' => 0,
                        'total' => $feeamount,
                        'total_tax' => 0,
                        'order_id' => $order->get_id(),
                    ));
                    $item->save();
                    $order->add_item($item);
                    $order->calculate_totals();
                }

                // Remove cart
                WC()->cart->empty_cart();

            } else {
                if (isset($result['error'])) {
                    $error .= '<b>' . $result['error'][0]['param'] . "</b> " . $result['error'][0]['message'] . '<br/>';
                } else {
                    $error .= $result['errors'][0]['message'] . '<br/>';
                }
            }
        }
        if ('' === $error) {
            // Return thankyou redirect
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
        } else {
			$json = json_encode($result, true) ;
			$message = explode('message":"',$json);
			$message = explode('","',$message[1]);
			
			$reasons = explode('["',$json);
			$reasons = explode('"]',$reasons[1]);
			
			if($message[0] == 'There was an error generating the boleto. The error was: Invalid value fields'){$mensagem = 'Ocorreu um erro ao gerar seu boleto!';}
			if($message[0] == 'Sorry, the buyer you are trying to use does not exist or has been deleted.'){$mensagem = 'Desculpe houve um erro na geração do seu boleto tente novamente, caso persista o erro entre em contato conosco.';}
			
			if($reasons[0] == 'CEP DO PAGADOR Nu00c3O NUMu00c9RICO OU INVu00c1LIDO'){$razoes = 'CEP informado inválido ou não encontrado, por favor digite novamente um CEP válido.';}
			if($reasons[0] == 'Amount is more than limit'){$razoes = 'Valor da compra maior que o limite permitido para gerar o boleto.';}
			if($reasons[0] == 'SIGLA ESTADO INCOMPATu00cdVEL COM CEP DO PAGADOR'){$razoes = 'CEP digitado incompatível com o estado selecionado, reveja seus dados de endereço digitados.';}
			
            //wc_add_notice(__('Payment error: ', 'cv_wc_Zoop') . json_encode($result) . '</b>', 'error');
            wc_add_notice(__('Payment error: ', 'cv_wc_Zoop') . $mensagem . ' <b>' . $razoes . '</b>', 'error');
            return;
        }
    }

    /**
     * @param $userID
     * @return bool
     */
    protected function setVendor($userID){
        global $cv_WC_Zoop;
        $vendor = get_user_meta($userID);
        if ($vendor['zoop_type'][0] == 'individual') {
            $zoop_vendor_id = $cv_WC_Zoop->ws->setVendor('individual', $vendor['zoop_taxpayer_id'][0], $vendor['zoop_first_name'], $vendor['zoop_email'][0],
                $vendor['zoop_phone_number'][0], $vendor['zoop_mcc'][0],
                $vendor['zoop_birthdate'][0], $vendor['zoop_statement_descriptor'][0],
                $vendor['zoop_address_line1'][0], $vendor['zoop_address_line2'][0],
                $vendor['zoop_address_neighborhood'][0], $vendor['zoop_address_city'][0],
                $vendor['zoop_address_state'][0], $vendor['zoop_address_postal_code'][0],
                $vendor['zoop_business_name'][0], $vendor['zoop_business_phone'][0],
                $vendor['zoop_business_email'][0], $vendor['zoop_business_description'][0],
                $vendor['zoop_business_opening_date'][0], $vendor['zoop_business_address_line1'][0],
                $vendor['zoop_business_address_line2'][0], $vendor['zoop_business_address_neighborhood'][0],
                $vendor['zoop_business_address_city'][0], $vendor['zoop_business_address_state'][0],
                $vendor['zoop_business_address_postal_code'][0], null);
        } else {
             $zoop_vendor_id = $cv_WC_Zoop->ws->setVendor('business', $vendor['zoop_ein'][0], $vendor['zoop_owner_first_name'], $vendor['zoop_owner_email'][0],
                $vendor['zoop_owner_phone_number'][0], $vendor['zoop_mcc'][0],
                $vendor['zoop_birthdate'][0], $vendor['zoop_statement_descriptor'][0],
                $vendor['zoop_owner_address_line1'][0], $vendor['zoop_owner_address_line2'][0],
                $vendor['zoop_owner_address_neighborhood'][0], $vendor['zoop_owner_address_city'][0],
                $vendor['zoop_owner_address_state'][0], $vendor['zoop_owner_address_postal_code'][0],
                $vendor['zoop_business_name'][0], $vendor['zoop_business_phone'][0],
                $vendor['zoop_business_email'][0], $vendor['zoop_business_description'][0],
                $vendor['zoop_business_opening_date'][0], $vendor['zoop_business_address_line1'][0],
                $vendor['zoop_business_address_line2'][0], $vendor['zoop_business_address_neighborhood'][0],
                $vendor['zoop_business_address_city'][0], $vendor['zoop_business_address_state'][0],
                $vendor['zoop_business_address_postal_code'][0], null);
        }
        
        $cv_WC_Zoop->logMsg("Vendor sent = ".json_encode($vendor), "debug", "setVendorGateway.log");
        $cv_WC_Zoop->logMsg("Vendor receive = ".json_encode($zoop_vendor_id), "debug", "setVendorGateway.log");
        if (isset($zoop_vendor_id['id'])){
            update_user_meta ( $userID, 'zoop_vendor_id', $zoop_vendor_id['id'] );
            return  $zoop_vendor_id['id'];
        }else{
            update_user_meta ( $userID, 'zoop_vendor_id', '' );
            return false;
        }
    }

    /**
     * Process the order status.
     *
     * @since 0.0.1
     * @param WC_Order $order
     * @param string $status
     * @param string $transaction_id
     */
    public function process_order_status($order, $status, $transaction_id, $url=null) {
        if ('yes' === $this->debug) {
            $this->log->add($this->id, 'Payment status for order ' . $order->get_order_number() . ' is now: ' . $status);
        }
        update_post_meta($order->id, 'zoop_transaction_id', $transaction_id);
        switch ($status) {
            case 'succeeded' :
                if (!in_array($order->get_status(), array('processing', 'completed'), true)) {
                    $order->update_status('processing', sprintf(__('Zoop: The transaction was authorized(id = %s.)', 'cv_wc_Zoop'), $transaction_id));
                }
                // Changing the order for processing and reduces the stock.
                $order->payment_complete();
                break;
            case 'pending' :
                if (!is_null($url)){
                    $order->update_status(
                        'on-hold',
                        sprintf(__(
                            'Zoop: The bankslip is being processed(id = %s.) The bankslip URL is %s', 'cv_wc_Zoop'),
                            $transaction_id, $url));
                }else{
                    $order->update_status('on-hold', sprintf(__('Zoop: The transaction is being processed(id = %s.)', 'cv_wc_Zoop'), $transaction_id));
                }
                break;
            case 'registered' :
                $order->update_status('on-hold', sprintf(__('Zoop: The banking ticket was issued but not paid yet(id = %s.)', 'cv_wc_Zoop'), $transaction_id));
                break;
            case 'canceled' :
            case 'failed' :
                $order->update_status('failed', sprintf(__('Zoop: The transaction was rejected by the card company or by fraud(id = %s.)', 'cv_wc_Zoop'), $transaction_id));


                $transaction_id = get_post_meta($order->id, '_wc_zoop_transaction_id', true);
                $this->send_email(
                    sprintf(esc_html__('The transaction for order %s was rejected by the card company or by fraud', 'cv_wc_Zoop'),
                        $order->get_order_number()), esc_html__('Transaction failed', 'cv_wc_Zoop'),
                    sprintf(esc_html__('Order %1$s has been marked as failed, because the transaction was rejected by the card '
                                .'company or by fraud. ID %2$s.', 'cv_wc_Zoop'), $order->get_order_number(), $transaction_id)
                );

                break;
            case 'cancelled' :
            case 'chargeback' :
                $order->update_status('refunded', sprintf(__('Zoop: The transaction was refunded/canceled (id = %s.)', 'cv_wc_Zoop'), $transaction_id));

                $transaction_id = get_post_meta($order->id, '_wc_zoop_transaction_id', true);

                $this->send_email(
                    sprintf(esc_html__('The transaction for order %s refunded', 'cv_wc_Zoop'), $order->get_order_number()), esc_html__('Transaction refunded', 'cv_wc_Zoop'), sprintf(esc_html__('Order %1$s has been marked as refunded by Zoop. ID %2$s', 'cv_wc_Zoop'), $order->get_order_number(), $transaction_id)
                );
                break;
            default :
                break;
        }
    }

    /**
     * Get the smallest installment amount.
     *
     * @since 0.0.1
     * @return int
     */
    public function get_smallest_installment() {
        return wc_format_decimal($this->smallest_installment) * 100;
    }

    /**
     * Send email notification.
     *
     * @since 0.0.1
     * @param string $subject Email subject.
     * @param string $title   Email title.
     * @param string $message Email message.
     */
    protected function send_email($subject, $title, $message) {
        $mailer = WC()->mailer();
        $mailer->send(get_option('admin_email'), $subject, $mailer->wrap_message($title, $message));
    }

    /**
     * Get Token
     *
     * @since 0.0.1
     * @return string
     */
    public function get_token() {
        return 'yes' === $this->sandbox ? 'B31DCE74-E768-43ED-86DA-85501612548F' : $this->token;
    }

    /**
     * Get log.
     *
     * @since 0.0.1
     * @return string
     */
    protected function get_log_view() {
        if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.2', '>=')) {
            return '<a href="' . esc_url(admin_url('admin.php?page=wc-status&tab=logs&log_file=' . esc_attr($this->id) . '-' . sanitize_file_name(wp_hash($this->id)) . '.log')) . '">' . __('System Status &gt; Logs', 'cv_wc_Zoop') . '</a>';
        }

        return '<code>woocommerce/logs/' . esc_attr($this->id) . '-' . sanitize_file_name(wp_hash($this->id)) . '.txt</code>';
    }

    /**
     * Save Account form
     * Synchronize woocommerce registration with Zoop
     *
     * @since 0.0.1
     * @param string $user_id
     */
    public function save_account_form($user_id) {
        global $cv_WC_Zoop;
        $user_info = get_userdata($user_id);
        $name = $user_info->first_name;
        $last_name = $user_info->last_name;
        $email = $user_info->user_email;
        if ($user_info->billing_persontype == '1') {
            $document = $user_info->billing_cpf;
        } else {
            $document = $user_info->billing_cnpj;
        }
        $phone = $user_info->billing_phone;
        $street = $user_info->billing_address_1 . ' ' . $user_info->billing_number . ' ' . $user_info->billing_address_2;
        $district = $user_info->billing_neighborhood;
        $city = $user_info->billing_city;
        $state = $user_info->billing_state;
        $zip = $user_info->billing_postcode;
        $zoop_customer_id = $user_info->zoop_id;
        // setting up user display name
        wp_update_user(array('ID' => $user_id, 'display_name' => $name));
        if (!$zoop_customer_id) {
            $result = $cv_WC_Zoop->ws->set_customer($name, $last_name, $email, $document, $phone, $street, $district, $city, $state, $zip);
        } else {
            $result = $cv_WC_Zoop->ws->update_customer($zoop_customer_id, $name, $email, $document, $phone, $street, $district, $city, $state, $zip);
        }
        switch ($result['Status']['Code']) {
            case '200':
                add_user_meta($user_id, 'zoop_id', $result['id'], true);
                return $result['id'];
                break;
            default:
                include dirname(__FILE__) . '/views/html-receipt-page-error.php';
                break;
        }
    }

    /**
     * View Card title
     *
     * @since 0.0.1
     * @global type $wp_query
     * @param String $title
     * @return String
     */
    public function endpoint_title($title) {
        global $wp_query;
        $is_endpoint = isset($wp_query->query_vars[
            'zoopcards']);
        if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {
// New page title.
            $title = __('Zoop Cards', 'cv_wc_Zoop');
            remove_filter('the_title', array($this, 'endpoint_title'));
        }
        return $title;
    }

    /**
     * Card menu items
     *
     * @since 0.0.1
     * @param arr $items
     * @return arr
     */
    public function zoopcards_account_menu_items($items) {

        $items['zoopcards'] = __('Cards', 'cv_wc_Zoop');

        return $items;
    }

    /**
     * View card
     *
     * @since 0.0.1
     */
    public function zoopcards_endpoint_content() {
        global $cv_WC_Zoop;
        if (file_exists(CVZP_DIR_PATH . 'assets/js/jquery.mask/jquery.mask.min.js')) { // jquery-mask
            wp_register_script('jquery-mask', CVZP_DIR_URL . 'assets/js/jquery.mask/jquery.mask.min.js');
            wp_enqueue_script('jquery-mask');
        }
        if (file_exists(CVZP_DIR_PATH . 'assets/js/card-master/card.js')) { // card-master
            wp_register_script('card-master', CVZP_DIR_URL . 'assets/js/card-master/card.js');
            wp_enqueue_script('card-master');
        }
        if (file_exists(CVZP_DIR_PATH . 'assets/js/jquery.creditCardValidator/jquery.creditCardValidator.js')) {
            wp_register_script('jquery.creditCardValidator', CVZP_DIR_URL . 'assets/js/jquery.creditCardValidator/jquery.creditCardValidator.js');
            wp_enqueue_script('jquery.creditCardValidator');
            wp_localize_script('jquery.creditCardValidator', 'wpurl', array('siteurl' => get_option('siteurl')));
        }
        if (file_exists(CVZP_DIR_PATH . 'assets/css/zoop.css')) {
            wp_register_style('zoopcss', CVZP_DIR_URL . 'assets/css/zoop.css');
            wp_enqueue_style('zoopcss');
        }
        $zoop_customer_id = wp_get_current_user()->zoop_id;
        if (!$zoop_customer_id) {
            $zoop_customer_id = $this->save_account_form(wp_get_current_user()->ID);
        }
        if (isset($_POST['cc_number'])) {
            $return = $cv_WC_Zoop->ws->set_card($zoop_customer_id, $_POST['cc_name'], $_POST['cc_number'], $_POST['cc_expiry'], $_POST['cc_cvc']);
            if (isset($return['errors'])) {
                wc_add_notice(__('Error: ', 'cv_wc_Zoop') . $return['Status']['Description'] . ' - ' . $return['errors'][0]['message'], 'error');
            }
        }
        if (isset($zoop_customer_id)) {
            $customer = $cv_WC_Zoop->ws->list_customer($zoop_customer_id);
            $cards = NULL;
            foreach ($customer['cards'] as $key => $value) {
                $cards[$key]['id'] = $value['id'];
                $cards[$key]['name'] = $value['name'];
                $cards[$key]['expires'] = $value['month'] . "/" . $value['year'];
                $cards[$key]['brand'] = $value['brand'];
                $cards[$key]['last4'] = $value['last4'];
            }
        } else {
            $cards = NULL;
        }
        $columns = array(
            '1' => __('Name', 'cv_wc_Zoop'),
            '2' => __('Expires', 'cv_wc_Zoop'),
            '3' => __('Brand', 'cv_wc_Zoop'),
            '4' => __('Last four', 'cv_wc_Zoop'),
            '5' => __('Action', 'cv_wc_Zoop')
        );
        include dirname(__FILE__) . '/views/html-cards.php';
    }

    /**
     * Cancel meta box
     *
     * @since 0.0.1
     */
    public function add_meta_boxes() {
        add_meta_box(
            'woocommerce-meta-zoopcards', __('Zoop Cards', 'cv_wc_Zoop'), array($this, 'meta_zoopcards'), 'shop_order', 'side', 'default'
        );
    }

    /**
     * View cancel metabox
     *
     * @since 0.0.1
     * @param WC_Order $order_id
     */
    public function meta_zoopcards($post) {
        $order = new WC_Order($post->ID);
        if (!$order->get_meta('zoop_canceled')){
            $zoop_transaction_id = $order->get_meta('zoop_transaction_id');
            $order_id = $order->get_id();
            $total = $order->get_total();
            include 'admin/meta.php';
        }
    }

    public function woo_change_order_received_text( $str, $order ) {
        $bankslip_url = $order->get_meta('zoop_bankslip_url');
        if (!is_null($bankslip_url) && $bankslip_url != "" && strpos($str, $bankslip_url) == false) {
            $str = $str . "</BR>" .
                sprintf(__(
                    "Your bankslip is available <a href='%s' target='_blank'>here</a>", 'cv_wc_Zoop'), $bankslip_url);
        }
        return $str;
    }

}