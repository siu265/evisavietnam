<?php
/**
 * Class Hooks
 */

use Elementor\Plugin;
use Elementor\User;

if ( ! class_exists( 'SLIDERPATH_Hooks' ) ) {
	class SLIDERPATH_Hooks {

		function __construct() {

			add_action( 'init', [ $this, 'register_everything' ] );
			add_action( 'admin_menu', [ $this, 'register_dashboard' ] );
			add_action( 'admin_init', [ $this, 'tablepress_backend_support' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

			add_action( 'pb_settings_before_sliderpath_elements_active', array( $this, 'render_settings_toggler' ) );
			add_action( 'pb_settings_before_sliderpath_elements_ext_active', array( $this, 'render_settings_toggler' ) );
			add_action( 'pb_settings_fields_area', array( $this, 'render_template_library' ) );
			add_action( 'sliderpath_update_data', array( $this, 'update_plugin_data' ) );

			add_action( 'wp_ajax_populate_import_popup', array( $this, 'populate_import_popup' ) );
			add_action( 'wp_ajax_import_element', array( $this, 'import_element' ) );
		}


		/**
		 * Import element
		 */
		function import_element() {
			
			$posted_data   = array_map('sanitize_text_field', $_POST );
			$page_id       = sliderpath()->get_settings_atts( 'page_id', '91', $posted_data );
			$local_page_id = sliderpath()->get_settings_atts( 'local_page_id', '', $posted_data );

			if ( empty( $page_id ) || $page_id === 0 ) {
				wp_send_json_error( esc_html__( 'Something went wrong with the data!', 'slider-path' ) );
			}


			// Checking curl response
			if ( is_wp_error( $response = wp_remote_get( sprintf( '%s/wp-json/sliderpath/page-data/%s', SLIDERPATH_API_URL, $page_id ) ) ) ) {
				wp_send_json_error( $response->get_error_message() );
			}

			// Parsing response data
			$response_data = wp_remote_retrieve_body( $response );
			$response_data = json_decode( $response_data, true );

			// Checking page and creating new page
			if ( empty( $local_page_id ) ) {
				$local_page_id = wp_insert_post( array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => sliderpath()->get_settings_atts( 'page_title', '', $response_data ),
					'post_content' => sliderpath()->get_settings_atts( 'post_content', '', $response_data ),
				) );
			}

			if ( is_wp_error( $local_page_id ) ) {
				wp_send_json_error( esc_html__( 'Something went wrong creating new page!', 'slider-path' ) );
			}

			// Updating response data to new page
			update_post_meta( $local_page_id, '_elementor_data', sliderpath()->get_settings_atts( '_elementor_data', '', $response_data ) );
			update_post_meta( $local_page_id, '_elementor_page_settings', sliderpath()->get_settings_atts( '_elementor_page_settings', '', $response_data ) );
			update_post_meta( $local_page_id, '_elementor_template_type', sliderpath()->get_settings_atts( '_elementor_template_type', '', $response_data ) );
			update_post_meta( $local_page_id, '_elementor_edit_mode', sliderpath()->get_settings_atts( '_elementor_edit_mode', '', $response_data ) );
			update_post_meta( $local_page_id, '_elementor_css', sliderpath()->get_settings_atts( '_elementor_css', '', $response_data ) );
			update_post_meta( $local_page_id, '_elementor_controls_usage', sliderpath()->get_settings_atts( '_elementor_controls_usage', '', $response_data ) );
			update_post_meta( $local_page_id, '_wp_page_template', sliderpath()->get_settings_atts( '_wp_page_template', '', $response_data ) );
			update_post_meta( $local_page_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $local_page_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $local_page_id, '_elementor_template_type', 'wp-page' );
			update_post_meta( $local_page_id, '_elementor_version', ELEMENTOR_VERSION );

			wp_update_post( array(
				'ID'           => $local_page_id,
				'post_content' => sliderpath()->get_settings_atts( 'post_content', '', $response_data ),
			) );

			Plugin::$instance->files_manager->clear_cache();

			wp_send_json_success(
				sprintf( '%s. <a href="%s" target="_blank">%s</a>',
					esc_html__( 'Successfully imported', 'slider-path' ),
					get_the_permalink( $local_page_id ),
					esc_html__( 'View Page', 'slider-path' )
				)
			);
		}


		/**
		 * Return html through ajax response
		 */
		function populate_import_popup() {

			$posted_data   = array_map('sanitize_text_field', $_POST );
			$template_id    = sliderpath()->get_settings_atts( 'template_id', '', $posted_data );
			$template_group = sliderpath()->get_settings_atts( 'template_group', '', $posted_data );
			$template       = sliderpath()->get_template_by_id( $template_id, $template_group );
			$active_plugins = sliderpath()->get_option( 'active_plugins', array() );
			$should_import  = true;

			ob_start();

			// Window title with close icon
			printf( '<h2>%s</h2><span class="dashicons dashicons-no-alt import-close"></span>', esc_html__( 'Template Importing', 'slider-path' ) );


			// Template Name
			printf( '<div class="import-inline"><strong>%s</strong><div class="inline-content">%s</div></div>',
				esc_html__( 'Template Name', 'slider-path' ),
				sliderpath()->get_settings_atts( 'title', '', $template )
			);


			// Required Plugins
			$req_plugins = array();
			foreach ( sliderpath()->get_settings_atts( 'req_plugins', array(), $template ) as $plugin ) {

				if ( empty( $plugin_id = sliderpath()->get_settings_atts( 'id', '', $plugin ) ) ) {
					continue;
				}

				$plugin_label  = sliderpath()->get_settings_atts( 'label', '', $plugin );
				$plugin_is_pro = sliderpath()->get_settings_atts( 'is_pro', false, $plugin );
				$plugin_type   = $plugin_is_pro ? 'pro' : 'free';
				$plugin_url    = $plugin_is_pro ? sliderpath()->get_settings_atts( 'url', '', $plugin ) : esc_url( sprintf( 'https://wordpress.org/plugins/%s', $plugin_id ) );
				$is_activated  = in_array( sprintf( '%1$s/%1$s.php', $plugin_id ), $active_plugins );
				$status_class  = $is_activated ? 'activated' : 'none';
				$req_plugins[] = sprintf( '<a class="status-%s type-%s" href="%s" target="_blank">%s</a>', $status_class, $plugin_type, $plugin_url, $plugin_label );

				if ( ! $is_activated ) {
					$should_import = false;
				}
			}
			if ( ! empty( $req_plugins ) ) {
				printf( '<div class="import-inline"><strong>%s</strong><div class="inline-content"><span class="note">%s</span>%s</div></div>',
					esc_html__( 'Required Plugins', 'slider-path' ),
					esc_html__( 'Please activate these plugins before importing', 'slider-path' ),
					implode( '', $req_plugins )
				);
			}


			// Page Selection
			$pages_array  = sliderpath()->PB_Settings()->get_pages_array();
			$page_options = array_map( function ( $page_name, $page_id ) {
				return sprintf( '<option value="%s">%s</option>', $page_id, $page_name );
			}, $pages_array, array_keys( $pages_array ) );
			printf( '<div class="import-inline"><strong>%s</strong><div class="inline-content"><span class="note">%s</span><select class="sliderpath-select2 local-page-id" >%s</select></div></div>',
				esc_html__( 'Select Page', 'slider-path' ),
				esc_html__( 'Leave empty if you wish to create a new page', 'slider-path' ),
				sprintf( '<option></option>%s', implode( ' ', $page_options ) )
			);
			printf( '<script>jQuery(document).ready(function () {jQuery(".sliderpath-select2").select2({placeholder: "%s"});});</script>', esc_html__( 'Select an option', 'slider-path' ) );


			// Rendering Button
			printf( '<div class="import-inline"><div class="sliderpath-import-button %s" data-page-id="%s">%s</div></div>',
				$should_import ? '' : 'disabled',
				sliderpath()->get_settings_atts( 'page_id', array(), $template ),
				esc_html__( 'Import Now', 'slider-path' )
			);

			wp_send_json_success( ob_get_clean() );
		}


		/**
		 * Update plugin data from api remote server
		 */
		function update_plugin_data() {

			update_option( 'sliderpath_api_response', sliderpath()->get_plugin_data_from_api() );
		}


		/**
		 * Render Template Library
		 *
		 * @param PB_Settings $pb_settings
		 */
		function render_template_library( PB_Settings $pb_settings ) {

			if ( $pb_settings->get_menu_slug() !== 'sliderpath-library' ) {
				return;
			}

			global $template_group;

			if ( ! empty( $template_id = sliderpath()->get_settings_atts( 'template-group', '', wp_unslash( $_GET ) ) ) ) {

				$templates      = sliderpath()->get_plugin_data( 'templates' );
				$template_group = isset( $templates[ $template_id ] ) ? array_merge( array( 'template_group' => $template_id ), $templates[ $template_id ] ) : array();

				include SLIDERPATH_PLUGIN_DIR . 'templates/template-library.php';
			} else {
				include SLIDERPATH_PLUGIN_DIR . 'templates/template-groups.php';
			}
		}


		/**
		 * Render Settings Toggler
		 */
		function render_settings_toggler() {
			printf( '<div class="sliderpath-toggler"><span class="button sliderpath-enable">%s</span><span class="button sliderpath-disable">%s</span></div>',
				esc_html__( 'Enable All', 'slider-path' ),
				esc_html__( 'Disable All', 'slider-path' )
			);
		}


		/**
		 * Add custom category for all widgets
		 *
		 * @param $elements_manager
		 */
		function add_category( $elements_manager ) {

			if ( $elements_manager instanceof Elementor\Elements_Manager ) {
				$elements_manager->add_category( 'sliderpath_category',
					array(
						'title' => esc_html__( 'Slider Path', 'slider-path' ),
					)
				);
			}
		}


		/**
		 * Provide tablepress support
		 */
		function tablepress_backend_support() {

			if ( ! class_exists( 'TablePress' ) ) {
				return;
			}

			TablePress::load_controller( 'frontend' );

			$controller = new TablePress_Frontend_Controller();
			$controller->init_shortcodes();

			add_action( 'admin_enqueue_scripts', array( $controller, 'enqueue_css' ) );
		}


		/**
		 * Render settings dashboard
		 */
		function render_dashboard() {

			wp_enqueue_style( 'bootstrap' );
			wp_enqueue_script( 'bootstrap' );

			include SLIDERPATH_PLUGIN_DIR . 'templates/settings-dashboard.php';
		}


		/**
		 * Register Dashboard Menu
		 */
		function register_dashboard() {

			$page_title = sprintf( esc_html__( '%s - Settings', 'slider-path' ), SLIDERPATH_PLUGIN_NAME );
			$menu_title = esc_html__( 'Slider Path', 'slider-path' );

			add_menu_page( $page_title, $menu_title, 'manage_options', 'sliderpath-settings', [ $this, 'render_dashboard' ], 'dashicons-image-flip-horizontal', 30 );
		}


		/**
		 * Register everything
		 */
		function register_everything() {

			// If team element is active then this will come
			if ( sliderpath()->is_element_active( 'team' ) ) {
				sliderpath()->PB_Settings()->register_post_type( 'team', array(
					'singular'      => esc_html__( 'Team', 'slider-path' ),
					'plural'        => esc_html__( 'Teams', 'slider-path' ),
					'menu_icon'     => 'dashicons-businessperson',
					'menu_position' => 20,
				) );

				sliderpath()->PB_Settings()->register_taxonomy( 'team_cat', 'team', array(
					'singular'     => esc_html__( 'Team Category', 'slider-path' ),
					'plural'       => esc_html__( 'Teams Categories', 'slider-path' ),
					'hierarchical' => true,
				) );
			}

			// Adding library menu
			sliderpath()->PB_Settings( array(
				'add_in_menu'     => true,
				'menu_type'       => 'submenu',
				'menu_title'      => esc_html__( 'Library', 'slider-path' ),
				'menu_name'       => sprintf( esc_html__( 'Template Library', 'slider-path' ), SLIDERPATH_PLUGIN_NAME ),
				'menu_page_title' => sprintf( esc_html__( '%s - Template Library', 'slider-path' ), SLIDERPATH_PLUGIN_NAME ),
				'capability'      => 'manage_options',
				'menu_slug'       => 'sliderpath-library',
				'parent_slug'     => 'sliderpath-settings',
			) );
		}
	}

	new SLIDERPATH_Hooks();
}