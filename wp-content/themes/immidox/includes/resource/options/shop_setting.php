<?php

return array(
	'title'      => esc_html__( 'Shop Page Settings', 'immigro' ),
	'id'         => 'shop_setting',
	'desc'       => '',
	'icon'       => ' fa fa-shopping-cart',
	'subsection' => false,
	'fields'     => array(
		array(
			'id'      => 'shop_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'shop Source Type', 'immigro' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'immigro' ),
				'e' => esc_html__( 'Elementor', 'immigro' ),
			),
			'default' => 'd',
		),
		array(
			'id'       => 'shop_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'immigro' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
				'posts_per_page'=> -1,
			],
			'required' => [ 'shop_source_type', '=', 'e' ],
		),

		array(
			'id'       => 'shop_default_st',
			'type'     => 'section',
			'title'    => esc_html__( 'shop Default', 'immigro' ),
			'indent'   => true,
			'required' => [ 'shop_source_type', '=', 'd' ],
		),
		array(
			'id'      => 'shop_page_banner',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Banner', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show banner on blog', 'immigro' ),
			'default' => true,
		),
		array(
			'id'       => 'shop_banner_title',
			'type'     => 'text',
			'title'    => esc_html__( 'Banner Section Title', 'immigro' ),
			'desc'     => esc_html__( 'Enter the title to show in banner section', 'immigro' ),
			'required' => array( 'shop_page_banner', '=', true ),
		),
	
		array(
			'id'       => 'shop_page_background',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Background Image', 'immigro' ),
			'desc'     => esc_html__( 'Insert background image for banner', 'immigro' ),
			'default'  => array(
				'url' => IMMIGRO_URI . 'assets/images/pagetitle.jpg',
			),
			'required' => array( 'shop_page_banner', '=', true ),
		),

		array(
			'id'       => 'shop_sidebar_layout',
			'type'     => 'image_select',
			'title'    => esc_html__( 'Layout', 'immigro' ),
			'subtitle' => esc_html__( 'Select main content and sidebar alignment.', 'immigro' ),
			'options'  => array(

				'left'  => array(
					'alt' => esc_html__( '2 Column Left', 'immigro' ),
					'img' => get_template_directory_uri() . '/assets/images/redux/2cl.png',
				),
				'full'  => array(
					'alt' => esc_html__( '1 Column', 'immigro' ),
					'img' => get_template_directory_uri() . '/assets/images/redux/1col.png',
				),
				'right' => array(
					'alt' => esc_html__( '2 Column Right', 'immigro' ),
					'img' => get_template_directory_uri() . '/assets/images/redux/2cr.png',
				),
			),

			'default' => 'right',
		),

		array(
			'id'       => 'shop_page_sidebar',
			'type'     => 'select',
			'title'    => esc_html__( 'Sidebar', 'immigro' ),
			'desc'     => esc_html__( 'Select sidebar to show at blog listing page', 'immigro' ),
			'required' => array(
				array( 'shop_sidebar_layout', '=', array( 'left', 'right' ) ),
			),
			'options'  => immigro_get_sidebars(),
		),
	

		  array (
        'id'       => 'shop_column',
        'type'     => 'select',
        'title'    => __('Shop Column', 'immigro'), 
        'desc'     => __('This is Shop Column', 'immigro'),
         // Must provide key => value pairs for select options
        'options'  => array(
            '6' => 'Two Column',
            '4' => 'Three Column',
            '3' => 'Four Column',
			'2' => 'Six Column',
            ),
        'default'  => '2',
    ),
	  array (
        'id'       => 'shop_product',
        'type'     => 'text',
        'title'    => __('Number of Products', 'immigro'), 
        'desc'     => __('This is Number of Products', 'immigro'),
        'default'  => '8',
    ),

		array(
			'id'       => 'shop_default_ed',
			'type'     => 'section',
			'indent'   => false,
			'required' => [ 'shop_source_type', '=', 'd' ],
		),
	),
);





