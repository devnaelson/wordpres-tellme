<?php
/**
 * Created by PhpStorm.
 * User: cassiovidal
 * Date: 09/07/18
 * Time: 15:48
 *
 * This class processa toda a comunicação com a api da zoop seja ela para criar vendedores ou para processar pagamentos
 */

class cv_zoop_ws
{
    private $apyKey;

    private $url;

    private $debug;

    private $marketPlaceID;

    private $sellerID;

    function __construct()
    {
        $this->apyKey = get_option('cv_wc_Zoop_PublishableKey');
        $this->marketPlaceID = get_option('cv_wc_Zoop_MarketPlaceID');
        $this->sellerID = get_option('cv_wc_Zoop_SellerID');
        $this->url = "https://api.zoop.ws/";
        $this->debug = get_option('cv_wc_Zoop_Debug');
        $this->daysDue = get_option('cvWCZoopBankDue');
        $this->bankLateFee = floatval(str_replace(",", ".", get_option('cvWCZoopBankLateFee')));
        $this->bankInterest = floatval(str_replace(",", ".", get_option('cvWCZoopBankInterest')));
        add_action('wp_ajax_delcard', array($this, 'del_card'));
        add_action('wp_ajax_zoopcancelregularpayment', array($this, 'cancel_regular_payment'));
        add_action('wp_ajax_nopriv_zoop_webhook', array($this, 'getWebhook'));
        add_action('wp_ajax_zoop_get_vendor', array($this, 'getVendor'));
        add_action('wp_ajax_zoop_get_customer', array($this, 'getCustomer'));
    }
    /**
     * Only numbers.
     *
     * @since 0.0.1
     * @param  string|int $string String to convert.
     * @return string|int
     */
    protected function only_numbers($string) {
        return preg_replace('([^0-9])', '', $string);
    }

