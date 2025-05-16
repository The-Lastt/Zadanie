<section class="attractions-list">
	<div class="section">
		<h2><?php _e('Atrakcje', 'template') ?></h2>
		<?php
			global $post;
			
			$post_list = get_posts(
				[
					'post_type' => 'attractions', 
					'posts_per_page' => 3,
					'order' => 'ASC',
					'orderby' => 'rand'
				]
			);
			
		?>
		<?php if( $post_list ) :  ?> 						
			<?php  foreach ( $post_list as $post ) : setup_postdata( $post );  ?>            
				<?php $thumbnail_large = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '650px' ); ?>
				<div class="post">
					<a href='<?php the_permalink(); ?>'><div class="img-wrapper"><img src="<?php echo $thumbnail_large[0]; ?>" alt="<?php the_title(); ?>" /></div>
					<h4 class="name"><?php the_title(); ?></h4></a>
				</div>
			<?php endforeach;  wp_reset_postdata(); ?>
		<?php else: ?>
			<h3 class='no-portfolio'><?php _e( 'Brak atrakcji.', 'template') ?></h3>
		<?php endif;?>
	</div>
</section>
