<?php
/**
 * VibeStart Academy theme functions.
 *
 * @package VibeStartAcademy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VIBESTART_ACADEMY_VERSION', '1.0.0' );

add_action( 'after_setup_theme', 'vibestart_academy_setup', 20 );
/**
 * Configure the child theme and detach unused parent presentation hooks.
 */
function vibestart_academy_setup() {
	load_child_theme_textdomain( 'vibestart-academy', get_stylesheet_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'style', 'script', 'navigation-widgets' ) );

	register_nav_menus(
		array(
			'vibestart-header'     => __( 'VibeStart Header', 'vibestart-academy' ),
			'vibestart-navigation' => __( 'VibeStart Footer Navigation', 'vibestart-academy' ),
			'vibestart-social'     => __( 'VibeStart Social Links', 'vibestart-academy' ),
			'vibestart-legal'      => __( 'VibeStart Legal Links', 'vibestart-academy' ),
		)
	);

	remove_action( 'wp_enqueue_scripts', 'generate_scripts' );
	remove_action( 'wp_enqueue_scripts', 'generate_enqueue_dynamic_css', 50 );
	remove_action( 'wp_enqueue_scripts', 'generate_enqueue_google_fonts', 0 );
	remove_filter( 'body_class', 'generate_body_classes' );
	remove_action( 'wp_head', 'generate_pingback_header' );
	remove_action( 'wp_head', 'generate_add_viewport', 1 );
	remove_action( 'wp_head', 'wp_generator' );
}

add_filter( 'the_generator', '__return_empty_string' );

add_action( 'wp_enqueue_scripts', 'vibestart_academy_assets', 100 );
/**
 * Load only the assets owned by this product.
 */
function vibestart_academy_assets() {
	$parent_styles = array(
		'generate-style',
		'generate-child',
		'generate-comments',
		'generate-widget-areas',
		'generate-fonts',
		'generate-google-fonts',
		'generate-font-icons',
		'font-awesome',
		'generate-rtl',
	);

	$parent_scripts = array(
		'generate-menu',
		'generate-dropdown-click',
		'generate-modal',
		'generate-navigation-search',
		'generate-back-to-top',
	);

	foreach ( $parent_styles as $handle ) {
		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
	}

	foreach ( $parent_scripts as $handle ) {
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}

	$font_file  = get_stylesheet_directory() . '/assets/css/fonts.css';
	$style_file = get_stylesheet_directory() . '/assets/css/academy.css';
	$script_file = get_stylesheet_directory() . '/assets/js/academy.js';

	wp_enqueue_style(
		'vibestart-type-library',
		get_stylesheet_directory_uri() . '/assets/css/fonts.css',
		array(),
		file_exists( $font_file ) ? (string) filemtime( $font_file ) : VIBESTART_ACADEMY_VERSION
	);

	wp_enqueue_style(
		'vibestart-learning-interface',
		get_stylesheet_directory_uri() . '/assets/css/academy.css',
		array( 'vibestart-type-library' ),
		file_exists( $style_file ) ? (string) filemtime( $style_file ) : VIBESTART_ACADEMY_VERSION
	);

	wp_enqueue_script(
		'vibestart-guided-navigation',
		get_stylesheet_directory_uri() . '/assets/js/academy.js',
		array(),
		file_exists( $script_file ) ? (string) filemtime( $script_file ) : VIBESTART_ACADEMY_VERSION,
		true
	);
}

add_filter( 'body_class', 'vibestart_academy_body_classes', 100 );
/**
 * Replace generic parent identifiers with the product class.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function vibestart_academy_body_classes( $classes ) {
	$blocked = array(
		'wp-theme-generatepress',
		'wp-child-theme-vibestart-academy',
	);

	$classes   = array_values( array_diff( $classes, $blocked ) );
	$classes[] = 'vibestart-learning-product';

	return array_unique( $classes );
}

add_filter( 'nav_menu_link_attributes', 'vibestart_academy_placeholder_attributes', 10, 4 );
/**
 * Mark theme menu links as non-navigational placeholders.
 *
 * @param array    $atts Menu link attributes.
 * @param WP_Post  $menu_item Menu item object.
 * @param stdClass $args Menu arguments.
 * @param int      $depth Menu depth.
 * @return array
 */
function vibestart_academy_placeholder_attributes( $atts, $menu_item, $args, $depth ) {
	unset( $menu_item, $depth );

	if ( isset( $args->theme_location ) && 0 === strpos( $args->theme_location, 'vibestart-' ) ) {
		$atts['href']          = '#';
		$atts['aria-disabled'] = 'true';
		$atts['tabindex']      = '-1';
		$atts['data-placeholder-link'] = 'true';
	}

	return $atts;
}

add_action( 'after_switch_theme', 'vibestart_academy_initialize_site' );
/**
 * Prepare the single-page demo and theme-owned menus.
 */
