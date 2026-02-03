<?php
$styles = [];
foreach(range(1, 28) as $val) {
    $styles[$val] = sprintf(esc_html__('Style %s', 'immigro'), $val);
}

return  array(
    'title'      => esc_html__( 'General Setting', 'immigro' ),
    'id'         => 'general_setting',
    'desc'       => '',
    'icon'       => 'el el-wrench',
    'fields'     => array(
		
		
		
         array(
            'id' => 'theme_color_scheme',
            'type' => 'color',
            'output' => array('.site-title'),
            'title' => esc_html__('Color Scheme', 'immigro'),
            'default' => '#EC4E4F',
            'transparent' => false
        ),
		
		
		
		 array(
            'id' => 'to_top',
            'type' => 'switch',
            'title' => esc_html__('Hide Scroll To Top', 'immigro'),
            'default' => false,
        ),
		 array(
            'id' => 'theme_rtl',
            'type' => 'switch',
            'title' => esc_html__('Select RTL', 'immigro'),
            'default' => false,
        ),
		array(
            'id' => 'theme_preloader',
            'type' => 'switch',
            'title' => esc_html__('Enable Preloader', 'immigro'),
            'default' => false,
        ),
		array(
			'id'      => 'preloader_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Preloader Type', 'immigro' ),
			'options' => array(
				'text'  => esc_html__( 'Text Loading', 'immigro' ),
				'logo'  => esc_html__( 'Logo Loading', 'immigro' ),
			),
			'default' => 'text',
			'required' => array( 'theme_preloader', '=', true ),
		),
		array(
			'id'      => 'preloader_text',
			'type'    => 'textarea',
			'title'   => __( 'Preloader Text', 'immigro' ),
			'desc'    => esc_html__( 'Enter the Preloader Text (HTML allowed, e.g. txt-loading spans)', 'immigro' ),
			'required' => array( 'preloader_type', '=', 'text' ),
		),
		array(
			'id'       => 'preloader_logo',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Preloader Logo', 'immigro' ),
			'desc'     => esc_html__( 'Upload logo for loading (shown inside circle animation)', 'immigro' ),
			'required' => array( 'preloader_type', '=', 'logo' ),
		),
		
		
    ),
);
