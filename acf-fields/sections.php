<?php
/**
 * Loading of ACF fields for used sections
 */

namespace ACFSections;


function assign_field_keys(&$fields, $prefix)
{
	foreach ($fields as &$field)
	{
		if (!isset($field['key']))
			$field['key'] = 'field_' . hash('md5', ($prefix ? $prefix . '/' : '') . ($field['name'] ? $field['name'] : $field['key']));

		if (isset($field['sub_fields']))
		{
			assign_field_keys($field['sub_fields'], ($prefix ? $prefix . '/' : '') . $field['name']);
		}
		if (isset($field['layouts']))
		{
			assign_field_keys($field['layouts'], ($prefix ? $prefix . '/' : '') . $field['name']);
		}
	}
}


function register_page_sections($template_file_name)
{
	if (!function_exists('acf_add_local_field_group'))
		return;

	$template_file = 'page-templates/' . $template_file_name . '.php';

	$pages = get_page_sections();

	if (!isset($pages[$template_file_name]))
		return;

	$sections = $pages[$template_file_name];

	$fields = [];

	// Page fields
	if (file_exists(__DIR__ . '/../page-templates/' . $template_file_name . '.acf.php'))
	{
		$page_fields = require __DIR__ . '/../page-templates/' . $template_file_name . '.acf.php';

		assign_field_keys($page_fields, $template_file_name);

		$fields = array_merge($fields, $page_fields);
	}

	// Sections fields
	foreach ($sections as $section)
	{
		if (!file_exists(__DIR__ . '/../page-sections/' . $section . '.acf.php'))
			continue;
		
		$section_fields = require __DIR__ . '/../page-sections/' . $section . '.acf.php';

		assign_field_keys($section_fields, $template_file_name . '#' . $section);

		$fields = array_merge($fields, $section_fields);
	}

	acf_add_local_field_group([
		'key' => 'group_' . $template_file_name,
		'title' => $template_file_name,
		'fields' => $fields,
		'location' =>
		[
			[
				[
					'param' => 'page_template',
					'operator' => '==',
					'value' => $template_file,
				],
			],
		],
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => [
			0 => 'the_content',
		],
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
		'flexible_content' => '',
	]);
}


add_action('acf/include_fields', function()
{
	foreach(scandir(__DIR__ . '/../page-templates/') as $file)
	{
		if (substr($file, -4) == '.php' && substr($file, -8) != '.acf.php')
			register_page_sections(substr($file, 0, -4));
	}	
});
