<?php
/**
 * VibeStart site footer.
 *
 * @package VibeStartAcademy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="vibestart-footer">
	<div class="vibestart-footer__grid">
		<div class="vibestart-footer__identity">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/vibestart-mark-light.svg' ); ?>" width="218" height="52" alt="<?php esc_attr_e( 'VibeStart Academy', 'vibestart-academy' ); ?>">
			<p><?php esc_html_e( 'A friendly first step into building useful projects with AI.', 'vibestart-academy' ); ?></p>
		</div>
		<div class="vibestart-footer__column">
			<h2><?php esc_html_e( 'Navigation', 'vibestart-academy' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'vibestart-navigation',
					'container'      => false,
					'menu_class'     => 'vibestart-footer-menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
		<div class="vibestart-footer__column">
			<h2><?php esc_html_e( 'Social', 'vibestart-academy' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'vibestart-social',
					'container'      => false,
					'menu_class'     => 'vibestart-footer-menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
		<div class="vibestart-footer__column">
			<h2><?php esc_html_e( 'Legal', 'vibestart-academy' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'vibestart-legal',
					'container'      => false,
					'menu_class'     => 'vibestart-footer-menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
	</div>
	<div class="vibestart-footer__copyright">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'VibeStart Learning. Start small, build clearly.', 'vibestart-academy' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
