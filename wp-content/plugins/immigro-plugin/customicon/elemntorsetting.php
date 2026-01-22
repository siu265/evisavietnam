<?php
// Title #1
// Sub title #2
// text #3
// list text #4
// Button with Background #5
// Button #6


// Title #1	==================	

if ( ! function_exists( 'title_control' ) ) {
	

	function title_control( $obj ) {

		$obj->start_controls_section(
			'title_settings',
			array(
				'label' => __( 'Title Setting', 'ecolab' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
	$obj->add_control(
			'title_alingment',
			array(
				'label' => esc_html__( 'Alignment', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'plugin-name' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'plugin-name' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'plugin-name' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .banner-carousel .content-box h1' => 'text-align: {{VALUE}} !important',
				),
			)
		);	
	$obj->add_control(
			'title_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .banner-carousel .content-box h1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .banner-carousel .content-box h1',
			)
		);
		$obj->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .banner-carousel .content-box h1' => 'color: {{VALUE}} !important',
				),
			)
		);

		$obj->end_controls_section();
	}
}
//End of  Title 	==================	

// SubTitle #2	==================	

if ( ! function_exists( 'subtitle_control' ) ) {
	

	function subtitle_control( $obj ) {

		$obj->start_controls_section(
			'subtitle_settings',
			array(
				'label' => __( 'Sub Title Setting', 'ecolab' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
	$obj->add_control(
			'subtitle_alingment',
			array(
				'label' => esc_html__( 'Alignment', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'plugin-name' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'plugin-name' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'plugin-name' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .banner-carousel .active .content-box p' => 'text-align: {{VALUE}} !important',
					'{{WRAPPER}} .sec-title .sub-title' => 'text-align: {{VALUE}} !important',
				),
			)
		);	
	$obj->add_control(
			'subtitle_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
			'{{WRAPPER}} .banner-carousel .active .content-box p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
			'{{WRAPPER}} .sec-title .sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',		
				),
			)
		);
		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
		
			array(
				'name'     => 'subtitle_typography',
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => 
						'{{WRAPPER}} .banner-carousel .content-box p',
			)
		);
		
		
		$obj->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .banner-carousel .active .content-box p' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .sec-title .sub-title' => 'color: {{VALUE}} !important',
				),
			)
		);

		$obj->end_controls_section();
	}
}
		

		
//End  subTitle 	=================
// Text #3	==================	

if ( ! function_exists( 'text_control' ) ) {
	

	function text_control( $obj ) {

		$obj->start_controls_section(
			'text_settings',
			array(
				'label' => __( 'Text Setting', 'ecolab' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
	$obj->add_control(
			'text_alingment',
			array(
				'label' => esc_html__( 'Alignment', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'plugin-name' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'plugin-name' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'plugin-name' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} p' => 'text-align: {{VALUE}} !important',
				),
			)
		);	
	$obj->add_control(
			'text_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);
		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}}  p',
			)
		);
		$obj->add_control(
			'text_color',
			array(
				'label'     => __( 'Color', 'ecolab' ),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  p' => 'color: {{VALUE}} !important',
				),
			)
		);

		$obj->end_controls_section();
	}
}
		
//text_control($this);
		
//End of Text	==================	

//Button With Background #4	==================			
if ( ! function_exists( 'button_control' ) ) {
	
	function button_control( $obj ) {

		$obj->start_controls_section(
			'button_control',
			array(
				'label' => __( 'Button Settings', 'ecolab' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

$obj->add_control(
			'button_color',
			array(
				'label'     => __( 'Button Color', 'ecolab' ),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .theme-btn.btn-one' => 'color: {{VALUE}} !important',

				),
			)
		);
$obj->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Background Color', 'ecolab' ),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .theme-btn.btn-one' => 'background: {{VALUE}} !important',
				),
			)
		);		
	$obj->add_control(
			'button_padding',
			array(
				'label'     => __( 'Padding', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .theme-btn.btn-one' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$obj->add_control(
			'button_margin',
			array(
				'label'     => __( 'Margin', 'ecolab' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .theme-btn.btn-one' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'ecolab' ),
				'selector' => '{{WRAPPER}} .theme-btn',
			)
		);
		$obj->end_controls_section();
	}
}
	