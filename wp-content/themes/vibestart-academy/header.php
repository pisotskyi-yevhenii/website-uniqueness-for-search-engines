<?php
/**
 * VibeStart site header.
 *
 * @package VibeStartAcademy
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
<a class="vibestart-skip-link" href="#vibestart-main"><?php esc_html_e( 'Skip to course overview', 'vibestart-academy' ); ?></a>
<header class="vibestart-header">
	<div class="vibestart-header__inner">
		<div class="vibestart-brand" aria-label="<?php esc_attr_e( 'VibeStart Academy', 'vibestart-academy' ); ?>">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/vibestart-mark.svg' ); ?>" width="218" height="52" alt="<?php esc_attr_e( 'VibeStart Academy', 'vibestart-academy' ); ?>">
		</div>
		<button class="vibestart-menu-toggle" type="button" aria-expanded="false" aria-controls="vibestart-primary-menu">
			<span><?php esc_html_e( 'Menu', 'vibestart-academy' ); ?></span>
			<span class="vibestart-menu-toggle__icon" aria-hidden="true"></span>
		</button>
		<nav class="vibestart-primary-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'vibestart-academy' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'vibestart-header',
					'container'      => false,
					'menu_id'        => 'vibestart-primary-menu',
					'menu_class'     => 'vibestart-menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>
