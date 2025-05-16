<section class="gallery">
	<div class="section">
		<h2><?php the_field('title') ?></h2>

		<?php if( get_field('images') ): ?>
			<div class="images">
			<?php foreach(get_field('images') as &$image ) : ?>
				<div class="image">
					<img src="<?= esc_url($image['url']) ?>" alt="" width="<?= $image['width'] ?>" height="<?= $image['height'] ?>" loading="lazy">
				</div>
			<?php endforeach; ?>
			</div>
		<?php else : ?>	
			No images
		<?php endif; ?>
	</div>
</section>
