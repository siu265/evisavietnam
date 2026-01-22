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

// DEBUG: Log ngay đầu file để xác nhận template được load
$log_file = WP_CONTENT_DIR . '/woo.log';
$log_msg = '[PAYMENT TEMPLATE] Template payment.php được load - ' . date( 'Y-m-d H:i:s' ) . "\n";
@file_put_contents( $log_file, $log_msg, FILE_APPEND | LOCK_EX );

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}

// FIX: Lấy available gateways nếu biến chưa được truyền vào (tương thích với WooCommerce mới)
if ( ! isset( $available_gateways ) ) {
	$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
}

// DEBUG: Debug chi tiết ở trang checkout - ghi vào woo.log
$all_gateways = WC()->payment_gateways()->payment_gateways();
$available_gateway_ids = array_keys( $available_gateways );
$all_gateway_ids = array_keys( $all_gateways );

$log_file = WP_CONTENT_DIR . '/woo.log';
$log_msg = "\n=== CHECKOUT PAYMENT TEMPLATE DEBUG ===\n";
$log_msg .= 'Template: payment.php được load\n';
$log_msg .= 'All registered gateways: ' . implode( ', ', $all_gateway_ids ) . "\n";
$log_msg .= 'Available gateways count: ' . count( $available_gateways ) . "\n";
$log_msg .= 'Available gateway IDs: ' . implode( ', ', $available_gateway_ids ) . "\n";

// Kiểm tra OnePay cụ thể
if ( isset( $all_gateways['onepay'] ) ) {
	$onepay = $all_gateways['onepay'];
	$log_msg .= "OnePay Gateway Found in template:\n";
	$log_msg .= '  - ID: ' . $onepay->id . "\n";
	$log_msg .= '  - Enabled: ' . ( isset( $onepay->enabled ) ? $onepay->enabled : 'NOT SET' ) . "\n";
	$log_msg .= '  - is_available(): ' . ( $onepay->is_available() ? 'TRUE' : 'FALSE' ) . "\n";
	$log_msg .= '  - In available_gateways: ' . ( isset( $available_gateways['onepay'] ) ? 'YES' : 'NO' ) . "\n";
} else {
	$log_msg .= "OnePay Gateway NOT FOUND in registered gateways!\n";
}

$log_msg .= 'Cart needs payment: ' . ( WC()->cart->needs_payment() ? 'YES' : 'NO' ) . "\n";
$log_msg .= "======================================\n";

@file_put_contents( $log_file, $log_msg, FILE_APPEND | LOCK_EX );
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

// DEBUG: Log payment gateways vào file woo.log (luôn chạy)
$all_gateways_detail = WC()->payment_gateways()->payment_gateways();

$log_file = WP_CONTENT_DIR . '/woo.log';
$log_msg = "\n=== CHECKOUT PAYMENT GATEWAYS DEBUG (Template) ===\n";
$log_msg .= 'Available gateways count: ' . count( $available_gateways ) . "\n";
$log_msg .= 'Available gateway IDs: ' . implode( ', ', array_keys( $available_gateways ) ) . "\n\n";

// Log chi tiết từng available gateway
foreach ( $available_gateways as $gateway_id => $gateway ) {
	$log_msg .= "Gateway: {$gateway_id}\n";
	$log_msg .= '  - Title: ' . ( isset( $gateway->title ) ? $gateway->title : 'N/A' ) . "\n";
	$log_msg .= '  - Method Title: ' . ( isset( $gateway->method_title ) ? $gateway->method_title : 'N/A' ) . "\n";
	$log_msg .= '  - Enabled: ' . ( isset( $gateway->enabled ) ? $gateway->enabled : 'NOT SET' ) . "\n";
	$log_msg .= '  - is_available(): ' . ( $gateway->is_available() ? 'TRUE' : 'FALSE' ) . "\n\n";
}

// Kiểm tra OnePay cụ thể
if ( isset( $all_gateways_detail['onepay'] ) ) {
	$onepay = $all_gateways_detail['onepay'];
	$log_msg .= "OnePay Gateway Details:\n";
	$log_msg .= '  - ID: ' . $onepay->id . "\n";
	$log_msg .= '  - Enabled: ' . ( isset( $onepay->enabled ) ? $onepay->enabled : 'NOT SET' ) . "\n";
	$log_msg .= '  - is_available(): ' . ( $onepay->is_available() ? 'TRUE' : 'FALSE' ) . "\n";
	$log_msg .= '  - In available_gateways: ' . ( isset( $available_gateways['onepay'] ) ? 'YES' : 'NO' ) . "\n";
	
	if ( ! isset( $available_gateways['onepay'] ) ) {
		$log_msg .= "  ⚠️ OnePay is registered but NOT in available_gateways!\n";
		$log_msg .= '  - Checking is_available() result: ' . ( $onepay->is_available() ? 'TRUE (should be available)' : 'FALSE (not available)' ) . "\n";
	}
} else {
	$log_msg .= "❌ OnePay Gateway NOT FOUND in registered gateways!\n";
	$log_msg .= 'All registered gateway IDs: ' . implode( ', ', array_keys( $all_gateways_detail ) ) . "\n";
}

$log_msg .= "\nCart needs payment: " . ( WC()->cart->needs_payment() ? 'YES' : 'NO' ) . "\n";
$log_msg .= "Cart total: " . WC()->cart->get_total() . "\n";
$log_msg .= "===================================================\n";

@file_put_contents( $log_file, $log_msg, FILE_APPEND | LOCK_EX );
