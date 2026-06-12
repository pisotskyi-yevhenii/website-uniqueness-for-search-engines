<?php
/**
 * DevAccelerate console header.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$devaccelerate_chrome = DevAccelerate_Theme::field(
	'devaccelerate_site_chrome',
	array(
		'devaccelerate_header_logo'        => 0,
		'devaccelerate_header_tagline'     => 'AI SYSTEMS FOR SOFTWARE TEAMS',
		'devaccelerate_skip_label'         => 'Skip to engineering workflow',
		'devaccelerate_mobile_menu_symbol' => '[+]',
		'devaccelerate_mobile_menu_label'  => 'Open console navigation',
		'devaccelerate_mobile_menu_close_label' => 'Close console navigation',
		'devaccelerate_system_state'       => 'SYSTEM: READY',
	)
);
$devaccelerate_header_logo = DevAccelerate_Theme::image(
	$devaccelerate_chrome['devaccelerate_header_logo'],
	get_stylesheet_directory_uri() . '/assets/images/devaccelerate-symbol.svg',
	'DevAccelerate Lab'
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
<a class="devaccelerate-skip" href="#devaccelerate-main"><?php echo esc_html( $devaccelerate_chrome['devaccelerate_skip_label'] ); ?></a>
<header class="devaccelerate-header">
	<div class="devaccelerate-brand-bar">
		<img src="<?php echo esc_url( $devaccelerate_header_logo['url'] ); ?>" width="310" height="58" alt="<?php echo esc_attr( $devaccelerate_header_logo['alt'] ); ?>">
		<p><?php echo esc_html( $devaccelerate_chrome['devaccelerate_header_tagline'] ); ?></p>
	</div>
	<div class="devaccelerate-nav-bar">
		<button
			class="devaccelerate-nav-toggle"
			type="button"
			aria-expanded="false"
			aria-controls="devaccelerate-console-menu"
			data-open-label="<?php echo esc_attr( $devaccelerate_chrome['devaccelerate_mobile_menu_label'] ); ?>"
			data-close-label="<?php echo esc_attr( $devaccelerate_chrome['devaccelerate_mobile_menu_close_label'] ); ?>"
		>
			<span class="devaccelerate-nav-toggle__symbol" aria-hidden="true"><?php echo esc_html( $devaccelerate_chrome['devaccelerate_mobile_menu_symbol'] ); ?></span>
			<span class="devaccelerate-nav-toggle__label"><?php echo esc_html( $devaccelerate_chrome['devaccelerate_mobile_menu_label'] ); ?></span>
		</button>
		<nav class="devaccelerate-console-nav" aria-label="<?php esc_attr_e( 'Console navigation', 'devaccelerate-lab' ); ?>">
			<button class="devaccelerate-console-nav__close" type="button">
				<span aria-hidden="true">[-]</span>
				<?php echo esc_html( $devaccelerate_chrome['devaccelerate_mobile_menu_close_label'] ); ?>
			</button>
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
		<span class="devaccelerate-system-state"><?php echo esc_html( $devaccelerate_chrome['devaccelerate_system_state'] ); ?></span>
	</div>
</header>
