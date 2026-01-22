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
?>
<script type="text/javascript">
(function() {
	'use strict';
	
	// Đợi DOM và WooCommerce ready
	if (typeof jQuery !== 'undefined' && typeof wc_checkout_params !== 'undefined') {
		jQuery(document).ready(function($) {
			// Log khi trang load
			console.log('=== CHECKOUT PAYMENT GATEWAYS DEBUG ===');
			
			// Lấy tất cả payment methods từ DOM
			var paymentMethods = $('.wc_payment_methods .wc_payment_method');
			console.log('Payment methods found in DOM: ' + paymentMethods.length);
			
			// Log từng payment method
			paymentMethods.each(function(index) {
				var $method = $(this);
				var methodId = $method.find('input[type="radio"]').attr('id');
				var methodTitle = $method.find('label').text().trim();
				var isChecked = $method.find('input[type="radio"]').is(':checked');
				
				console.log('Payment Method #' + (index + 1) + ':');
				console.log('  - ID: ' + methodId);
				console.log('  - Title: ' + methodTitle);
				console.log('  - Checked: ' + isChecked);
				console.log('  - Element:', $method[0]);
			});
			
			// Kiểm tra OnePay cụ thể
			var onepayMethod = $('#payment_method_onepay');
			if (onepayMethod.length > 0) {
				console.log('✅ OnePay Gateway FOUND in DOM');
				console.log('  - Element:', onepayMethod[0]);
				console.log('  - Parent:', onepayMethod.closest('.wc_payment_method')[0]);
				console.log('  - Visible: ' + onepayMethod.closest('.wc_payment_method').is(':visible'));
			} else {
				console.log('❌ OnePay Gateway NOT FOUND in DOM');
				console.log('  - Searching for any input with "onepay" in ID...');
				var anyOnepay = $('input[id*="onepay"], input[name*="onepay"]');
				if (anyOnepay.length > 0) {
					console.log('  - Found ' + anyOnepay.length + ' element(s) with "onepay" in ID/name');
					anyOnepay.each(function() {
						console.log('    - Element:', this);
					});
				}
			}
			
			// Log khi payment methods được update (AJAX)
			$(document.body).on('updated_checkout', function() {
				console.log('--- Checkout Updated (AJAX) ---');
				var updatedMethods = $('.wc_payment_methods .wc_payment_method');
				console.log('Payment methods after update: ' + updatedMethods.length);
				
				updatedMethods.each(function(index) {
					var $method = $(this);
					var methodId = $method.find('input[type="radio"]').attr('id');
					console.log('  Method #' + (index + 1) + ': ' + methodId);
				});
			});
			
			// Log khi payment method được chọn
			$('.wc_payment_methods input[type="radio"]').on('change', function() {
				console.log('Payment method changed to: ' + $(this).attr('id'));
			});
			
			console.log('=====================================');
		});
	} else {
		console.warn('WooCommerce checkout scripts not loaded yet');
		// Retry sau 1 giây
		setTimeout(function() {
			if (typeof jQuery !== 'undefined') {
				jQuery(document).ready(function($) {
					console.log('Retry: Checking payment methods...');
					var paymentMethods = $('.wc_payment_methods .wc_payment_method');
					console.log('Payment methods: ' + paymentMethods.length);
				});
			}
		}, 1000);
	}
})();
</script>
<?php
