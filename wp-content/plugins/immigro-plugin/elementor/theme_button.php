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
class Theme_Button extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_theme_button';
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
		return esc_html__( 'Theme Button', 'immigro' );
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
			'theme_button',
			[
				'label' => esc_html__( 'Theme Button', 'immigro' ),
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
					'style4'   => esc_html__( 'Style Four', 'rashid' ),
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
			'bttn',
			[
				'label'       => __( 'Button', 'rashid' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your Button Title', 'rashid' ),
				'default' => esc_html__('Read More', 'rashid'),
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
		<div class="btn-box <?php echo esc_attr($settings['sec_class']);?>">
			<a href="<?php echo esc_url($settings['btnlink']['url']);?>" class="btn-1"><?php echo $settings['bttn'];?>
				<span></span>
			</a>
		</div>
		<?php endif ;?>	

		<?php  if ( 'style2' === $settings['style'] ) : ?>
		<div class="btn-box <?php echo esc_attr($settings['sec_class']);?>">
			<a href="<?php echo esc_url($settings['btnlink']['url']);?>" class="btn-4"><?php echo $settings['bttn'];?>
				<span></span>
			</a>
		</div>
		<?php endif ;?>

		<?php  if ( 'style3' === $settings['style'] ) : ?>
		<div class="btn-box <?php echo esc_attr($settings['sec_class']);?>">
			<a href="<?php echo esc_url($settings['btnlink']['url']);?>" class="btn-3"><?php echo $settings['bttn'];?>
				<span></span>
			</a>
		</div>
		<?php endif ;?>

		<?php  if ( 'style4' === $settings['style'] ) : ?>
		<div class="btn-box <?php echo esc_attr($settings['sec_class']);?>">
			<a href="<?php echo esc_url($settings['btnlink']['url']);?>" class="btn-5"><?php echo $settings['bttn'];?>
				<span></span>
			</a>
		</div>
		<?php endif ;?>


             
		<?php 
	}

}