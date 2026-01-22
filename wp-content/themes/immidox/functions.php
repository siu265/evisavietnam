<?php

require_once get_template_directory() . '/includes/loader.php';

add_action( 'after_setup_theme', 'immigro_setup_theme' );
add_action( 'after_setup_theme', 'immigro_load_default_hooks' );


function immigro_setup_theme() {

	load_theme_textdomain( 'immigro', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-lightbox');
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );


	// Set the default content width.
	$GLOBALS['content_width'] = 525;
	
	/*---------- Register image sizes ----------*/
	
	//Register image sizes
	add_image_size( 'immigro_370x310', 370, 310, true ); //'immigro_370x310 Our Services'
	add_image_size( 'immigro_70x70', 70, 70, true ); //'immigro_70x70 Our Testimonials'
	add_image_size( 'immigro_370x290', 370, 290, true ); //'immigro_370x290 Latest News'
	add_image_size( 'immigro_440x305', 440, 305, true ); //'immigro_440x305 Our Team'
	add_image_size( 'immigro_310x305', 310, 305, true ); //'immigro_310x305 Our Team V2'
	add_image_size( 'immigro_1170x440', 1170, 440, true ); //'immigro_1170x440 Our Blog'
	/*---------- Register image sizes ends ----------*/
	
	
	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'main_menu' => esc_html__( 'Main Menu', 'immigro' ),
		'onepage_menu' => esc_html__( 'OnePage Menu', 'immigro' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'      => 250,
		'height'     => 250,
		'flex-width' => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style();
	add_action( 'admin_init', 'immigro_admin_init', 2000000 );
}

/**
 * [immigro_admin_init]
 *
 * @param  array $data [description]
 *
 * @return [type]       [description]
 */


function immigro_admin_init() {
	remove_action( 'admin_notices', array( 'ReduxFramework', '_admin_notices' ), 99 );
}

/*---------- Sidebar settings ----------*/

/**
 * [immigro_widgets_init]
 *
 * @param  array $data [description]
 *
 * @return [type]       [description]
 */
function immigro_widgets_init() {

	global $wp_registered_sidebars;

	$theme_options = get_theme_mod( 'immigro' . '_options-mods' );

	register_sidebar( array(
		'name'          => esc_html__( 'Default Sidebar', 'immigro' ),
		'id'            => 'default-sidebar',
		'description'   => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'immigro' ),
		'before_widget' => '<div id="%1$s" class="mrwidget widget sidebar-widget single-sidebar %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
	register_sidebar(array(
		'name' => esc_html__('Footer Widget', 'immigro'),
		'id' => 'footer-sidebar',
		'description' => esc_html__('Widgets in this area will be shown in Footer Area.', 'immigro'),
		'before_widget'=>'<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 footer-column"><div id="%1$s" class="footer-widget single-footer-widget %2$s">',
		'after_widget'=>'</div></div>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	));
	if ( class_exists( '\Elementor\Plugin' )){
	register_sidebar(array(
		'name' => esc_html__('RTL Footer Widget', 'immigro'),
		'id' => 'footer-sidebar-2',
		'description' => esc_html__('Widgets in this area will be shown in Footer Area.', 'immigro'),
		'before_widget'=>'<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 footer-column"><div id="%1$s" class="footer-widget single-footer-widget %2$s">',
		'after_widget'=>'</div></div>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	));
	register_sidebar(array(
		'name' => esc_html__('Services Widget', 'immigro'),
		'id' => 'service-sidebar',
		'description'   => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'immigro' ),
		'before_widget' => '<div id="%1$s" class="mrwidget widget sidebar-widget single-sidebar %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	));
	}
	register_sidebar(array(
	  'name' => esc_html__( 'Blog Listing', 'immigro' ),
	  'id' => 'blog-sidebar',
'description'   => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'immigro' ),
		'before_widget' => '<div id="%1$s" class="mrwidget widget sidebar-widget single-sidebar %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	));
	if ( ! is_object( immigro_WSH() ) ) {
		return;
	}

	$sidebars = immigro_set( $theme_options, 'custom_sidebar_name' );

	foreach ( array_filter( (array) $sidebars ) as $sidebar ) {

		if ( immigro_set( $sidebar, 'topcopy' ) ) {
			continue;
		}

		$name = $sidebar;
		if ( ! $name ) {
			continue;
		}
		$slug = str_replace( ' ', '_', $name );

		register_sidebar( array(
			'name'          => $name,
			'id'            => sanitize_title( $slug ),
			'before_widget' => '<div id="%1$s" class="%2$s widget single-sidebar">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h3>',
			'after_title'   => '</h3></div>',
		) );
	}

	update_option( 'wp_registered_sidebars', $wp_registered_sidebars );
}

add_action( 'widgets_init', 'immigro_widgets_init' );

/*---------- Sidebar settings ends ----------*/

/*---------- Gutenberg settings ----------*/

function immigro_gutenberg_editor_palette_styles() {
    add_theme_support( 'editor-color-palette', array(
        array(
            'name' => esc_html__( 'strong yellow', 'immigro' ),
            'slug' => 'strong-yellow',
            'color' => '#f7bd00',
        ),
        array(
            'name' => esc_html__( 'strong white', 'immigro' ),
            'slug' => 'strong-white',
            'color' => '#fff',
        ),
		array(
            'name' => esc_html__( 'light black', 'immigro' ),
            'slug' => 'light-black',
            'color' => '#242424',
        ),
        array(
            'name' => esc_html__( 'very light gray', 'immigro' ),
            'slug' => 'very-light-gray',
            'color' => '#797979',
        ),
        array(
            'name' => esc_html__( 'very dark black', 'immigro' ),
            'slug' => 'very-dark-black',
            'color' => '#000000',
        ),
    ) );
	
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name' => esc_html__( 'Small', 'immigro' ),
			'size' => 10,
			'slug' => 'small'
		),
		array(
			'name' => esc_html__( 'Normal', 'immigro' ),
			'size' => 15,
			'slug' => 'normal'
		),
		array(
			'name' => esc_html__( 'Large', 'immigro' ),
			'size' => 24,
			'slug' => 'large'
		),
		array(
			'name' => esc_html__( 'Huge', 'immigro' ),
			'size' => 36,
			'slug' => 'huge'
		)
	) );
	
}
add_action( 'after_setup_theme', 'immigro_gutenberg_editor_palette_styles' );

/*---------- Gutenberg settings ends ----------*/

/*---------- Enqueue Styles and Scripts ----------*/

function immigro_enqueue_scripts() {

	
    //styles
	wp_enqueue_style( 'fontawesome-all', get_template_directory_uri() . '/assets/css/font-awesome-all.css' );
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/assets/css/flaticon.css' );
	wp_enqueue_style( 'owl', get_template_directory_uri() . '/assets/css/owl.css' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css' );
	wp_enqueue_style( 'jquery-fancybox', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.css' );
	wp_enqueue_style( 'nice-select', get_template_directory_uri() . '/assets/css/nice-select.css' );
	
	
	
	//Peefixe Change  CSS
	wp_enqueue_style( 'immigro-color', get_template_directory_uri() . '/assets/css/color.css' );
	wp_enqueue_style( 'immigro-elpath', get_template_directory_uri() . '/assets/css/elpath.css' );
	wp_enqueue_style( 'immigro-banner', get_template_directory_uri() . '/assets/css/module-css/banner.css' );
	wp_enqueue_style( 'immigro-feature', get_template_directory_uri() . '/assets/css/module-css/feature.css' );
	wp_enqueue_style( 'immigro-visa', get_template_directory_uri() . '/assets/css/module-css/visa.css' );
	wp_enqueue_style( 'immigro-about', get_template_directory_uri() . '/assets/css/module-css/about.css' );
	wp_enqueue_style( 'immigro-country', get_template_directory_uri() . '/assets/css/module-css/country.css' );
	wp_enqueue_style( 'immigro-working-process', get_template_directory_uri() . '/assets/css/module-css/working-process.css' );
	wp_enqueue_style( 'immigro-funfact', get_template_directory_uri() . '/assets/css/module-css/funfact.css' );
	wp_enqueue_style( 'immigro-team', get_template_directory_uri() . '/assets/css/module-css/team.css' );
	wp_enqueue_style( 'immigro-cta', get_template_directory_uri() . '/assets/css/module-css/cta.css' );
	wp_enqueue_style( 'immigro-clients', get_template_directory_uri() . '/assets/css/module-css/clients.css' );
	wp_enqueue_style( 'immigro-news', get_template_directory_uri() . '/assets/css/module-css/news.css' );
	wp_enqueue_style( 'immigro-search', get_template_directory_uri() . '/assets/css/module-css/search.css' );
	wp_enqueue_style( 'immigro-skills', get_template_directory_uri() . '/assets/css/module-css/skills.css' );
	wp_enqueue_style( 'immigro-coaching', get_template_directory_uri() . '/assets/css/module-css/coaching.css' );
	wp_enqueue_style( 'immigro-search-field', get_template_directory_uri() . '/assets/css/module-css/search-field.css' );
	wp_enqueue_style( 'immigro-testimonial', get_template_directory_uri() . '/assets/css/module-css/testimonial.css' );
	wp_enqueue_style( 'immigro-subscribe', get_template_directory_uri() . '/assets/css/module-css/subscribe.css' );
	wp_enqueue_style( 'immigro-faq', get_template_directory_uri() . '/assets/css/module-css/faq.css' );
	wp_enqueue_style( 'immigro-visa-details', get_template_directory_uri() . '/assets/css/module-css/visa-details.css' );
	wp_enqueue_style( 'immigro-subscribe', get_template_directory_uri() . '/assets/css/module-css/subscribe.css' );
	wp_enqueue_style( 'immigro-page-title', get_template_directory_uri() . '/assets/css/module-css/page-title.css' );
	wp_enqueue_style( 'immigro-sidebar', get_template_directory_uri() . '/assets/css/module-css/sidebar.css' );
	wp_enqueue_style( 'immigro-contact', get_template_directory_uri() . '/assets/css/module-css/contact.css' );
	wp_enqueue_style( 'immigro-blog', get_template_directory_uri() . '/assets/css/module-css/blog.css' );
	wp_enqueue_style( 'immigro-error', get_template_directory_uri() . '/assets/css/module-css/error.css' );	
	
	//main
	wp_enqueue_style( 'immigro-main', get_stylesheet_uri() );
	wp_enqueue_style( 'immigro-style', get_template_directory_uri() . '/assets/css/style.css' );
	wp_enqueue_style( 'immigro-style-2', get_template_directory_uri() . '/assets/css/style-2.css' );
	wp_enqueue_style( 'immigro-responsive', get_template_directory_uri() . '/assets/css/responsive.css' );
	
	// Custom CSS
	wp_enqueue_style( 'immigro-custom', get_template_directory_uri() . '/custom.css', array(), '1.0.0' );	
	wp_enqueue_style( 'immigro-error', get_template_directory_uri() . '/assets/css/theme/error.css' );
	wp_enqueue_style( 'immigro-comment', get_template_directory_uri() . '/assets/css/theme/comment.css' );
	wp_enqueue_style( 'immigro-fixing', get_template_directory_uri() . '/assets/css/theme/fixing.css' );
	wp_enqueue_style( 'immigro-loader-min', get_template_directory_uri() . '/assets/css/theme/loader.min.css' );
	wp_enqueue_style( 'immigro-sidebar', get_template_directory_uri() . '/assets/css/theme/sidebar.css' );
	wp_enqueue_style( 'immigro-tut', get_template_directory_uri() . '/assets/css/theme/tut.css' );	
	
	// Script
	wp_enqueue_script( 'appear', get_template_directory_uri().'/assets/js/appear.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'popper', get_template_directory_uri().'/assets/js/bootstrap.min.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'owl', get_template_directory_uri().'/assets/js/owl.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'wow', get_template_directory_uri().'/assets/js/wow.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'jquery-fancybox', get_template_directory_uri().'/assets/js/jquery.fancybox.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'isotope', get_template_directory_uri().'/assets/js/isotope.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'parallax-scroll', get_template_directory_uri().'/assets/js/parallax-scroll.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'nav-tool', get_template_directory_uri().'/assets/js/nav-tool.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'nice-select', get_template_directory_uri().'/assets/js/jquery.nice-select.min.js', array( 'jquery' ), '2.1.2', true );

	wp_enqueue_script( 'immigro-main-script', get_template_directory_uri().'/assets/js/script.js', array(), false, true );

	if( is_singular() ) wp_enqueue_script('comment-reply');
	
}
add_action( 'wp_enqueue_scripts', 'immigro_enqueue_scripts' );

/*---------- Enqueue styles and scripts ends ----------*/

/*---------- Google fonts ----------*/

function immigro_fonts_url() {
	
	$fonts_url = '';
		
		$font_families['Poppins']      = 'Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900';

		$font_families['League Spartan']      = 'League Spartan:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900';

		$font_families = apply_filters( 'immigro/includes/classes/header_enqueue/font_families', $font_families );

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$protocol  = is_ssl() ? 'https' : 'http';
		$fonts_url = add_query_arg( $query_args, $protocol . '://fonts.googleapis.com/css' );

		return esc_url_raw($fonts_url);

}

function immigro_theme_styles() {
    wp_enqueue_style( 'immigro-theme-fonts', immigro_fonts_url(), array(), null );
}

add_action( 'wp_enqueue_scripts', 'immigro_theme_styles' );
add_action( 'admin_enqueue_scripts', 'immigro_theme_styles' );

/*---------- Google fonts ends ----------*/

/*---------- More functions ----------*/

// 1) immigro_set function

/**
 * [immigro_set description]
 *
 * @param  array $data [description]
 *
 * @return [type]       [description]
 */
if ( ! function_exists( 'immigro_set' ) ) {
	function immigro_set( $var, $key, $def = '' ) {
		//if( ! $var ) return false;

		if ( is_object( $var ) && isset( $var->$key ) ) {
			return $var->$key;
		} elseif ( is_array( $var ) && isset( $var[ $key ] ) ) {
			return $var[ $key ];
		} elseif ( $def ) {
			return $def;
		} else {
			return false;
		}
	}
}

// 2) immigro_add_editor_styles function

function immigro_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'immigro_add_editor_styles' );

// 3) Add specific CSS class by filter body class.

$options = immigro_WSH()->option(); 
if( immigro_set($options, 'boxed_wrapper') ){

	add_filter( 'body_class', function( $classes ) {
		$classes[] = 'boxed_wrapper';
		return $classes;
	} );
}
