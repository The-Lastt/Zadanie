<?php get_header(); ?>

<div class="page-content page-default">
	<section class="default-title-header" style="background-image: url('<?= esc_attr(get_field('site_header_image', 'options')) ?>')">
		<div class="section">
			<h1><?php the_title(); ?></h1>
			<!-- TODO: Add breadcrumbs -->
		</div>
	</section>
	<section class="default-posts">
		<div class="section">
			<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
				<div class="post">
					<?php the_post_thumbnail() ?>
					<div class="contents">
						<h3><?php the_title() ?></h3>

						<div class="excerpt">
							<?php the_excerpt() ?>
						</div>
						<a href="<?php the_permalink() ?>">Zobacz</a>
					</div>
				</div>
			<?php endwhile; ?>
			<?= paginate_links() ?>

			<?php else : ?>

			<h2 class="search-empty">Nie znaleziono...</h2>
				
			<?php endif ?>
		</div>
	</section>
</div>

<?php get_footer();
