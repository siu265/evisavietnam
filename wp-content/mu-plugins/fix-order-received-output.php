<?php
/**
 * Plugin Name: Fix Order Received Page Output
 * Description: Khắc phục trang thank you chỉ hiển thị ký tự lỗi - thay thế output bị hỏng bằng trang thank you tối thiểu.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	$uri = $_SERVER['REQUEST_URI'] ?? '';
	if ( strpos( $uri, 'order-received' ) === false && empty( $_GET['key'] ) ) {
		return;
	}
	ob_start( function( $buffer ) {
		if ( ! is_string( $buffer ) ) {
			return $buffer;
		}
		$stripped = trim( strip_tags( $buffer ) );
		$has_replacement = (bool) preg_match( '/\x{FFFD}/u', $buffer );
		$is_broken = ( strlen( $buffer ) < 300 )
			|| ( $has_replacement && strlen( $stripped ) < 50 );
		if ( $is_broken ) {
			$order_id = 0;
			if ( preg_match( '#/order-received/(\d+)#', $_SERVER['REQUEST_URI'] ?? '', $m ) ) {
				$order_id = (int) $m[1];
			} elseif ( ! empty( $_GET['order'] ) ) {
				$order_id = (int) $_GET['order'];
			}
			$order_info = '';
			if ( $order_id && function_exists( 'wc_get_order' ) ) {
				$order = wc_get_order( $order_id );
				if ( $order ) {
					$order_info = '<p><strong>Order #' . esc_html( $order->get_order_number() ) . '</strong></p>';
					$order_info .= '<p>Total: ' . wp_kses_post( $order->get_formatted_order_total() ) . '</p>';
				}
			}
			return '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Thank You</title></head><body>'
				. '<h1>Thank you for your order</h1>'
				. '<p>Your payment has been received successfully.</p>'
				. $order_info
				. '<p><a href="' . esc_url( home_url( '/' ) ) . '">Return to homepage</a></p>'
				. '</body></html>';
		}
		return $buffer;
	} );
}, 0 );
