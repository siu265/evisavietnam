<?php
return array(
	'title'      => esc_html__( 'Logo Setting', 'immigro' ),
	'id'         => 'logo_setting',
	'desc'       => '',
	'subsection' => false,
	'fields'     => array(
		array(
			'id'       => 'image_favicon',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Favicon', 'immigro' ),
			'subtitle' => esc_html__( 'Insert site favicon image', 'immigro' ),
			'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/favicon.png' ),
		),
	
		array(
			'id'       => 'image_normal_logo',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Logo One', 'immigro' ),
			'subtitle' => esc_html__( 'Insert site Light  logo image', 'immigro' ),
	
		),
		array(
			'id'       => 'normal_logo_dimension',
			'type'     => 'dimensions',
			'title'    => esc_html__( ' Logo Dimentions', 'immigro' ),
			'subtitle' => esc_html__( 'Select Light Logo Dimentions', 'immigro' ),
			'units'    => array( 'em', 'px', '%' ),
			'default'  => array( 'Width' => '', 'Height' => '' ),
			'required' => array( 'normal_logo_show', '=', true ),
		),

		array(
			'id'       => 'image_normal_logo2',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Logo Two', 'immigro' ),
			'subtitle' => esc_html__( 'Insert site Dark logo image', 'immigro' ),
		),
		array(
			'id'       => 'normal_logo_dimension2',
			'type'     => 'dimensions',
			'title'    => esc_html__( ' Logo Dimentions', 'immigro' ),
			'subtitle' => esc_html__( 'Select Dark Dimentions', 'immigro' ),
			'units'    => array( 'em', 'px', '%' ),
			'default'  => array( 'Width' => '', 'Height' => '' ),
			'required' => array( 'normal_logo_show2', '=', true ),
		),
		
		
		/*
		
		array(
			'id'       => 'image_normal_logo3',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Logo Three', 'immigro' ),
			'subtitle' => esc_html__( 'Insert logo image', 'immigro' ),
		),
		array(
			'id'       => 'normal_logo_dimension3',
			'type'     => 'dimensions',
			'title'    => esc_html__( ' Logo Dimentions', 'immigro' ),
			'subtitle' => esc_html__( 'Select Dimentions', 'immigro' ),
			'units'    => array( 'em', 'px', '%' ),
			'default'  => array( 'Width' => '', 'Height' => '' ),
			'required' => array( 'normal_logo_show3', '=', true ),
		),
		
		
		*/
		
		array(
			'id'       => 'logo_settings_section_end',
			'type'     => 'section',
			'indent'      => false,
		),
	),
);