function vibestart_academy_initialize_site() {
	$home_id = vibestart_academy_prepare_home_page();

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', 0 );
	update_option( 'blogname', 'VibeStart Academy' );
	update_option( 'blogdescription', 'AI building skills for complete beginners' );

	$privacy_page = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
	if ( $privacy_page instanceof WP_Post && (int) $privacy_page->ID !== $home_id ) {
		wp_delete_post( $privacy_page->ID, true );
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );

	$locations['vibestart-header'] = vibestart_academy_build_menu(
		'VibeStart Header Menu',
		array( 'Home', 'About Us', 'Services', 'Our Team', 'Contact Us' )
	);
	$locations['vibestart-navigation'] = vibestart_academy_build_menu(
		'VibeStart Navigation Menu',
		array( 'Home', 'About Us', 'Services', 'Our Team', 'Contact Us' )
	);
	$locations['vibestart-social'] = vibestart_academy_build_menu(
		'VibeStart Social Menu',
		array( 'LinkedIn', 'X', 'Facebook' )
	);
	$locations['vibestart-legal'] = vibestart_academy_build_menu(
		'VibeStart Legal Menu',
		array( 'Privacy Policy', 'Terms and Conditions' )
	);

	set_theme_mod( 'nav_menu_locations', $locations );
	delete_option( 'vibestart_academy_acf_seeded' );
}

/**
 * Reuse the existing sample page as the only public page.
 *
 * @return int
 */
function vibestart_academy_prepare_home_page() {
	$page = get_page_by_path( 'home', OBJECT, 'page' );

	if ( ! $page ) {
		$page = get_page_by_path( 'sample-page', OBJECT, 'page' );
	}

	if ( ! $page ) {
		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'Home',
				'post_name'    => 'home',
				'post_content' => '',
			)
		);

		return is_wp_error( $page_id ) ? 0 : (int) $page_id;
	}

	wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_title'   => 'Home',
			'post_name'    => 'home',
			'post_status'  => 'publish',
			'post_content' => '',
		)
	);

	return (int) $page->ID;
}

/**
 * Create a predictable placeholder menu without duplicating its items.
 *
 * @param string   $menu_name Menu name.
 * @param string[] $labels Menu item labels.
 * @return int
 */
function vibestart_academy_build_menu( $menu_name, $labels ) {
	$menu = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}
	} else {
		$menu_id = (int) $menu->term_id;
		foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}

	foreach ( $labels as $position => $label ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'    => $label,
				'menu-item-url'      => '#',
				'menu-item-status'   => 'publish',
				'menu-item-position' => $position + 1,
				'menu-item-type'     => 'custom',
			)
		);
	}

	return $menu_id;
}

add_action( 'acf/init', 'vibestart_academy_seed_acf_values', 20 );
/**
 * Store editable starter content after ACF has loaded the local JSON group.
 */
function vibestart_academy_seed_acf_values() {
	if ( ! function_exists( 'update_field' ) || get_option( 'vibestart_academy_acf_seeded' ) ) {
		return;
	}

	$home_id = (int) get_option( 'page_on_front' );
	if ( ! $home_id ) {
		return;
	}

	update_field(
		'field_vibestart_hero',
		array(
			'vibestart_hero_eyebrow'     => 'AI BUILDING FOR ABSOLUTE BEGINNERS',
			'vibestart_hero_title'       => 'Build with AI Before You Learn to Code',
			'vibestart_hero_description' => 'A practical starting point for turning plain-language ideas into useful digital projects with modern AI tools.',
			'vibestart_hero_cta_label'   => 'Explore the beginner path',
		),
		$home_id
	);

	update_field(
		'field_vibestart_learning_path',
		array(
			'vibestart_path_title'       => 'A Simple Path from Idea to Prototype',
			'vibestart_path_description' => 'Learn the repeatable habits that help non-programmers communicate with AI, improve outputs, and finish small working products.',
		),
		$home_id
	);

	$cards = array(
		'field_vibestart_card_one' => array(
			'vibestart_card_one_title'       => 'Describe Your Idea',
			'vibestart_card_one_description' => 'Turn a rough concept into a clear request that an AI coding tool can understand.',
		),
		'field_vibestart_card_two' => array(
			'vibestart_card_two_title'       => 'Guide the AI',
			'vibestart_card_two_description' => 'Review each result, provide focused feedback, and keep the project aligned with your goal.',
		),
		'field_vibestart_card_three' => array(
			'vibestart_card_three_title'       => 'Launch a Working Prototype',
			'vibestart_card_three_description' => 'Combine small verified steps into a simple project you can open, test, and share.',
		),
	);

	foreach ( $cards as $field_key => $value ) {
		update_field( $field_key, $value, $home_id );
	}

	update_option( 'vibestart_academy_acf_seeded', 1 );
}

/**
 * Return an ACF group or a stable fallback.
 *
 * @param string $name Field name.
 * @param array  $fallback Default values.
 * @return array
 */
function vibestart_academy_group( $name, $fallback ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name );
		if ( is_array( $value ) ) {
			return wp_parse_args( $value, $fallback );
		}
	}

	return $fallback;
}
