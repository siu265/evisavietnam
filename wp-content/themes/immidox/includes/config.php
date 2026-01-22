<?php
/**
 * Theme config file.
 *
 * @package IMMIGRO
 * @author  ThemeKalia
 * @version 1.0
 * changed
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Restricted' );
}

$config = array();

$config['default']['immigro_main_header'][] 	= array( 'immigro_preloader', 98 );
$config['default']['immigro_main_header'][] 	= array( 'immigro_main_header_area', 99 );

$config['default']['immigro_main_footer'][] 	= array( 'immigro_preloader', 98 );
$config['default']['immigro_main_footer'][] 	= array( 'immigro_main_footer_area', 99 );

$config['default']['immigro_sidebar'][] 	    = array( 'immigro_sidebar', 99 );

$config['default']['immigro_banner'][] 	    = array( 'immigro_banner', 99 );


return $config;
