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
class Faq extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_faq';
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
		return esc_html__( 'Faq', 'immigro' );
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
			'faq',
			[
				'label' => esc_html__( 'Faq', 'immigro' ),
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
		<div class="faq-inner <?php echo esc_attr($settings['sec_class']);?>">
			<ul class="accordion-box">
			<?php foreach($settings['repeat'] as $key=>$item):?>	
				<li class="accordion block <?php if($key == 1) echo 'active-block';?>">
					<div class="acc-btn <?php if($key == 1) echo 'active';?>">
						<div class="icon-outer"></div>
						<h5><?php echo wp_kses($item['block_title'], $allowed_tags);?></h5>
					</div>
					<div class="acc-content <?php if($key == 1) echo 'current';?>">
						<div class="text">
							<p><?php echo wp_kses($item['block_text'], $allowed_tags);?></p>
						</div>
					</div>
				</li>
			<?php endforeach; ?>	
			</ul>
		</div>
		<?php endif ;?>	


		<?php  if ( 'style2' === $settings['style'] ) : ?>
		<section class="visa-details-content">
			<div class="content-three">
				<ul class="accordion-box">
					<?php foreach($settings['repeat'] as $key=>$item):?>	
					<li class="accordion block <?php if($key == 1) echo 'active-block';?>">
						<div class="acc-btn <?php if($key == 1) echo 'active';?>">
							<div class="icon-outer"></div>
							<h5><?php echo wp_kses($item['block_title'], $allowed_tags);?></h5>
						</div>
						<div class="acc-content <?php if($key == 1) echo 'current';?>">
							<div class="text">
								<p><?php echo wp_kses($item['block_text'], $allowed_tags);?></p>
							</div>
						</div>
					</li>
					<?php endforeach; ?>	
				</ul>
			</div>
		</section>
		<?php endif ;?>	

		<?php 
	}

}