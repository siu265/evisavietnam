<?php

return array(
	'title'  => esc_html__( 'Blog Page Settings', 'immigro' ),
	'id'     => 'blog_setting',
	'desc'   => '',
	'icon'   => 'el el-indent-left',
	'fields' => array(
		array(
			'id'      => 'blog_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Blog Source Type', 'immigro' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'immigro' ),
				'e' => esc_html__( 'Elementor', 'immigro' ),
			),
			'default' => 'd',
		),
		array(
			'id'       => 'blog_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'immigro' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
				'posts_per_page'=> -1,
			],
			'required' => [ 'blog_source_type', '=', 'e' ],
		),

		array(
			'id'       => 'blog_default_st',
			'type'     => 'section',
			'title'    => esc_html__( 'Blog Default', 'immigro' ),
			'indent'   => true,
			'required' => [ 'blog_source_type', '=', 'd' ],
		),
/*
		array(
			'id'      => 'blog_page_banner',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Banner', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show banner on blog', 'immigro' ),
			'default' => true,
		),
		
		array(
			'id'       => 'blog_banner_title',
			'type'     => 'text',
			'title'    => esc_html__( 'Banner Section Title', 'immigro' ),
			'desc'     => esc_html__( 'Enter the title to show in banner section', 'immigro' ),
			'required' => array( 'blog_page_banner', '=', true ),
		),
		array(
			'id'       => 'blog_page_breadcrumb',
			'type'     => 'raw',
			'content'  => "<div style='background-color:#c33328;color:white;padding:20px;'>" . esc_html__( 'Use Yoast SEO plugin for breadcrumb.', 'immigro' ) . "</div>",
			'required' => array( 'blog_page_banner', '=', true ),
		),
		array(
			'id'       => 'blog_page_background',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Background Image', 'immigro' ),
			'desc'     => esc_html__( 'Insert background image for banner', 'immigro' ),
			'default'  => array(
				'url' => IMMIGRO_URI . 'assets/images/top-title-bg.jpg',
			),
			'required' => array( 'blog_page_banner', '=', true ),
		),

		array(
			'id'       => 'blog_sidebar_layout',
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
			'id'       => 'blog_page_sidebar',
			'type'     => 'select',
			'title'    => esc_html__( 'Sidebar', 'immigro' ),
			'desc'     => esc_html__( 'Select sidebar to show at blog listing page', 'immigro' ),
			'required' => array(
				array( 'blog_sidebar_layout', '=', array( 'left', 'right' ) ),
			),
			'options'  => immigro_get_sidebars(),
		),
		array(
			'id'      => 'blog_post_date',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Post Date', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show post data on posts listing', 'immigro' ),
			'default' => false,
		),
*/	
		
		array(
			'id'      => 'blog_post_author',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Author', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show author on posts listing', 'immigro' ),
			'default' => false,
		),
		array(
			'id'      => 'blog_post_comment',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Comment', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show Comment Number', 'immigro' ),
			'default' => false,
		),
		array(
			'id'      => 'blog_post_readmore',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Blog Read More', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show post data on posts listing', 'immigro' ),
			'default' => true,
		),
		array(
		    'id'       => 'blog_post_readmoretext',
		    'type'     => 'text',
		    'title'    => esc_html__( 'Read More Text', 'immigro' ),
		    'desc'     => esc_html__( 'Enter Read More Text to Show.', 'immigro' ),
			'default'  => esc_html__( 'Read More', 'immigro' ),
	    ),
		
		array(
			'id'       => 'blog_default_ed',
			'type'     => 'section',
			'indent'   => false,
			'required' => [ 'blog_source_type', '=', 'd' ],
		),
	),
);