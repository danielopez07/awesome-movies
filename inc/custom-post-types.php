<?php
add_action( 'init', 'awesome_movies_cpt_init' );
/**
 * Register Movie post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function awesome_movies_cpt_init() {
	$labels = array(
		'name'               => _x( 'Movie', 'post type general name', 'awesome-movies' ),
		'singular_name'      => _x( 'Movie', 'post type singular name', 'awesome-movies' ),
		'menu_name'          => _x( 'Movies', 'menu admin', 'awesome-movies' ),
		'name_admin_bar'     => _x( 'Add', 'adicionar nuevo en la barra de admin', 'awesome-movies' ),
		'add_new'            => _x( 'Add movie', 'movie', 'awesome-movies' ),
		'add_new_item'       => __( 'Add new', 'awesome-movies' ),
		'new_item'           => __( 'New movie', 'awesome-movies' ),
		'edit_item'          => __( 'Edit movie', 'awesome-movies' ),
		'view_item'          => __( 'View movie', 'awesome-movies' ),
		'all_items'          => __( 'All movies', 'awesome-movies' ),
		'search_items'       => __( 'Search movies', 'awesome-movies' ),
		'parent_item_colon'  => __( 'Parent movie:', 'awesome-movies' ),
		'not_found'          => __( 'No movies found.', 'awesome-movies' ),
		'not_found_in_trash' => __( 'No movies found in trash.', 'awesome-movies' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'awesome-movies' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
    'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'movie' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
    'taxonomies'         => array( 'category', 'post_tag' ),
		'supports'           => array( 'title', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'movie', $args );
}

add_action( 'init', 'awesome_actors_cpt_init' );
/**
 * Register Actor post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function awesome_actors_cpt_init() {
	$labels = array(
		'name'               => _x( 'Actor', 'post type general name', 'awesome-movies' ),
		'singular_name'      => _x( 'Actor', 'post type singular name', 'awesome-movies' ),
		'menu_name'          => _x( 'Actors', 'menu admin', 'awesome-movies' ),
		'name_admin_bar'     => _x( 'Add', 'adicionar nuevo en la barra de admin', 'awesome-movies' ),
		'add_new'            => _x( 'Add actor', 'actor', 'awesome-movies' ),
		'add_new_item'       => __( 'Add new', 'awesome-movies' ),
		'new_item'           => __( 'New actor', 'awesome-movies' ),
		'edit_item'          => __( 'Edit actor', 'awesome-movies' ),
		'view_item'          => __( 'View actor', 'awesome-movies' ),
		'all_items'          => __( 'All actors', 'awesome-movies' ),
		'search_items'       => __( 'Search actors', 'awesome-movies' ),
		'parent_item_colon'  => __( 'Parent actor:', 'awesome-movies' ),
		'not_found'          => __( 'No actors found.', 'awesome-movies' ),
		'not_found_in_trash' => __( 'No actors found in trash.', 'awesome-movies' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'awesome-actors-cpt' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
    'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'actor' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
    'taxonomies'         => array( 'category', 'post_tag' ),
		'supports'           => array( 'title', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'actor', $args );
}
