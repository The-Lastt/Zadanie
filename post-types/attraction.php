<?php

register_post_type('attraction',
	[
		'labels' =>
		[
			'name'                => _x( 'Atrakcje', 'Post Type General Name'),
			'singular_name'       => _x( 'Atrakcja', 'Post Type Singular Name'),
			'menu_name'           => __( 'Atrakcje'),
			'parent_item_colon'   => __( 'Atrakcje nadrzędne'),
			'all_items'           => __( 'Wszystkie atrakcje'),
			'view_item'           => __( 'Zobacz atrakcję'),
			'add_new_item'        => __( 'Dodaj nową atrakcję'),
			'add_new'             => __( 'Dodaj nową'),
			'edit_item'           => __( 'Edytuj atrakcję'),
			'update_item'         => __( 'Zaktualizuj atrakcję'),
			'search_items'        => __( 'Szukaj atrakcji'),
			'not_found'           => __( 'Nie znaleziono atrakcji'),
			'not_found_in_trash'  => __( 'Nie znaleziono atrakcji w Koszu'),
		],
		'public' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'rewrite' => [ 'slug' => 'atrakcja' ],
		'show_in_rest' => false,
		'supports' => [ 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ]
	]
);

register_taxonomy('attraction-cat', [ 'attraction' ],
	[
		'hierarchical' => true,
		'labels' =>
		[
			'name' => _x( 'Typy atrakcji', 'taxonomy general name' ),
			'singular_name' => _x( 'Typ atrakcji', 'taxonomy singular name' ),
			'search_items' =>  __( 'Szukaj' ),
			'all_items' => __( 'Wszystkie typy' ),
			'parent_item' => __( 'Typ nadrzędny' ),
			'parent_item_colon' => __( 'Typ nadrzędny:' ),
			'edit_item' => __( 'Edytuj typ' ), 
			'update_item' => __( 'Zaktualizuj typ' ),
			'add_new_item' => __( 'Dodaj nowy typ' ),
			'new_item_name' => __( 'Nowa typ' ),
			'menu_name' => __( 'Typy atrakcji' ),
		],
		'show_ui' => true,
		'has_archive' => false,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => [ 'slug' => 'katagoria-atrakcji' ]
	]
);
