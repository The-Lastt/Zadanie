<?php get_header(); ?>

<div class="page-content page-default">
	<section class="default-title-header">
		<div class="section">
			<h1><?php the_title(); ?></h1>
			<!-- TODO: Add breadcrumbs -->
		</div>
	</section>
	<section class="default-page">
		<div class="section">
			<?php the_content(); ?>
		</div>
	</section>
</div>

<?php get_footer();
