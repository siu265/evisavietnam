<?php
/**
 * Plugin Name: Fix Order Received Page Output
 * Description: Đảm bảo hook thankyou chạy và trang order-received hiển thị đầy đủ (header/footer + nội dung thankyou) khi flow mặc định không chạy.
 * Version: 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fix_uri = $_SERVER['REQUEST_URI'] ?? '';
$fix_is_order_received = ( strpos( $fix_uri, 'order-received' ) !== false || ! empty( $_GET['key'] ) );

if ( $fix_is_order_received ) {
	$fix_log_dir = dirname( __DIR__ ) . '/uploads/visa-checkout-logs';
	if ( ! is_dir( $fix_log_dir ) ) {
		if ( function_exists( 'wp_mkdir_p' ) ) {
			wp_mkdir_p( $fix_log_dir );
		} else {
			@mkdir( $fix_log_dir, 0755, true );
		}
	}
	$fix_log_file = $fix_log_dir . '/visa-checkout.log';
	@file_put_contents( $fix_log_file, '[' . date( 'Y-m-d H:i:s' ) . '] [MU-PLUGIN] ORDER RECEIVED REQUEST - URI: ' . $fix_uri . "\n", FILE_APPEND | LOCK_EX );
}

add_action( 'template_redirect', function() {
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

	// Giống WC_Shortcode_Checkout::order_received - setup session
	if ( WC()->session ) {
		unset( WC()->session->order_awaiting_payment );
	}
	wc_empty_cart();

	// Chạy hook thankyou: OnePay cập nhật trạng thái đơn, visa clear session
	ob_start();
	do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
	$gateway_output = ob_get_clean();
	do_action( 'woocommerce_thankyou', $order->get_id() );

	// Tránh OnePay chạy 2 lần khi load template - thay bằng output đã capture
	remove_all_actions( 'woocommerce_thankyou_' . $order->get_payment_method() );
	add_action( 'woocommerce_thankyou_' . $order->get_payment_method(), function() use ( $gateway_output ) {
		echo $gateway_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}, 10 );

	while ( ob_get_level() ) {
		ob_end_clean();
	}

	if ( ! headers_sent() ) {
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'X-Robots-Tag: noindex' );
	}

	// Layout đầy đủ: header + nội dung thankyou (theme) + footer
	get_header();

	echo '<section class="blog-pagev2-area sidebar-page-container"><div class="container"><div class="row"><div class="col-12">';
	wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
	echo '</div></div></div></section>';

	get_footer();

	exit;
}, 1 );
