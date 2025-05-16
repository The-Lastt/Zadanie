<?php
/**
 * Theme template.
 *
 * @package Theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?= esc_attr(get_field('site_description', 'options')) ?>">

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header class="header-default">

	<div class="header-contents">

		<div class="section">
			<div class="header-logo">
				<?php if (has_custom_logo()) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?= home_url( '/' ); ?>"><?php bloginfo('name') ?></a>
				<?php endif; ?>
			</div>

			<div class="header-elements">
				<?php if (has_nav_menu('primary_menu')) : ?>
					<?php
					wp_nav_menu(
						[
							'theme_location'  => 'primary_menu',
							'container'       => 'nav',
							'container_class' => 'main-menu'
						]
					);
					?>
				<?php endif; ?>

				<div class="search-box">
					<button class="menu-item">🔎</button>
					<div class="search-form-box">
						<form role="search" method="get" class="search-form" action="<?= home_url( '/' ); ?>">
							<input type="search" class="search-field" placeholder="Szukaj…" value="<?= esc_html(get_search_query()) ?>" name="s" title="Szukaj:" />
							<input type="submit" class="search-submit" value="Szukaj" />
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>

</header>

<div class="page-contents">
