<?php
return array(
	'title'      => 'Immigro Project Setting',
	'id'         => 'immigro_meta_projects',
	'icon'       => 'el el-cogs',
	'position'   => 'normal',
	'priority'   => 'core',
	'post_types' => array( 'immigro_project' ),
	'sections'   => array(
		array(
			'id'     => 'immigro_projects_meta_setting',
			'fields' => array(
				array(
					'id'    => 'meta_subtitle',
					'type'  => 'text',
					'title' => esc_html__( 'Subtitle', 'immigro' ),
				),
				array(
					'id'    => 'page_link',
					'type'  => 'text',
					'title' => esc_html__( 'Page Link', 'immigro' ),
				),
				array(
					'id'    => 'image_link',
					'type'  => 'text',
					'title' => esc_html__( 'Image Link', 'immigro' ),
				),
				array(
					'id'    => 'meta_number',
					'type'  => 'text',
					'title' => esc_html__( 'Column Number', 'immigro' ),
					'default' => esc_html__( '3', 'immigro' ),
				),
			),
		),
	),
);