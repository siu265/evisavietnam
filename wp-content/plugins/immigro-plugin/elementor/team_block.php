<?php

namespace IMMIGROPLUGIN\Element;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

/**
 * Elementor button widget.
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Team_Block extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_team_block';
	}

	/**
	 * Get widget title.
	 * Retrieve button widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Team Block', 'immigro' );
	}

	/**
	 * Get widget icon.
	 * Retrieve button widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-briefcase';
	}

	/**
	 * Get widget categories.
	 * Retrieve the list of categories the button widget belongs to.
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'immigro' ];
	}
	
	/**
	 * Register button widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'team_block',
			[
				'label' => esc_html__( 'Team Block', 'immigro' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Select Style', 'rashid' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => array(
					'style1'   => esc_html__( 'Style One', 'rashid' ),
					'style2'   => esc_html__( 'Style Two', 'rashid' ),
					'style3'   => esc_html__( 'Style Three', 'rashid' ),
				),
			]
		);

		$this->add_control(
			'sec_class',
			[
				'label'       => __( 'Section Class', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter Section Class', 'rashid' ),
			]
		);
		
		$this->add_control(
			'image',
				[
				  'label' => __( 'Image', 'rashid' ),
				  'type' => Controls_Manager::MEDIA,
				  'default' => ['url' => Utils::get_placeholder_image_src(),],
				]
		);	
		
	$this->add_control(
			'alt_text',
			[
				'label'       => __( 'Image Texts', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your Image Texts', 'rashid' ),
			]
		);


		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'rashid' ),
			]
		);
		

		$this->add_control(
			'btnlink',
			[
			  'label' => __( 'Button Url', 'rashid' ),
			  'type' => Controls_Manager::URL,
			  'placeholder' => __( 'https://your-link.com', 'rashid' ),
			  'show_external' => true,
			  'default' => [
				'url' => '',
				'is_external' => true,
				'nofollow' => true,
			  ],
			
		   ]
		);

	$this->add_control(
			'subtitle',
			[
				'label'       => __( 'Sub Title', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your Sub title', 'rashid' ),
			]
		);

		$this->add_control(
			'extra_images',
			[
				'label'   => esc_html__( 'Extra Images', 'immigro' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => [],
			]
		);

		$this->add_control(
			'show_social',
			[
				'label'   => esc_html__( 'Show Social Icons', 'immigro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on'  => esc_html__( 'Yes', 'immigro' ),
				'label_off' => esc_html__( 'No', 'immigro' ),
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => __( 'Description Text', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your Description', 'rashid' ),
			]
		);

		
		$this->end_controls_section();


		// New Tab#1

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slider Block', 'rashid' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
		  'repeat', 
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => 
					[
						['block_title' => esc_html__('Projects Completed', 'rashid')],
					],
				'fields' => 
					[	
							'block_title' =>
							[
								'name' => 'block_title',
								'label' => esc_html__( 'Label', 'immigro' ),
								'type'  => Controls_Manager::TEXT,
								'placeholder' => esc_html__( 'Social', 'immigro' ),
							],

							  'block_icons'=>	

							  [
								'name' => 'block_icons',
								'label' => esc_html__('Enter The icons', 'rashid'),
								'type' => Controls_Manager::ICONS,							
							],

							'block_btnlink'=>
							
							[
							  'name' => 'block_btnlink',
							  'label' => __( 'Button Url', 'rashid' ),
							  'type' => Controls_Manager::URL,
							  'placeholder' => __( 'https://your-link.com', 'rashid' ),
							  'show_external' => true,
							  'default' => [
								'url' => '',
								'is_external' => true,
								'nofollow' => true,
							  ],
						   ],
					
									
					],
				'title_field' => '{{block_title}}',
			 ]
	);
		
		
$this->end_controls_section();

		// Style: Extra Images
		$this->start_controls_section(
			'extra_images_style',
			[
				'label' => esc_html__( 'Extra Images Style', 'immigro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'extra_img_size',
			[
				'label'   => esc_html__( 'Size (px)', 'immigro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 50,
				'min'     => 24,
				'max'     => 120,
				'selectors' => [
					'{{WRAPPER}} .team-extra-images li' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
					'{{WRAPPER}} .team-extra-images li img' => 'width: {{VALUE}}px; height: {{VALUE}}px; object-fit: cover;',
				],
			]
		);
		$this->add_control(
			'extra_img_gap',
			[
				'label'   => esc_html__( 'Gap (px)', 'immigro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 0,
				'selectors' => [
					'{{WRAPPER}} .team-extra-images li' => 'margin: 0 {{VALUE}}px {{VALUE}}px 0;',
				],
			]
		);
		$this->add_control(
			'extra_img_radius',
			[
				'label'      => esc_html__( 'Border Radius (%)', 'immigro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [ 'min' => 0, 'max' => 50 ],
				],
				'default'    => [ 'unit' => '%', 'size' => 50 ],
				'selectors'  => [
					'{{WRAPPER}} .team-extra-images li, {{WRAPPER}} .team-extra-images li img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Style: Title
		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Title Style', 'immigro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .inner-box h3, {{WRAPPER}} .lower-content h3',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-box h3 a, {{WRAPPER}} .lower-content h3 a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		// Style: Subtitle
		$this->start_controls_section(
			'subtitle_style',
			[
				'label' => esc_html__( 'Subtitle Style', 'immigro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .designation',
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .designation' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		// Style: Description
		$this->start_controls_section(
			'text_style',
			[
				'label' => esc_html__( 'Description Style', 'immigro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .inner-box p, {{WRAPPER}} .lower-content p',
			]
		);
		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-box p, {{WRAPPER}} .lower-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();	
				
		
		}

	/**
	 * Render button widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
		$extra_imgs  = isset( $settings['extra_images'] ) && is_array( $settings['extra_images'] ) ? $settings['extra_images'] : [];
		?>
		<style>.team-extra-images{list-style:none;margin:10px 0 15px 0;padding:0;display:flex;flex-wrap:wrap;align-items:center}.team-extra-images li{display:inline-block;overflow:hidden;box-shadow:0 10px 50px rgba(34,34,34,0.2);background:#fff}.team-extra-images li img{display:block;vertical-align:top}</style>
		<?php  if ( 'style1' === $settings['style'] ) : ?>

		<section class="team-section centred <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">

                    <div class="col-lg-12 col-md-12 col-sm-12 team-block">
                        <div class="team-block-one">
                            <div class="inner-box">
                                <figure class="image-box">
									<?php  if ( esc_url($settings['image']['id']) ) : ?>   
									<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
									<?php else :?>
									<div class="noimage"></div>
									<?php endif;?>
								</figure>
                                <div class="content-box">
                                    <h3><a href="<?php echo esc_url($settings['btnlink']['url']);?>"><?php echo $settings['title'];?></a></h3>
                                    <span class="designation"><?php echo $settings['subtitle'];?></span>
                                    <?php if ( ! empty( $extra_imgs ) ) :
                                    ?>
                                    <ul class="team-extra-images clearfix">
                                        <?php foreach ( $extra_imgs as $img ) :
                                            $url = ! empty( $img['url'] ) ? $img['url'] : ( ! empty( $img['id'] ) ? wp_get_attachment_url( (int) $img['id'] ) : '' );
                                            if ( $url ) :
                                        ?>
                                        <li><img src="<?php echo esc_url( $url ); ?>" alt="" /></li>
                                        <?php endif; endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                    <p><?php echo $settings['text'];?></p>
                                    <?php if ( ( $settings['show_social'] ?? 'yes' ) === 'yes' ) : ?>
                                    <ul class="social-links clearfix">
									<?php foreach($settings['repeat'] as $item):?>	
                                        <li><a href="<?php echo esc_url($item['block_btnlink']['url']);?>"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value'] ) );?>"></i></a></li>
									<?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </section>

		<?php endif;?>

		<?php  if ( 'style2' === $settings['style'] ) : ?>
		<section class="team-style-two <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">

                    <div class="col-lg-12 col-md-12 col-sm-12 team-block">
                        <div class="team-block-two wow fadeInUp animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="inner-box">
                                <figure class="image-box">
									<?php  if ( esc_url($settings['image']['id']) ) : ?>   
									<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
									<?php else :?>
									<div class="noimage"></div>
									<?php endif;?>
								</figure>
                                <div class="lower-content p_relative">
                                    <?php if ( ( $settings['show_social'] ?? 'yes' ) === 'yes' ) : ?>
                                    <div class="share-box p_absolute centred">
                                        <a href="#" class="share-icon fs_14 b_radius_50 d_iblock"><i class="fas fa-share-alt"></i></a>
                                        <ul class="share-links p_absolute clearfix">
										<?php foreach($settings['repeat'] as $item):?>	
                                            <li class="p_relative d_block mb_10">
                                                <a href="<?php echo esc_url($item['block_btnlink']['url']);?>" class="fs_16 b_radius_50 d_iblock"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value']));?>"></i></a>
                                            </li>
										<?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
									<h3><a href="<?php echo esc_url($settings['btnlink']['url']);?>"><?php echo $settings['title'];?></a></h3>
                                    <span class="designation"><?php echo $settings['subtitle'];?></span>
                                    <?php if ( ! empty( $extra_imgs ) ) : ?>
                                    <ul class="team-extra-images clearfix">
                                        <?php foreach ( $extra_imgs as $img ) :
                                            $url = ! empty( $img['url'] ) ? $img['url'] : ( ! empty( $img['id'] ) ? wp_get_attachment_url( (int) $img['id'] ) : '' );
                                            if ( $url ) :
                                        ?>
                                        <li><img src="<?php echo esc_url( $url ); ?>" alt="" /></li>
                                        <?php endif; endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                    <p><?php echo $settings['text'];?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
		<?php endif;?>

		<?php  if ( 'style3' === $settings['style'] ) : ?>

			<section class="team-style-three centred p-0 <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">

                    <div class="col-lg-12 col-md-12 col-sm-12 team-block">
                        <div class="team-block-three wow fadeInUp animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image">
										<?php  if ( esc_url($settings['image']['id']) ) : ?>   
										<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
										<?php else :?>
										<div class="noimage"></div>
										<?php endif;?>
									</figure>
                                    <?php if ( ( $settings['show_social'] ?? 'yes' ) === 'yes' ) : ?>
                                    <ul class="social-links clearfix">
										<?php foreach($settings['repeat'] as $item):?>	
                                            <li>
                                                <a href="<?php echo esc_url($item['block_btnlink']['url']);?>" class="fs_16 b_radius_50 d_iblock"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value']));?>"></i></a>
                                            </li>
										<?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="lower-content">
                                    <h3><a href="<?php echo esc_url($settings['btnlink']['url']);?>"><?php echo $settings['title'];?></a></h3>
                                    <span class="designation"><?php echo $settings['subtitle'];?></span>
                                    <?php if ( ! empty( $extra_imgs ) ) : ?>
                                    <ul class="team-extra-images clearfix">
                                        <?php foreach ( $extra_imgs as $img ) :
                                            $url = ! empty( $img['url'] ) ? $img['url'] : ( ! empty( $img['id'] ) ? wp_get_attachment_url( (int) $img['id'] ) : '' );
                                            if ( $url ) :
                                        ?>
                                        <li><img src="<?php echo esc_url( $url ); ?>" alt="" /></li>
                                        <?php endif; endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </section>

		<?php endif;?>

  
		<?php 
	}

}