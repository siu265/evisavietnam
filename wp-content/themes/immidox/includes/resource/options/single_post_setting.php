<?php

return array(
	'title'      => esc_html__( 'Single Post Settings', 'immigro' ),
	'id'         => 'single_post_setting',
	'desc'       => '',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'single_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Single Post Source Type', 'immigro' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'immigro' ),
				'e' => esc_html__( 'Elementor', 'immigro' ),
			),
			'default' => 'd',
		),
		array(
			'id'      => 'single_post_author',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Author', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show author on posts detail page', 'immigro' ),
			'default' => false,
		),
		
		/*
		
		array(
			'id'      => 'single_post_date',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Date', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show post publish date on posts detail page', 'immigro' ),
			'default' => true,
		),
		
		*/
		
		array(
			'id'      => 'single_post_comments',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Comments', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show number of comments on posts single page', 'immigro' ),
			'default' => true,
		),
		array(
			'id'      => 'single_post_tag',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Tags', 'immigro' ),
			'desc'    => esc_html__( 'Enable to show post tags on posts single page', 'immigro' ),
			'default' => true,
		),
		array(
			'id'       => 'single_default_st',
			'type'     => 'section',
			'title'    => esc_html__( 'Post Default', 'immigro' ),
			'indent'   => true,
			'required' => [ 'single_source_type', '=', 'd' ],
		),
		array(
			'id'       => 'single_section_default_ed',
			'type'     => 'section',
			'indent'   => false,
			'required' => [ 'single_source_type', '=', 'd' ],
		),
	),
);