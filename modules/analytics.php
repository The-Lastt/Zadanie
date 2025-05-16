<?php

add_action('wp_head', function()
{
	if (get_field('analytics_ga_code', 'option'))
	{
	?>
			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php the_field('analytics_ga_code', 'option') ?>"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '<?php the_field('analytics_ga_code', 'option') ?>');
			</script>
		<?php
	}

	if (get_field('analytics_pixel_code', 'option'))
	{
		?>
			<!-- Facebook Pixel Code -->
			<script>
				!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window, document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '<?php the_field('analytics_pixel_code', 'option') ?>');
				fbq('track', 'PageView');
			</script>
			<noscript>
			<img height="1" width="1" style="display:none" 
				src="https://www.facebook.com/tr?id=<?php the_field('analytics_pixel_code', 'option') ?>&ev=PageView&noscript=1"/>
			</noscript>
			<!-- End Facebook Pixel Code -->
		<?php
	}
});


add_action('acf/include_fields', function()
{
	if (!function_exists('acf_add_local_field_group'))
	{
		return;
	}

	acf_add_local_field_group(
		[
			'key' => 'group_analytics',
			'title' => 'Analityki',
			'fields' =>
			[
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
				]
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
});
