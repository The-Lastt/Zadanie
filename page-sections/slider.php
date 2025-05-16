<section class="slider">

	<div class="tiny-slider slider-<?= ++$section_vars['sliders'] ?>">
	
	<?php if( get_field('slider_images') ) :
		while( have_rows('slider_images') ) : the_row();
			$image = get_sub_field('slide_background');
		
			?>
			<div>
				<div class="background">
					<img src="<?= esc_url($image['url']) ?>" alt="" width="<?= $image['width'] ?>" height="<?= $image['height'] ?>" loading="lazy">
				</div>
				<div class="contents">
					<?= get_sub_field('slide_text') ?>
				</div>
			</div>
		<?php endwhile; ?>
	<?php else : ?>	
		<div>Slider empty</div>
	<?php endif; ?>

	</div>
</section>
