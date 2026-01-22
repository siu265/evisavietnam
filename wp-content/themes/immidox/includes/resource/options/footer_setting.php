<?php

return array(
	'title'      => esc_html__( 'Footer Setting', 'immigro' ),
	'id'         => 'footer_setting',
	'desc'       => '',
	'subsection' => false,
	'fields'     => array(
		array(
			'id'      => 'footer_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Footer Source Type', 'immigro' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'immigro' ),
				'e' => esc_html__( 'Elementor', 'immigro' ),
			),
			'default' => 'd',
		),
		array(
			'id'       => 'footer_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'immigro' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
				'posts_per_page'	=> -1
			],
			'required' => [ 'footer_source_type', '=', 'e' ],
		),
		array(
			'id'       => 'footer_style_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer Settings', 'immigro' ),
			'required' => array( 'footer_source_type', '=', 'd' ),
		),
		array(
		    'id'       => 'footer_style_settings',
		    'type'     => 'image_select',
		    'title'    => esc_html__( 'Choose Footer Styles', 'immigro' ),
		    'subtitle' => esc_html__( 'Choose Footer Styles', 'immigro' ),
		    'options'  => array(

			    'footer_v1'  => array(
				    'alt' => esc_html__( 'Footer  Style One', 'immigro' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/footer/footer1.png',
			    ),
			   /* 'footer_v2'  => array(
				    'alt' => esc_html__( 'Footer RTL Style', 'immigro' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/footer/footer2.png',
			    ), */
			),
			'required' => array( 'footer_source_type', '=', 'd' ),
			'default' => 'footer_v1',
	    ),
		/***********************************************************************
								Footer Version 1 Start
		************************************************************************/
		array(
			'id'       => 'footer_v1_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer  Style Settings', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v1' ),
		),
		

		array(
			'id'      => 'copyright_text',
			'type'    => 'textarea',
			'title'   => __( 'Copyright Text', 'immigro' ),
			'desc'    => esc_html__( 'Enter the Copyright Text', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v1' ),
		),
		/***********************************************************************
								Footer Version 2 Start
		************************************************************************/
		array(
			'id'       => 'footer_v2_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer RTL Style Settings', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
		    'id'       => 'show_news_letter_area_v2',
		    'type'     => 'switch',
		    'title'    => esc_html__( 'Enable New Letter Area', 'immigro' ),
		    'desc'     => esc_html__( 'Enable/Disable New Letter Area.', 'immigro' ),
			'default'  => '',
		    'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
	    ),
		array(
			'id'    => 'footer_v2_social_share',
			'type'  => 'social_media',
			'title' => esc_html__( 'Social Profiles', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
			'id'      => 'newletter_form_title_v2',
			'type'    => 'text',
			'title'   => __( 'News Letter Form Title', 'immigro' ),
			'desc'    => esc_html__( 'Enter the Form Title', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
			'id'      => 'newletter_form_id_v2',
			'type'    => 'text',
			'title'   => __( 'News Letter Form ID', 'immigro' ),
			'desc'    => esc_html__( 'Enter the Form ID', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
			'id'       => 'footer_shape_three',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Background Shape Image', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
			'id'       => 'footer_shape_four',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Background Shape Image Two', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
			'id'      => 'copyright_text2',
			'type'    => 'textarea',
			'title'   => __( 'Copyright Text', 'immigro' ),
			'desc'    => esc_html__( 'Enter the Copyright Text', 'immigro' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		array(
			'id'       => 'footer_default_ed',
			'type'     => 'section',
			'indent'   => false,
			'required' => [ 'footer_source_type', '=', 'd' ],
		),
	),
);