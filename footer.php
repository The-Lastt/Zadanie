</div>

	<footer>
		<div class="section">
			Footer

			<?php if (has_nav_menu('footer_menu')) : ?>
				<?php
				wp_nav_menu(
					[
						'theme_location'  => 'footer_menu',
						'container'       => 'nav',
						'container_class' => 'footer-menu'
					]
				);
				?>
			<?php endif; ?>
		</div>
		<div class="section footer-bottom">
			Copyright <?= date('Y') ?> © <?php bloginfo('name') ?> | Realizacja <a href="https://proadax.pl" target="_blank">Proadax.pl</a>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