    /**
     * @param null $endpoint
     * @param null $data_in
     * @param null $filter
     * @param null $limit
     * @param null $page
     * @return bool|mixed
     */
    protected function communicate($endpoint=null, $method = null, $data=null){
        global $cv_WC_Zoop;
        $cv_WC_Zoop->logMsg('ENDPOINT - '. $this->url . $endpoint, 'info', 'communicateWS.log');
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->url . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode($this->apyKey .":"),
                'Content-Type:application/json'
            ),
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if (!is_null($data)){
            if (is_array($data)){
                $data = json_encode($data);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Basic " . base64_encode($this->apyKey .":"),
                'Content-Type:application/json'
            ));
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Basic " . base64_encode($this->apyKey .":")
            ));
        }
        if ($this->debug){
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_STDERR, fopen(CVZP_DIR_PATH.'log/curl_output.log', 'w'));
        }
        $result = curl_exec($ch);
        $error = curl_error($ch);
        if ($error != "") {
            $cv_WC_Zoop->logMsg('Erro na transação: Dados enviados - ' . $data, 'error', 'communicateWS.log');
            $cv_WC_Zoop->logMsg($this->gateway->id, 'Call Zoop API error: ' . $error, 'error', 'communicateWS.log');
            curl_close($ch);
            return array('code' => '500', 'message' => $error); // erro
        } else {
            if ($this->debug){
                $cv_WC_Zoop->logMsg('Sucesso na transação: Dados enviados - ' . $data, 'debug', 'communicateWS.log');
                $cv_WC_Zoop->logMsg('HEADERS enviados - ' . json_encode(curl_getinfo($ch)), 'debug', 'communicateWS.log');
                $cv_WC_Zoop->logMsg('Sucesso na transação Dados recebidos - ' . json_encode($result), 'debug', 'communicateWS.log');
                $cv_WC_Zoop->logMsg('Erro na transação  - ' . $error, 'debug', 'communicateWS.log');
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            switch ($httpcode) {
                case '200':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '200';
                    break;
                case '204':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '200';
                    break;
                case '400':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '400';
                    $result['Status']['Name'] = 'Bad Request';
                    $result['Status']['Description'] = __('Something went wrong. Often a required param is missing', 'cv_wc_Zoop');
                    break;
                case '401':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '401';
                    $result['Status']['Name'] = 'Unauthorized';
                    $result['Status']['Description'] = __('Not a valid API Key', 'cv_wc_Zoop');
                    break;
                case '402':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '402';
                    $result['Status']['Name'] = 'Request failed';
                    $result['Status']['Description'] = __('Params were right, but something failed, also used to rejected charges', 'cv_wc_Zoop');
                    break;
                case '404':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '404';
                    break;
                case '410':
                    curl_close($ch);
                    $result['message'] = $result;
                    $result['Status']['Code'] = '410';
                    $result['Status']['Name'] = 'Not found';
                    $result['Status']['Description'] = __('Requested item doesn\'t exists', 'cv_wc_Zoop');
                    break;
                case '422':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '422';
                    $result['Status']['Name'] = 'Unprocessable Entity';
                    $result['Status']['Description'] = __('Some param is invalid', 'cv_wc_Zoop');
                    break;
                case '500':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '500';
                    $result['Status']['Name'] = 'Server Error';
                    $result['Status']['Description'] = __('Something went wrong on our servers', 'cv_wc_Zoop');
                    break;
                case '501':
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = '501';
                    $result['Status']['Name'] = 'Server Error';
                    $result['Status']['Description'] = __('Feature not available yet', 'cv_wc_Zoop');
                    break;
                default:
                    curl_close($ch);
                    $result = json_decode($result, true);
                    $result['Status']['Code'] = 'NA';
                    $result['Status']['Name'] = __('Not Available', 'cv_wc_Zoop');
                    $result['Status']['Description'] = __('Not Available', 'cv_wc_Zoop');
                    break;
            }
        }
        return $result;        
    }

    /**
     * @param $data
     * @return null|string
     */
    protected function parse($data) {
        if (!$data)
            return null;
        array_walk_recursive($data, function (&$value) {
            $value = iconv('UTF-8', 'ISO-8859-1', utf8_encode($value));
        });
        return $data = json_encode((array) $data);
    }

    /**
     * @param $arr
     * @param $col
     * @param int $dir
     * @return mixed
     */
    protected function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
        return $arr;
    }

    /**
     * @return mixed
     */
    public function getMCC(){
        $return = array();
        $items = array();
        $i = 0;
        do{
            $i++;
            $data = $this->communicate("v1/merchant_category_codes?page=$i", "GET");
			if (isset($data['items']) && is_array($data['items'])){
				$items = array_merge($items, $data['items']);
			}
        }while($data['total_pages'] > $i);
        if (is_array($items)){
            foreach ($items as $item){
                $return[$item['category']][] = array(
                    'code' => $item['id'],
                    'description'=> $item['description']
                );
            }
            ksort($return);
            foreach($return as $key => $value){
                $return[$key] = $this->array_sort_by_column($value, 'description');
            }
        }else{
            $return = array();
        }

        return $return;
    }

    public function setVendor($type, $cpf, $name, $owner_email, $phone, $mcc, $birthdate, $statement_descriptor, $line1,
                              $line2, $neighborhood, $city, $state, $postal_code, $business_name, $business_phone,
                              $business_email, $business_description, $business_opening_date, $business_address_line1,
                              $business_address_line2, $business_address_neighborhood, $business_address_city,
                              $business_address_state, $business_address_postal_code, $vendor_id = null){
        global $cv_WC_Zoop;
        $birthdate = date("Y-m-d", strtotime(str_replace('/', '-',$birthdate)));
        $business_opening_date = date("Y-m-d", strtotime(str_replace('/', '-',$business_opening_date)));
        $data = array();
        if ($type === "individual"){
            $data['type'] = "individual";
            $data['taxpayer_id'] = $this->only_numbers($cpf);
            $data['first_name'] = $name;
            $data['email'] = $owner_email;
            $data['phone_number'] = $phone;
            $data['mcc'] = $mcc;
            $data['birthdate'] = $birthdate;
            $data['statement_descriptor'] = $statement_descriptor;
            $data['address'] = array(
                'line1' => $line1,
                'line2' => $line2,
                'neighborhood' => $neighborhood,
                'city' => $city,
                'state' => $state,
                'postal_code' => $postal_code,
                'country_code' => 'BR'
            );           
            if (is_null($vendor_id) || $vendor_id == ""){
                $ret = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/search?taxpayer_id={$data['taxpayer_id']}", "GET");
                if (isset($ret['id'])){
                    $vendor_id = $ret['id'];
                }
            }
            $cv_WC_Zoop->logMsg("Data sent = ".json_encode($data), "debug", "setVendorWS.log");
            if (is_null($vendor_id) || $vendor_id == ""){
                return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/individuals", "POST", $data);
            }else{
                return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/individuals/$vendor_id", "PUT", $data);
            }
        }else{
            $data['type'] = "business";
            $data['ein'] =  $this->only_numbers($cpf);
            $data['business_name'] = $business_name;
            $data['business_phone'] = $business_phone;
            $data['business_email'] = $business_email;
            $data['business_description'] = $business_description;
            $data['business_opening_date'] = $business_opening_date;
            $data['statement_descriptor'] = $statement_descriptor;
            $data['mcc'] = $mcc;
            $data['business_address'] = array(
                'line1' => $business_address_line1,
                'line2' => $business_address_line2,
                'neighborhood' => $business_address_neighborhood,
                'city' => $business_address_city,
                'state' => $business_address_state,
                'postal_code' => $business_address_postal_code,
                'country_code' => 'BR'
            );
            // dados do proprietário da empresa
            $data['owner']['first_name'] = $name;
            $data['owner']['email'] = $owner_email;
            $data['owner']['phone_number'] = $phone;
            $data['owner']['birthdate'] = $birthdate;
            $data['owner']['address'] = array(
                'line1' => $line1,
                'line2' => $line2,
                'neighborhood' => $neighborhood,
                'city' => $city,
                'state' => $state,
                'postal_code' => $postal_code,
                'country_code' => 'BR'
            );
            if (is_null($vendor_id) || $vendor_id == ""){
                $ret = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/search?ein={$data['ein']}", "GET");
                if (isset($ret['id'])){
                    $vendor_id = $ret['id'];
                }
            }
            if (is_null($vendor_id) || $vendor_id == ""){
                return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/businesses", "POST", $data);
            }else{
                return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/businesses/$vendor_id", "PUT", $data);
            }
        }
    }

    public function set_customer($first_name, $last_name, $email, $document, $phone, $birthdate, $street_1, $street_2,
                                 $district, $city, $state, $zip)
    {
        global $cv_WC_Zoop;
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone_number' => $this->only_numbers($phone),
            'taxpayer_id' => $document,
            'birthdate' => $birthdate,
            'address' => array(
                'line1' => $street_1,
                'line2' => $street_2,
                'neighborhood' => $district,
                'city' => $city,
                'state' => $state,
                'postal_code' => $zip,
                'country_code' => 'BR'
            )
        );     
        $cv_WC_Zoop->logMsg('Call without parameter = ' . json_encode($data), 'debug', 'setCustomerWS.log');   
        if ($first_name !='' && $last_name !='' && $email !='' && $document !='' && $phone !='' && $birthdate !='' && $street_1 !=''
                && $district !='' && $city !='' && $state !='' && $zip !=''){
            $document =  $this->only_numbers($document);
            $ret = $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/buyers/search?taxpayer_id={$document}", "GET");
            $cv_WC_Zoop->logMsg('Return buyers/search = ' . json_encode($ret), 'debug', 'setCustomerWS.log');           
            if (isset($ret['id'])){
                return $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/buyers/{$ret['id']}", "PUT", $data);
            }else {
                return $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/buyers", "POST", $data);
            }
        } else {
            $cv_WC_Zoop->logMsg('Call without parameter = ' . json_encode($data), 'debug', 'setCustomerWS.log');
            return array(
                'error' => array(
                    'message' => __('An error occured on register the Gateway Paymment. Review your data and try again.')
                )
            );
        }
    }

    public function set_card($zoop_customer_id, $cc_name, $cc_number, $cc_expiry, $cc_cvc)
    {
        global $cv_WC_Zoop;
        $data = array(
            'holder_name' => $cc_name,
            'expiration_month' => substr($cc_expiry, 0, 2),
            'expiration_year' => "20" . substr($cc_expiry, -2),
            'security_code' => $cc_cvc,
            'card_number' => $this->only_numbers($cc_number)
        );
        $return = $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/cards/tokens", "POST", $data);
        if (isset($return['id'])) {
            return $this->card_to_customer($zoop_customer_id, $return['id']);
        } else {
            $cv_WC_Zoop->logMsg('Erro ao criar token do cartão - ' . json_encode($return), 'error', 'setCardWS.log');
            return $return;
        }
    }

    protected function card_to_customer($zoop_customer_id, $zoop_id_card)
    {
        $data = array(
            'customer' => $zoop_customer_id,
            'token' => $zoop_id_card
        );
        return $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/cards/", "POST", $data);
    }

    public function process_regular_payment($amount, $customer_id, $card_id, $installments = 1, $split = null, $order_id)
    {
        $amount = $this->only_numbers($amount);
        $amount = $amount * $installments;
        $data = array(
            'on_behalf_of' => $this->sellerID,
            'amount' => (int)$amount,
            'currency' => 'BRL',
            'description' => "order_$order_id",
            'reference_id' => $order_id,
            'payment_type' => 'credit',
            'capture' => true,
            /*'source' => array(
                'usage' => 'reusable',
                'type' => 'card',
                'capture' => 'true',
                'card' => array(
                    'id' => $card_id,
                    'amount' => $amount,
                ),
                'amount' => $amount,
                'currency' => 'BRL',
            ),*/
            'customer' => $customer_id
        );
        if ($installments > 1){
            $data['installment_plan'] = array(
              'mode' => 'interest_free',
              'number_installments' => $installments
            );
        }
        if (isset($split) && !is_null($split)){
            $data['split_rules'] = $split;
        }
        return $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/transactions/", "POST", $data);
    }

    public function process_boleto_payment($amount, $customer_id, $split = null, $order_id)
    {
        if (is_null($order_id)){
            $order_id = 0;
        }
        $amount = $this->only_numbers($amount);
        $data = array(
            'amount' => $amount,
            'currency' => 'BRL',
            'description' => "order_$order_id",
            'reference_id' => $order_id,
            'on_behalf_of' => $this->sellerID,
            'customer' =>$customer_id,
            'payment_type' => 'boleto',
			'capture' => true,
            'payment_method' => array(
                'expiration_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (int)$this->daysDue, date('Y'))),
                'payment_limit_date' => date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (int)$this->daysDue, date('Y'))),
                'body_instructions' => array("order_$order_id"),
                'billing_instructions' => array(
                )
            )            
        );
        if ($this->bankLateFee > 0){
            $data['payment_method']['billing_instructions']['late_fee'] = array(
                'mode'=> 'PERCENTAGE',
                'percentage' =>  number_format($this->bankLateFee, 2, ".", "")
            );    
        }
        if ($this->bankInterest  > 0){
            $data['payment_method']['billing_instructions']['interest'] = array(
                'mode' => 'DAILY_PERCENTAGE',
                'percentage' =>  number_format($this->bankInterest, 2, ".", "")
            );
        }
        if (isset($split) && !is_null($split) && !empty($split)){
            $split1 = array();
            foreach ($split as $key => $value ){
                $split1[$key] = $value;
                //$split1[$key]['amount'] = number_format($value['amount']/100, 2, ".", "");
                $split1[$key]['amount'] = number_format($value['amount'], 0, "", "");
            }
            $data['split_rules'] = $split1;
        }
        return $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/transactions/", "POST", $data);
    }

    public function cancel_regular_payment() {
        if (isset($_REQUEST['order_id'])) {
            $transaction_id = $_REQUEST['transaction_id'];
            $order_id = $_REQUEST['order_id'];
            $order = new WC_Order($order_id);
            $data = array(
                "on_behalf_of" => $this->sellerID,
                "amount" => $this->only_numbers($_REQUEST['total']),
            );
            $return = $this->communicate("v1/marketplaces/" . $this->marketPlaceID . "/transactions/$transaction_id/void", "POST", $data);
        }
        if (isset($return['id'])) {
            $order->add_order_note(sprintf(__('Zoop: Transaction was canceled (id = %s.)', 'cv_wc_Zoop'), $return['id']));
            update_post_meta($order_id, 'zoop_canceled', 'TRUE');
            $order->update_status('refunded', sprintf(__('Zoop: The transaction was refunded/canceled (id = %s.)', 'cv_wc_Zoop'), $return['id']));
            wp_send_json(array('code' => '200', 'message' => __('Payment was canceled successfuly.', 'cv_wc_Zoop')));
        } else {
            wp_send_json(array('code' => '500', 'message' => __('An error has occurred. Try Again', 'cv_wc_Zoop')));
        }
    }

    public function setBank($type, $holder_name, $bank_code, $routing_number, $account_number, $taxpayer_id) {
        if ($type === "individual"){
            $data = array(
                'holder_name'       => $holder_name,
                'bank_code'         => $bank_code,
                'routing_number'    => $routing_number,
                'account_number'    => $account_number,
                'taxpayer_id'       => preg_replace('/[^0-9]/','',$taxpayer_id),
                'type'              => 'Checking'
            );
        }
        else{
            $data = array(
                'holder_name'       => $holder_name,
                'bank_code'         => $bank_code,
                'routing_number'    => $routing_number,
                'account_number'    => $account_number,
                'ein'              => preg_replace('/[^0-9]/','',$taxpayer_id),
                'type'              => 'Checking'
            );
        }
        return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/bank_accounts/tokens", "POST", $data);
    }

    public function bank_to_vendor($account_id, $vendor_id){
        $data = array(
            'customer'    => $vendor_id,
            'token'       => $account_id
        );
        return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/bank_accounts", "POST", $data);
    }

    public function receivingPolicy($vendor_id, $transfer_interval, $transfer_day, $transfer_enabled, $mininum_transfer_value){
        $data = array(
            'transfer_interval'         => $transfer_interval,
            'transfer_day'              => $transfer_day,
            'transfer_enabled'          => $transfer_enabled,
            'minimum_transfer_value'    => $mininum_transfer_value
        );
        return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/$vendor_id/receiving_policy", "POST", $data);
    }

    public function setWebhook() {
        $data = array(
            'url' => get_site_url() . '/wp-admin/admin-ajax.php?action=zoop_webhook',
            'method' => "POST",
            'event' => array(
                "transaction.canceled",
                "transaction.succeeded",
                "transaction.failed",
                "transaction.reversed",
                "transaction.reversed",
                "transaction.updated",
                "transaction.disputed",
                //"transaction.disputed.succeeded",
                "transaction.charged_back"
            )
        );
        return $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/webhooks", "POST", $data);

    }
	
	public function getWebhook () {
        global $cv_WC_Zoop;
        $cv_WC_Zoop->logMsg( 'request = ' . json_encode($_REQUEST), 'info', 'getWebhookWS.log');
        $cv_WC_Zoop->logMsg( 'body = ' . json_encode(file_get_contents('php://input')), 'info', 'getWebhookWS.log');
		if (isset($_REQUEST['data'])){
            $data = $_REQUEST['data'];
            $this->process_status($data['items']);
        } else {
			$data = file_get_contents('php://input');        
			if (isset($data)){
				$data = json_decode($data, true);
				if (isset($data['id'])){
				    $test[] = array(
				        'id' => $data['id']);
					$this->process_status($test);
				}
			}
		}
        
    }
    
    public function importAllHook(){
        $data = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/events", "GET");
        $data = json_decode($data);
        $type = array(
            "transaction.canceled",
            "transaction.succeeded",
            "transaction.failed",
            "transaction.reversed",
            "transaction.updated",
            "transaction.disputed",
            "transaction.charged_back"
        );        
        $this->process_status($data['items']);
        
    }

    private function process_status($data = array()){
        global $cv_WC_Zoop;        
        foreach($data as $key => $value){
            $order_id = null;
            $cv_WC_Zoop->logMsg( 'first data= ' . json_encode($value, true), 'debug', 'processStatusWS.log');
            if(isset($value['id'])){
                $cv_WC_Zoop->logMsg( 'valueID = ' . $value['id'], 'info', 'processStatusWS.log');
                $value = $this->confirmHook($value['id']);
                $cv_WC_Zoop->logMsg( 'value = ' . json_encode($value, true), 'info', 'processStatusWS.log');
            }
            if (!is_null($value)){
                $cv_WC_Zoop->logMsg( 'type = ' . $value['type'], 'info', 'processStatusWS.log');
                $order_id = $this->get_order($value['payload']['object']['description']);
                if (is_null($order_id) || $order_id == ''){
                    $order_id = $this->get_order($value['payload']['object']['payment_method']['description']);    
                    $cv_WC_Zoop->logMsg( 'entrou', 'info', 'processStatusWS.log');
                }
                $cv_WC_Zoop->logMsg( $value['payload']['object']['payment_method']['description'], 'info', 'processStatusWS.log');
                $cv_WC_Zoop->logMsg( 'order_id = ' . $order_id, 'info', 'processStatusWS.log');
                switch ($value['type']) {
                    case 'transaction.canceled':
                        $order = new WC_Order($order_id);
                        $cv_WC_Zoop->gateway->process_order_status($order, 'canceled', $value['payload']['object']['id']);
                        break;
                    case 'transaction.succeeded':
                        $order = new WC_Order($order_id);
                        $cv_WC_Zoop->gateway->process_order_status($order, 'succeeded', $value['payload']['object']['id']);
                        break;
                    case 'transaction.failed':                    
                        $order = new WC_Order($order_id);
                        $cv_WC_Zoop->gateway->process_order_status($order, 'failed', $value['payload']['object']['id']);
                        break;
                    case 'transaction.reversed':
                        $order = new WC_Order($order_id);
                        $cv_WC_Zoop->gateway->process_order_status($order, 'chargeback', $value['payload']['object']['id']);
                        break;
                    case 'transaction.disputed':
                        $order = new WC_Order($order_id);
                        $cv_WC_Zoop->gateway->process_order_status($order, 'canceled', $value['payload']['object']['id']);
                        break;
                    case 'transaction.charged_back':
                        $order = new WC_Order($order_id);
                        $cv_WC_Zoop->gateway->process_order_status($order, 'chargeback', $value['payload']['object']['id']);
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
    }

    private function confirmHook($id = null){
        $return = null;
        if (!is_null($id)){
            $return = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/events/" . $id, "GET");
        }
        return $return;
    }
    private function get_order($string){
        if (strpos($string, 'order_') !== FALSE){
            return str_replace('order_', '', $string);
        } else {
            return null;
        }
        
    }
	
	public function getVendor() {
		global $cv_WC_Zoop;
		$data = $_REQUEST;
		$return = "";
		if (isset($data['ein'])){
			$return = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/search?ein={$data['ein']}", "GET");
		} else if (isset($data['taxpayer_id'])){
			$return = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/sellers/search?taxpayer_id={$data['taxpayer_id']}", "GET");
		}
		$cv_WC_Zoop->logMsg(json_encode($return, true));
		wp_send_json(array('code' => '200', 'message' => $return));
		return $return;
    }
    
    public function getCustomer() {
		global $cv_WC_Zoop;
		$data = $_REQUEST;
        $return = $this->communicate("v1/marketplaces/". $this->marketPlaceID . "/buyers/search?ein={$data['customer']}", "GET");
		$cv_WC_Zoop->logMsg(json_encode($return, true));
		wp_send_json(array('code' => '200', 'message' => $return));
		return $return;
	}
}