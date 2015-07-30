<?php
/*
Author: 2020 Creative
URL: htp://2020creative.com
Requirements: php5.5.*
*/

//// 2020 CPT's

add_action( 'init', function() {
    $singular = 'Speaker';
    $plural = $singular.'s';
    $slug = str_replace(' ', '_', strtolower($singular));

	$labels = array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'name_admin_bar'     => $singular,
		'add_new'            => 'Add New '.$singular,
		'add_new_item'       => 'Add New '.$singular,
		'new_item'           => 'New '.$singular,
		'edit_item'          => 'Edit '.$singular,
		'view_item'          => 'View '.$singular,
		'all_items'          => 'All '.$plural,
		'search_items'       => 'Search '.$plural,
		'parent_item_colon'  => 'Parent '.$plural.': ',
		'not_found'          => 'No '.$plural.' found.',
		'not_found_in_trash' => 'No '.$plural.' found in Trash.',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => ['slug' => $slug],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
	);

	register_post_type( $slug, $args );
});
    
add_action( 'init', function() {
    $singular = 'Testimonial';
    $plural = $singular.'s';
    $slug = str_replace(' ', '_', strtolower($singular));

	$labels = array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'name_admin_bar'     => $singular,
		'add_new'            => 'Add New '.$singular,
		'add_new_item'       => 'Add New '.$singular,
		'new_item'           => 'New '.$singular,
		'edit_item'          => 'Edit '.$singular,
		'view_item'          => 'View '.$singular,
		'all_items'          => 'All '.$plural,
		'search_items'       => 'Search '.$plural,
		'parent_item_colon'  => 'Parent '.$plural.': ',
		'not_found'          => 'No '.$plural.' found.',
		'not_found_in_trash' => 'No '.$plural.' found in Trash.',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => ['slug' => $slug],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
	);

	register_post_type( $slug, $args );
});

/*
add_action( 'init', function() {
    $singular = 'Gallery Image';
    $plural = $singular.'s';
    $slug = str_replace(' ', '_', strtolower($singular));

	$labels = array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'name_admin_bar'     => $singular,
		'add_new'            => 'Add New '.$singular,
		'add_new_item'       => 'Add New '.$singular,
		'new_item'           => 'New '.$singular,
		'edit_item'          => 'Edit '.$singular,
		'view_item'          => 'View '.$singular,
		'all_items'          => 'All '.$plural,
		'search_items'       => 'Search '.$plural,
		'parent_item_colon'  => 'Parent '.$plural.': ',
		'not_found'          => 'No '.$plural.' found.',
		'not_found_in_trash' => 'No '.$plural.' found in Trash.',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => ['slug' => $slug],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
	);

	register_post_type( $slug, $args );
});
*/
