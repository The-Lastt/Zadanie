<?php 

add_action('wp_enqueue_scripts', function ()
{

	px_enqueue_style('page-default', '/dist/css/page-default.css', [ 'default' ]);
}, 30);
global $post;
get_header(); ?>

<div class="page-content page-default">
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
	<section class="default-page">
		<div class="section">
			<?php the_content(); ?>
		</div>
	</section>
</div>

<?php get_footer();