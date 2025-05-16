<?php

add_action('wp_enqueue_scripts', function()
{
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
	<!--[if (lt IE 9)]><script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.helper.ie8.js"></script><![endif]-->
	<?php
});

$section_vars['sliders'] = 0;

add_action('wp_footer', function()
{
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>

	<script>
		for (let i = 1; i <= <?= $GLOBALS['section_vars']['slider']['sliders'] ?>; i++)
			tns({
				container: `.tiny-slider.slider-${ i }`,
				items: 1,
				slideBy: 'page',
				autoplay: true,
				controls: false,
				nav: false,
				autoplayButtonOutput: false,
				speed: 1000,
				autoplayTimeout: 10000
			});
	</script>
	<?php
});
