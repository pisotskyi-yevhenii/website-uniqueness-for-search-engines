<?php
/**
 * VibeStart site footer.
 *
 * @package VibeStartAcademy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vibestart_chrome = vibestart_academy_group(
	'vibestart_site_chrome',
	array(
		'vibestart_footer_logo'             => 0,
		'vibestart_footer_description'      => 'A friendly first step into building useful projects with AI.',
		'vibestart_footer_navigation_title' => 'Navigation',
		'vibestart_footer_social_title'     => 'Social',
		'vibestart_footer_legal_title'      => 'Legal',
		'vibestart_copyright'               => '© 2026 VibeStart Learning. Start small, build clearly.',
	)
);
$vibestart_footer_logo = vibestart_academy_image(
	$vibestart_chrome['vibestart_footer_logo'],
	get_stylesheet_directory_uri() . '/assets/images/vibestart-mark-light.svg',
	'VibeStart Academy'
);
?>
<footer class="vibestart-footer">
	<div class="vibestart-footer__grid">
		<div class="vibestart-footer__identity">
			<img src="<?php echo esc_url( $vibestart_footer_logo['url'] ); ?>" width="218" height="52" alt="<?php echo esc_attr( $vibestart_footer_logo['alt'] ); ?>">
			<p><?php echo esc_html( $vibestart_chrome['vibestart_footer_description'] ); ?></p>
		</div>
		<div class="vibestart-footer__column">
			<h2><?php echo esc_html( $vibestart_chrome['vibestart_footer_navigation_title'] ); ?></h2>
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
			<h2><?php echo esc_html( $vibestart_chrome['vibestart_footer_social_title'] ); ?></h2>
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
			<h2><?php echo esc_html( $vibestart_chrome['vibestart_footer_legal_title'] ); ?></h2>
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
		<p><?php echo esc_html( $vibestart_chrome['vibestart_copyright'] ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
