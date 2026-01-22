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
class Visa_Slider extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_visa_slider';
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
		return esc_html__( 'Visa Slider', 'immigro' );
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
			'visa_slider',
			[
				'label' => esc_html__( 'Visa Slider', 'immigro' ),
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

										'block_bgimg'=>		
									[
										'name' => 'block_bgimg',
										'label' => esc_html__('Background Image', 'rashid'),
										'type' => Controls_Manager::MEDIA,
										'default' => ['url' => Utils::get_placeholder_image_src(),],
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
									  'label' => esc_html__('Image Text', 'rashid'),
									  'type' => Controls_Manager::TEXTAREA,
									  'default' => esc_html__('', 'rashid')
								  	],	

									  'block_icons'=>	

									  [
										'name' => 'block_icons',
										'label' => esc_html__('Enter The icons', 'rashid'),
										'type' => Controls_Manager::ICONS,							
									],
																		
										'block_title'=>	
									[
										'name' => 'block_title',
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



<?php
	  echo '
	 <script>
 jQuery(document).ready(function($)
 {

//put the js code under this line 
if ($(".three-item-carousel").length) {
	$(".three-item-carousel").owlCarousel({
		loop:true,
		margin:30,
		nav:true,
		smartSpeed: 500,
		autoplay: 1000,
		responsive:{
			0:{
				items:1
			},
			480:{
				items:1
			},
			600:{
				items:2
			},
			800:{
				items:2
			},			
			1200:{
				items:3
			}

		}
	});    		
}
//put the code above the line 

  });
</script>';


?>


		<section class="visa-section <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="three-item-carousel owl-carousel owl-theme owl-nav-none">
				<?php foreach($settings['repeat'] as $item):?>	
                    <div class="visa-block-one">
                        <div class="inner-box">
							<?php if(wp_get_attachment_url($item['block_bgimg']['id'])): ?>
							<div class="shape" style="background-image: url(<?php echo wp_get_attachment_url($item['block_bgimg']['id']);?>);">
							<?php else :?>
							<div class="noimage">
							<?php endif;?>
							</div>
                            <figure class="image-box">
								<?php if(wp_get_attachment_url($item['block_image']['id'])): ?>
								<img src="<?php echo wp_get_attachment_url($item['block_image']['id']);?>" alt="<?php echo wp_kses($item['block_alt_text'], $allowed_tags);?>">
								<?php else :?>
								<div class="noimage"></div>
								<?php endif;?>
							</figure>
                            <div class="lower-content">
                                <div class="icon-box"><i class="<?php echo str_replace("icon ", " ", esc_attr( $item['block_icons']['value']));?>"></i></div>
                                <h3><a href="<?php echo esc_url($item['block_btnlink']['url']);?>"><?php echo wp_kses($item['block_title'], $allowed_tags);?></a></h3>
                                <p><?php echo wp_kses($item['block_text'], $allowed_tags);?></p>
                                <div class="btn-box">
                                    <a href="<?php echo esc_url($item['block_btnlink']['url']);?>" class="btn-4"><?php echo wp_kses($item['block_button'], $allowed_tags);?><span></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>  
                </div>
            </div>
        </section>



             
		<?php 
	}

}