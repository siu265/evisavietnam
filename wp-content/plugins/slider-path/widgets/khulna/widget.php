<?php
/**
 * Widget: Khulna
 */


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();



class SLIDERPATH_Khulna extends SLIDERPATH_Widget_base {

	public function get_name() {
		return 'sliderpath-khulna';
	}
	public function get_style_depends() {
		return [ 'sliderpath-khulna' ];
	}
	public function get_script_depends() {
		return [ 'banner', 'sliderpath-khulna' ];
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
				'label'       => esc_html__( 'Name', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Slider Name',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		


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
					'{{WRAPPER}} .slider_path .owl-nav ' => 'display: {{VALUE}} !important',
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
					'{{WRAPPER}}  .slider_path .owl-nav ' => 'color: {{VALUE}} !important',

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
					'{{WRAPPER}}  .slider_path .owl-nav .owl-prev:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}}  .slider_path .owl-nav .owl-next:hover' => 'color: {{VALUE}} !important',

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
		
//End of Button	



	}
}



