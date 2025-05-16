<?php get_header(); ?>

<div class="page-content page-search">
	<section class="default-title-header">
		<div class="section">
			
				<form role="search" method="get" class="search-form" action="<?= home_url( '/' ); ?>">
					<h1>Wyszukiwanie: 
					<input type="search" class="search-field" placeholder="Szukaj…" value="<?= esc_html(get_search_query()) ?>" name="s" title="Szukaj:" />
					<input type="submit" class="search-submit" value="Szukaj" />
					</h1>
				</form>
			<!-- TODO: Add breadcrumbs -->
		</div>
	</section>
	<section class="default-posts search-results">
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
