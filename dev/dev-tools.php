<?php

add_action('wp_enqueue_scripts', function ()
{
	wp_enqueue_script('dev-js', get_stylesheet_directory_uri() . '/dev/dev-tools.js', [], hash('xxh64', (filemtime(get_stylesheet_directory() . '/dev/dev-tools.js'))), [ 'strategy' => 'defer', 'in_footer' => true ]);
}, 100);

function px_dev_stylesheet_file_version($file)
{
	return hash('xxh64', (filemtime($file)));
}

function px_dev_stylesheet_version()
{
	$data = json_decode(file_get_contents('php://input'), true);

	if (!$data)
	{
		return new WP_Error('no_data', 'No data provided', [ 'status' => 400 ]);
	}

	$files = [];

	foreach ($data as $file_id => $file_url)
	{
		$file_path = parse_url($file_url, PHP_URL_PATH);
		$file_path = path_join(ABSPATH, substr($file_path, 1));

		$ver = '0';

		if (file_exists($file_path))
			$ver = px_dev_stylesheet_file_version($file_path);

		$files[] =
		[
			'name' => $file_id,
			'url' => $file_url,
			'version' => substr($ver, 0, 8),
			'type' => 'style'
		];
	}
  
	return $files;
}


add_action('rest_api_init', function ()
{
	register_rest_route('proadax/v1', '/px_dev_stylesheet_version',
		[
		'methods' => 'POST',
		'callback' => 'px_dev_stylesheet_version',
		]
	);
});

// Template file info
add_action('admin_bar_menu', function ($admin_bar)
{
	global $template;

	if (!$template)
		return;

	$admin_bar->add_node(
		[
			'id' => 'template-path-info',
			'title' =>  substr($template, strlen(WP_CONTENT_DIR) + 7)
		]
	);
}, 999);
