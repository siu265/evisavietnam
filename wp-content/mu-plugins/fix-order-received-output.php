<?php
/**
 * Plugin Name: Fix Order Received Page Output
 * Description: Khắc phục trang thank you chỉ hiển thị ký tự lỗi - thay thế output bị hỏng bằng trang thank you tối thiểu.
 * Version: 1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bắt buffer NGAY khi load mu-plugin (trước mọi plugin/theme) nếu là order-received
$fix_uri = $_SERVER['REQUEST_URI'] ?? '';
$fix_is_order_received = ( strpos( $fix_uri, 'order-received' ) !== false || ! empty( $_GET['key'] ) );

if ( $fix_is_order_received ) {
	ob_start( function( $buffer ) {
		if ( ! is_string( $buffer ) ) {
			return $buffer;
		}
		$stripped = trim( strip_tags( $buffer ) );
		$has_replacement = (bool) preg_match( '/\x{FFFD}/u', $buffer );
		$len = strlen( $buffer );
		$stripped_len = strlen( $stripped );
		// Trang hỏng: quá ngắn HOẶC có ký tự thay thế + nội dung text ít
		$is_broken = ( $len < 500 ) || ( $has_replacement && $stripped_len < 100 );
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
