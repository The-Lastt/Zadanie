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
	<section class="default-posts">
		<div class="section">
			<?php while ( have_posts() ) : the_post(); ?>
				<div>
					<h3><?php the_title(); ?></h3>

					<div><?php the_excerpt(); ?></div>
					<div><a href="<?php the_permalink(); ?>">Zobacz</a></div>
				</div>
			<?php endwhile; ?>

			<?php echo paginate_links(); ?>
		</div>
	</section>
</div>

<?php get_footer();
