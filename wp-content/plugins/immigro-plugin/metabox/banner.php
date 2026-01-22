<?php

return array(
	'id'     => 'immigro_banner_settings',
	'title'  => esc_html__( "Immigro Banner Settings", "konia" ),
	'fields' => array(
		array(
			'id'      => 'banner_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Banner Source Type', 'immigro' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'immigro' ),
				'e' => esc_html__( 'Elementor', 'immigro' ),
			),
			'default' => '',
		),
		array(
			'id'       => 'banner_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'viral-buzz' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
				'posts_per_page'=> -1,
			],
			'required' => [ 'banner_source_type', '=', 'e' ],
		),
		array(
			'id'       => 'banner_page_banner',
			'type'     => 'switch',
			'title'    => esc_html__( 'Show Banner', 'immigro' ),
			'default'  => false,
			'required' => [ 'banner_source_type', '=', 'd' ],
		),
		array(
			'id'       => 'banner_banner_title',
			'type'     => 'text',
			'title'    => esc_html__( 'Banner Section Title', 'immigro' ),
			'desc'     => esc_html__( 'Enter the title to show in banner section', 'immigro' ),
			'required' => array( 'banner_page_banner', '=', true ),
		),
		array(
			'id'       => 'banner_page_background',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Background Image', 'immigro' ),
			'desc'     => esc_html__( 'Insert background image for banner', 'immigro' ),
			'default'  => array(
				'url' => IMMIGRO_URI . 'assets/images/breadcrumb/breadcrumb-1.jpg',
			),
			'required' => array( 'banner_page_banner', '=', true ),
		),
	),
);