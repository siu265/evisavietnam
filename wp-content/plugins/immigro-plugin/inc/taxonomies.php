<?php

namespace IMMIGROPLUGIN\Inc;


use IMMIGROPLUGIN\Inc\Abstracts\Taxonomy;


class Taxonomies extends Taxonomy {


	public static function init() {

		$labels = array(
			'name'              => _x( 'Project Category', 'wpimmigro' ),
			'singular_name'     => _x( 'Project Category', 'wpimmigro' ),
			'search_items'      => __( 'Search Category', 'wpimmigro' ),
			'all_items'         => __( 'All Categories', 'wpimmigro' ),
			'parent_item'       => __( 'Parent Category', 'wpimmigro' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpimmigro' ),
			'edit_item'         => __( 'Edit Category', 'wpimmigro' ),
			'update_item'       => __( 'Update Category', 'wpimmigro' ),
			'add_new_item'      => __( 'Add New Category', 'wpimmigro' ),
			'new_item_name'     => __( 'New Category Name', 'wpimmigro' ),
			'menu_name'         => __( 'Project Category', 'wpimmigro' ),
		);
		$args   = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'project_cat' ),
		);

		register_taxonomy( 'project_cat', 'immigro_project', $args );
		
		//Services Taxonomy Start
		$labels = array(
			'name'              => _x( 'Service Category', 'wpimmigro' ),
			'singular_name'     => _x( 'Service Category', 'wpimmigro' ),
			'search_items'      => __( 'Search Category', 'wpimmigro' ),
			'all_items'         => __( 'All Categories', 'wpimmigro' ),
			'parent_item'       => __( 'Parent Category', 'wpimmigro' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpimmigro' ),
			'edit_item'         => __( 'Edit Category', 'wpimmigro' ),
			'update_item'       => __( 'Update Category', 'wpimmigro' ),
			'add_new_item'      => __( 'Add New Category', 'wpimmigro' ),
			'new_item_name'     => __( 'New Category Name', 'wpimmigro' ),
			'menu_name'         => __( 'Service Category', 'wpimmigro' ),
		);
		$args   = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'service_cat' ),
		);


		register_taxonomy( 'service_cat', 'immigro_service', $args );
		
		//Testimonials Taxonomy Start
		$labels = array(
			'name'              => _x( 'Testimonials Category', 'wpimmigro' ),
			'singular_name'     => _x( 'Testimonials Category', 'wpimmigro' ),
			'search_items'      => __( 'Search Category', 'wpimmigro' ),
			'all_items'         => __( 'All Categories', 'wpimmigro' ),
			'parent_item'       => __( 'Parent Category', 'wpimmigro' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpimmigro' ),
			'edit_item'         => __( 'Edit Category', 'wpimmigro' ),
			'update_item'       => __( 'Update Category', 'wpimmigro' ),
			'add_new_item'      => __( 'Add New Category', 'wpimmigro' ),
			'new_item_name'     => __( 'New Category Name', 'wpimmigro' ),
			'menu_name'         => __( 'Testimonials Category', 'wpimmigro' ),
		);
		$args   = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'testimonials_cat' ),
		);


		register_taxonomy( 'testimonials_cat', 'immigro_testimonials', $args );
		
		
		//Team Taxonomy Start
		$labels = array(
			'name'              => _x( 'Team Category', 'wpimmigro' ),
			'singular_name'     => _x( 'Team Category', 'wpimmigro' ),
			'search_items'      => __( 'Search Category', 'wpimmigro' ),
			'all_items'         => __( 'All Categories', 'wpimmigro' ),
			'parent_item'       => __( 'Parent Category', 'wpimmigro' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpimmigro' ),
			'edit_item'         => __( 'Edit Category', 'wpimmigro' ),
			'update_item'       => __( 'Update Category', 'wpimmigro' ),
			'add_new_item'      => __( 'Add New Category', 'wpimmigro' ),
			'new_item_name'     => __( 'New Category Name', 'wpimmigro' ),
			'menu_name'         => __( 'Team Category', 'wpimmigro' ),
		);
		$args   = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'team_cat' ),
		);


		register_taxonomy( 'team_cat', 'immigro_team', $args );
		
		//Faqs Taxonomy Start
		$labels = array(
			'name'              => _x( 'Faqs Category', 'wpimmigro' ),
			'singular_name'     => _x( 'Faq Category', 'wpimmigro' ),
			'search_items'      => __( 'Search Category', 'wpimmigro' ),
			'all_items'         => __( 'All Categories', 'wpimmigro' ),
			'parent_item'       => __( 'Parent Category', 'wpimmigro' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpimmigro' ),
			'edit_item'         => __( 'Edit Category', 'wpimmigro' ),
			'update_item'       => __( 'Update Category', 'wpimmigro' ),
			'add_new_item'      => __( 'Add New Category', 'wpimmigro' ),
			'new_item_name'     => __( 'New Category Name', 'wpimmigro' ),
			'menu_name'         => __( 'Faq Category', 'wpimmigro' ),
		);
		$args   = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'faqs_cat' ),
		);


		register_taxonomy( 'faqs_cat', 'immigro_faqs', $args );
	}
	
}
