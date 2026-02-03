<?php
/**
 * Plugin Name: Fix Order Received Page Output
 * Description: Bypass theme/hook (nguồn lỗi "1" + ký tự hỏng) - Chạy OnePay thankyou, xuất trang tĩnh sạch.
 * Version: 1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fix_uri = $_SERVER['REQUEST_URI'] ?? '';
$fix_is_order_received = ( strpos( $fix_uri, 'order-received' ) !== false && ! empty( $_GET['key'] ) );

// Bắt output NGAY từ đầu - chặn "1" + ký tự lỗi output trước template_redirect
if ( $fix_is_order_received ) {
	ob_start();
	$fix_log_dir = dirname( __DIR__ ) . '/uploads/visa-checkout-logs';
	if ( ! is_dir( $fix_log_dir ) ) {
		@mkdir( $fix_log_dir, 0755, true );
	}
	$fix_log_file = $fix_log_dir . '/visa-checkout.log';
	@file_put_contents( $fix_log_file, '[' . date( 'Y-m-d H:i:s' ) . '] [MU-PLUGIN] ORDER RECEIVED - URI: ' . $fix_uri . "\n", FILE_APPEND | LOCK_EX );
}

add_action( 'init', function() {
	$uri = $_SERVER['REQUEST_URI'] ?? '';
	if ( strpos( $uri, 'order-received' ) === false || empty( $_GET['key'] ) ) {
		return;
	}
	if ( ! function_exists( 'wc_get_order' ) ) {
		return;
	}

	$order_id = 0;
	if ( preg_match( '#/order-received/(\d+)#', $uri, $m ) ) {
		$order_id = (int) $m[1];
	}
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order || is_wp_error( $order ) ) {
		return;
	}

	$order_key = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : '';
	if ( ! hash_equals( $order->get_order_key(), $order_key ) ) {
		return;
	}

	// Xóa buffer đã bắt từ đầu (chứa "1" + ký tự lỗi nếu có)
	if ( ob_get_level() ) {
		ob_end_clean();
	}

	if ( WC()->session ) {
		unset( WC()->session->order_awaiting_payment );
	}
	wc_empty_cart();

	// Chạy hook OnePay + woocommerce_thankyou (cập nhật trạng thái đơn, visa clear) - BẮT output
	ob_start();
	do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
	do_action( 'woocommerce_thankyou', $order->get_id() );
	ob_end_clean();

	// Xóa mọi buffer rác có thể đã bị output trước (theme/hook lỗi "1" + ký tự hỏng)
	while ( ob_get_level() ) {
		ob_end_clean();
	}

	if ( ! headers_sent() ) {
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'X-Robots-Tag: noindex' );
	}

	// Xuất trang tĩnh HOÀN TOÀN - KHÔNG dùng get_header, get_footer, wc_get_template (tránh hook/theme lỗi)
	$order_number = $order->get_order_number();
	$order_total  = $order->get_formatted_order_total();
	$order_date   = $order->get_date_created() ? wc_format_datetime( $order->get_date_created() ) : '';
	$order_email  = $order->get_billing_email();
	$payment_title = $order->get_payment_method_title();
	$gateway_msg   = '';
	if ( $order->has_status( 'failed' ) ) {
		$gateway_msg = '<p class="woocommerce-notice woocommerce-notice--error">' . esc_html__( 'Unfortunately your order cannot be processed. Please attempt your purchase again.', 'woocommerce' ) . '</p>';
	} else {
		$gateway_msg = '<p class="woocommerce-notice woocommerce-notice--success">' . esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ) . '</p>';
	}
	$home_url = esc_url( home_url( '/' ) );

	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php esc_html_e( 'Order received', 'woocommerce' ); ?></title>
	<style>
		body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;margin:0;padding:40px 20px;background:#f5f5f5;color:#333;line-height:1.6;}
		.thankyou-wrap{max-width:600px;margin:0 auto;background:#fff;padding:32px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
		h1{color:#2e7d32;margin:0 0 16px;font-size:1.5em;}
		.order-details{list-style:none;padding:0;margin:20px 0;border-top:1px solid #eee;border-bottom:1px solid #eee;}
		.order-details li{padding:10px 0;display:flex;justify-content:space-between;}
		.order-details li strong{margin-right:12px;}
		.actions{margin-top:24px;}
		.btn{display:inline-block;padding:10px 20px;background:#1976d2;color:#fff;text-decoration:none;border-radius:4px;}
		.btn:hover{background:#1565c0;}
	</style>
</head>
<body>
	<div class="thankyou-wrap">
		<h1><?php esc_html_e( 'Thank you. Your order has been received.', 'woocommerce' ); ?></h1>
		<?php echo wp_kses_post( $gateway_msg ); ?>
		<ul class="order-details">
			<li><strong><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order_number ); ?></li>
			<li><strong><?php esc_html_e( 'Date:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order_date ); ?></li>
			<?php if ( $order_email ) : ?>
			<li><strong><?php esc_html_e( 'Email:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order_email ); ?></li>
			<?php endif; ?>
			<li><strong><?php esc_html_e( 'Total:', 'woocommerce' ); ?></strong> <?php echo wp_kses_post( $order_total ); ?></li>
			<?php if ( $payment_title ) : ?>
			<li><strong><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></strong> <?php echo esc_html( $payment_title ); ?></li>
			<?php endif; ?>
		</ul>
		<div class="actions">
			<a href="<?php echo esc_url( $home_url ); ?>" class="btn"><?php esc_html_e( 'Return to homepage', 'woocommerce' ); ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn" style="background:#666;margin-left:8px;"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
	<?php
	exit;
}, 999 );
