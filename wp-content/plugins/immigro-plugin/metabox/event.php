<?php
return array(
	'title'      => 'Immigro Evant Setting',
	'id'         => 'immigro_meta_event',
	'icon'       => 'el el-cogs',
	'position'   => 'normal',
	'priority'   => 'core',
	'post_types' => array( 'tribe_events' ),
	'sections'   => array(
		array(
			'id'     => 'immigro_event_meta_setting',
			'fields' => array(
				array(
					'id'    => 'video',
					'type'  => 'text',
					'title' => esc_html__( 'Video Link', 'immigro' ),
				),
				
			),
		),
	),
);