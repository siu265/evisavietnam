<?php

return  array(
    'title'      => esc_html__( 'Search Page Settings', 'immigro' ),
    'id'         => 'search_setting',
    'desc'       => '', 
    'subsection' => true,
    'fields'     => array(
	    array(
		    'id'      => 'search_source_type',
		    'type'    => 'button_set',
		    'title'   => esc_html__( 'Search Source Type', 'immigro' ),
		    'options' => array(
			    'd' => esc_html__( 'Default', 'immigro' ),
			    'e' => esc_html__( 'Elementor', 'immigro' ),
		    ),
		    'default' => 'd',
	    ),
	    array(
		    'id'       => 'search_elementor_template',
		    'type'     => 'select',
		    'title'    => __( 'Template', 'immigro' ),
		    'data'     => 'posts',
		    'args'     => [
			    'post_type' => [ 'elementor_library' ],
				'posts_per_page'=> -1,
		    ],
		    'required' => [ 'search_source_type', '=', 'e' ],
	    ),

	    array(
		    'id'       => 'search_default_st',
		    'type'     => 'section',
		    'title'    => esc_html__( 'Search Default', 'immigro' ),
		    'indent'   => true,
		    'required' => [ 'search_source_type', '=', 'd' ],
	    ),
	    array(
		    'id'      => 'search_page_banner',
		    'type'    => 'switch',
		    'title'   => esc_html__( 'Show Banner', 'immigro' ),
		    'desc'    => esc_html__( 'Enable to show banner on blog', 'immigro' ),
		    'default' => true,
	    ),
	    array(
		    'id'       => 'search_banner_title',
		    'type'     => 'text',
		    'title'    => esc_html__( 'Banner Section Title', 'immigro' ),
		    'desc'     => esc_html__( 'Enter the title to show in banner section', 'immigro' ),
		    'required' => array( 'search_page_banner', '=', true ),
	    ),
	    array(
		    'id'       => 'search_page_background',
		    'type'     => 'media',
		    'url'      => true,
		    'title'    => esc_html__( 'Background Image', 'immigro' ),
		    'desc'     => esc_html__( 'Insert background image for banner', 'immigro' ),
		    'default'  => '',
		    'required' => array( 'search_page_banner', '=', true ),
	    ),

	    array(
		    'id'       => 'search_sidebar_layout',
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
		    'id'       => 'search_page_sidebar',
		    'type'     => 'select',
		    'title'    => esc_html__( 'Sidebar', 'immigro' ),
		    'desc'     => esc_html__( 'Select sidebar to show at blog listing page', 'immigro' ),
		    'required' => array(
			    array( 'search_sidebar_layout', '=', array( 'left', 'right' ) ),
		    ),
		    'options'  => immigro_get_sidebars(),
	    ),
	   //
		array(
			'id'    => 'search_page_title',
			'type'  => 'text',
			'title' => esc_html__( 'Search Title', 'immigro' ),
			'desc'  => esc_html__( 'Enter Search section title that you want to show', 'immigro' ),

		),
		array(
			'id'    => 'search_page_text',
			'type'  => 'textarea',
			'title' => esc_html__( 'Search Page Description', 'immigro' ),
			'desc'  => esc_html__( 'Enter Search page description that you want to show.', 'immigro' ),

		),
	

		
	    array(
		    'id'       => 'search_default_ed',
		    'type'     => 'section',
		    'indent'   => false,
		    'required' => [ 'search_source_type', '=', 'd' ],
	    ),

    ),
);





