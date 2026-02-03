<?php
/**
 * Plugin Name: Block Backdoor Request Params
 * Description: Chặn request kích hoạt backdoor PHP (tham số hex + payload số chấm). Ngăn backdoor thực thi nếu bị chèn lại.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'muplugins_loaded', 'evisa_block_backdoor_params', 0 );

function evisa_block_backdoor_params() {
	$suspicious_keys = array( 'sym', 'key', 'holder', 'object', 'marker', 'ref', 'desc', 'rec', 'entry', 'mrk', 'flag', 'ptr', 'comp', 'itm', 'dat', 'bind', 'pgpr', 'factor' );
	$source          = array_merge( array_keys( $_POST ), array_keys( $_REQUEST ) );
	$source          = array_unique( $source );

	foreach ( $source as $k ) {
		$lower = strtolower( trim( (string) $k ) );
		if ( ! in_array( $lower, $suspicious_keys, true ) ) {
			continue;
		}
		$val = isset( $_REQUEST[ $k ] ) ? $_REQUEST[ $k ] : '';
		if ( ! is_string( $val ) ) {
			continue;
		}
		if ( strlen( $val ) > 50 && preg_match( '/^[\d.\s]+$/', $val ) ) {
			wp_die(
				__( 'Request blocked for security.', 'evisa' ),
				__( 'Forbidden', 'evisa' ),
				array( 'response' => 403 )
			);
		}
	}
}
