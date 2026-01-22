<?php

return array(
	'title'      => esc_html__( '404 Page Settings', 'immigro' ),
	'id'         => '404_setting',
	'desc'       => '',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => '404_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( '404 Source Type', 'immigro' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'immigro' ),
				'e' => esc_html__( 'Elementor', 'immigro' ),
			),
			'default' => 'd',
		),
		array(
			'id'       => '404_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'immigro' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
			],
			'required' => [ '404_source_type', '=', 'e' ],
		),
		array(
			'id'       => '404_default_st',
			'type'     => 'section',
			'title'    => esc_html__( '404 Default', 'immigro' ),
			'indent'   => true,
			'required' => [ '404_source_type', '=', 'd' ],
		),
		array(
			'id'      => '404_page_banner',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Banner', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show banner on blog', 'immigro' ),
			'default' => true,
		),
		array(
			'id'       => '404_page_background',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Background Image', 'immigro' ),
			'desc'     => esc_html__( 'Insert background image for banner', 'immigro' ),
			'default'  => '',
			'required' => array( '404_page_banner', '=', true ),
		),
		array(
			'id'    => '404_page_title',
			'type'  => 'text',
			'title' => esc_html__( '404 Title', 'immigro' ),
			'desc'  => esc_html__( 'Enter 404 section title that you want to show', 'immigro' ),

		),
		/*array(
			'id'    => '404_page_form',
			'type'  => 'switch',
			'title' => esc_html__( 'Show Search Form', 'immigro' ),
			'desc'  => esc_html__( 'Enable to show search form on 404 page', 'immigro' ),
			'default'  => true,
		),*/
		array(
			'id'    => 'back_home_btn',
			'type'  => 'switch',
			'title' => esc_html__( 'Show Button', 'immigro' ),
			'desc'  => esc_html__( 'Enable to show back to home button.', 'immigro' ),
			'default'  => true,
		),
		array(
			'id'       => 'back_home_btn_label',
			'type'     => 'text',
			'title'    => esc_html__( 'Button Label', 'immigro' ),
			'desc'     => esc_html__( 'Enter back to home button label that you want to show.', 'immigro' ),
			'default'  => esc_html__( 'Back To Home Page', 'immigro' ),
			'required' => array( 'back_home_btn', '=', true ),
		),
		
		

		array(
			'id'     => '404_post_settings_end',
			'type'   => 'section',
			'indent' => false,
		),

	),
);





