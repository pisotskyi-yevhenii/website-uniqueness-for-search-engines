<?php
/**
 * VibeStart site header.
 *
 * @package VibeStartAcademy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vibestart_chrome = vibestart_academy_group(
	'vibestart_site_chrome',
	array(
		'vibestart_header_logo'       => 0,
		'vibestart_skip_label'        => 'Skip to course overview',
		'vibestart_mobile_menu_label' => 'Menu',
	)
);
$vibestart_header_logo = vibestart_academy_image(
	$vibestart_chrome['vibestart_header_logo'],
	get_stylesheet_directory_uri() . '/assets/images/vibestart-mark.svg',
	'VibeStart Academy'
);
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
<a class="vibestart-skip-link" href="#vibestart-main"><?php echo esc_html( $vibestart_chrome['vibestart_skip_label'] ); ?></a>
<header class="vibestart-header">
	<div class="vibestart-header__inner">
		<div class="vibestart-brand">
			<img src="<?php echo esc_url( $vibestart_header_logo['url'] ); ?>" width="218" height="52" alt="<?php echo esc_attr( $vibestart_header_logo['alt'] ); ?>">
		</div>
		<button class="vibestart-menu-toggle" type="button" aria-expanded="false" aria-controls="vibestart-primary-menu">
			<span><?php echo esc_html( $vibestart_chrome['vibestart_mobile_menu_label'] ); ?></span>
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
