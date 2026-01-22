<?php
/*
Plugin Name: onepay Payment Gateway For WooCommerce
Plugin URI: https://github.com/onepay-srilanka/onepay-woocommerce
Description: onepay Payment Gateway allows you to accept payment on your Woocommerce store via Visa, MasterCard, AMEX, & Lanka QR services.
Version: 1.1.3
Author: onepay
Author URI: https://www.onepay.lk
License: GPLv3 or later
WC tested up to: 8.0
*/
// Include the block registration file
require plugin_dir_path(__FILE__) . 'blocks/class-onepay-block-loader.php';

add_action('plugins_loaded', 'woocommerce_gateway_onepay_init', 0);
define('onepay_IMG', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets/img/');
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
function woocommerce_gateway_onepay_init() {
	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
 	 * Gateway class
 	 */
	class WC_Gateway_onepay extends WC_Payment_Gateway {

	     /**
         * Make __construct()
         **/	
		public function __construct(){
			
			$this->id 					= 'onepay'; // ID for WC to associate the gateway values
            $this -> icon = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__ )) . '/assets/img/logo_onepay.png';
			$this->method_title 		= 'onepay'; // Gateway Title as seen in Admin Dashboad
			$this->method_description	= 'The Digital Payment Service Provider of Sri Lanka'; // Gateway Description as seen in Admin Dashboad
			$this->has_fields 			= false; // Inform WC if any fileds have to be displayed to the visitor in Frontend 
			
			$this->init_form_fields();	// defines your settings to WC
			$this->init_settings();		// loads the Gateway settings into variables for WC
						
			$this->liveurl 			= 'https://merchant-api-live-v2.onepay.lk/api/ipg/gateway/request-transaction/';



			$this->title 			= $this->settings['title']; // Title as displayed on Frontend
			$this->description 		= $this->settings['description']; // Description as displayed on Frontend

			$this->salt_string 		= $this->settings['salt_string'];
			$this->app_id 		= $this->settings['app_id'];
            $this->auth_token 		    = $this->settings['auth_token'];
			$this->redirect_page	= $this->settings['redirect_page']; // Define the Redirect Page.

			
			$this->msg['message']	= '';
			$this->msg['class'] 	= '';
			

			
            add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'check_onepay_response')); //update for woocommerce >2.0

            if ( version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) ); //update for woocommerce >2.0
                 } else {
                    add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) ); // WC-1.6.6
                }
            add_action('woocommerce_receipt_onepay', array(&$this, 'receipt_page'));	
		} //END-__construct
		
        /**
         * Initiate Form Fields in the Admin Backend
         **/
		function init_form_fields(){

			$this->form_fields = array(
				// Activate the Gateway
				'enabled' => array(
					'title' 			=> __('Enable/Disable', 'onepayipg'),
					'type' 			=> 'checkbox',
					'label' 			=> __('Enable onepay', 'onepayipg'),
					'default' 		=> 'yes',
					'description' 	=> 'Show in the Payment List as a payment option'
				),
				// Title as displayed on Frontend
      			'title' => array(
					'title' 			=> __('Title', 'onepayipg'),
					'type'			=> 'text',
					'default' 		=> __('Bank card / Bank Account - OnePay', 'onepayipg'),
					'description' 	=> __('This controls the title which the user sees during checkout.', 'onepayipg'),
					'desc_tip' 		=> true
				),
				// Description as displayed on Frontend
      			'description' => array(
					'title' 			=> __('Description:', 'onepayipg'),
					'type' 			=> 'textarea',
					'default' 		=> __(' Pay by Visa, MasterCard, AMEX, or Lanka QR via onepay.', 'onepayipg'),
					'description' 	=> __('This controls the description which the user sees during checkout.', 'onepayipg'),
					'desc_tip' 		=> true
				),
				// LIVE App ID
				'app_id' => array(
					'title' 		=> __('App ID', 'onepayipg'),
					'type' 			=> 'text',
					'description' 	=> __('Your onepay App ID'),
					'desc_tip' 		=> true
				),
				// LIVE App ID
				'auth_token' => array(
					'title' 		=> __('App Token', 'onepayipg'),
					'type' 			=> 'text',
					'description' 	=> __('Your onepay App token'),
					'desc_tip' 		=> true
				),
				'salt_string' => array(
					'title' 		=> __('Hash Salt', 'onepayipg'),
					'type' 			=> 'text',
					'description' 	=> __('Your onepay Hash Salt String'),
					'desc_tip' 		=> true
				),
  				// Page for Redirecting after Transaction
      			'redirect_page' => array(
					'title' 			=> __('Return Page'),
					'type' 			=> 'select',
					'options' 		=> $this->onepay_get_pages('Select Page'),
					'description' 	=> __('Page to redirect the customer after payment', 'onepayipg'),
					'desc_tip' 		=> true
                )
			);

		} //END-init_form_fields
		
        /**
         * Admin Panel Options
         * - Show info on Admin Backend
         **/
		public function admin_options(){
			echo '<h3>'.esc_html__('onepay', 'onepayipg').'</h3>';
			echo '<p>'.esc_html__('WooCommerce Payment Plugin of onepay Payment Gateway, The Digital Payment Service Provider of Sri Lanka').'</p>';
			echo '<div style="background-color: #ffd5ba;color: #a04701;padding: 5px 20px">';
			echo '<h4><span class="dashicons dashicons-warning"></span>Important!!</h4>';
			echo '<p>If you want to enable sandbox create a development app in onepay merchant portal.</p>';
			echo '</div>';
			echo '<table class="form-table">';
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			echo '</table>';
		} //END-admin_options

        /**
         *  There are no payment fields, but we want to show the description if set.
         **/
		function payment_fields(){
			if( $this->description ) {
				echo wpautop( wptexturize( esc_attr__($this->description) ) );
			}
		} //END-payment_fields
		
        /**
         * Receipt Page
         **/


		function receipt_page($order){
			echo '<p><strong>' . esc_html__('Thank you for your order.', 'onepayipg').'</strong><br/>' . esc_html__('The payment page will open soon.', 'onepay').'</p>';
			echo wp_kses_normalize_entities($this->generate_onepay_form($order));
		} //END-receipt_page
    
        /**
         * Generate button link
         **/
		function generate_onepay_form($order_id){
			global $woocommerce;
			$order = wc_get_order( $order_id );

			// Redirect URL
			if ( $this->redirect_page == '' || $this->redirect_page == 0 ) {
				$redirect_url = get_site_url() . "/";
			} else {
				$redirect_url = get_permalink( $this->redirect_page );
			}

			$redirect_url .= 'wc-api/WC_Gateway_onepay';

			// Redirect URL : For WooCoomerce 2.0
			if ( version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
				$notify_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );
			}

   
			

			$onepay_args = array(
				'transaction_redirect_url' => esc_url_raw($redirect_url),
                'customer_first_name' => sanitize_text_field($order -> get_billing_first_name()),
                'customer_last_name' => sanitize_text_field($order -> get_billing_last_name()),
                'customer_email' => sanitize_email($order -> get_billing_email()),
                'customer_phone_number' => sanitize_text_field($order -> get_billing_phone()),
                'reference' => sanitize_text_field($order_id),
                'amount' => number_format(floatval($order -> get_total()), 2, '.', ''),
				'app_id' => sanitize_text_field($this->app_id),
				'is_sdk' => 1,
				'sdk_type' => "woocommerce",
				'currency' => get_woocommerce_currency(),
				'authorization' => sanitize_text_field($this->auth_token)
			);

			$hash_args = array(
                'customer_email' => sanitize_email($order -> get_billing_email()),
                'reference' => sanitize_text_field(strval($order_id)),
				'app_id' => sanitize_text_field($this->app_id),
				'is_sdk' => "1",
				'sdk_type' => "woocommerce",
				'authorization' => sanitize_text_field($this->auth_token),
				'amount' => number_format(floatval($order -> get_total()), 2, '.', ''),

			);
			$result_body = json_encode($hash_args,JSON_UNESCAPED_SLASHES);

			$data=json_encode($hash_args,JSON_UNESCAPED_SLASHES);
			$hash_salt=sanitize_text_field($this->salt_string);
			$data .= $hash_salt;
			$hash_result = hash('sha256',$data);

            
            
			$onepay_args_array = array();

			foreach($onepay_args as $key => $value){
				$onepay_args_array[] = "<input type='hidden' name='$key' value='$value'/>";
			}

			$phone=$order->get_billing_phone();

			
			// $is_correct=preg_match('/^[0-9]+$/',$phone );
			if(sanitize_text_field($this->auth_token)=="" || sanitize_text_field($this->app_id)=="" || sanitize_text_field($this->salt_string)==""){
				$is_correct=0;
			}else{
				$is_correct=1;
			}

			$payment_url = $this->liveurl . "?hash=$hash_result";


			return '	<form action="'.$payment_url.'" method="post" id="onepay_payment_form">
  				' . implode('', $onepay_args_array) . '
				<input type="submit" class="button-alt" id="submit_onepay_payment_form" value="'.__('Pay via onepay', 'onepayipg').'" /> <a class="button cancel" href="'.$order->get_cancel_order_url().'">'.__('Cancel order &amp; restore cart', 'onepayipg').'</a>
					<script type="text/javascript">
					jQuery(function(){

						if('.$is_correct.')
						{

							jQuery("#submit_onepay_payment_form").click();

						}else{
							alert("Please add onepay payment configurations before proceeding");
						}
					

						
				
				});
					</script>
				</form>';	



		
		} 

        /**
         * Process the payment and return the result
         **/
        function process_payment($order_id){
			global $woocommerce;
            $order = wc_get_order($order_id);
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '>=' ) ) { // For WC 2.1.0
			  	$checkout_payment_url = $order->get_checkout_payment_url( true );
			} else {
				$checkout_payment_url = get_permalink( get_option ( 'woocommerce_pay_page_id' ) );
			}

			return array(
				'result' => 'success', 
				'redirect' => add_query_arg(
					'order', 
					$order->id, 
					add_query_arg(
						'key', 
						$order->get_order_key(), 
						$checkout_payment_url						
					)
				)
			);
		} //END-process_payment

        /**
         * Check for valid gateway server callback
         **/
        function check_onepay_response(){

			// Validate required parameters
			if( !isset($_REQUEST['merchant_transaction_id']) || !isset($_REQUEST['hash']) || !isset($_REQUEST['onepay_transaction_id']) ) {
				wp_die( esc_html__('Invalid request parameters.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 400 ) );
			}

			global $woocommerce;

			$order_id = absint($_REQUEST['merchant_transaction_id']);
			$hash_string = sanitize_text_field($_REQUEST['hash']);
			$onepay_transaction_id = sanitize_text_field($_REQUEST['onepay_transaction_id']);
			$status = isset($_REQUEST['status']) ? (int)$_REQUEST['status'] : 0;

			// Validate order ID
			if( empty($order_id) ) {
				wp_die( esc_html__('Invalid order ID.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 400 ) );
			}

			// Get the order
			$order = wc_get_order( $order_id );
			if( !$order ) {
				wp_die( esc_html__('Order not found.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 404 ) );
			}

			// Verify the order belongs to this payment gateway
			if( $order->get_payment_method() !== $this->id ) {
				wp_die( esc_html__('Invalid payment method for this order.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 400 ) );
			}

			// Verify salt_string is configured
			if( empty($this->salt_string) ) {
				$order->add_order_note('Security Error: Hash salt not configured.');
				wp_die( esc_html__('Payment gateway configuration error.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 500 ) );
			}

			// Build request arguments for hash verification (matching gateway callback format)
			// Gateway callback format matches: { onepay_transaction_id, merchant_transaction_id, status }
			$request_args = array(
				'onepay_transaction_id' => $onepay_transaction_id,
				'merchant_transaction_id' => (string)$order_id,
				'status' => $status
			);

			// Gateway hash calculation (matching gateway's jsonToShaValidator function):
			// JavaScript: JSON.stringify -> replace("'", "\"") -> replace(" ", "") -> remove newlines -> sha256
			// Note: JavaScript replace() without 'g' flag only replaces first occurrence, but gateway code may vary
			
			// Generate JSON (matching JavaScript JSON.stringify)
			$json_string = json_encode($request_args, JSON_UNESCAPED_SLASHES);
			
			if( empty($json_string) ) {
				$order->add_order_note('Error: Failed to generate JSON for hash verification.');
				wp_die( esc_html__('Payment verification error.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 500 ) );
			}
			
			// Clean the JSON string to match gateway's format exactly
			// Gateway JavaScript does: JSON.stringify -> replace("'", "\"") -> replace(" ", "") -> remove newlines
			$cleaned_json = $json_string;
			
			// Step 1: Replace single quotes with double quotes (JSON shouldn't have single quotes, but gateway does this)
			$cleaned_json = str_replace("'", '"', $cleaned_json);
			
			// Step 2: Remove all spaces
			$cleaned_json = str_replace(' ', '', $cleaned_json);
			
			// Step 3: Remove newlines and special characters
			// JavaScript regex: /[\r\n\x0B\x0C\u0085\u2028\u2029]+/g
			// Remove: \r, \n, \x0B, \x0C, \x85, \u2028, \u2029
			// Use a simple approach that definitely works
			$cleaned_json = str_replace(array("\r", "\n", "\x0B", "\x0C", "\x85"), '', $cleaned_json);
			// Remove Unicode separators if they exist (rare, but gateway checks for them)
			$cleaned_json = str_replace(array("\xE2\x80\xA8", "\xE2\x80\xA9"), '', $cleaned_json); // UTF-8 encoded \u2028 and \u2029
			
			// Safety check: ensure cleaned JSON is not empty
			if( empty($cleaned_json) ) {
				$order->add_order_note('Error: Cleaned JSON is empty. Original JSON: ' . $json_string);
				wp_die( esc_html__('Payment verification error.', 'onepayipg'), esc_html__('Payment Error', 'onepayipg'), array( 'response' => 500 ) );
			}
			
			// Calculate hash (gateway does NOT use salt_string for callback verification)
			$calculated_hash = hash('sha256', $cleaned_json);

			// Verify hash using timing-safe comparison
			$verified = false;
			if( !empty($hash_string) && hash_equals($calculated_hash, $hash_string) ) {
				$verified = true;
			}

			// Additional security validation (since callback hash doesn't use salt)
			if( $verified ) {
				// Verify order belongs to this gateway
				if( $order->get_payment_method() !== $this->id ) {
					$verified = false;
					$order->add_order_note('Security Error: Order payment method mismatch.');
				}
				
				// Verify order total is valid
				$order_total = floatval($order->get_total());
				if( $order_total <= 0 ) {
					$verified = false;
					$order->add_order_note('Security Error: Invalid order total detected.');
				}
			}

			// Log hash verification details for debugging (only if verification failed)
			if( !$verified ) {
				$debug_info = array(
					'received_hash' => substr($hash_string, 0, 20) . '...',
					'calculated_hash' => substr($calculated_hash, 0, 20) . '...',
					'original_json' => $json_string,
					'cleaned_json' => $cleaned_json,
					'cleaned_length' => strlen($cleaned_json),
					'request_args' => $request_args
				);
				$order->add_order_note('Hash verification failed. Debug: ' . print_r($debug_info, true));
			} else {
				// Log successful verification for audit trail
				$order->add_order_note('Hash verification successful. onepay transaction ID: ' . $onepay_transaction_id);
			}

			// Check if order is already completed
			$order_status = $order->get_status();
			if( in_array($order_status, array('completed', 'processing', 'wc-completed', 'wc-processing'), true) ) {
				// Order already processed, just redirect
				if ( ($this->redirect_page == '' || $this->redirect_page == 0) ) {
					$redirect_url = $this->get_return_url( $order );
				} else {
					$redirect_url = get_permalink( $this->redirect_page );
				}
				wp_redirect( esc_url_raw($redirect_url) );
				exit;
			}

			$trans_authorised = false;

			if( $verified ) {
				if( $status == 1 ) {
					// Payment successful
					$trans_authorised = true;
					$this->msg['message'] = esc_html__("Thank you for shopping with us. Your account has been charged and your transaction is successful.", 'onepayipg');
					$this->msg['class'] = 'woocommerce-message';
					
					if( in_array($order_status, array('processing', 'wc-processing'), true) ) {
						$order->add_order_note('onepay transaction ID: ' . $onepay_transaction_id);
					} else {
						$order->payment_complete();
						$order->add_order_note('onepay payment successful.<br/>onepay transaction ID: ' . $onepay_transaction_id);
						$woocommerce->cart->empty_cart();
					}
				} else if( $status == 0 ) {
					// Payment failed
					$trans_authorised = true;
					$this->msg['class'] = 'woocommerce-error';
					$this->msg['message'] = esc_html__("Thank you for shopping with us. However, the transaction has been failed. We will keep you informed", 'onepayipg');
					$order->add_order_note('Transaction ERROR. onepay transaction ID: ' . $onepay_transaction_id);
					$order->update_status('failed');
					$woocommerce->cart->empty_cart();
				}
			} else {
				// Hash verification failed
				$this->msg['class'] = 'error';
				$this->msg['message'] = esc_html__("Security Error. Illegal access detected.", 'onepayipg');
				$order->add_order_note('Checksum ERROR: Invalid hash verification. Received: ' . $hash_string . ', Expected: ' . $calculated_hash);
			}

			if( $trans_authorised == false && $verified == false ) {
				$order->update_status('failed');
			}

			// Display message and redirect
			echo '<p><strong>' . esc_html__('Thank you for your order.', 'onepayipg').'</strong><br/>' . esc_html__('You will be redirected soon....', 'onepay').'</p>';

			if ( ($this->redirect_page == '' || $this->redirect_page == 0) ) {
				$redirect_url = $this->get_return_url( $order );
			} else {
				$redirect_url = get_permalink( $this->redirect_page );
			}

			wp_redirect( esc_url_raw($redirect_url) );
			exit;

		} //END-check_onepay_response
		

        /**
         * Get Page list from WordPress
         **/
		function onepay_get_pages($title = false, $indent = true) {
			$wp_pages = get_pages('sort_column=menu_order');
			$page_list = array();
			if ($title) $page_list[] = $title;
			foreach ($wp_pages as $page) {
				$prefix = '';
				// show indented child pages?
				if ($indent) {
                	$has_parent = $page->post_parent;
                	while($has_parent) {
                    	$prefix .=  ' - ';
                    	$next_page = get_post($has_parent);
                    	$has_parent = $next_page->post_parent;
                	}
            	}
            	// add to page list array array
            	$page_list[$page->ID] = $prefix . $page->post_title;
        	}
        	return $page_list;
		} 

	} //END-class
	
	/**
 	* Add the Gateway to WooCommerce
 	**/
	function woocommerce_add_gateway_onepay_gateway($methods) {
		$methods[] = 'WC_Gateway_onepay';
		return $methods;
	}//END-wc_add_gateway
	
	add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_onepay_gateway' );
	
} //END-init

/**
* 'Settings' link on plugin page
**/
add_filter( 'plugin_action_links', 'onepay_add_action_plugin', 10, 5 );
function onepay_add_action_plugin( $actions, $plugin_file ) {
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {

			$settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=checkout&section=wc_gateway_onepay">' . __('Settings') . '</a>');
		
    			$actions = array_merge($settings, $actions);
			
		}
		
		return $actions;
}//END-settings_add_action_link