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
class Image_Double_Round_Shape extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_image_double_round_shape';
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
		return esc_html__( 'Image Double Round Shape', 'immigro' );
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
			'image_double_round_shape',
			[
				'label' => esc_html__( 'Image Double Round Shape', 'immigro' ),
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
					'style5'   => esc_html__( 'Style Five', 'rashid' ),
					'style6'   => esc_html__( 'Style Six', 'rashid' ),
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
			'bgimg',
			[
				'label' => esc_html__('Pattern image One', 'rashid'),
				'type' => Controls_Manager::MEDIA,
				'default' => ['url' => Utils::get_placeholder_image_src(),],
			]
		);	

		$this->add_control(
			'bgimg1',
			[
				'label' => esc_html__('Pattern image Two', 'rashid'),
				'type' => Controls_Manager::MEDIA,
				'default' => ['url' => Utils::get_placeholder_image_src(),],
			]
		);	

		$this->add_control(
			'bgimg2',
			[
				'label' => esc_html__('Pattern image Three', 'rashid'),
				'type' => Controls_Manager::MEDIA,
				'default' => ['url' => Utils::get_placeholder_image_src(),],
			]
		);	
			

		$this->add_control(
			'image',
				[
				  'label' => __( 'Main Image', 'rashid' ),
				  'type' => Controls_Manager::MEDIA,
				  'default' => ['url' => Utils::get_placeholder_image_src(),],
				]
		);	

		$this->add_control(
			'alt_text',
			[
				'label'       => __( 'Alt text', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your Description', 'rashid' ),
			]
		);

		$this->add_control(
			'image1',
				[
				  'label' => __( 'Small Image', 'rashid' ),
				  'type' => Controls_Manager::MEDIA,
				  'default' => ['url' => Utils::get_placeholder_image_src(),],
				]
		);	

		$this->add_control(
			'alt_text1',
			[
				'label'       => __( 'Alt text', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your Description', 'rashid' ),
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
		<section class="cta-section <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 image-block">
                        <div class="image_block_two">
                            <div class="image-box">
                                <div class="image-shape">
									<?php  if ( esc_url($settings['bgimg']['id']) ) : ?>
									<div class="shape-1" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>

									<?php  if ( esc_url($settings['bgimg1']['id']) ) : ?>
									<div class="shape-2" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg1']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>
                                    <div class="shape-3"></div>
                                </div>
                                <figure class="image">
									<?php  if ( esc_url($settings['image']['id']) ) : ?>   
									<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
									<?php else :?>
									<div class="noimage"></div>
									<?php endif;?>
								</figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	<?php endif ;?>	

	<?php  if ( 'style2' === $settings['style'] ) : ?>
		<section class="about-style-two p_relative <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 image-column">
                        <div class="image_block_three">
                            <div class="image-box ml_30">
                                <div class="image-shape">
									<?php  if ( esc_url($settings['bgimg']['id']) ) : ?>
									<div class="shape-1" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>

									<?php  if ( esc_url($settings['bgimg1']['id']) ) : ?>
									<div class="shape-2" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg1']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>
                                    <div class="shape-3"></div>
                                </div>
                                <figure class="image image-1">
									<?php  if ( esc_url($settings['image']['id']) ) : ?>   
									<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
									<?php else :?>
									<div class="noimage"></div>
									<?php endif;?>
								</figure>
                                <figure class="image image-2">
									<?php  if ( esc_url($settings['image1']['id']) ) : ?>   
									<img src="<?php echo wp_get_attachment_url($settings['image1']['id']);?>" alt="<?php echo esc_attr($settings['alt_text1']);?>"/>
									<?php else :?>
									<div class="noimage"></div>
									<?php endif;?>
								</figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	<?php endif ;?>	


	<?php  if ( 'style3' === $settings['style'] ) : ?>
		<section class="country-style-two <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="image_block_four">
                            <div class="image-box pr_30">
								<?php  if ( esc_url($settings['bgimg']['id']) ) : ?>
								<div class="image-shape" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg']['id']);?>);"></div>
								<?php else :?>	
								<div class="noimage"></div>
								<?php endif;?>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                        <figure class="image">
											<?php  if ( esc_url($settings['image']['id']) ) : ?>   
											<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
											<?php else :?>
											<div class="noimage"></div>
											<?php endif;?>
										</figure>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                        <figure class="image">
											<?php  if ( esc_url($settings['image1']['id']) ) : ?>   
											<img src="<?php echo wp_get_attachment_url($settings['image1']['id']);?>" alt="<?php echo esc_attr($settings['alt_text1']);?>"/>
											<?php else :?>
											<div class="noimage"></div>
											<?php endif;?>
										</figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>	
	<?php endif ;?>	

	<?php  if ( 'style4' === $settings['style'] ) : ?>

		<section class=" p_relative <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row align-items-center clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 image-column">
                        <div class="image_block_five">
                            <div class="image-box">
                                <div class="image-shape">

									<?php  if ( esc_url($settings['bgimg']['id']) ) : ?>
									<div class="shape-1" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>

									<?php  if ( esc_url($settings['bgimg1']['id']) ) : ?>
									<div class="shape-2" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg1']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>

									<?php  if ( esc_url($settings['bgimg2']['id']) ) : ?>
									<div class="shape-3" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg2']['id']);?>);"></div>
									<?php else :?>	
									<div class="noimage"></div>
									<?php endif;?>
									
                                    <div class="shape-4"></div>
                                </div>
                                <figure class="image">
									<?php  if ( esc_url($settings['image']['id']) ) : ?>   
									<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
									<?php else :?>
									<div class="noimage"></div>
									<?php endif;?>
								</figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

	<?php endif ;?>	

	<?php  if ( 'style5' === $settings['style'] ) : ?>
		<div class="col-lg-12 col-md-12 col-sm-12 image-column <?php echo esc_attr($settings['sec_class']);?>">
			<div class="image_block_six">
				<div class="image-box">
					<div class="image-shape">
						<?php  if ( esc_url($settings['bgimg']['id']) ) : ?>
						<div class="shape-1" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg']['id']);?>);"></div>
						<?php else :?>	
						<div class="noimage"></div>
						<?php endif;?>
						<div class="shape-2"></div>
					</div>
					<figure class="image">
						<?php  if ( esc_url($settings['image']['id']) ) : ?>   
						<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
						<?php else :?>
						<div class="noimage"></div>
						<?php endif;?>
					</figure>
				</div>
			</div>
		</div>
	<?php endif ;?>	

	<?php  if ( 'style6' === $settings['style'] ) : ?>
	<section class="about-ex-section p_relative p-0">
		<div class="col-lg-12 col-md-12 col-sm-12 image-column">
			<div class="image-box">
				<div class="image-shape">
					<?php  if ( esc_url($settings['bgimg']['id']) ) : ?>
					<div class="shape-1" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg']['id']);?>);"></div>
					<?php else :?>	
					<div class="noimage"></div>
					<?php endif;?>

					<?php  if ( esc_url($settings['bgimg1']['id']) ) : ?>
					<div class="shape-2" style="background-image: url(<?php echo wp_get_attachment_url($settings['bgimg1']['id']);?>);"></div>
					<?php else :?>	
					<div class="noimage"></div>
					<?php endif;?>

					<div class="shape-3"></div>
				</div>
				<figure class="image image-1">
					<?php  if ( esc_url($settings['image']['id']) ) : ?>   
					<img src="<?php echo wp_get_attachment_url($settings['image']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
					<?php else :?>
					<div class="noimage"></div>
					<?php endif;?>
				</figure>
				<figure class="image image-2">
					<?php  if ( esc_url($settings['image1']['id']) ) : ?>   
					<img src="<?php echo wp_get_attachment_url($settings['image1']['id']);?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/>
					<?php else :?>
					<div class="noimage"></div>
					<?php endif;?>
				</figure>
			</div>
		</div>
	</section>
	<?php endif ;?>	


             
		<?php 
	}

}