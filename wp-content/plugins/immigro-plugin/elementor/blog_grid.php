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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Utils;

/**
 * Elementor button widget.
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Blog_Grid extends Widget_Base {

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'immigro_blog_grid';
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
		return esc_html__( 'Blog Grid', 'immigro' );
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
			'blog_grid',
			[
				'label' => esc_html__( 'Blog Grid', 'immigro' ),
			]
		);
		
		$this->add_control(
            'style', 
				[
					'label'   => esc_html__( 'Choose Different Style', 'rashid' ),
					'label_block' => true,
					'type'    => Controls_Manager::SELECT,
					'default' => 'style1',
					'options' => array(
						'style1' => esc_html__( 'Choose Style 1', 'rashid' ),
						'style2' => esc_html__( 'Choose Style 2', 'rashid' ),
					),
				]
		);
		
		$this->add_control(
            'thumb', 
				[
					'label'   => esc_html__( 'Choose Post Image', 'rashid' ),
					'label_block' => true,
					'type'    => Controls_Manager::SELECT,
					'default' => 'style1',
					'options' => array(
						'style1' => esc_html__( 'Meta Box Image', 'rashid' ),
						'style2' => esc_html__( 'Dafult Thumbnail', 'rashid' ),
					),
				]
		);
		
		$this->add_control(
			'bttn',
			[
				'label'       => __( 'Button', 'immigro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your Button Title', 'immigro' ),
				'default' => esc_html__('Read More', 'immigro'),
			]
		);	
		$this->add_control(
			'column',
			[
				'label'   => esc_html__( 'Column', 'immigro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'12'   => esc_html__( 'One Column', 'immigro' ),
					'6'   => esc_html__( 'Two Column', 'immigro' ),
					'4'   => esc_html__( 'Three Column', 'immigro' ),
					'3'   => esc_html__( 'Four Column', 'immigro' ),
					'2'   => esc_html__( 'Six Column', 'immigro' ),
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
	
		$this->start_controls_section(
				'content_section',
				[
					'label' => __( 'Blog Block', 'immigro' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);
			
		
		$this->add_control(
			'text_limit',
			[
				'label'   => esc_html__( 'Text Limit', 'immigro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 15,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
			]
		);
		$this->add_control(
			'query_number',
			[
				'label'   => esc_html__( 'Number of post', 'immigro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
			]
		);
		$this->add_control(
			'query_orderby',
			[
				'label'   => esc_html__( 'Order By', 'immigro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'immigro' ),
					'title'      => esc_html__( 'Title', 'immigro' ),
					'menu_order' => esc_html__( 'Menu Order', 'immigro' ),
					'rand'       => esc_html__( 'Random', 'immigro' ),
				),
			]
		);
		$this->add_control(
			'query_order',
			[
				'label'   => esc_html__( 'Order', 'immigro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESc' => esc_html__( 'DESC', 'immigro' ),
					'ASC'  => esc_html__( 'ASC', 'immigro' ),
				),
			]
		);
		$this->add_control(
			'query_exclude',
			[
				'label'       => esc_html__( 'Exclude', 'immigro' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Exclude posts, pages, etc. by ID with comma separated.', 'immigro' ),
			]
		);
		$this->add_control(
            'query_category', 
				[
				  'type' => Controls_Manager::SELECT,
				  'label' => esc_html__('Category', 'immigro'),
				  'options' => get_blog_categories()
				]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Enable/Disable Pagination', 'immigro' ),
				'type'     => Controls_Manager::SWITCHER,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enable/Disable Pagination', 'immigro' ),
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
        
        $paged = immigro_set($_POST, 'paged') ? esc_attr($_POST['paged']) : 1;

		$this->add_render_attribute( 'wrapper', 'class', 'templatepath-immigro' );
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => immigro_set( $settings, 'query_number' ),
			'orderby'        => immigro_set( $settings, 'query_orderby' ),
			'order'          => immigro_set( $settings, 'query_order' ),
			'paged'         => $paged
		);
		if ( immigro_set( $settings, 'query_exclude' ) ) {
			$settings['query_exclude'] = explode( ',', $settings['query_exclude'] );
			$args['post__not_in']      = immigro_set( $settings, 'query_exclude' );
		}
		if( immigro_set( $settings, 'query_category' ) ) $args['category_name'] = immigro_set( $settings, 'query_category' );
		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) 
		{ ?>

     <?php  if ( 'style1' === $settings['style'] ) : ?>
		<section class="news-section <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
                <div class="row clearfix">
				<?php while ( $query->have_posts() ) : $query->the_post();
					$meta_image = get_post_meta( get_the_id(), 'meta_image', true );
					?>
                    <div class="col-lg-<?php echo esc_attr($settings['column'], true );?> col-md-6 col-sm-12 news-block mb-4">
                        <div class="news-block-one wow fadeInUp animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="inner-box">
                                <figure class="image-box">
									<a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>">
										<?php  if ( 'style1' === $settings['thumb'] ) : ?>
										<img src="<?php echo wp_get_attachment_url($meta_image['id']);?>" alt="" />
										<?php endif; ?> 
										<?php  if ( 'style2' === $settings['thumb'] ) : ?>      
										<?php  the_post_thumbnail();    ?>
										<?php endif; ?> 
									</a>
								</figure>
                                <div class="lower-content">
                                    <div class="post-date"><span><?php echo get_the_date('d'); ?></span><?php echo get_the_date('M'); ?></div>  
                                    <h3><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h3>
                                    <ul class="post-info clearfix">
                                        <li class="admin">
                                            <figure class="admin-thumb">
												<?php echo get_avatar(get_the_author_meta('ID'), 90); ?>
											</figure>
                                            <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )); ?>">By <?php the_author(); ?></a>
                                        </li>
                                        <li><?php comments_number(); ?></li>
                                    </ul>
                                    <p>
										<?php echo immigro_trim(get_the_content(), $settings['text_limit']); ?>
                                    </p>
									<?php if($settings['bttn']): ?>
                                    <div class="btn-box">
                                        <a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>" class="btn-4"><?php echo $settings['bttn'];?><span></span></a>
                                    </div>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php endwhile; ?>        
                </div>
            </div>
        </section>

		<?php endif;?>
		
		<?php  if ( 'style2' === $settings['style'] ) : ?>
		
			<section class="news-section style-two <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
               
                <div class="row clearfix">
				
				
				<?php while ( $query->have_posts() ) : $query->the_post();
					$meta_image = get_post_meta( get_the_id(), 'meta_image', true );
					?>
				
				
                    <div class="col-xl-<?php echo esc_attr($settings['column'], true );?> col-lg-12 col-md-12 news-block mb-4">
                        <div class="news-block-one wow fadeInUp animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="inner-box">
                                <figure class="image-box">
									<a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>">
										<?php  if ( 'style1' === $settings['thumb'] ) : ?>
										<img src="<?php echo wp_get_attachment_url($meta_image['id']);?>" alt="" />
										<?php endif; ?> 
										<?php  if ( 'style2' === $settings['thumb'] ) : ?>      
										<?php  the_post_thumbnail();    ?>
										<?php endif; ?> 
									</a>
                                    <div class="post-date"><span><?php echo get_the_date('d'); ?></span><?php echo get_the_date('M'); ?></div>
                                </figure>
                                <div class="lower-content">
                                    <h3><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h3>
									<ul class="post-info clearfix">
                                        <li class="admin">
                                            <figure class="admin-thumb">
												<?php echo get_avatar(get_the_author_meta('ID'), 90); ?>
											</figure>
                                            <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )); ?>">By <?php the_author(); ?></a>
                                        </li>
                                        <li><?php comments_number(); ?></li>
                                    </ul>
                                    <p>
										<?php echo immigro_trim(get_the_content(), $settings['text_limit']); ?>
                                    </p>
									<?php if($settings['bttn']): ?>
                                    <div class="btn-box">
                                        <a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>" class="btn-4"><?php echo $settings['bttn'];?><span></span></a>
                                    </div>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php endwhile; ?>      
                </div>
				<?php if($settings['show_pagination']){ ?>
					<div class="pagination-wrapper centred mt_40">
						<?php immigro_the_pagination2(array('total'=>$query->max_num_pages, 'next_text' => ' <i class="far fa-angle-right"></i>', 'prev_text' => '<i class="far fa-angle-left"></i>')); ?>
					</div>
				<?php } ?>
            </div>
        </section>
		<?php endif;?>

		
        <?php }
		wp_reset_postdata();
	}

}