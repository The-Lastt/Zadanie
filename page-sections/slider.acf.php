<?php

return [
	[
		'label' => 'Slider',
		'name' => 'tab_slider',
		'type' => 'tab'
	],
	[
		'label' => 'Slajdy',
		'name' => 'slider_images',
		'type' => 'repeater',
		'sub_fields' =>
		[
			[
				'label' => 'Tło',
				'name' => 'slide_background',
				'type' => 'image'
			],
			[
				'label' => 'Tekst',
				'name' => 'slide_text',
				'type' => 'wysiwyg'
			]
		]
	]
];
