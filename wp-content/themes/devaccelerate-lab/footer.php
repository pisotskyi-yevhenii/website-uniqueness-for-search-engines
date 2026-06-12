<?php
/**
 * DevAccelerate console footer.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="devaccelerate-footer">
	<section class="devaccelerate-footer__prompt">
		<p><?php esc_html_e( 'NEXT SYSTEM UPGRADE', 'devaccelerate-lab' ); ?></p>
		<h2><?php esc_html_e( 'Make AI part of the engineering process, not a shortcut around it.', 'devaccelerate-lab' ); ?></h2>
		<a href="#" aria-disabled="true" data-console-placeholder="true"><?php esc_html_e( 'Open implementation notes', 'devaccelerate-lab' ); ?></a>
	</section>
	<div class="devaccelerate-footer__links">
		<div class="devaccelerate-footer__brand">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/devaccelerate-symbol-light.svg' ); ?>" width="250" height="47" alt="<?php esc_attr_e( 'DevAccelerate Lab', 'devaccelerate-lab' ); ?>">
		</div>
		<nav aria-label="<?php esc_attr_e( 'Engineering resources', 'devaccelerate-lab' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'devaccelerate-resources',
					'container'      => false,
					'menu_class'     => 'devaccelerate-link-row',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<nav aria-label="<?php esc_attr_e( 'Social network links', 'devaccelerate-lab' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'devaccelerate-network',
					'container'      => false,
					'menu_class'     => 'devaccelerate-link-row devaccelerate-link-row--social',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
	<div class="devaccelerate-footer__base">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'DevAccelerate Engineering. Human-reviewed systems only.', 'devaccelerate-lab' ); ?></p>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'devaccelerate-policy',
				'container'      => false,
				'menu_class'     => 'devaccelerate-policy-menu',
				'fallback_cb'    => false,
			)
		);
		?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
