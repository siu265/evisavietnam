<?php
/**
 * Plugin Name: Fix Order Received Page Output
 * Description: Khắc phục trang thank you chỉ hiển thị ký tự lỗi - thay thế output bị hỏng bằng trang thank you tối thiểu.
 * Version: 1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bắt buffer NGAY khi load mu-plugin (trước mọi plugin/theme) nếu là order-received
$fix_uri = $_SERVER['REQUEST_URI'] ?? '';
$fix_is_order_received = ( strpos( $fix_uri, 'order-received' ) !== false || ! empty( $_GET['key'] ) );

if ( $fix_is_order_received ) {
	// Log sớm nhất có thể (mu-plugin load trước mọi thứ)
	$fix_log_dir = dirname( __DIR__ ) . '/uploads/visa-checkout-logs';
	if ( ! is_dir( $fix_log_dir ) ) {
		if ( function_exists( 'wp_mkdir_p' ) ) {
			wp_mkdir_p( $fix_log_dir );
		} else {
			@mkdir( $fix_log_dir, 0755, true );
		}
	}
	$fix_log_file = $fix_log_dir . '/visa-checkout.log';
	$fix_line = '[' . date( 'Y-m-d H:i:s' ) . '] [MU-PLUGIN] ORDER RECEIVED REQUEST - URI: ' . $fix_uri . "\n";
	@file_put_contents( $fix_log_file, $fix_line, FILE_APPEND | LOCK_EX );

	ob_start( function( $buffer ) {
		if ( ! is_string( $buffer ) ) {
			return $buffer;
		}
		$stripped = trim( strip_tags( $buffer ) );
		$has_replacement = (bool) preg_match( '/\x{FFFD}/u', $buffer );
		$len = strlen( $buffer );
		// Trang hỏng: quá ngắn HOẶC có ký tự U+FFFD (luôn coi là lỗi encoding)
		$is_broken = ( $len < 500 ) || $has_replacement;
		if ( $is_broken ) {
			$order_id = 0;
			$uri = $_SERVER['REQUEST_URI'] ?? '';
			if ( preg_match( '#/order-received/(\d+)#', $uri, $m ) ) {
				$order_id = (int) $m[1];
			} elseif ( ! empty( $_GET['order'] ) ) {
				$order_id = (int) $_GET['order'];
			}
			$order_info = '';
			if ( $order_id && function_exists( 'wc_get_order' ) ) {
				$order = wc_get_order( $order_id );
				if ( $order && ! is_wp_error( $order ) ) {
					$order_info = '<p><strong>Order #' . esc_html( $order->get_order_number() ) . '</strong></p>';
					$order_info .= '<p>Total: ' . wp_kses_post( $order->get_formatted_order_total() ) . '</p>';
				}
			}
			return '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Thank You</title><style>body{font-family:system-ui,sans-serif;max-width:600px;margin:60px auto;padding:20px;line-height:1.6;}</style></head><body>'
				. '<h1>Thank you for your order</h1>'
				. '<p>Your payment has been received successfully.</p>'
				. $order_info
				. '<p><a href="' . esc_url( home_url( '/' ) ) . '">Return to homepage</a></p>'
				. '</body></html>';
		}
		return $buffer;
	} );
}
