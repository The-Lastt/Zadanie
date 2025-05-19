<?php


acf_add_local_field_group(
	[
		'key' => 'group_analytics',
		'title' => 'Opcje strony',
		'fields' =>
		[                       			
			#region Klucze recaptcha
            [
				'key' => 'field_66d6asdasesas9',
				'label' => 'Klucze Recaptcha',
				'type' => 'tab',
			], 			
			[
				'key' => 'field_asac6',
				'label' => 'Klucz publiczny',
				'name' => 'forms_recaptcha_site_key',
				'type' => 'text',
			], 			
			[
				'key' => 'field_asaczc6',
				'label' => 'Klucz prywatny',
				'name' => 'forms_recaptcha_secret_key',
				'type' => 'text',
			], 
			[
				'key' => 'field_66a3a9dafeb2c',
				'label' => 'Adres e-mail do formularzy',
				'name' => 'forms_email',
				'type' => 'email'
			],
			#region Analytics
			[
				'key' => 'field_64c094b6e4b42',
				'label' => 'Analityki',
				'type' => 'tab',
			],
			[
				'key' => 'field_65c094b6e4442',
				'label' => 'Kod śledzenia Google Analytics',
				'name' => 'analytics_ga_code',
				'type' => 'text',
			],
			[
				'key' => 'field_64c09446e4b42',
				'label' => 'Kod śledzenia Meta Pixel',
				'name' => 'analytics_pixel_code',
				'type' => 'text',
			],

		],
		'location' =>
		[
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'opcje',
				],
			],
		]
	]
);