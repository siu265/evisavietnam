<?php

/**
 * Plugin Name: OnePay payment gateway for WooCommerce VCB Exchange Rate V1.1
 * Plugin URI: http://onepay.vn
 * Description: Full integration for Onepay payment gateway for WooCommerce
 * Version: 1.1
 * Author: OnePay
 * Author URI: http://onepay.vn
 * License: GPL2
 */

// Khai báo hỗ trợ WooCommerce Blocks
add_action('before_woocommerce_init', function() {
	if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
	}
});

add_action('plugins_loaded', 'woocommerce_onepay_init', 0);

function woocommerce_onepay_init()
{
	if (!class_exists('WC_Payment_Gateway'))
		return;

	class WC_onepay extends WC_Payment_Gateway
	{

		// URL checkout của onepay.vn - Checkout URL for OnePay
		private $onepay_url;

		// Mã merchant site code
		private $merchant_site_code;

		// Mật khẩu bảo mật - Secure password
		private $secure_pass;

		// Debug parameters
		private $debug_params;
		private $debug_md5;

		private $exchange_rate_config;
		public static $log_enabled = false;
		public static $ipn_enabled = false;

		/** @var WC_Logger Logger instance */
		public static $log = false;

		function __construct()
		{

			$this->icon = plugins_url('onepay-payment-gateway-for-woocommerce-paygate-vcb-exchange-rate-v1.1/logo.png', dirname(__FILE__)); // Icon URL
			$this->id = 'onepay';
			$this->method_title = 'OnePay-Paygate-VCB-Exchange-Rate';
			$this->has_fields = false;
			
			// Hỗ trợ Block checkout (WooCommerce Blocks)
			$this->supports = array(
				'products',
			);
			
			// Thêm support cho Block checkout nếu WooCommerce Blocks đang active
			if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
				$this->supports[] = 'block';
			}

			$this->init_form_fields();
			$this->init_settings();

			// Đảm bảo enabled được set từ settings (parent init_settings đã set, nhưng set lại để chắc chắn)
			$this->enabled = ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'] ? 'yes' : 'no';

			$this->title = $this->settings['title'];
			$this->description = $this->settings['description'];
			$this->exchange_rate_config = $this->settings['exchange_rate_config'] ?? '';
			$this->onepay_url = $this->settings['onepay_url'];
			$this->merchant_access_code = $this->settings['merchant_access_code'];
			$this->merchant_id = $this->settings['merchant_id'];
			$this->secure_secret = $this->settings['secure_secret'];
			//$this->redirect_page_id = $this->settings['redirect_page_id'];

			$this->debug = $this->settings['debug'];
			$this->order_button_text = __('Pay now', 'monepayus');

			$this->msg['message'] = "";
			$this->msg['class'] = "";

			self::$log_enabled = $this->debug;
			self::$ipn_enabled = $this->settings['IPN'];

			if (version_compare(WOOCOMMERCE_VERSION, '2.0.8', '>=')) {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
			} else {
				add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
			}
			// Add the page after checkout to redirect to OnePAY
			add_action('woocommerce_receipt_' . $this->id, array(&$this, 'receipt_page'));
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
			add_action('woocommerce_api_' . $this->id, array($this, 'handle_onepay_ipn'));
		}

		public function handle_onepay_ipn()
		{
			if (isset($_REQUEST['vpc_SecureHash'])) {
				$this->process_onepay_ipn($_REQUEST, 'ipn');
			}
		}

		public function get_onepay_ipn_url()
		{
			return WC()->api_request_url($this->id);
		}

		function init_form_fields()
		{
			// Admin fields
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Activate', 'monepayus'),
					'type' => 'checkbox',
					'label' => __('Activate the payment gateway for OnePay', 'monepayus'),
					'default' => 'no'
				),
				'title' => array(
					'title' => __('Name:', 'monepayus'),
					'type' => 'text',
					'description' => __('Name of payment method (as the customer sees it)', 'monepayus'),
					'default' => __('OnePay Paygate', 'monepayus')
				),
				'description' => array(
					'title' => __('', 'monepayus'),
					'type' => 'textarea',
					'description' => __('Payment gateway description', 'monepayus'),
					'default' => __('Click place order and you will be directed to the OnePAY website in order to make payment', 'monepayus')
				),


				'nlcurrency' => array(
					'title' => __('Currency', 'monepayus'),
					'type' => 'text',
					'default' => 'vnd',
					'description' => __('"vnd" or "usd"', 'monepayus')
				),
				'exchange_rate_config' => array(
					'title' => __('Exchange Rate Config', 'monepayus'),
					'type' => 'text',
					'description' => $this->getExchangeRateDesc()
				),
				'onepay_url' => array(
					'title' => __('OnePAY URL', 'monepayus'),
					'type' => 'text'
				),
				'merchant_id' => array(
					'title' => __('Merchant ID', 'monepayus'),
					'type' => 'text'
				),
				'merchant_access_code' => array(
					'title' => __('Merchant Access Code', 'monepayus'),
					'type' => 'text'
				),

				'secure_secret' => array(
					'title' => __('Secure Secret', 'monepayus'),
					'type' => 'text'
				),
				'IPN' => array(
					'title' => __('Instant Payment Notification (IPN)', 'monepayus'),
					'type' => 'checkbox',
					'label' => __('Enable IPN', 'monepayus'),
					'default' => 'yes',
					'description' => sprintf(__('Notifications will be send to: %s', 'monepayus'), '<code>'
						. $this->get_onepay_ipn_url() . '</code>')
				),
				'debug' => array(
					'title' => __('Debug', 'monepayus'),
					'type' => 'checkbox',
					'label' => __('Enable logging', 'monepayus'),
					'default' => 'no',
					'description' => sprintf(__('Log events, such as IPN requests, inside %s', 'monepayus'), '<code>'
						. WC_Log_Handler_File::get_log_file_path(get_called_class()) . '</code>')
				)
			);
		}

		public function admin_options()
		{
			echo '<h3>' . __('Onepay Payment Gateway', 'monepayus') . '</h3>';
			echo '<table class="form-table">';
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			echo '</table>';
		}

		/**
		 *  There are no payment fields for onepayUS, but we want to show the description if set.
		 **/
		function payment_fields()
		{
			if ($this->description)
				echo wpautop(wptexturize(__($this->description, 'monepayus')));
		}

		/**
		 * Check if the gateway is available for use.
		 * Override để đảm bảo gateway hiển thị khi enabled = 'yes'
		 *
		 * @return bool
		 */
		public function is_available()
		{
			// Kiểm tra enabled trước tiên
			if ( $this->enabled !== 'yes' ) {
				return false;
			}

			// Gọi parent để kiểm tra các điều kiện khác (max_amount, etc.)
			return parent::is_available();
		}

		/**
		 * Process the payment and return the result
		 **/
		function process_payment($order_id)
		{
			$order = new WC_Order($order_id);

			if (!$this->form_submission_method) {

				return array(
					'result' => 'success',
					'redirect' => $this->generate_onepayUS_url($order_id)
				);
			}
		}

		/**
		 * Receipt Page
		 **/
		function receipt_page($order_id)
		{
			echo '<p>' . __('Chúng tôi đã nhận đơn đặt hàng của Quý khách. <br /><b>Hệ thống sẽ tự động chuyển tiếp đến hệ thống của OnePay để xử lý.', 'monepayus') . '</p>';
			$checkouturl = $this->generate_onepayUS_url($order_id);

			if ($this->debug == 'yes') {
				// Debug just shows the URL
				echo '<code>' . $checkouturl . '</code>';
				// echo '<p>secure pass ' . $this->secure_pass . '</p>';
				// echo '<p>params ' . strval($this->debug_params) . '</p>';
				// echo '<p>md5 ' . strval($this->debug_md5) . '</p>';
			} else {
				// Adds javascript to the post-checkout screen to redirect to OnePAY with a fully-constructed URL
				// Note: wp_redirect() fails with OnePAY
				echo '<a href="' . $checkouturl . '">' . __('Kích vào đây để thanh toán nếu hệ thống không tự động chuyển tiếp sau 5 giây', 'monepayus') . '</a>';
				echo "<script type='text/javaScript'><!--
          setTimeout(\"location.href = '" . $checkouturl . "';\",1500);
          --></script>";
			}
		}

		function thankyou_page($order_id)
		{

			// Return to site after checking out with OnePAY
			// Note this has not been fully-tested
			global $woocommerce;

			$order = new WC_Order($order_id);

			// *********************
			// START OF MAIN PROGRAM
			// *********************


			// Define Constants
			// ----------------
			// This is secret for encoding the MD5 hash
			// This secret will vary from merchant to merchant
			// To not create a secure hash, let SECURE_SECRET be an empty string - ""
			// $SECURE_SECRET = "secure-hash-secret";
			$SECURE_SECRET = $this->settings['secure_secret']; //93E963BC17BF022F2A03B685784D0CFA
			//$SECURE_SECRET = "93E963BC17BF022F2A03B685784D0CFA";
			// If there has been a merchant secret set then sort and loop through all the
			// data in the Virtual Payment Client response. While we have the data, we can
			// append all the fields that contain values (except the secure hash) so that
			// we can create a hash and validate it against the secure hash in the Virtual
			// Payment Client response.



			// Define Variables
			// ----------------
			// Extract the available receipt fields from the VPC Response
			// If not present then let the value be equal to 'No Value Returned'
			// Standard Receipt Data
			$amount = $this->null2unknown($_GET["vpc_Amount"]);
			$orderInfo = $this->null2unknown($_GET["vpc_OrderInfo"]);
			$txnResponseCode = $this->null2unknown($_GET["vpc_TxnResponseCode"]);

			// This is the display title for 'Receipt' page
			//$title = $_GET ["Title"];


			// This method uses the QSI Response code retrieved from the Digital
			// Receipt and returns an appropriate description for the QSI Response Code
			//
			// @param $responseCode String containing the QSI Response Code
			//
			// @return String containing the appropriate description
			//

			//  ----------------------------------------------------------------------------
			$hashValidated = $this->validateHash($_GET, $SECURE_SECRET);

			$transStatus = "";
			if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
				$transStatus = '<h1 class = "entry-title" style="color:green;">Payment was successful</h1>';
				// Mark as on-hold (we're awaiting the cheque)
				//$order->update_status( 'onepayVN', __( 'Payment complete', 'woocommerce' ) ,'Payment complete');
				$order->add_order_note(__('Payment completed', 'woocommerce'));
				// Reduce stock levels
				//$order->reduce_order_stock();
				// Remove cart
				$order->payment_complete();
				$order->update_status('completed');
				WC()->cart->empty_cart();
			} elseif ($hashValidated == "CORRECT" && $txnResponseCode != "0") {
				$tranDesc = $this->getResponseDescription($txnResponseCode);
				$transStatus = '<h1 class = "entry-title" style="color:red;">Payment was fail-' . $tranDesc . '</h1>';
				$order->update_status('failed');
				$order->add_order_note(__('Payment failed-' . $tranDesc, 'woocommerce'));
			} elseif ($hashValidated == "INVALID HASH") {
				$transStatus = '<h1 class = "entry-title" style="color:red;">Payment Pending</h1>';
				$order->add_order_note(__('Payment pending', 'woocommerce'));
			}
			print $transStatus;
		}

		function validateHash($agrs, $SECURE_SECRET)
		{

			// NOTE: If the vpc_TxnResponseCode in not a single character then
			// there was a Virtual Payment Client error and we cannot accurately validate
			// the incoming data from the secure hash. */

			// get and remove the vpc_TxnResponseCode code from the response fields as we
			// do not want to include this field in the hash calculation
			$vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
			unset($_GET["vpc_SecureHash"]);

			// set a flag to indicate if hash has been validated
			$errorExists = false;

			if (strlen($SECURE_SECRET) > -1 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned") {
				ksort($_GET);
				//$stringHashData = $SECURE_SECRET;
				//*****************************khởi tạo chuỗi mã hóa rỗng*****************************
				$stringHashData = "";

				// sort all the incoming vpc response fields and leave out any with no value
				foreach ($_GET as $key => $value) {
					//        if ($key != "vpc_SecureHash" or strlen($value) > -1) {
					//            $stringHashData .= $value;
					//        }
					//      *****************************chỉ lấy các tham số bắt đầu bằng "vpc_" hoặc "user_" và khác trống và không phải chuỗi hash code trả về*****************************
					if ($key != "vpc_SecureHash" && (strlen($value) > -1) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
						$stringHashData .= $key . "=" . $value . "&";
					}
				}
				//  *****************************Xóa dấu & thừa cuối chuỗi dữ liệu*****************************
				$stringHashData = rtrim($stringHashData, "&");

				$stringReturnHash = strtoupper($vpc_Txn_Secure_Hash);
				$stringVerifyHash = strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)));


				//    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md4 ( $stringHashData ) )) {
				//    *****************************Thay hàm tạo chuỗi mã hóa*****************************
				if (hash_equals($stringReturnHash, $stringVerifyHash)) {
					// Secure Hash validation succeeded, add a data field to be displayed
					// later.
					$hashValidated = "CORRECT";
				} else {
					// Secure Hash validation failed, add a data field to be displayed
					// later.
					$hashValidated = "INVALID HASH";
				}
			} else {
				// Secure Hash was not validated, add a data field to be displayed later.
				$hashValidated = "INVALID HASH";
			}

			return $hashValidated;
		}

		function generate_onepayUS_url($order_id)
		{
			// This is from the class provided by OnePAY. Not advisable to mess.
			global $woocommerce;


			$order = new WC_Order($order_id);

			$order_items = $order->get_items();

			$return_url = $this->get_return_url($order);
			/////////////////////////////////////////////////////////////

			$amount = $order->get_total();

			//exchange rate
			$amount_rate = $this->getRate();

			$amount = round($amount * $amount_rate, 0);

			$oder_info = $order_id;
			$checkouturl = $this->buildCheckoutUrl($amount, $oder_info, $return_url);


			return $checkouturl;
		}

		function showMessage($content)
		{
			return '<div class="box ' . $this->msg['class'] . '-box">' . $this->msg['message'] . '</div>' . $content;
		}




		public function buildCheckoutUrl($amount, $oder_info, $return_url)
		{

			///////////////////////////////////////////////////////////////////////
			////////////// ONEPAY CODE /////////////////////
			// *********************
			// START OF MAIN PROGRAM
			// *********************
			$url_return = $return_url; // Fixed - url return
			/////////////////////////////////////////////////////////////////////////////

			///////////////////////// URL payment ////////////////////////////////
			$vpcURL = $this->settings['onepay_url']; // Test mode
			//$vpcURL = "http://mtf.onepay.vn/vpcpay/vpcpay.op"; // Live mode
			//////////////////////// OnePAY ACC //////////////////////////////////
			$Merchant_ID = $this->settings['merchant_id']; // Fixed, provide by OnePAY
			$Access_Code = $this->settings['merchant_access_code']; // Fixed, provide by OnePAY
			$SECURE_SECRET = $this->settings['secure_secret']; // Fixed, provide by OnePAY
			///////////////////////////////////////////////////////////////////////
			$lang = get_bloginfo("language"); // get current language of website
			//print $lang;exit;
			if ($lang == "vi") {
				$vpc_Locale = "vn";
			} else
				$vpc_Locale = "en";
			// Define Constants
			// ----------------
			// This is secret for encoding the MD5 hash
			// This secret will vary from merchant to merchant
			// To not create a secure hash, let SECURE_SECRET be an empty string - ""
			// $SECURE_SECRET = "secure-hash-secret";
			// Khóa bí mật - được cấp bởi OnePAY
			$op_var = array(
				'AgainLink' => 'onepay.vn',
				'Title' => 'onepay.vn',
				'vpc_Locale' => $vpc_Locale, //ngôn ngữ hiển thị trên cổng thanh toán
				'vpc_Version' => '2', //Phiên bản modul
				'vpc_Command' => 'pay', //tên hàm
				//'vpc_Currency'			=>  'VND',
				'vpc_Merchant' => $Merchant_ID, //mã đơn vị(OP cung cấp)
				'vpc_AccessCode' => $Access_Code, //mã truy nhập cổng thanh toán (OP cung cấp)
				'vpc_MerchTxnRef' => date('YmdHis') . rand(), //ID giao dịch (duy nhất)
				'vpc_OrderInfo' => $oder_info, //mã đơn hàng
				'vpc_Amount' => $amount * 100, //số tiền thanh toán
				'vpc_ReturnURL' => $url_return,	//url nhận kết quả trả về từ OnePAY
				'vpc_TicketNo' => $_SERVER["REMOTE_ADDR"] //ip khách hàng
			);
			if (self::$ipn_enabled == 'yes') {
				$op_var['vpc_CallbackURL'] = $this->get_onepay_ipn_url();
			}
			// add the start of the vpcURL querystring parameters
			// *****************************Lấy giá trị url cổng thanh toán*****************************

			$vpcURL .= "?";
			// Remove the Virtual Payment Client URL from the parameter hash as we
			// do not want to send these fields to the Virtual Payment Client.
			// bỏ giá trị url và nút submit ra khỏi mảng dữ liệu
			//unset($arr_variables["virtualPaymentClientURL"]);
			//unset($arr_variables["SubButL"]);

			//$stringHashData = $SECURE_SECRET; *****************************Khởi tạo chuỗi dữ liệu mã hóa trống*****************************
			$stringHashData = "";
			// sắp xếp dữ liệu theo thứ tự a-z trước khi nối lại
			// arrange array data a-z before make a hash

			ksort($op_var);

			// set a parameter to show the first pair in the URL
			// đặt tham số đếm = 0
			$appendAmp = 0;


			foreach ($op_var as $key => $value) {

				// create the md5 input and URL leaving out any fields that have no value
				// tạo chuỗi đầu dữ liệu những tham số có dữ liệu
				if (strlen($value) > 0) {
					// this ensures the first paramter of the URL is preceded by the '?' char
					if ($appendAmp == 0) {
						$vpcURL .= urlencode($key) . '=' . urlencode($value);
						$appendAmp = 1;
					} else {
						$vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
					}
					//$stringHashData .= $value; *****************************sử dụng cả tên và giá trị tham số để mã hóa*****************************
					if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
						$stringHashData .= $key . "=" . $value . "&";
					}
				}
			}

			//*****************************xóa ký tự & ở thừa ở cuối chuỗi dữ liệu mã hóa*****************************
			$stringHashData = rtrim($stringHashData, "&");

			//print_r($stringHashData);
			// Create the secure hash and append it to the Virtual Payment Client Data if
			// the merchant secret has been provided.
			// thêm giá trị chuỗi mã hóa dữ liệu được tạo ra ở trên vào cuối url
			if (strlen($SECURE_SECRET) > 0) {
				//$vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($stringHashData));
				// *****************************Thay hàm mã hóa dữ liệu*****************************
				$vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)));
			}
			$this->log('get_pay_url - http_args: ' . print_r($op_var, true));

			return $vpcURL;
		}

		function null2unknown($data)
		{
			if ($data == "") {
				return "No Value Returned";
			} else {
				return $data;
			}
		}

		// @return String containing the appropriate description
		//
		function getResponseDescription($responseCode)
		{

			switch ($responseCode) {
				case "0":
					$result = "Giao dịch thành công";
					break;
				case "?":
					$result = "Transaction status is unknown";
					break;
				case "1":
					$result = "Giao dịch không thành công,Ngân hàng phát hành thẻ không cấp phép cho giao dịch hoặc thẻ chưa được kích hoạt dịch vụ thanh toán trên Internet. Vui lòng liên hệ ngân hàng theo số điện thoại sau mặt thẻ được hỗ trợ chi tiết.";
					break;
				case "2":
					$result = "Giao dịch không thành công,Ngân hàng phát hành thẻ từ chối cấp phép cho giao dịch. Vui lòng liên hệ ngân hàng theo số điện thoại sau mặt thẻ để biết chính xác nguyên nhân Ngân hàng từ chối.";
					break;
				case "3":
					$result = "Giao dịch không thành công, Cổng thanh toán không nhận được kết quả trả về từ ngân hàng phát hành thẻ. Vui lòng liên hệ với ngân hàng theo số điện thoại sau mặt thẻ để biết chính xác trạng thái giao dịch và thực hiện thanh toán lại ";
					break;
				case "4":
					$result = "Giao dịch không thành công do thẻ hết hạn sử dụng hoặc nhập sai thông tin tháng/ năm hết hạn của thẻ. Vui lòng kiểm tra lại thông tin và thanh toán lại";
					break;
				case "5":
					$result = "Giao dịch không thành công,Thẻ không đủ hạn mức hoặc tài khoản không đủ số dư để thanh toán. Vui lòng kiểm tra lại thông tin và thanh toán lại";
					break;
				case "6":
					$result = "Giao dịch không thành công, Quá trình xử lý giao dịch phát sinh lỗi từ ngân hàng phát hành thẻ. Vui lòng liên hệ ngân hàng theo số điện thoại sau mặt thẻ được hỗ trợ chi tiết.";
					break;
				case "7":
					$result = "Giao dịch không thành công,Đã có lỗi phát sinh trong quá trình xử lý giao dịch. Vui lòng thực hiện thanh toán lại.";
					break;
				case "8":
					$result = "Giao dịch không thành công. Số thẻ không đúng. Vui lòng kiểm tra và thực hiện thanh toán lại ";
					break;
				case "9":
					$result = "Giao dịch không thành công. Tên chủ thẻ không đúng. Vui lòng kiểm tra và thực hiện thanh toán lại";
					break;
				case "10":
					$result = "Giao dịch không thành công. Thẻ hết hạn/Thẻ bị khóa. Vui lòng kiểm tra và thực hiện thanh toán lại ";
					break;
				case "11":
					$result = "Giao dịch không thành công. Thẻ chưa đăng ký sử dụng dịch vụ thanh toán trên Internet. Vui lòng liên hê ngân hàng theo số điện thoại sau mặt thẻ để được hỗ trợ";
					break;
				case "12":
					$result = "Giao dịch không thành công. Ngày phát hành/Hết hạn không đúng. Vui lòng kiểm tra và thực hiện thanh toán lại";
					break;
				case "13":
					$result = "Giao dịch không thành công. thẻ/ tài khoản đã vượt quá hạn mức thanh toán. Vui lòng kiểm tra và thực hiện thanh toán lại ";
					break;
				case "21":
					$result = "Giao dịch không thành công. Số tiền không đủ để thanh toán. Vui lòng kiểm tra và thực hiện thanh toán lại";
					break;
				case "22":
					$result = "Giao dịch không thành công. Thông tin tài khoản không đúng. Vui lòng kiểm tra và thực hiện thanh toán lại ";
					break;
				case "23":
					$result = "Giao dịch không thành công. Tài khoản bị khóa.Vui lòng liên hê ngân hàng theo số điện thoại sau mặt thẻ để được hỗ trợ";
					break;
				case "24":
					$result = "Giao dịch không thành công. Thông tin thẻ không đúng. Vui lòng kiểm tra và thực hiện thanh toán lại";
					break;
				case "25":
					$result = "Giao dịch không thành công. OTP không đúng.Vui lòng kiểm tra và thực hiện thanh toán lại ";
					break;
				case "253":
					$result = "Giao dịch không thành công. Quá thời gian thanh toán. Vui lòng thực hiện thanh toán lại";
					break;
				case "99":
					$result = "Giao dịch không thành công. Người sử dụng hủy giao dịch";
					break;
				case "B":
					$result = "Giao dịch không thành công do không xác thực được 3D-Secure. Vui lòng liên hệ ngân hàng theo số điện thoại sau mặt thẻ được hỗ trợ chi tiết.";
					break;
				case "E":
					$result = "Giao dịch không thành công do nhập sai CSC (Card Security Card) hoặc ngân hàng từ chối cấp phép cho giao dịch. Vui lòng liên hệ ngân hàng theo số điện thoại sau mặt thẻ được hỗ trợ chi tiết.";
					break;
				case "F":
					$result = "Giao dịch không thành công do không xác thực được 3D-Secure. Vui lòng liên hệ ngân hàng theo số điện thoại sau mặt thẻ được hỗ trợ chi tiết";
					break;
				case "Z":
					$result = "Giao dịch của bạn bị từ chối. Vui lòng liên hệ Đơn vị chấp nhận thẻ để được hỗ trợ.";
					break;
				default:
					$result = "Payment fail";
			}
			return $result;
		}

		public static function log($message, $level = 'info')
		{
			if (self::$log_enabled == 'yes') {
				if (empty(self::$log)) {
					self::$log = wc_get_logger();
				}
				self::$log->log($level, $message, array('source' => get_called_class()));
			}
		}


		public function process_onepay_ipn($args)
		{

			$SECURE_SECRET = $this->settings['secure_secret'];
			$is_secure = $this->validateHash($_GET, $SECURE_SECRET) == "CORRECT";

			// Process the data
			if ($is_secure) {

				$vpc_OrderInfo = $args['vpc_OrderInfo'];
				$vpc_TxnResponseCode = $args['vpc_TxnResponseCode'];

				$order = wc_get_order($vpc_OrderInfo);

				// $this->log( 'process_onepay_ipn - order_id: ' . $vpc_OrderInfo);
				// $this->log( 'process_onepay_ipn - order: ' . print_r($order, true));


				// Add the order note for the reference
				$order_note = get_called_class() . sprintf(
					__(' Gateway Info | Code: %1$s | Message: %2$s ', 'monepayus'),
					$vpc_TxnResponseCode,
					$this->getResponseDescription($vpc_TxnResponseCode),
				);
				$order->add_order_note($order_note);

				// Log data
				$message_log = sprintf('process_onepay_ipn - Order ID: %1$s - Order Note: %2$s - http_args: %3$s', $vpc_OrderInfo, $order_note, print_r($args, true));
				$this->log($message_log);

				// Do action for the order based on the response code from OnePay
				// This is an intentional DRY switch - refer to #DRY_vpc_TxnResponseCode below
				switch ($vpc_TxnResponseCode) {
					case '0':
						// If the payment is successful, update the order
						$order->payment_complete();
						$order->save();
						break;
					case '99':
						// If the user cancels payment, cancel the order
						$order->update_status('cancelled');
						$order->save();
						break;
					default:
						// For other cases, do nothing. By default, the order status is still "Pending Payment"
						break;
				}

				exit('responsecode=1&desc=confirm-success');
			} else {
				exit('responsecode=0&desc=confirm-success');
			}
		}

		function getRate()
		{
			$vcbRate = "0";
			$currency = get_woocommerce_currency();
			if ("VND" === $currency) {
				$vcbRate = "1";
			} else if ("USD" === $currency || "EUR" === $currency || "JPY" === $currency) {
				$exratesXML = "https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx";
				$args = array(
					'timeout' => 15, // Timeout sau 15 giây
				);
				$response = wp_remote_get($exratesXML, $args);
				if (is_wp_error($response)) {
					echo 'Lỗi khi lấy dữ liệu từ Vietcombank';
					return '0';
				}
				$body = wp_remote_retrieve_body($response);
				// Load xml file
				$xml = simplexml_load_string($body);
				if ($xml) { // if link live
					// Get attr of tag
					foreach ($xml->Exrate as $Exrate) {
						$CurrencyCode = $Exrate["CurrencyCode"];
						if ($CurrencyCode == $currency) {
							$value = $Exrate["Sell"];
						}
					}
					//return $value;
					$vcbRate = str_replace(',', '', $value);
				} else {
					$vcbRate = '0';
				}
			}

			$exchangeRateConfig = strval($this->settings['exchange_rate_config'] ?? '');
			if ($exchangeRateConfig == null || strlen(trim($exchangeRateConfig)) == 0) {
				return $vcbRate;
			} else {
				return $exchangeRateConfig;
			}
		}

		public function getExchangeRateDesc()
		{
			//Exchange Rate config condition
			$isVND = true;
			$isForeignCurrency = true;
			$currency = get_woocommerce_currency();

			$vcbRate = "0";

			if ("VND" === $currency) {
				$vcbRate = "1";
			} else if ("USD" === $currency || "EUR" === $currency || "JPY" === $currency) {
				$exratesXML = "https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx";
				// Load xml file
				$args = array(
					'timeout' => 15, // Timeout sau 15 giây
				);
				$response = wp_remote_get($exratesXML, $args);
				if (is_wp_error($response)) {
					echo 'Lỗi khi lấy dữ liệu từ Vietcombank';
					return '0';
				}
				$body = wp_remote_retrieve_body($response);
				// Load xml file
				$xml = simplexml_load_string($body);
				if ($xml) { // if link live
					// Get attr of tag
					foreach ($xml->Exrate as $Exrate) {
						$CurrencyCode = $Exrate["CurrencyCode"];
						if ($CurrencyCode == $currency) {
							$value = $Exrate["Sell"];
						}
					}
					//return $value;
					$vcbRate = str_replace(',', '', $value);
				} else {
					$vcbRate = '0';
				}
			}

			if ("VND" === $currency) {
				$isVND = true;
			} else {
				$isVND = false;
			}
			if ("USD" === $currency || "EUR" === $currency || "JPY" === $currency) {
				$isForeignCurrency = true;
			} else {
				$isForeignCurrency = false;
			}

			if ($isVND == true && $isForeignCurrency == false) {
				return sprintf('<span style="color: red;">' . __('Do not configure "Exchange Rate Config", Current currency is', 'monepayus') . '</span> <code>'
					. $currency . '</code>');
			} else if ($isVND == false && $isForeignCurrency == true) {
				return sprintf(__('If you do not configure "Exchange Rate Config, the system will automatically get rate: ', 'monepayus') . '<code>'
					. "1 " . $currency . " = " . $vcbRate . " VND" . '</code>');
			} else if ($isVND == false && $isForeignCurrency == false) {
				return sprintf(__('The system dose not support automatically get rate, please configure "Exchange Rate Config", current currency is: ', 'monepayus') . '<code>'
					. $currency . '</code>');
			} else {
				return sprintf('<span style="color: red;">' . __('Error system: INTERNAL_SERVER_ERROR', 'monepayus') . '</span>');
			}
		}
	}




	function woocommerce_add_onepay_gateway($methods)
	{
		$methods[] = 'WC_onepay';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'woocommerce_add_onepay_gateway');
	
	// Hỗ trợ Block checkout: Đảm bảo OnePay được include trong Block checkout
	add_filter('woocommerce_gateway_title', function($title, $gateway_id) {
		if ($gateway_id === 'onepay') {
			// Log để debug
			$log_file = WP_CONTENT_DIR . '/woo.log';
			$log_msg = "[ONEPAY GATEWAY] Filter woocommerce_gateway_title được gọi cho onepay - " . date('Y-m-d H:i:s') . "\n";
			@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
		}
		return $title;
	}, 10, 2);
	
	// Filter để đảm bảo OnePay có trong available payment gateways cho Block checkout
	add_filter('woocommerce_available_payment_gateways', function($available_gateways) {
		$log_file = WP_CONTENT_DIR . '/woo.log';
		$log_msg = "\n[BLOCK CHECKOUT FILTER] woocommerce_available_payment_gateways - " . date('Y-m-d H:i:s') . "\n";
		$log_msg .= "Available gateways: " . implode(', ', array_keys($available_gateways)) . "\n";
		
		// Kiểm tra xem OnePay có trong available_gateways không
		if (!isset($available_gateways['onepay'])) {
			$all_gateways = WC()->payment_gateways()->payment_gateways();
			if (isset($all_gateways['onepay'])) {
				$onepay = $all_gateways['onepay'];
				if ($onepay->is_available()) {
					$log_msg .= "⚠️ OnePay có trong all_gateways và is_available()=TRUE nhưng KHÔNG có trong available_gateways!\n";
					$log_msg .= "Thêm OnePay vào available_gateways...\n";
					$available_gateways['onepay'] = $onepay;
				}
			}
		} else {
			$log_msg .= "✓ OnePay đã có trong available_gateways\n";
		}
		
		$log_msg .= "Sau filter - Available gateways: " . implode(', ', array_keys($available_gateways)) . "\n\n";
		@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
		
		return $available_gateways;
	}, 999); // Priority cao để chạy sau các filter khác
}

