<?php
/**
 * DevAccelerate console footer.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$devaccelerate_chrome = DevAccelerate_Theme::field(
	'devaccelerate_site_chrome',
	array(
		'devaccelerate_footer_logo' => 0,
		'devaccelerate_copyright'   => '© 2026 DevAccelerate Engineering. Human-reviewed systems only.',
	)
);
$devaccelerate_footer_prompt = DevAccelerate_Theme::field(
	'devaccelerate_footer_prompt',
	array(
		'devaccelerate_footer_eyebrow'    => 'NEXT SYSTEM UPGRADE',
		'devaccelerate_footer_title'      => 'Make AI part of the engineering process, not a shortcut around it.',
		'devaccelerate_footer_link_label' => 'Open implementation notes',
	)
);
$devaccelerate_footer_logo = DevAccelerate_Theme::image(
	$devaccelerate_chrome['devaccelerate_footer_logo'],
	get_stylesheet_directory_uri() . '/assets/images/devaccelerate-symbol-light.svg',
	'DevAccelerate Lab'
);
?>
<footer class="devaccelerate-footer">
	<section class="devaccelerate-footer__prompt">
		<p><?php echo esc_html( $devaccelerate_footer_prompt['devaccelerate_footer_eyebrow'] ); ?></p>
		<h2><?php echo esc_html( $devaccelerate_footer_prompt['devaccelerate_footer_title'] ); ?></h2>
		<a href="#" aria-disabled="true" data-console-placeholder="true"><?php echo esc_html( $devaccelerate_footer_prompt['devaccelerate_footer_link_label'] ); ?></a>
	</section>
	<div class="devaccelerate-footer__links">
		<div class="devaccelerate-footer__brand">
			<img src="<?php echo esc_url( $devaccelerate_footer_logo['url'] ); ?>" width="250" height="47" alt="<?php echo esc_attr( $devaccelerate_footer_logo['alt'] ); ?>">
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
		<p><?php echo esc_html( $devaccelerate_chrome['devaccelerate_copyright'] ); ?></p>
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
