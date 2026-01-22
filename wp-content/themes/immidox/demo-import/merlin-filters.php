<?php
/**
 * Available filters for extending Merlin WP.
 */

function immigro_unset_default_widgets_args( $widget_areas ) {

	$widget_areas = array(
		'default-sidebar' => array(),
	);

	return $widget_areas;
}
add_filter( 'merlin_unset_default_widgets_args', 'immigro_unset_default_widgets_args' );


/**
 * Execute custom code after the whole import has finished.
 */
function immigro_after_import_setup() {
	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
	$onepage_menu = get_term_by( 'name', 'One Page', 'nav_menu' );
	set_theme_mod(
		    'nav_menu_locations', array(
			'main_menu' => $main_menu->term_id,
			'onepage_menu' => $onepage_menu->term_id,
		)
	);
	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Home Page One' );
	$blog_page_id  = get_page_by_title( 'Blog Standard' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );
    $logo = get_page_by_title( 'logo', OBJECT, 'attachment' );
    if( $logo ) {
    	set_theme_mod( 'custom_logo', $logo->ID );
    }
}

add_action( 'merlin_after_all_import', 'immigro_after_import_setup' );

function immigro_local_import_files() {
	return array(
		array(
			'import_file_name'         => esc_html__('Main Demo', 'immigro'),
			'local_import_widget_file' => trailingslashit( get_template_directory() ) . 'demo-import/content/widgets.json',
			'local_import_redux'       => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'demo-import/content/redux_options.json',
					'option_name' => 'immigro_options',
				),
			),
			'local_import_file'        => trailingslashit( get_template_directory() ) . 'demo-import/content/content.xml',
			'import_preview_image_url' => get_template_directory_uri() . '/screenshot.png',
			'import_notice'            => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'immigro' ),
			'preview_url'              => esc_url(home_url('/')),
		),
	);
}

add_filter( 'merlin_import_files', 'immigro_local_import_files' );

function immigro_child_functions_php( $output, $slug ) {

	$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

	$output = "
		<?php
		/**
		 * Theme functions and definitions.
		 */
		function {$slug_no_hyphens}_child_enqueue_styles() {

		    if ( SCRIPT_DEBUG ) {
		        wp_enqueue_style( '{$slug}-style' , get_template_directory_uri() . '/style.css' );
		    } else {
		        wp_enqueue_style( '{$slug}-minified-style' , get_template_directory_uri() . '/style.min.css' );
		    }

		    wp_enqueue_style( '{$slug}-child-style',
		        get_stylesheet_directory_uri() . '/style.css',
		        array( '{$slug}-style' ),
		        wp_get_theme()->get('Version')
		    );
		}

		add_action(  'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles' );\n
	";

	// Let's remove the tabs so that it displays nicely.
	$output = trim( preg_replace( '/\t+/', '', $output ) );

	// Filterable return.
	return $output;
}
add_filter( 'merlin_generate_child_functions_php', 'immigro_child_functions_php', 10, 2 );



