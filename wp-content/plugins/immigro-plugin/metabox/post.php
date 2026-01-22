<?php
return array(
	'title' => 'immigro Post Setting',
	'id' => 'immigro_meta_post',
	'icon' => 'el el-cogs',
	'position' => 'normal',
	'priority' => 'core',
	'post_types' => array( 'post' ),
	'sections' => array(
		array(
			'id' => 'immigro_post_meta_setting',
			'fields' => array(
				array(
					'id' => 'meta_image',
					'type' => 'media',
					'title' => esc_html__( 'Meta image', 'indext' ),
				),
				

			),
		),
	),
);