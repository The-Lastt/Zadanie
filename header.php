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
				<div class="header-menu-container">
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
				</div>
			</div>
			<div class="header-mobile">
				<button aria-label="otwórz menu" class="mobile-menu-toggle">
					<div></div>
					<div></div>
					<div></div>
				</button>
			</div>
			<div class="mobile-menu">
					<div class="menu-containter">
						<?php if (has_nav_menu('primary_menu')) : ?>
							<?php
							wp_nav_menu(
								[
									'theme_location'  => 'primary_menu',
									'container'       => 'nav',
									'container_class' => 'main-menu',
									'after'           => '<button class="expand" aria-label="rozwiń podmenu"></button>'
								]
							);
							?>
						<?php endif; ?>
					</div>

					<div class="footer-buttons">
						<div class="action-buttons">


						</div>
					</div>
			</div>

		</div>

	</div>

</header>

<div class="page-contents">