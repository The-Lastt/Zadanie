<?php

#region Disabling Unnecesary Elements

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action('wp_enqueue_scripts', function()
{
    // Remove CSS on the front end.
    wp_dequeue_style('wp-block-library');

    // Remove Gutenberg theme.
    wp_dequeue_style('wp-block-library-theme');

    // Remove inline global CSS on the front end.
    wp_dequeue_style('global-styles');

    // Remove classic-themes CSS for backwards compatibility for button blocks.
    wp_dequeue_style('classic-theme-styles');

}, 20);


function theme_remove_actions()
{
	// Disable emoji's
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

	// Remove generator information from the head
	remove_action('wp_head', 'wp_generator');

	// Remove API discovery tags
	remove_action('wp_head', 'rest_output_link_wp_head');
}
add_action('init', 'theme_remove_actions');
#endregion

#region Registering Theme Elements
add_action('after_setup_theme', function()
{
	// Registering menus
	register_nav_menus(
		[
			'primary_menu' => __('Primary Menu', 'proadax_theme'),
			'footer_menu'  => __('Footer Menu',  'proadax_theme'),
		]
	);

	// Registering post types
	require_once __DIR__ . '/post-types/attraction.php';
});


function theme_custom_logo_setup()
{
	add_theme_support( 'custom-logo',
		[
			'height'               => 150,
			'width'                => 300,
			'flex-height'          => true,
			'flex-width'           => true,
			'header-text'          => [ 'site-title', 'site-description' ],
			'unlink-homepage-logo' => true
		]
	);

	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
}

add_action('after_setup_theme', 'theme_custom_logo_setup');


function px_enqueue_script($handle, $src = '', $deps = [], $ver = true, $args = [ 'strategy' => 'defer', 'in_footer' => true ])
{
	if ($ver === true)
	{
		if (file_exists(filemtime(get_template_directory() . $src)))
			$ver = substr(hash('xxh64', filemtime(get_template_directory() . $src)), 0, 8);
		else
			$ver = false;
	}

	wp_enqueue_script($handle, get_template_directory_uri() . $src, $deps, $ver, $args);
}

function px_enqueue_style($handle, $src = '', $deps = [], $ver = true, $media = 'all')
{
	if ($ver === true)
	{
		if (file_exists(filemtime(get_template_directory() . $src)))
			$ver = substr(hash('xxh64', filemtime(get_template_directory() . $src)), 0, 8);
		else
			$ver = false;
	}

	wp_enqueue_style($handle, get_template_directory_uri() . $src, $deps, $ver, $media);
}


function enqueue_theme_styles()
{
	px_enqueue_style('default', '/dist/css/main.css');
	px_enqueue_script('default', '/dist/js/main.js');
}

add_action('wp_enqueue_scripts', 'enqueue_theme_styles', 100);
#endregion

#region Custom Sections
$GLOBALS['section_vars'] = [];

function get_page_sections()
{
	static $pages = false;

	if ($pages)
		return $pages;

	$pages = require __DIR__ . '/page_sections.php';
	return $pages;
}


function enqueue_section($section_name)
{
	$section_vars = &$GLOBALS['section_vars'][$section_name];

	if (file_exists(__DIR__ . '/page-sections/' . $section_name . '.enqueue.php'))
		require_once __DIR__ . '/page-sections/' . $section_name . '.enqueue.php';
}


function enqueue_sections($page)
{
	$sections = get_page_sections()[$page];

	if ($sections)
	foreach ($sections as &$section)
		enqueue_section($section);
}


function the_section($section_name)
{
	$section_vars = &$GLOBALS['section_vars'][$section_name];
	require __DIR__ . '/page-sections/' . $section_name . '.php';
}


require_once __DIR__ . '/acf-fields/acf-init.php';

require_once __DIR__ . '/modules/analytics.php';
#endregion

#region Custom Login Page Logo
function my_login_logo()
{
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );

	if ($image)
	{
	?>
		<style>
			#login h1 a, .login h1 a
			{
				background-image: url(<?= $image[0]; ?>);
				width: 320px;
				height: 120px;
				background-size: contain;
				background-repeat: no-repeat;
				background-position: center;
			}
		</style>
	<?php
	}
	?>
		<script>
			document.addEventListener("DOMContentLoaded", e =>
			{
				document.querySelector("#login h1 a").href = "/";
			});
		</script>
	<?php
}
add_action('login_enqueue_scripts', 'my_login_logo');
#endregion

#region Allow SVG
add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes)
{
	$filetype = wp_check_filetype( $filename, $mimes );

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];
  
}, 10, 4);


add_filter( 'upload_mimes', function ($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
});

add_action( 'admin_head', function ()
{
	echo
	'<style type="text/css">
	.attachment-266x266, .thumbnail img {
		width: 100% !important;
		height: auto !important;
	}
	</style>';
});
#endregion

#region Development Tools
if (WP_DEBUG)
{
	if (file_exists(__DIR__ . '/dev/dev-tools.php'))
		require_once __DIR__ . '/dev/dev-tools.php';
}
#endregion
