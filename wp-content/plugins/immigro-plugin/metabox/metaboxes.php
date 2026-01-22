<?php
if ( ! function_exists( "immigro_add_metaboxes" ) ) {
	function immigro_add_metaboxes( $metaboxes ) {
		$directories_array = array(
			'page.php',
			'projects.php',
			'service.php',
			'team.php',
			'testimonials.php',
			'event.php',
			'post.php',
		);
		foreach ( $directories_array as $dir ) {
			$metaboxes[] = require_once( IMMIGROPLUGIN_PLUGIN_PATH . '/metabox/' . $dir );
		}

		return $metaboxes;
	}

	add_action( "redux/metaboxes/immigro_options/boxes", "immigro_add_metaboxes" );
}

