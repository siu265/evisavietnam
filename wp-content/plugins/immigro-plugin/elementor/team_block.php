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
		?>


		
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
                                    <p><?php echo $settings['text'];?></p>
                                    <ul class="social-links clearfix">
									<?php foreach($settings['repeat'] as $item):?>	
                                        <li><a href="<?php echo esc_url($item['block_btnlink']['url']);?>"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value']));?>"></i></a></li>
									<?php endforeach; ?>
                                    </ul>
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
									<h3><a href="<?php echo esc_url($settings['btnlink']['url']);?>"><?php echo $settings['title'];?></a></h3>
                                    <span class="designation"><?php echo $settings['subtitle'];?></span>
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
                                    <ul class="social-links clearfix">
										<?php foreach($settings['repeat'] as $item):?>	
                                            <li>
                                                <a href="<?php echo esc_url($item['block_btnlink']['url']);?>" class="fs_16 b_radius_50 d_iblock"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value']));?>"></i></a>
                                            </li>
										<?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="lower-content">
                                    <h3><a href="<?php echo esc_url($settings['btnlink']['url']);?>"><?php echo $settings['title'];?></a></h3>
                                    <span class="designation"><?php echo $settings['subtitle'];?></span>
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