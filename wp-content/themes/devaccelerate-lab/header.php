<?php
/**
 * DevAccelerate console header.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="devaccelerate-skip" href="#devaccelerate-main"><?php esc_html_e( 'Skip to engineering workflow', 'devaccelerate-lab' ); ?></a>
<header class="devaccelerate-header">
	<div class="devaccelerate-brand-bar">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/devaccelerate-symbol.svg' ); ?>" width="310" height="58" alt="<?php esc_attr_e( 'DevAccelerate Lab', 'devaccelerate-lab' ); ?>">
		<p><?php esc_html_e( 'AI SYSTEMS FOR SOFTWARE TEAMS', 'devaccelerate-lab' ); ?></p>
	</div>
	<div class="devaccelerate-nav-bar">
		<button class="devaccelerate-nav-toggle" type="button" aria-expanded="false" aria-controls="devaccelerate-console-menu">
			<span aria-hidden="true">[+]</span>
			<?php esc_html_e( 'Open console navigation', 'devaccelerate-lab' ); ?>
		</button>
		<nav class="devaccelerate-console-nav" aria-label="<?php esc_attr_e( 'Console navigation', 'devaccelerate-lab' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'devaccelerate-console',
					'container'      => false,
					'menu_id'        => 'devaccelerate-console-menu',
					'menu_class'     => 'devaccelerate-console-menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<span class="devaccelerate-system-state"><?php esc_html_e( 'SYSTEM: READY', 'devaccelerate-lab' ); ?></span>
	</div>
</header>
