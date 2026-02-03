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
			'show_search',
			[
				'label' => esc_html__( 'Show Search Bar', 'immigro' ),
				'type'  => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);
		$this->add_control(
			'search_heading',
			[
				'label'       => esc_html__( 'Search Heading', 'immigro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search more blogs', 'immigro' ),
				'placeholder' => esc_html__( 'Enter heading', 'immigro' ),
				'condition'   => [
					'show_search' => 'yes',
				],
			]
		);
		$this->add_control(
			'search_placeholder',
			[
				'label'       => esc_html__( 'Placeholder Search', 'immigro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Fill your keywords', 'immigro' ),
				'placeholder' => esc_html__( 'Enter placeholder', 'immigro' ),
				'condition'   => [
					'show_search' => 'yes',
				],
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
		$this->add_control(
			'show_author',
			[
				'label'   => esc_html__( 'Show Author', 'immigro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_comments',
			[
				'label'   => esc_html__( 'Show Comments', 'immigro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_category',
			[
				'label'   => esc_html__( 'Show Category', 'immigro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	
		
		$this->end_controls_section();

		/* ===== Style controls ===== */
		$this->start_controls_section(
			'style_blog_item_section',
			[
				'label' => esc_html__( 'Blog Item', 'immigro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'blog_item_bg',
			[
				'label'     => esc_html__( 'Background Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .inner-box' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'blog_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'immigro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .news-block-one .lower-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'blog_item_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'immigro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .news-block-one .inner-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .news-block-one .image-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0; overflow: hidden;',
					'{{WRAPPER}} .news-block-one .image-box img' => 'border-radius: inherit;',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'blog_item_shadow',
				'selector' => '{{WRAPPER}} .news-block-one .inner-box',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_title_section',
			[
				'label' => esc_html__( 'Post Title', 'immigro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_title_typography',
				'selector' => '{{WRAPPER}} .news-block-one .post-title a',
			]
		);
		$this->add_control(
			'post_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_title_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_category_section',
			[
				'label' => esc_html__( 'Category', 'immigro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_cat_typography',
				'selector' => '{{WRAPPER}} .news-block-one .post-category-list a',
			]
		);
		$this->add_control(
			'post_cat_color',
			[
				'label'     => esc_html__( 'Text Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-category-list a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_cat_bg',
			[
				'label'     => esc_html__( 'Background Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-category-list a' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_cat_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-category-list a' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_cat_gap',
			[
				'label' => esc_html__( 'Gap (px)', 'immigro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-category-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_meta_section',
			[
				'label' => esc_html__( 'Meta (date, author, comment)', 'immigro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_meta_typography',
				'selector' => '{{WRAPPER}} .news-block-one .post-meta-line .post-meta-item',
			]
		);
		$this->add_control(
			'post_meta_color',
			[
				'label'     => esc_html__( 'Text Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-meta-line .post-meta-item' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_meta_gap',
			[
				'label' => esc_html__( 'Gap Between Items (px)', 'immigro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'default' => [
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .news-block-one .post-meta-line' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_excerpt_section',
			[
				'label' => esc_html__( 'Excerpt / Description', 'immigro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_excerpt_typography',
				'selector' => '{{WRAPPER}} .news-block-one .lower-content p',
			]
		);
		$this->add_control(
			'post_excerpt_color',
			[
				'label'     => esc_html__( 'Text Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .news-block-one .lower-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_excerpt_spacing',
			[
				'label' => esc_html__( 'Spacing Top (px)', 'immigro' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [ 'min' => 0, 'max' => 40 ],
				],
				'default' => [ 'size' => 0 ],
				'selectors' => [
					'{{WRAPPER}} .news-block-one .lower-content p' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_search_section',
			[
				'label' => esc_html__( 'Search Bar', 'immigro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_search' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_heading_typography',
				'selector' => '{{WRAPPER}} .immigro-blog-search-heading',
			]
		);
		$this->add_control(
			'search_heading_color',
			[
				'label'     => esc_html__( 'Heading Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immigro-blog-search-heading' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_input_typography',
			[
				'label' => esc_html__( 'Input Typography', 'immigro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_input_typo',
				'selector' => '{{WRAPPER}} .immigro-blog-search-form input',
			]
		);
		$this->add_control(
			'search_input_color',
			[
				'label'     => esc_html__( 'Input Text Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immigro-blog-search-form input' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_input_bg',
			[
				'label'     => esc_html__( 'Input Background', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immigro-blog-search-form input' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_input_border',
			[
				'label'     => esc_html__( 'Input Border Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immigro-blog-search-form input' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_button_color',
			[
				'label'     => esc_html__( 'Search Icon Color', 'immigro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immigro-blog-search-btn' => 'color: {{VALUE}};',
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
		$search_param = 'immigro_blog_search_' . $this->get_id();
		$search_query = '';
		if ( isset( $_GET[ $search_param ] ) ) {
			$search_query = sanitize_text_field( wp_unslash( $_GET[ $search_param ] ) );
		}
		
        $paged = immigro_set($_POST, 'paged') ? esc_attr($_POST['paged']) : 1;

		$this->add_render_attribute( 'wrapper', 'class', 'templatepath-immigro' );
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => immigro_set( $settings, 'query_number' ),
			'orderby'        => immigro_set( $settings, 'query_orderby' ),
			'order'          => immigro_set( $settings, 'query_order' ),
			'paged'         => $paged
		);
		if ( ! empty( $search_query ) ) {
			$args['s'] = $search_query;
		}
		if ( immigro_set( $settings, 'query_exclude' ) ) {
			$settings['query_exclude'] = explode( ',', $settings['query_exclude'] );
			$args['post__not_in']      = immigro_set( $settings, 'query_exclude' );
		}
		if( immigro_set( $settings, 'query_category' ) ) $args['category_name'] = immigro_set( $settings, 'query_category' );
		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) 
		{ ?>

		<?php
		static $immigro_blog_grid_styles = false;
		if ( ! $immigro_blog_grid_styles ) :
			$immigro_blog_grid_styles = true;
		?>
		<style>
		.templatepath-immigro .news-block-one .inner-box {
			border-radius: 12px;
			overflow: hidden;
			background: #fff;
			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
		}
		.templatepath-immigro .news-block-one .image-box {
			border-radius: 12px 12px 0 0;
			overflow: hidden;
		}
		.templatepath-immigro .news-block-one .image-box img {
			width: 100%;
			height: auto;
			display: block;
			object-fit: cover;
		}
		.templatepath-immigro .news-block-one .lower-content {
			padding: 24px 24px 28px;
		}
		.templatepath-immigro .news-block-one .post-category-list {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
			margin: 0 0 15px 0;
		}
		.templatepath-immigro .news-block-one .post-category-list a {
			display: inline-flex;
			align-items: center;
			padding: 6px 18px;
			border-radius: 30px;
			border: 1px solid #ff7a00;
			background-color: #fff;
			font-size: 14px;
			line-height: 1.2;
			text-transform: capitalize;
			color: #ff7a00;
			transition: all 0.3s ease;
		}
		.templatepath-immigro .news-block-one .post-category-list a:hover {
			background-color: #ff7a00;
			color: #ffffff;
		}
		.templatepath-immigro .news-block-one .post-title {
			margin-bottom: 12px;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			font-weight: 700;
			font-size: 1.25rem;
			line-height: 1.35;
		}
		.templatepath-immigro .news-block-one .post-title a {
			color: #ff7a00;
		}
		.templatepath-immigro .news-block-one .post-title a:hover {
			color: #e66d00;
		}
		.templatepath-immigro .news-block-one .post-meta-line {
			display: flex;
			flex-wrap: wrap;
			gap: 12px;
			margin-bottom: 15px;
			font-size: 14px;
			color: #6f6f6f;
		}
		.templatepath-immigro .news-block-one .post-meta-line .post-meta-item {
			display: inline-flex;
			align-items: center;
			column-gap: 6px;
		}
		.templatepath-immigro .news-block-one .lower-content p {
			font-size: 15px;
			line-height: 1.6;
			color: #555;
			margin-bottom: 0;
		}
		.templatepath-immigro .immigro-blog-search-wrap {
			text-align: center;
			margin-bottom: 30px;
		}
		.templatepath-immigro .immigro-blog-search-form {
			max-width: 520px;
			margin: 0 auto;
			display: flex;
			align-items: center;
			position: relative;
		}
		.templatepath-immigro .immigro-blog-search-form input {
			width: 100%;
			padding: 14px 52px 14px 22px;
			border-radius: 40px;
			border: 1px solid #e0e0e0;
			background: #f8f8f8;
		}
		.templatepath-immigro .immigro-blog-search-form .immigro-blog-search-btn {
			position: absolute;
			right: 12px;
			border: none;
			background: transparent;
			color: #ff7a00;
			font-size: 20px;
			cursor: pointer;
		}
		</style>
		<?php endif; ?>

     <?php  if ( 'style1' === $settings['style'] ) : ?>
		<section class="news-section <?php echo esc_attr($settings['sec_class']);?>">
            <div class="auto-container">
				<?php if ( 'yes' === $settings['show_search'] ) : ?>
					<div class="immigro-blog-search-wrap">
						<?php if ( ! empty( $settings['search_heading'] ) ) : ?>
							<h3 class="immigro-blog-search-heading"><?php echo esc_html( $settings['search_heading'] ); ?></h3>
						<?php endif; ?>
						<form class="immigro-blog-search-form" method="get">
							<input type="text" name="<?php echo esc_attr( $search_param ); ?>" value="<?php echo esc_attr( $search_query ); ?>" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>">
							<button type="submit" class="immigro-blog-search-btn" aria-label="<?php esc_attr_e( 'Search blog posts', 'immigro' ); ?>"><i class="far fa-search"></i></button>
						</form>
					</div>
				<?php endif; ?>
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
									<?php
									if ( ( $settings['show_category'] ?? 'yes' ) === 'yes' ) {
										$categories = get_the_category();
										if ( ! empty( $categories ) ) :
									?>
									<div class="post-category-list">
										<?php foreach ( $categories as $category ) : ?>
											<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-category-link"><?php echo esc_html( $category->name ); ?></a>
										<?php endforeach; ?>
									</div>
									<?php
										endif;
									}
									?>
                                    <h3 class="post-title"><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h3>
									<?php
									$meta_items = [];
									$meta_items[] = '<span class="post-meta-item post-meta-date">' . esc_html( get_the_date() ) . '</span>';
									if ( 'yes' === $settings['show_author'] ) {
										$meta_items[] = '<span class="post-meta-item post-meta-author">' . sprintf( esc_html__( 'By %s', 'immigro' ), esc_html( get_the_author() ) ) . '</span>';
									}
									if ( 'yes' === $settings['show_comments'] ) {
										$comments_number = get_comments_number();
										if ( 0 === $comments_number ) {
											$meta_items[] = '<span class="post-meta-item post-meta-comments">' . esc_html__( 'No comments', 'immigro' ) . '</span>';
										} else {
											$meta_items[] = '<span class="post-meta-item post-meta-comments">' . esc_html( sprintf( _n( '%s comment', '%s comments', $comments_number, 'immigro' ), number_format_i18n( $comments_number ) ) ) . '</span>';
										}
									}
									if ( ! empty( $meta_items ) ) :
									?>
									<div class="post-meta-line">
										<?php echo wp_kses_post( implode( '', $meta_items ) ); ?>
									</div>
									<?php endif; ?>
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
				<?php if ( 'yes' === $settings['show_search'] ) : ?>
					<div class="immigro-blog-search-wrap">
						<?php if ( ! empty( $settings['search_heading'] ) ) : ?>
							<h3 class="immigro-blog-search-heading"><?php echo esc_html( $settings['search_heading'] ); ?></h3>
						<?php endif; ?>
						<form class="immigro-blog-search-form" method="get">
							<input type="text" name="<?php echo esc_attr( $search_param ); ?>" value="<?php echo esc_attr( $search_query ); ?>" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>">
							<button type="submit" class="immigro-blog-search-btn" aria-label="<?php esc_attr_e( 'Search blog posts', 'immigro' ); ?>"><i class="far fa-search"></i></button>
						</form>
					</div>
				<?php endif; ?>
               
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
                                    <h3 class="post-title"><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h3>
									<ul class="post-info clearfix">
										<?php if ( 'yes' === $settings['show_author'] ) : ?>
                                        <li class="admin no-link">
                                            <figure class="admin-thumb">
												<?php echo get_avatar(get_the_author_meta('ID'), 90); ?>
											</figure>
                                            <span><?php printf( esc_html__( 'By %s', 'immigro' ), esc_html( get_the_author() ) ); ?></span>
                                        </li>
										<?php endif; ?>
										<?php if ( 'yes' === $settings['show_comments'] ) : ?>
                                        <li><?php comments_number(); ?></li>
										<?php endif; ?>
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