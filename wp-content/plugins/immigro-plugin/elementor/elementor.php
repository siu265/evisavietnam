<?php

namespace IMMIGROPLUGIN\Element;


class Elementor {
	static $widgets = array(
		
		'banner_with_left_text',
		'banner_multi_shapes',
		'blog_grid',
		'banner_button',
		'coaching_block',
		'course_block',
		'feature_icon_and_text',
		'faq',
		'image_capsule',
		'image_round_shape',
		'image_double_round_shape',
		'progress_bar',
		'theme_button',
		'team_block',
		'testimonial',
		'tab',
		'tab2',
		'tab_with_sec_title',
		'tab_with_image',
		'tab_country',
		'tab_country_details',
		'visa_slider',
		'wi_catagory',
		'wi_catagory_2',
		'working_round_block',
		
	);

	static function init() {
		add_action( 'elementor/init', array( __CLASS__, 'loader' ) );
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'register_cats' ) );
	}

	static function loader() {

		foreach ( self::$widgets as $widget ) {

			$file = IMMIGROPLUGIN_PLUGIN_PATH . '/elementor/' . $widget . '.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}

			add_action( 'elementor/widgets/register', array( __CLASS__, 'register' ) );
		}
	}

	static function register( $elemntor ) {
		foreach ( self::$widgets as $widget ) {
			$class = '\\IMMIGROPLUGIN\\Element\\' . ucwords( $widget );

			if ( class_exists( $class ) ) {
				$elemntor->register( new $class );
			}
		}
	}

	static function register_cats( $elements_manager ) {

		$elements_manager->add_category(
			'immigro',
			[
				'title' => esc_html__( 'Immigro', 'immigro' ),
				'icon'  => 'fa fa-plug',
			]
		);
		$elements_manager->add_category(
			'csslatepath',
			[
				'title' => esc_html__( 'Template Path', 'immigro' ),
				'icon'  => 'fa fa-plug',
			]
		);

	}
}

Elementor::init();