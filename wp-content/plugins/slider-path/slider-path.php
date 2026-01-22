<?php
/**
 * Plugin Name: Slider Path
 * Plugin URI: https://wordpress.org/plugins/slider-path/
 * Description: Slider Path is a Addon for Elemntor Slider Making
 * Version: 3.0.0
 * Author: Rashid87
 * Text Domain: slider-path
 * Domain Path: /languages/
 * Author URI: http://mahfuzrashid.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


defined( 'ABSPATH' ) || exit;

// This is file area
define( 'SLIDERPATH_PLUGIN_URL', plugins_url( 'slider-path' ) . '/' );
defined( 'SLIDERPATH_PLUGIN_DIR' ) || define( 'SLIDERPATH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'SLIDERPATH_PLUGIN_FILE' ) || define( 'SLIDERPATH_PLUGIN_FILE', plugin_basename( __FILE__ ) );
//This is Plugin Name area
defined( 'SLIDERPATH_PLUGIN_NAME' ) || define( 'SLIDERPATH_PLUGIN_NAME', esc_html( 'Slider Path' ) );
//This is the Amdin area
defined( 'SLIDERPATH_PLUGIN_TUTORIAL' ) || define( 'SLIDERPATH_PLUGIN_TUTORIAL', 'https://finestwp.com/sliderpath/' );
defined( 'SLIDERPATH_PLUGIN_DOC' ) || define( 'SLIDERPATH_PLUGIN_DOC', 'https://finestwp.com/doc/sliderpath/' );
defined( 'SLIDERPATH_PLUGIN_DEMO' ) || define( 'SLIDERPATH_PLUGIN_DEMO', 'https://finestwp.com/sliderpath/' );
defined( 'SLIDERPATH_PLUGIN_CONTACT' ) || define( 'SLIDERPATH_PLUGIN_CONTACT', 'https://finestwp.com/sliderpath/contact/' );
//This URL is for Memo Import API area
defined( 'SLIDERPATH_API_URL' ) || define( 'SLIDERPATH_API_URL', esc_url( 'https://finestwp.com/sliderpath/' ) );
//This is Version Area
defined( 'SLIDERPATH_VERSION' ) || define( 'SLIDERPATH_VERSION', '1.0.0' );
 

function sliderpath_scripts() {
    
	//bootstrap css
    $handle_bootstrap_css = 'sliderpath_bootstrap';
	wp_enqueue_style( $handle_bootstrap_css , SLIDERPATH_PLUGIN_URL . 'assets/front/css/sliderpath_bootstrap.css', array(), SLIDERPATH_VERSION );
    if ( isset( $handle_bootstrap_css ) || empty( $handle_bootstrap_css ) ) {
           // wp_enqueue_style( $handle_bootstrap_css , SLIDERPATH_PLUGIN_URL . 'assets/front/css/sliderpath_bootstrap.css', array(), SLIDERPATH_VERSION );
    }
	//owl css
	$handle_owl_css = 'sliderpath_owl';
	wp_enqueue_style( $handle_owl_css , SLIDERPATH_PLUGIN_URL . 'assets/front/css/sliderpath_owl.css', array(), SLIDERPATH_VERSION );
    if ( isset( $handle_owl_css ) || empty( $handle_owl_css ) ) {
              //wp_enqueue_style( $handle_owl_css , SLIDERPATH_PLUGIN_URL . 'assets/front/css/sliderpath_owl.css', array(), SLIDERPATH_VERSION );
    }

    wp_enqueue_style( 'slider-path-pb-core', SLIDERPATH_PLUGIN_URL . 'assets/front/css/pb-core-styles.css', array(), SLIDERPATH_VERSION );
	
	//Style css
	 wp_enqueue_style( 'slider-path-main', SLIDERPATH_PLUGIN_URL . 'assets/front/css/main.css', array(), SLIDERPATH_VERSION );


	//owl js
     $handle_owl_js = 'sliderpath_owl';
	wp_enqueue_script( $handle_owl_js , SLIDERPATH_PLUGIN_URL . 'assets/front/js/sliderpath_owl.js', array( 'jquery' ), SLIDERPATH_VERSION, false );
    if ( ! isset( $handle_owl_js ) || empty( $handle_owl_js ) ) {
       //wp_enqueue_script( $handle_owl_js , SLIDERPATH_PLUGIN_URL . 'assets/front/js/sliderpath_owl.js', array( 'jquery' ), SLIDERPATH_VERSION, false );
    }
	
    //sliderscript js
     $handle_sliderscript_js = 'sliderscript';
	  wp_enqueue_script( $handle_sliderscript_js , SLIDERPATH_PLUGIN_URL . 'assets/front/js/sliderscript.js', array( 'jquery' ), SLIDERPATH_VERSION, false );
    if ( ! isset( $handle_sliderscript_js ) || empty( $handle_sliderscript_js ) ) {
       //wp_enqueue_script( $handle_sliderscript_js , SLIDERPATH_PLUGIN_URL . 'assets/front/js/sliderscript.js', array( 'jquery' ), SLIDERPATH_VERSION, false );
    }
     

   }

add_action( 'wp_enqueue_scripts', 'sliderpath_scripts' );

if ( ! class_exists( 'SLIDERPATH_Main' ) ) {
    /**
     * Class SLIDERPATH_Main
     */
    class SLIDERPATH_Main {

        function __construct() {

            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
            add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
            add_action( 'init', [ $this, 'i18n' ] );
            add_action( 'plugins_loaded', [ $this, 'include_files' ] );
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
            add_action( 'elementor/frontend/before_enqueue_styles', [ $this, 'register_styles' ] );
            add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );

            register_activation_hook( __FILE__, [ $this, 'plugin_activated' ] );
        }


        /**
         * Register plugin activation hook
         */
        function plugin_activated() {

            // Adding cron schedule
            if ( ! wp_next_scheduled( 'sliderpath_update_data' ) ) {
                wp_schedule_event( time(), 'daily', 'sliderpath_update_data' );
            }
        }


        /**
         * Register all widgets
         */
        function register_widgets() {

            include_once( SLIDERPATH_PLUGIN_DIR . 'includes/classes/class-widget-base.php' );

            $elements_ext_active = sliderpath()->get_option( 'sliderpath_elements_ext_active', array_keys( sliderpath()->get_widgets_options( 'external' ) ) );
            
            $elements_ext_active = empty( $elements_ext_active ) || ! is_array( $elements_ext_active ) ? array() : $elements_ext_active;
            $elements_active     = sliderpath()->get_option( 'sliderpath_elements_active', array_keys( sliderpath()->get_widgets_options( 'self' ) ) );
            $elements_active     = array_merge( $elements_active, $elements_ext_active );
            $elements_active     = empty( $elements_active ) || ! is_array( $elements_active ) ? array() : $elements_active;

            foreach ( sliderpath()->get_widgets() as $widget_slug => $widget ) {
                if ( in_array( $widget_slug, $elements_active ) ) {
                    $widget_slug = str_replace( 'sliderpath_', '', $widget_slug );
                    $widget_slug = str_replace( '_', '-', $widget_slug );
                    $class_name  = sliderpath()->get_settings_atts( 'class_name', '', $widget );
                    sliderpath()->include_widget_class( $widget_slug );
                    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name );
                }
            }
        }

        /**
         * Includes classes and Functions
         */
        public function include_files() {
            include_once( SLIDERPATH_PLUGIN_DIR . 'includes/classes/class-pb-settings.php' );
            include_once( SLIDERPATH_PLUGIN_DIR . 'includes/classes/class-functions.php' );
            include_once( SLIDERPATH_PLUGIN_DIR . 'includes/classes/class-hooks.php' );
            include_once( SLIDERPATH_PLUGIN_DIR . 'includes/functions.php' );

        }

        public function enqueue_editor_styles() {
            wp_enqueue_style( 'sliderpath-admin', SLIDERPATH_PLUGIN_URL . 'assets/admin/css/style.css' );
        }

        /**
         *  Register site scripts
         */
        public function register_scripts() {

            wp_register_script( 'sliderpath-script', SLIDERPATH_PLUGIN_URL . 'assets/front/js/script.js', array( 'jquery' ), SLIDERPATH_VERSION, false );
           
        }

        /**
         * Register site styles
         */
        public function register_styles() {
      
            wp_register_style( 'medical', SLIDERPATH_PLUGIN_URL . 'assets/front/css/medical.css', array(), SLIDERPATH_VERSION );
            
            
        }

        /**
         * Localize Scripts
         *
         * @return mixed|void
         */
        function localize_scripts() {
            return apply_filters( 'sliderpath_filters_localize_scripts', array(
                'ajaxurl'           => admin_url( 'admin-ajax.php' ),
                'importingText'     => esc_html__( 'Importing...', 'slider-path' ),
                'confirmRemoveText' => esc_html__( 'Do you really want to remove this item?', 'slider-path' ),
            ) );
        }

        /**
         * Load Admin Scripts
         */
        function admin_scripts() {
            wp_enqueue_style( 'sliderpath-core', SLIDERPATH_PLUGIN_URL . 'assets/admin/css/core-style.css' );
            wp_enqueue_style( 'sliderpath-admin', SLIDERPATH_PLUGIN_URL . 'assets/admin/css/style.css' );
            wp_enqueue_script( 'sliderpath-admin', SLIDERPATH_PLUGIN_URL . 'assets/admin/js/scripts.js', array( 'jquery', 'jquery-ui-sortable' ) );
            wp_localize_script( 'sliderpath-admin', 'sliderpath', $this->localize_scripts() );
            wp_register_style( 'bootstrap', SLIDERPATH_PLUGIN_URL . 'assets/admin/css/bootstrap.css' );
            wp_register_script( 'bootstrap', SLIDERPATH_PLUGIN_URL . 'assets/admin/js/bootstrap.min.js', array( 'jquery' ) );
            wp_enqueue_style( 'select2', SLIDERPATH_PLUGIN_URL . 'assets/admin/css/select2.min.css' );
            wp_enqueue_script( 'select2', SLIDERPATH_PLUGIN_URL . 'assets/admin/js/select2.min.js', array( 'jquery' ) );
        }

        /**
         * Language and Textdomain
         */
        public function i18n() {
            load_plugin_textdomain( 'slider-path', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
        }
    }

    new SLIDERPATH_Main();
}

 function slider_pat_elementor_template_x_($type = null) {
        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];
        if ($type) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }
        $template = get_posts($args);
        $tpl = array();
        if (!empty($template) && !is_wp_error($template)) {
            foreach ($template as $post) {
                $tpl[$post->post_name] = $post->post_title;
            }
        }
        return $tpl;
    } 

 function slider_pat_elementor_template_($type = null) {
        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];
        if ($type) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }
        $template = get_posts($args);
        $tpl = array();
        if (!empty($template) && !is_wp_error($template)) {
            foreach ($template as $post) {
                $tpl[$post->post_name] = $post->post_title;
            }
        }
        return $tpl;
    } 


    function slider_path_elemntor_content( $slug ) {
  
        $content_post = get_posts(array(
            'name' => $slug,
            'posts_per_page' => 1,
            'post_type' => 'elementor_library',
            'post_status' => 'publish'
        ));
        if (array_key_exists(0, $content_post) == true) {
            $id = $content_post[0]->ID;
            return $id;
        }
    }


