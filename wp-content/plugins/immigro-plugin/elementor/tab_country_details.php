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
class Tab_Country_Details extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_tab_country_details';
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
		return esc_html__( 'Tab Country Details', 'immigro' );
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
			'tab_country_details',
			[
				'label' => esc_html__( 'Tab Country Details', 'immigro' ),
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



		
		$this->end_controls_section();
		
		// New Tab#1

		$this->start_controls_section(
					'content_section',
					[
						'label' => __( 'Feature Block', 'rashid' ),
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
																	
										'block_title'=>	
									[
										'name' => 'block_title',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_icons'=>

									[
										'name' => 'block_icons',
										'label' => esc_html__('Enter The icons', 'rashid'),
										'type' => Controls_Manager::ICONS,							
									],

									'block_title1'=>	
									[
										'name' => 'block_title1',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text'=>
									
									[
										'name' => 'block_text',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_text1'=>
									
									[
										'name' => 'block_text1',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_text2'=>
									
									[
										'name' => 'block_text2',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],	

									'block_image'=>

									[
										'name' => 'block_image',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text'=>

									  [
										'name' => 'block_alt_text',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

									'block_title2'=>	
									[
										'name' => 'block_title2',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text3'=>
									
									[
										'name' => 'block_text3',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_button'=>

									[
										'name' => 'block_button',
										'label'       => __( 'Button', 'rashid' ),
										'type'        => Controls_Manager::TEXT,
										'dynamic'     => [
											'active' => true,
										],
										'placeholder' => __( 'Enter your Button Title', 'rashid' ),
										'default' => esc_html__('Read More', 'rashid'),
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

								   'block_image1'=>

									[
										'name' => 'block_image1',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text1'=>

									  [
										'name' => 'block_alt_text1',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

									'block_title3'=>	
									[
										'name' => 'block_title3',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text4'=>
									
									[
										'name' => 'block_text4',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_button1'=>

									[
										'name' => 'block_button1',
										'label'       => __( 'Button', 'rashid' ),
										'type'        => Controls_Manager::TEXT,
										'dynamic'     => [
											'active' => true,
										],
										'placeholder' => __( 'Enter your Button Title', 'rashid' ),
										'default' => esc_html__('Read More', 'rashid'),
									],
							
									'block_btnlink1'=>
									
									[
									  'name' => 'block_btnlink1',
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

								   'block_image2'=>

									[
										'name' => 'block_image2',
										'label' => __( 'Image', 'rashid' ),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
									  ],	

									  'block_alt_text2'=>

									  [
										'name' => 'block_alt_text2',
										'label' => esc_html__('Alt Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('iamge', 'rashid')
									],

									'block_title4'=>	
									[
										'name' => 'block_title4',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text5'=>
									
									[
										'name' => 'block_text5',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_button2'=>

									[
										'name' => 'block_button2',
										'label'       => __( 'Button', 'rashid' ),
										'type'        => Controls_Manager::TEXT,
										'dynamic'     => [
											'active' => true,
										],
										'placeholder' => __( 'Enter your Button Title', 'rashid' ),
										'default' => esc_html__('Read More', 'rashid'),
									],
							
									'block_btnlink2'=>
									
									[
									  'name' => 'block_btnlink2',
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

								   'block_title5'=>	
									[
										'name' => 'block_title5',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text6'=>
									
									[
										'name' => 'block_text6',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],


									'block_text7'=>
									
									[
										'name' => 'block_text7',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_text8'=>
									
									[
										'name' => 'block_text8',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_text9'=>
									
									[
										'name' => 'block_text9',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_title6'=>	
									[
										'name' => 'block_title6',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text10'=>
									
									[
										'name' => 'block_text10',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_icons1'=>

									[
										'name' => 'block_icons1',
										'label' => esc_html__('Enter The icons', 'rashid'),
										'type' => Controls_Manager::ICONS,							
									],

									'block_title7'=>	
									[
										'name' => 'block_title7',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text11'=>
									
									[
										'name' => 'block_text11',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_icons2'=>

									[
										'name' => 'block_icons2',
										'label' => esc_html__('Enter The icons', 'rashid'),
										'type' => Controls_Manager::ICONS,							
									],


									'block_title8'=>	
									[
										'name' => 'block_title8',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text12'=>
									
									[
										'name' => 'block_text12',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],

									'block_icons3'=>

									[
										'name' => 'block_icons3',
										'label' => esc_html__('Enter The icons', 'rashid'),
										'type' => Controls_Manager::ICONS,							
									],

									'block_title9'=>	

									[
										'name' => 'block_title9',
										'label' => esc_html__('Title', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
									],
									
										'block_text13'=>
									
									[
										'name' => 'block_text13',
										'label' => esc_html__('Text', 'rashid'),
										'type' => Controls_Manager::TEXTAREA,
										'default' => esc_html__('', 'rashid')
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
        
	
<div class="country-details-content p_relative d_block">
	<div class="tabs-box">
		<div class="tab-btn-box p_relative d_block pb_50">
			<ul class="tab-btns tab-buttons clearfix">
				<?php foreach($settings['repeat'] as $key=>$item):?>
					<li class="tab-btn <?php if($key == 1) echo 'active-btn';?>" data-tab="#tab-<?php echo esc_attr($key);?>">
					<h5><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value']));?>"></i><?php echo wp_kses($item['block_title'], $allowed_tags);?></h5>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="tabs-content">
		<?php foreach($settings['repeat'] as $key=>$item):?>
			<div class="tab <?php if($key == 1) echo 'active-tab';?>" id="tab-<?php echo esc_attr($key);?>">
				<div class="content-box">
					<div class="content-one">
						<h2><?php echo wp_kses($item['block_title1'], $allowed_tags);?></h2>
						<p><?php echo wp_kses($item['block_text'], $allowed_tags);?></p>
						<ul class="list-item clearfix">
							<li><?php echo wp_kses($item['block_text1'], $allowed_tags);?></li>
							<li><?php echo wp_kses($item['block_text2'], $allowed_tags);?></li>
						</ul>
					</div>
					<div class="content-two">
						<div class="row clearfix">
							<div class="col-lg-4 col-md-6 col-sm-12 single-column">
								<div class="single-item">
									<figure class="image-box">
										<?php if(wp_get_attachment_url($item['block_image']['id'])): ?>
										<img src="<?php echo wp_get_attachment_url($item['block_image']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text'], $allowed_tags);?>">
										<?php else :?>
										<div class="noimage"></div>
										<?php endif;?>
									</figure>
									<div class="lower-content">
										<h4><?php echo wp_kses($item['block_title2'], $allowed_tags);?></h4>
										<p><?php echo wp_kses($item['block_text3'], $allowed_tags);?></p>
										<div class="btn-box">
											<a href="<?php echo esc_url($item['block_btnlink']['url']);?>" class="btn-4"><?php echo wp_kses($item['block_button'], $allowed_tags);?><span></span></a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 single-column">
								<div class="single-item">
									<figure class="image-box">
										<?php if(wp_get_attachment_url($item['block_image1']['id'])): ?>
										<img src="<?php echo wp_get_attachment_url($item['block_image1']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text1'], $allowed_tags);?>">
										<?php else :?>
										<div class="noimage"></div>
										<?php endif;?>		
									</figure>
									<div class="lower-content">
										<h4><?php echo wp_kses($item['block_title3'], $allowed_tags);?></h4>
										<p><?php echo wp_kses($item['block_text4'], $allowed_tags);?></p>
										<div class="btn-box">
											<a href="<?php echo esc_url($item['block_btnlink1']['url']);?>" class="btn-4"><?php echo wp_kses($item['block_button1'], $allowed_tags);?><span></span></a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 single-column">
								<div class="single-item">
									<figure class="image-box">
										<?php if(wp_get_attachment_url($item['block_image2']['id'])): ?>
										<img src="<?php echo wp_get_attachment_url($item['block_image2']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text2'], $allowed_tags);?>">
										<?php else :?>
										<div class="noimage"></div>
										<?php endif;?>
									</figure>
									<div class="lower-content">
										<h4><?php echo wp_kses($item['block_title4'], $allowed_tags);?></h4>
										<p><?php echo wp_kses($item['block_text5'], $allowed_tags);?></p>
										<div class="btn-box">
											<a href="<?php echo esc_url($item['block_btnlink2']['url']);?>" class="btn-4"><?php echo wp_kses($item['block_button2'], $allowed_tags);?><span></span></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="content-three">
						<h3><?php echo wp_kses($item['block_title5'], $allowed_tags);?></h3>
						<p><?php echo wp_kses($item['block_text6'], $allowed_tags);?></p>
						<ul class="list-item clearfix">
							<li><?php echo wp_kses($item['block_text7'], $allowed_tags);?></li>
							<li><?php echo wp_kses($item['block_text8'], $allowed_tags);?></li>
							<li><?php echo wp_kses($item['block_text9'], $allowed_tags);?></li>
						</ul>
					</div>
					<div class="content-four">
						<div class="text">
							<h3><?php echo wp_kses($item['block_title6'], $allowed_tags);?></h3>
							<p><?php echo wp_kses($item['block_text10'], $allowed_tags);?></p>
						</div>
						<div class="row clearfix">
							<div class="col-lg-4 col-md-6 col-sm-12 single-column">
								<div class="single-item">
									<div class="icon-box"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons1']['value']));?>"></i></div>
									<h4><?php echo wp_kses($item['block_title7'], $allowed_tags);?></h4>
									<p><?php echo wp_kses($item['block_text11'], $allowed_tags);?></p>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 single-column">
								<div class="single-item">
									<div class="icon-box"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons2']['value']));?>"></i></div>
									<h4><?php echo wp_kses($item['block_title8'], $allowed_tags);?></h4>
									<p><?php echo wp_kses($item['block_text12'], $allowed_tags);?></p>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 single-column">
								<div class="single-item">
									<div class="icon-box"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons3']['value']));?>"></i></div>
									<h4><?php echo wp_kses($item['block_title9'], $allowed_tags);?></h4>
									<p><?php echo wp_kses($item['block_text13'], $allowed_tags);?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?> 
		</div>
	</div>
</div>
   
		<?php 
	}

}