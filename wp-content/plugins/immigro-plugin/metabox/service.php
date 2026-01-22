<?php
return array(
	'title'      => 'Immigro Service Setting',
	'id'         => 'immigro_meta_service',
	'icon'       => 'el el-cogs',
	'position'   => 'normal',
	'priority'   => 'core',
	'post_types' => array( 'immigro_service' ),
	'sections'   => array(
		array(
			'id'     => 'immigro_service_meta_setting',
			'fields' => array(
				array(
					'id'       => 'service_icon',
					'type'     => 'select',
					'title'    => esc_html__( 'Service Icons', 'immigro' ),
					'options'  => get_fontawesome_icons(),
				),
				array(
					'id'    => 'ext_url',
					'type'  => 'text',
					'title' => esc_html__( 'Enter Read More Link', 'immigro' ),
				),
			),
		),
	),
);