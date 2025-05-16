<?php
/**
 * Template Name: Strona głowna
 */

enqueue_sections('home');

get_header(); ?>

<div class="page-home">

	<?php the_section('slider') ?>

	<section class="header">
		<div class="section">
			<h1>Strona główna</h1>
		</div>
	</section>

	<?php the_section('text-area') ?>
	
	<?php the_section('test') ?>
	<?php the_section('gallery') ?>
	<?php the_section('attractions') ?>

</div>

<?php get_footer();
