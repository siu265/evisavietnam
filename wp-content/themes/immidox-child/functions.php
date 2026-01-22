<?php
/**
 * Theme functions and definitions.
 */
function immidox_child_enqueue_styles() {

    if ( SCRIPT_DEBUG ) {
        wp_enqueue_style( 'immidox-style' , get_template_directory_uri() . '/style.css' );
    } else {
        wp_enqueue_style( 'immidox-minified-style' , get_template_directory_uri() . '/style.min.css' );
    }

    wp_enqueue_style( 'immidox-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'immidox-style' ),
        wp_get_theme()->get('Version')
    );
}

add_action(  'wp_enqueue_scripts', 'immidox_child_enqueue_styles' );