// Đăng ký integration với WooCommerce Blocks
add_action( 'woocommerce_blocks_loaded', function() {
	if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
		return;
	}
	
	// Định nghĩa class integration khi Blocks đã load
	class WC_Onepay_Blocks_Integration extends \Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType {
		protected $name = 'onepay';
		
		public function initialize() {
			$this->settings = get_option( 'woocommerce_onepay_settings', array() );
			
			// Log để debug
			$log_file = WP_CONTENT_DIR . '/woo.log';
			$log_msg = "[BLOCKS INTEGRATION] initialize() called - Settings loaded: " . (empty($this->settings) ? 'NO' : 'YES') . "\n";
			if (!empty($this->settings['enabled'])) {
				$log_msg .= "Enabled setting: " . $this->settings['enabled'] . "\n";
			}
			@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
		}
		
		public function is_active() {
			$is_active = ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'];
			
			// Log để debug
			$log_file = WP_CONTENT_DIR . '/woo.log';
			$log_msg = "[BLOCKS INTEGRATION] is_active() called - enabled: " . (isset($this->settings['enabled']) ? $this->settings['enabled'] : 'NOT SET') . ", result: " . ($is_active ? 'TRUE' : 'FALSE') . "\n";
			@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
			
			return $is_active;
		}
		
		public function get_payment_method_script_handles() {
			// Không cần custom script, dùng default của WooCommerce Blocks
			return array();
		}
		
		public function get_payment_method_data() {
			$log_file = WP_CONTENT_DIR . '/woo.log';
			$log_msg = "\n[BLOCKS INTEGRATION] get_payment_method_data() called\n";
			
			$gateway = WC()->payment_gateways()->payment_gateways()['onepay'] ?? null;
			
			if ( ! $gateway ) {
				$log_msg .= "❌ Gateway 'onepay' NOT FOUND in payment_gateways()\n";
				$all_gateways = WC()->payment_gateways()->payment_gateways();
				$log_msg .= "Available gateway IDs: " . implode(', ', array_keys($all_gateways)) . "\n";
				@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
				return array();
			}
			
			$title = $gateway->get_title();
			$description = $gateway->get_description();
			$supports = $this->get_supported_features();
			$icon = $gateway->get_icon();
			
			$data = array(
				'title'       => $title,
				'description' => $description,
				'supports'    => $supports,
				'icon'        => $icon,
			);
			
			$log_msg .= "Gateway found - Title: {$title}\n";
			$log_msg .= "Description: {$description}\n";
			$log_msg .= "Supports: " . implode(', ', $supports) . "\n";
			$log_msg .= "Icon: " . (is_string($icon) ? $icon : 'HTML') . "\n";
			$log_msg .= "Returning data: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n\n";
			@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
			
			return $data;
		}
		
		public function get_supported_features() {
			$gateway = WC()->payment_gateways()->payment_gateways()['onepay'] ?? null;
			
			if ( ! $gateway ) {
				return array();
			}
			
			$supports = array();
			
			// Kiểm tra các features được hỗ trợ
			if ( method_exists( $gateway, 'supports' ) ) {
				$features = array( 'products' );
				foreach ( $features as $feature ) {
					if ( $gateway->supports( $feature ) ) {
						$supports[] = $feature;
					}
				}
			} else {
				$supports = array( 'products' );
			}
			
			return $supports;
		}
	}
	
	add_action(
		'woocommerce_blocks_payment_method_type_registration',
		function( \Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
			$integration = new WC_Onepay_Blocks_Integration();
			
			// Gọi initialize() để load settings
			$integration->initialize();
			
			// Kiểm tra is_active() trước khi đăng ký
			$is_active = $integration->is_active();
			$log_file = WP_CONTENT_DIR . '/woo.log';
			$log_msg = "[BLOCKS INTEGRATION] Before register - is_active(): " . ($is_active ? 'TRUE' : 'FALSE') . "\n";
			
			$payment_method_registry->register( $integration );
			
			$log_msg .= "[BLOCKS INTEGRATION] OnePay Blocks Integration đã được đăng ký - " . date('Y-m-d H:i:s') . "\n";
			@file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
		}
	);
});
