<?php
/**
 * Class - Widget Base
 */

use Elementor\Widget_Base;

abstract class SLIDERPATH_Widget_base extends Widget_Base {


	/**
	 * Get widget title
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return apply_filters( 'sliderpath_filters_widget_title', $this->get_this_widget( 'title', esc_html__( 'Widget - Widget Pack', 'slider-path' ) ), $this );
	}


	/**
	 * Return anything of a widget or the whole widget data
	 *
	 * @param string $thing
	 * @param string $default
	 *
	 * @return array|mixed|string
	 */
	public function get_this_widget( $thing = '', $default = '' ) {

		$widgets         = sliderpath()->get_widgets();
		$this_widget     = isset( $widgets[ $this->get_widget_slug() ] ) ? $widgets[ $this->get_widget_slug() ] : array();

		if ( ! empty( $thing ) ) {
			return isset( $this_widget[ $thing ] ) ? $this_widget[ $thing ] : $default;
		}

		return $this_widget;
	}


	/**
	 * Return widget slug
	 *
	 * @param bool $is_raw
	 *
	 * @return mixed
	 */
	public function get_widget_slug( $is_raw = false ) {

		$slug = $this->get_name();
		$slug = $is_raw ? str_replace( 'sliderpath-', '', $slug ) : str_replace( '-', '_', $slug );

		return apply_filters( 'sliderpath_filters_widget_slug', $slug, $this );
	}


	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return apply_filters( 'sliderpath_filters_widget_title', $this->get_this_widget( 'icon', 'fa fa-heart' ), $this );
	}


	/**
	 * Return Category for this widget
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'sliderpath_category' ];
	}


	/**
	 * Add common controls on this widget
	 */
	public function add_common_controls() {
		$this->add_control( 'style',
			[
				'label'   => esc_html__( 'Select Style', 'slider-path' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 1,
				'options' => sliderpath()->load_widget_styles( $this->get_this_widget( 'styles' ) ),
			]
		);
	}


	/**
	 * Return array of taxonomies
	 *
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public function sliderpath_get_taxonomies( $taxonomy ) {
		$taxonomies = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		) );

		if ( ! empty( $taxonomies ) && ! is_wp_error( $taxonomies ) ) {
			foreach ( $taxonomies as $category ) {
				$options[ $category->term_id ] = $category->name . ' (' . $category->count . ')';
			}

			return $options;

		}
	}


	/**
	 * Return array of post type
	 *
	 * @param $post_type
	 *
	 * @return mixed
	 */
	function sliderpath_get_post_type( $post_type ) {
		$post_lists = get_posts( array(
			'post_type' => $post_type,
			'showposts' => - 1,
		) );

		if ( ! empty( $post_lists ) && ! is_wp_error( $post_lists ) ) {
			foreach ( $post_lists as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}

			return $options;
		}
	}


	/**
	 * Return ninja forms data
	 *
	 * @return array
	 */
	function sliderpath_get_ninja_forms_data() {
		if ( ! class_exists( 'Ninja_Forms' ) ) {
			return array();
		}

		$ninja_forms = array();
		foreach ( ninja_forms_get_all_forms() as $ninja_form ) {
			if ( ! empty( $form_id = sliderpath()->get_settings_atts( 'id', '', $ninja_form ) ) ) {
				$form_data = sliderpath()->get_settings_atts( 'data', array(), $ninja_form );

				$ninja_forms[ $form_id ] = sliderpath()->get_settings_atts( 'title', '', $form_data );
			}
		}

		return $ninja_forms;
	}


	/**
	 * Return keywords so that widget can easily find out from the search
	 *
	 * @return array|void
	 */
	public function get_keywords() {
		return apply_filters( 'sliderpath_filters_widget_keywords', $this->get_this_widget( 'keywords', array( 'slider-path' ) ), $this );
	}


	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
	}


	/**
	 * Render output for this widget
	 */
	protected function render() {

		sliderpath_do_settings( $this );

		$views = $this->get_this_widget( 'views' );
		$style = sliderpath()->get_settings_atts( 'style', 1 );

		sliderpath_get_template( sprintf( 'views/%s.php', sliderpath()->get_settings_atts( $style, 'template-1', $views ) ), $this );
	}
}