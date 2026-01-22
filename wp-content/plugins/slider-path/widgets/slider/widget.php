<?php
/**
 * Widget: Slider
 */


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();


//prefix slider_path
class SLIDERPATH_Slider extends SLIDERPATH_Widget_base {

	public function get_name() {
		return 'sliderpath-slider';
	}
	public function get_style_depends() {
		return [ 'sliderpath-slider' ];
	}
	public function get_script_depends() {
		return [ 'banner', 'sliderpath-slider' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_slider_content',
			[
				'label' => esc_html__( 'Content', 'element-path' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

	
		$this->add_control( 
			'slider_style',
			[
				'label' => esc_html__( 'Slyder Types', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'plugin_slides',
				'options' => [
					'plugin_slides' => esc_html__( 'Plugin Sliders', 'element-path' ),
					'theme_slides'  => esc_html__( 'Theme Slider', 'element-path' ),
				],
			]
		);

		$this->add_control( 
			'slider_path_elemntor_template_x',
			[
				'label' => esc_html__( 'Template', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => slider_pat_elementor_template_(),
				'condition' => [
					'slider_style' => 'theme_slides',
				],				
			]
		);	
	
			
		

		$repeater = new Repeater();

		$repeater->add_control(
			'slider_title', [
				'label'       => esc_html__( 'Title', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Slider Title',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		
		
//New Code from Plugin



$repeater->add_control( 
			'slider_type',
			[
				'label' => esc_html__( 'Content type', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'content'  => esc_html__( 'Slider Path', 'element-path' ),
					'template' => esc_html__( 'Elmntor Template', 'element-path' ),
				],
			]
		);

		$repeater->add_control( 
			'slider_path_elemntor_template',
			[
				'label' => esc_html__( 'Template', 'element-path' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => slider_pat_elementor_template_(),
				'condition' => [
					'slider_type' => 'template',
				],				
			]
		);	
		

//End of plugin code


	$repeater->add_control(
			'slider_path_image',
			[
				'label'   => esc_html__( 'Select BG Image', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [ 'active' => true ],
				'default' => [
					'url' => SLIDERPATH_PLUGIN_URL ."assets/images/placeholder.png",
				],
			]
		);

//Title Area	

	   $repeater->add_control(
			'slider_path_title', [
				'label'       => esc_html__( 'Slides Title', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Slides Title is the Best way to get the Title in Slider', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

	
//SubTitle Area

	    $repeater->add_control(
			'slider_path_subtitle', [
				'label'       => esc_html__( 'Slides Sub Title', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'We are Since 2005', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);


//Text Area		
		 $repeater->add_control(
			'slider_path_text', [
				'label'       => esc_html__( 'Slides Text', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Get all the Quality Service and Support form us anytime ', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);


//Button One Area
		
		$repeater->add_control(
			'slider_path_button', [
				'label'       => esc_html__( 'Button', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Read More', 'element-path' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slider_path_link', [
				'label'       => esc_html__( 'Link', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::URL,
			]
		);


//Button Two  Area
		
		$repeater->add_control(
			'slider_path_button_2', [
				'label'       => esc_html__( 'Button Two', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slider_path_link_2', [
				'label'       => esc_html__( 'Link ', 'element-path' ),
				'condition'    => array( 'slider_type' => 'content' ),
				'type'        => Controls_Manager::URL,
			]
		);



	



//End

		$this->add_control(
			'repeat',
			[
				'label'       => esc_html__( 'Sliders', 'element-path' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'separator'   => 'before',
				'title_field' => '{{ title }}',
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					[
						'title' => esc_html__( 'Slider Path Slide', 'element-path' ),
					],
				],
				'fields'      => $repeater->get_controls(),
			]
		);





		$this->end_controls_section();

//====================Star of Setting area==============================================
	
// Basic Setting
	
$this->start_controls_section(
			'slider_path_basic_control',
			
			array(
				'label' => __( 'Slider Basic Settings', 'ecolab' ),
				
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
			)
		);
		
$this->add_control(
			'slider_path_basic_show',
			array(
				'label' => esc_html__( 'Show Slider', 'ecolab' ),
					'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path' => 'display: {{VALUE}} !important',
				),
			)
		);	

	$this->add_control(
			'slider_path_alingment',
			array(
				'label' => esc_html__( 'Alignment', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ecolab' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ecolab' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ecolab' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'toggle' => true,
				'selectors' => array(
				
					'{{WRAPPER}} .slider_path_title ' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_text ' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_subtitle ' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_button_container ' => 'justify-content: {{VALUE}} !important',
				),
			)
		);			
$this->add_control(
			'slider_path_slider_width',
			[
				'label' => esc_html__( 'Block Width', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .slider_path_slide' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);	
		$this->add_control(
			'slider_path_slider_height',
			[
				'label' => esc_html__( 'Block Height', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .slider_path_slide' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);	

	$this->add_control(
			'slider_path_container_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

$this->add_control(
			'slider_path_container_bgcolor',
			array(
				'label'     => __( 'Slider Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_container' => 'background: {{VALUE}} !important',
				),
			)
		);
$this->add_control(
			'slider_path_slider_before_color',
			array(
				'label'     => __( 'Slider Before Color', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_slide:before' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_slider_after_color',
			array(
				'label'     => __( 'Slider After Color', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_slide:after' => 'background: {{VALUE}} !important',
				),
			)
		);	



		
$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'slider_path_slider_border',
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'label' => esc_html__( 'Box Border', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_slide',
			]
		);
		$this->add_control(
			'slider_path_slider_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'ecolab' ),
				'condition'    => array( 'slider_path_basic_show' => 'show' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'condition'    => array( 'show_button' => 'show' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .slider_path_slide' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			)
		);


 $this->add_control( 'slider_path_background_size',
		            [
		                'label' => esc_html__( 'Background Size', 'rashid'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'Default', 'rashid' ),
		                    'auto' => esc_html__( 'Auto', 'rashid' ),
		                    'cover' => esc_html__( 'Cover', 'rashid' ),
		                    'contain' => esc_html__( 'Contain', 'rashid' ),
		                ],
		            ]
		        );
$this->add_control( 'slider_path_background_position',
		            [
		                'label' => esc_html__( 'Background Position', 'rashid' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '',
		                'options' => [
		                	'' => esc_html__( 'Default', 'rashid' ),
		                	'center center' => esc_html__( 'Center Center', 'rashid' ),
		                	'center left' => esc_html__( 'Center Left', 'rashid' ),
		                	'center right' => esc_html__( 'Center Right', 'rashid' ),
		                	'top center' => esc_html__( 'Top Center', 'rashid'),
		                	'top left' => esc_html__( 'Top Left', 'rashid' ),
		                	'top right' => esc_html__( 'Top Right', 'rashid' ),
		                	'bottom center' => esc_html__( 'Bottom Center', 'rashid' ),
		                	'bottom left' => esc_html__( 'Bottom Left', 'rashid' ),
		                	'bottom right' => esc_html__( 'Bottom Right', 'rashid' ),
		                ],
		            ]
		        );



$this->add_control(
		'slider_path_slider_animation',
		[
			'label'   => esc_html__( 'Slider Image Animation ', 'rashid' ),
			'condition'    => array( 'slider_path_basic_show' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_slider_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_slider_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_slider_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);			

$this->add_control(
			'slider_path_zoom_show',
			array(
				'label' => esc_html__( 'Show Slider Zoom Animation', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_slider_path .owl-stage' => 'transform: {{VALUE}} !important',
				),
			)
		);	


		$this->end_controls_section();
		
//End of Button	
// Title Slider Setting 001 	==================	

		
		$this->start_controls_section(
			'slider_path_title_settings',
			array(
				'label' => __( 'Title Setting', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		
	$this->add_control(
			'slider_path_show_title',
			array(
				'label' => esc_html__( 'Show Title', 'ecolabe' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_title' => 'display: {{VALUE}} !important',
				),
			)
		);	

	


	$this->add_control(
			'slider_path_title_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'condition'    => array( 'slider_path_show_title' => 'show' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_title_typography',
				'condition'    => array( 'slider_path_show_title' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_title ',
			)
		);
		$this->add_control(
			'slider_path_title_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_title' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_title ' => 'color: {{VALUE}} !important',
		
				),
			)
		);

	$this->add_control(
		'slider_path_animation_title',
		[
			'label'   => esc_html__( 'Slider Title Animatin Style ', 'rashid' ),
			'condition'    => array( 'slider_path_show_title' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_title_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_title_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_title_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);




	$this->end_controls_section();
	

// Subtitle Slider Setting 002 	==================	

		
		$this->start_controls_section(
			'slider_path_subtitle_settings',
			array(
				'label' => __( 'Sub Title Setting', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		
	$this->add_control(
			'slider_path_show_subtitle',
			array(
				'label' => esc_html__( 'Show Sub Title', 'ecolabe' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_subtitle' => 'display: {{VALUE}} !important',
				),
			)
		);	



	$this->add_control(
			'slider_path_subtitle_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_subtitle_typography',
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_subtitle ',
			)
		);
		$this->add_control(
			'slider_path_subtitle_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_subtitle ' => 'color: {{VALUE}} !important',
		
				),
			)
		);

	$this->add_control(
		'slider_path_animation_subtitle',
		[
			'label'   => esc_html__( 'Slider Animatin Subtitle ', 'rashid' ),
			'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_subtitle_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_subtitle_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_subtitle_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);


		$this->end_controls_section();

//Slider Text 03 ==============

		$this->start_controls_section(
			'slider_path_text_settings',
			array(
				'label' => __( 'Text Setting', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		
	$this->add_control(
			'slider_path_show_text',
			array(
				'label' => esc_html__( 'Show Text', 'ecolabe' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_text' => 'display: {{VALUE}} !important',
				),
			)
		);	
		


	$this->add_control(
			'slider_path_text_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'condition'    => array( 'slider_path_show_text' => 'show' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_text_typography',
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_text ',
			)
		);
		$this->add_control(
			'slider_path_text_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_text ' => 'color: {{VALUE}} !important',
		
				),
			)
		);

	$this->add_control(
		'slider_path_animation_text',
		[
			'label'   => esc_html__( 'Slider Animatin Text ', 'rashid' ),
			'condition'    => array( 'slider_path_show_subtitle' => 'show' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => array(
				'slider_path_animation_text_style_1'   => esc_html__( 'Animations Style 01', 'rashid' ),
				'slider_path_animation_text_style_2'   => esc_html__( 'Animations Style 02', 'rashid' ),
				'slider_path_animation_text_style_3'   => esc_html__( 'Animations Style 03', 'rashid' ),
			
			),
		]
	);

		$this->end_controls_section();	

// Button Setting 005

$this->start_controls_section(
			'slider_path_button_control',
			array(
				'label' => __( 'Button Settings', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_path_show_button',
			array(
				'label' => esc_html__( 'Show Button', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'display: {{VALUE}} !important',
				),
			)
		);		

$this->add_control(
			'slider_path_button_color',
			array(
				'label'     => __( 'Button Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'color: {{VALUE}} !important',

				),
			)
		);

$this->add_control(
			'slider_path_button_color_hover',
			array(
				'label'     => __( 'Button Color Hover', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button:before' => 'background: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);				
	$this->add_control(
			'slider_path_button_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_typography',
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border',
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'selector' => '{{WRAPPER}} .slider_path_button',
			)
		);
	

		$this->add_control(
			'slider_path_border_radius',
			array(
				'label'     => __( 'Border Radius', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->end_controls_section();
		
//End of Button		

// Button Setting 005

$this->start_controls_section(
			'slider_path_button_2_control',
			array(
				'label' => __( 'Button 2 Settings', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_path_show_button_2',
			array(
				'label' => esc_html__( 'Show Button', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'display: {{VALUE}} !important',
				),
			)
		);		

$this->add_control(
			'slider_path_button_2_color',
			array(
				'label'     => __( 'Button Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'color: {{VALUE}} !important',

				),
			)
		);

$this->add_control(
			'slider_path_button_2_color_hover',
			array(
				'label'     => __( 'Button Color Hover', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2:hover' => 'color: {{VALUE}} !important',

				),
			)
		);


$this->add_control(
			'slider_path_button_2_bg_color',
			array(
				'label'     => __( 'Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_2_hover_color',
			array(
				'label'     => __( 'Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2:before' => 'background: {{VALUE}} !important',
					'{{WRAPPER}} .slider_path_button_2:hover' => 'background: {{VALUE}} !important',
				),
			)
		);				
	$this->add_control(
			'slider_path_button_2_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_2_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_2_typography',
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .slider_path_button_2',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border_2',
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'selector' => '{{WRAPPER}} .slider_path_button_2',
			)
		);
	

		$this->add_control(
			'slider_path_border_2_radius',
			array(
				'label'     => __( 'Border Radius', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_2' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .slider_path_button_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->end_controls_section();
		
//End of Button	

// Arrow Button Setting
	
$this->start_controls_section(
			'slider_path_button_3_control',
			array(
				'label' => __( 'Slider Arrow  Settings', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_path_show_button_3',
			array(
				'label' => esc_html__( 'Show Button', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path .owl-nav button' => 'display: {{VALUE}} !important',
				),
			)
		);		

$this->add_control(
			'slider_path_button_3_color',
			array(
				'label'     => __( 'Button Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_3_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_3_bg_color',
			array(
				'label'     => __( 'Background Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_3_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);				
	$this->add_control(
			'slider_path_button_3_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_3_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_3_typography',
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border_3',
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
			)
		);
	

		$this->add_control(
			'slider_path_border_3_radius',
			array(
				'label'     => __( 'Border Radius', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_horizontal_prev',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'ecolab' ),
						'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
				$this->add_control( 'slider_path_horizontal_next',
					[
						'label' => esc_html__( 'Horizontal Position Next', 'ecolab'),
						'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'right: {{SIZE}}{{UNIT}};',
						],
						
					]
				);
		
				$this->add_control( 'slider_path_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'ecolab' ),
						'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 250,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav ' => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav ' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);


		$this->end_controls_section();
		
	

// Dot Button Setting
	
$this->start_controls_section(
			'slider_path_dot_control',
			array(
				'label' => __( 'Slider Dot  Settings', 'ecolab' ),
				'condition'    => array( 'slider_style' => 'plugin_slides' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_path_show_dot',
			array(
				'label' => esc_html__( 'Show Dot', 'ecolab' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'show' => [
						'show' => esc_html__( 'Show', 'ecolab' ),	
						'icon' => 'eicon-check-circle',
					],
					'none' => [
						'none' => esc_html__( 'Hide', 'ecolab' ),
						'icon' => 'eicon-close-circle',
					],
				],
				'default' => 'show',
				'selectors' => array(
					'{{WRAPPER}} .slider_path .owl-dots ' => 'display: {{VALUE}} !important',
				),
			)
		);		


				$this->add_control( 'slider_path_dot_width',
					[
						'label' => esc_html__( 'Dot Width',  'ecolab' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_path_dot_height',
					[
						'label' => esc_html__( 'Dot Height', 'ecolab' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 250,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
		
$this->add_control(
			'slider_path_dot_color',
			array(
				'label'     => __( 'Dot Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_dot_color_hover',
			array(
				'label'     => __( 'Dot Hover Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_dot_bg_color',
			array(
				'label'     => __( 'Active Color', 'ecolab' ),
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}}  .owl-theme .owl-dots .owl-dot.active span' => 'background: {{VALUE}} !important',
				),
			)
		);	
			
	$this->add_control(
			'slider_path_dot_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_dot_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_dot_border',
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span',
			)
		);
	

		$this->add_control(
			'slider_path_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'condition'    => array( 'slider_path_show_dot' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'ecolab' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_path_dot_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'ecolab' ),
						'condition'    => array( 'slider_path_show_dot' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 250,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots  ' => 'top: {{SIZE}}{{UNIT}};',
					
						]
					]
				);


		$this->end_controls_section();
		
	
//End Dot

		
		

	}
}



