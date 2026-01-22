<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}

// FIX: Lấy available gateways nếu biến chưa được truyền vào (tương thích với WooCommerce mới)
if ( ! isset( $available_gateways ) ) {
	$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
}

// DEBUG: Debug chi tiết ở trang checkout
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	$all_gateways = WC()->payment_gateways()->payment_gateways();
	$available_gateway_ids = array_keys( $available_gateways );
	$all_gateway_ids = array_keys( $all_gateways );
	
	error_log( '=== CHECKOUT PAYMENT TEMPLATE DEBUG ===' );
	error_log( 'All registered gateways: ' . implode( ', ', $all_gateway_ids ) );
	error_log( 'Available gateways count: ' . count( $available_gateways ) );
	error_log( 'Available gateway IDs: ' . implode( ', ', $available_gateway_ids ) );
	
	// Kiểm tra OnePay cụ thể
	if ( isset( $all_gateways['onepay'] ) ) {
		$onepay = $all_gateways['onepay'];
		error_log( 'OnePay Gateway Found:' );
		error_log( '  - ID: ' . $onepay->id );
		error_log( '  - Enabled: ' . ( isset( $onepay->enabled ) ? $onepay->enabled : 'NOT SET' ) );
		error_log( '  - is_available(): ' . ( $onepay->is_available() ? 'TRUE' : 'FALSE' ) );
		error_log( '  - In available_gateways: ' . ( isset( $available_gateways['onepay'] ) ? 'YES' : 'NO' ) );
	} else {
		error_log( 'OnePay Gateway NOT FOUND in registered gateways!' );
	}
	
	error_log( 'Cart needs payment: ' . ( WC()->cart->needs_payment() ? 'YES' : 'NO' ) );
	error_log( '======================================' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'immigro' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'immigro' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'immigro' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'immigro' ); ?>"><?php esc_html_e( 'Update totals', 'immigro' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php
		// FIX: Lấy order button text nếu biến chưa được truyền vào
		if ( ! isset( $order_button_text ) ) {
			$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );
		}
		echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine
		?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}

// DEBUG: JavaScript để log payment gateways vào console
// Pass PHP data to JavaScript
$debug_data = array(
	'available_gateways' => array_keys( $available_gateways ),
	'gateways_count' => count( $available_gateways ),
	'all_gateways' => array(),
);
if ( isset( $all_gateways ) ) {
	foreach ( $all_gateways as $id => $gateway ) {
		$debug_data['all_gateways'][ $id ] = array(
			'enabled' => isset( $gateway->enabled ) ? $gateway->enabled : 'NOT SET',
			'available' => isset( $available_gateways[ $id ] ) ? 'YES' : 'NO',
		);
	}
}
?>
<script type="text/javascript">
(function() {
	'use strict';
	
	// Log ngay từ đầu để biết script đã chạy
	console.log('🔍 Payment Gateway Debug Script Loaded');
	
	// PHP data
	var phpDebugData = <?php echo json_encode( $debug_data ); ?>;
	console.log('PHP Debug Data:', phpDebugData);
	
	// Function để check payment methods
	function checkPaymentMethods() {
		console.log('=== CHECKOUT PAYMENT GATEWAYS DEBUG ===');
		console.log('Available gateways from PHP: ' + phpDebugData.gateways_count);
		console.log('Gateway IDs from PHP:', phpDebugData.available_gateways);
		
		// Check OnePay specifically
		if (phpDebugData.all_gateways.onepay) {
			console.log('OnePay Gateway Info from PHP:');
			console.log('  - Enabled: ' + phpDebugData.all_gateways.onepay.enabled);
			console.log('  - In available_gateways: ' + phpDebugData.all_gateways.onepay.available);
		} else {
			console.log('❌ OnePay NOT in all_gateways from PHP');
		}
		
		// Check DOM (classic checkout)
		if (typeof document !== 'undefined') {
			var paymentMethods = document.querySelectorAll('.wc_payment_methods .wc_payment_method');
			console.log('Payment methods in DOM (classic): ' + paymentMethods.length);
			
			if (paymentMethods.length > 0) {
				paymentMethods.forEach(function(method, index) {
					var radio = method.querySelector('input[type="radio"]');
					var label = method.querySelector('label');
					if (radio) {
						console.log('Payment Method #' + (index + 1) + ':');
						console.log('  - ID: ' + radio.id);
						console.log('  - Name: ' + radio.name);
						console.log('  - Title: ' + (label ? label.textContent.trim() : 'N/A'));
						console.log('  - Checked: ' + radio.checked);
					}
				});
			}
			
			// Check OnePay in DOM
			var onepayRadio = document.getElementById('payment_method_onepay');
			if (onepayRadio) {
				console.log('✅ OnePay Gateway FOUND in DOM');
				console.log('  - Element:', onepayRadio);
				var onepayMethod = onepayRadio.closest('.wc_payment_method');
				if (onepayMethod) {
					console.log('  - Visible: ' + (onepayMethod.offsetParent !== null));
					console.log('  - Display: ' + window.getComputedStyle(onepayMethod).display);
				}
			} else {
				console.log('❌ OnePay Gateway NOT FOUND in DOM');
				// Search for any onepay elements
				var anyOnepay = document.querySelectorAll('[id*="onepay"], [name*="onepay"], [class*="onepay"]');
				if (anyOnepay.length > 0) {
					console.log('  - Found ' + anyOnepay.length + ' element(s) with "onepay"');
					anyOnepay.forEach(function(el) {
						console.log('    - Tag: ' + el.tagName + ', ID: ' + el.id + ', Class: ' + el.className);
					});
				}
			}
			
			// Check Block checkout
			var blockCheckout = document.querySelector('.wc-block-checkout, .wp-block-woocommerce-checkout');
			if (blockCheckout) {
				console.log('⚠️ Block Checkout Detected');
				var blockPaymentMethods = blockCheckout.querySelectorAll('[data-payment-method-id]');
				console.log('Block payment methods: ' + blockPaymentMethods.length);
				blockPaymentMethods.forEach(function(method) {
					console.log('  - Method ID: ' + method.getAttribute('data-payment-method-id'));
				});
			}
		}
		
		console.log('=====================================');
	}
	
	// Run immediately
	checkPaymentMethods();
	
	// Run when DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', checkPaymentMethods);
	} else {
		// DOM already ready
		setTimeout(checkPaymentMethods, 100);
	}
	
	// Run with jQuery if available
	if (typeof jQuery !== 'undefined') {
		jQuery(document).ready(function($) {
			console.log('jQuery Ready - Rechecking...');
			checkPaymentMethods();
			
			// Listen for checkout updates
			$(document.body).on('updated_checkout', function() {
				console.log('--- Checkout Updated (AJAX) ---');
				setTimeout(checkPaymentMethods, 100);
			});
			
			// Listen for payment method changes
			$(document).on('change', '.wc_payment_methods input[type="radio"]', function() {
				console.log('Payment method changed to: ' + $(this).attr('id'));
			});
		});
	}
	
	// Retry after 2 seconds
	setTimeout(function() {
		console.log('--- Final Check (2s delay) ---');
		checkPaymentMethods();
	}, 2000);
	
})();
</script>
<?php
