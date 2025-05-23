<?php

add_action('acf/init', function()
{
	acf_add_options_page(
		[
			'page_title' => 'Opcje strony',
			'menu_slug' => 'opcje',
			'icon_url' => 'dashicons-admin-generic'
		]
	);
});

require_once __DIR__ . '/sections.php';

add_action('acf/include_fields', function()
{
	if ( ! function_exists( 'acf_add_local_field_group' ) )
		return;

	require __DIR__ . '/global/options.php';
});