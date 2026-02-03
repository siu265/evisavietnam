<?php
/**
 * Plugin Name: Load Slider Path Textdomain Early
 * Description: Load slider-path textdomain at muplugins_loaded to prevent "too early" notice. Must-use plugin.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'muplugins_loaded', function() {
	if ( function_exists( 'load_plugin_textdomain' ) && file_exists( WP_PLUGIN_DIR . '/slider-path/slider-path.php' ) ) {
		load_plugin_textdomain( 'slider-path', false, 'slider-path/languages/' );
	}
}, 0 );
