<?php
/**
 * Template Name: Strona główna
 */

add_action('wp_enqueue_scripts', function(){
	px_enqueue_style('page-home', '/dist/css/home.css', [ 'default' ]); 
});

get_header(); ?>

<div class="page-home">
<section class="default-title-header">
	<div class="section">
		<h1><?php the_title(); ?></h1>
		<nav class="breadcrumbs">
			<ul>
				<li><a href="/"><?php _e('Strona główna', 'template'); ?></a></li>
				<li><?php the_title() ?></li>
			</ul>
		</nav>
	</div>
</section>
<section class="contact-section">
	<div class="section">
		<div class="contact-wrapper">
			<div class="contact-form">
				<h3><?php _e('Skontaktuj się z nami', 'template'); ?></h3>
				<?php get_template_part('template-parts/form/contact-form'); ?>
			</div>
		</div>
	</div>
</section>

</div>

<?php get_footer();