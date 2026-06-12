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
define( 'VIBESTART_ACADEMY_CONTENT_VERSION', '2.0.0' );

add_action( 'after_setup_theme', 'vibestart_academy_setup', 20 );
add_filter( 'acf/load_field_groups', 'vibestart_academy_filter_field_groups', 30 );
add_filter( 'acf/pre_update_field_group', 'vibestart_academy_assign_field_group_owner', 20 );
add_action( 'pre_get_posts', 'vibestart_academy_filter_field_group_admin_list' );
add_action( 'load-post.php', 'vibestart_academy_block_foreign_field_group_edit' );

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
	remove_action( 'wp_footer', 'wp_print_speculation_rules' );
}

/**
 * Assign newly created or edited groups to this product.
 *
 * @param array $field_group Field group data.
 * @return array
 */
function vibestart_academy_assign_field_group_owner( $field_group ) {
	$field_group['theme_owner'] = 'vibestart-academy';

	return $field_group;
}

/**
 * Resolve a field group owner from stored metadata or its legacy key prefix.
 *
 * @param array|WP_Post $field_group Field group data.
 * @return string
 */
function vibestart_academy_field_group_owner( $field_group ) {
	if ( $field_group instanceof WP_Post ) {
		$stored = maybe_unserialize( $field_group->post_content );
		$owner  = is_array( $stored ) ? ( $stored['theme_owner'] ?? '' ) : '';
		$key    = $field_group->post_name;
	} else {
		$owner = $field_group['theme_owner'] ?? '';
		$key   = $field_group['key'] ?? '';
	}

	if ( $owner ) {
		return sanitize_key( $owner );
	}

	if ( 0 === strpos( $key, 'group_vibestart_' ) ) {
		return 'vibestart-academy';
	}

	if ( 0 === strpos( $key, 'group_devaccelerate_' ) ) {
		return 'devaccelerate-lab';
	}

	return 'global';
}

/**
 * Keep groups owned by other products out of ACF runtime screens.
 *
 * @param array[] $field_groups Loaded ACF field groups.
 * @return array[]
 */
function vibestart_academy_filter_field_groups( $field_groups ) {
	return array_values(
		array_filter(
			$field_groups,
			static function ( $field_group ) {
				return in_array( vibestart_academy_field_group_owner( $field_group ), array( 'vibestart-academy', 'global' ), true );
			}
		)
	);
}

/**
 * Hide the other product field group from the ACF admin list.
 *
 * @param WP_Query $query Current WordPress query.
 */
function vibestart_academy_filter_field_group_admin_list( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'acf-field-group' !== $query->get( 'post_type' ) ) {
		return;
	}

	$field_groups = get_posts(
		array(
			'post_type'      => 'acf-field-group',
			'post_status'    => array( 'publish', 'acf-disabled', 'trash' ),
			'posts_per_page' => -1,
		)
	);

	$foreign_group_ids = array();
	foreach ( $field_groups as $field_group ) {
		if ( ! in_array( vibestart_academy_field_group_owner( $field_group ), array( 'vibestart-academy', 'global' ), true ) ) {
			$foreign_group_ids[] = (int) $field_group->ID;
		}
	}

	$excluded = array_filter( array_map( 'absint', (array) $query->get( 'post__not_in' ) ) );
	$query->set( 'post__not_in', array_unique( array_merge( $excluded, array_map( 'absint', $foreign_group_ids ) ) ) );
}

/**
 * Prevent direct editing of the other product field group.
 */
function vibestart_academy_block_foreign_field_group_edit() {
	$post_id = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT );
	$post    = $post_id ? get_post( $post_id ) : null;

	if ( $post instanceof WP_Post && 'acf-field-group' === $post->post_type && ! in_array( vibestart_academy_field_group_owner( $post ), array( 'vibestart-academy', 'global' ), true ) ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=acf-field-group' ) );
		exit;
	}
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

	if ( $menu ) {
		return (int) $menu->term_id;
	}

	$menu_id = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return 0;
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
	if ( ! function_exists( 'update_field' ) || VIBESTART_ACADEMY_CONTENT_VERSION === get_option( 'vibestart_academy_acf_seeded' ) ) {
		return;
	}

	$home_id = (int) get_option( 'page_on_front' );
	if ( ! $home_id ) {
		return;
	}

	$header_logo_id = vibestart_academy_seed_image(
		'vibestart-header-logo',
		get_stylesheet_directory() . '/assets/images/vibestart-mark.png',
		'VibeStart Academy'
	);
	$footer_logo_id = vibestart_academy_seed_image(
		'vibestart-footer-logo',
		get_stylesheet_directory() . '/assets/images/vibestart-mark-light.png',
		'VibeStart Academy'
	);

	update_field(
		'field_vibestart_site_chrome',
		array(
			'vibestart_header_logo'            => $header_logo_id,
			'vibestart_footer_logo'            => $footer_logo_id,
			'vibestart_skip_label'             => 'Skip to course overview',
			'vibestart_mobile_menu_label'      => 'Menu',
			'vibestart_footer_description'     => 'A friendly first step into building useful projects with AI.',
			'vibestart_footer_navigation_title' => 'Navigation',
			'vibestart_footer_social_title'    => 'Social',
			'vibestart_footer_legal_title'     => 'Legal',
			'vibestart_copyright'              => '© 2026 VibeStart Learning. Start small, build clearly.',
		),
		$home_id
	);

	update_field(
		'field_vibestart_hero',
		array(
			'vibestart_hero_eyebrow'     => 'AI BUILDING FOR ABSOLUTE BEGINNERS',
			'vibestart_hero_title'       => 'Build with AI Before You Learn to Code',
			'vibestart_hero_description' => 'A practical starting point for turning plain-language ideas into useful digital projects with modern AI tools.',
			'vibestart_hero_cta_label'   => 'Explore the beginner path',
			'vibestart_prompt_label'      => 'YOUR IDEA',
			'vibestart_prompt_text'       => 'Create a simple course page for people who have never written code.',
			'vibestart_prompt_status'     => 'Ready to build',
		),
		$home_id
	);

	update_field(
		'field_vibestart_learning_path',
		array(
			'vibestart_path_eyebrow'     => 'YOUR FIRST THREE STEPS',
			'vibestart_path_title'       => 'A Simple Path from Idea to Prototype',
			'vibestart_path_description' => 'Learn the repeatable habits that help non-programmers communicate with AI, improve outputs, and finish small working products.',
		),
		$home_id
	);

	$cards = array(
		'field_vibestart_card_one' => array(
			'vibestart_card_one_marker'      => '01',
			'vibestart_card_one_title'       => 'Describe Your Idea',
			'vibestart_card_one_description' => 'Turn a rough concept into a clear request that an AI coding tool can understand.',
		),
		'field_vibestart_card_two' => array(
			'vibestart_card_two_marker'      => '02',
			'vibestart_card_two_title'       => 'Guide the AI',
			'vibestart_card_two_description' => 'Review each result, provide focused feedback, and keep the project aligned with your goal.',
		),
		'field_vibestart_card_three' => array(
			'vibestart_card_three_marker'      => '03',
			'vibestart_card_three_title'       => 'Launch a Working Prototype',
			'vibestart_card_three_description' => 'Combine small verified steps into a simple project you can open, test, and share.',
		),
	);

	foreach ( $cards as $field_key => $value ) {
		update_field( $field_key, $value, $home_id );
	}

	update_option( 'vibestart_academy_acf_seeded', VIBESTART_ACADEMY_CONTENT_VERSION );
}

/**
 * Register a bundled image in the Media Library once.
 *
 * @param string $asset_key Stable asset key.
 * @param string $source_path Theme image path.
 * @param string $alt_text Initial alternative text.
 * @return int
 */
function vibestart_academy_seed_image( $asset_key, $source_path, $alt_text ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_vibestart_seed_asset',
			'meta_value'     => $asset_key,
		)
	);

	if ( $existing ) {
		return (int) $existing[0];
	}

	if ( ! file_exists( $source_path ) ) {
		return 0;
	}

	$upload = wp_upload_bits( basename( $source_path ), null, file_get_contents( $source_path ) );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/png',
			'post_title'     => sanitize_text_field( $alt_text ),
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt_text );
	update_post_meta( $attachment_id, '_vibestart_seed_asset', $asset_key );

	return (int) $attachment_id;
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

/**
 * Return a single editable ACF value.
 *
 * @param string $name Field name.
 * @param mixed  $fallback Default value.
 * @return mixed
 */
function vibestart_academy_field( $name, $fallback ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name );
		if ( false !== $value && null !== $value && '' !== $value ) {
			return $value;
		}
	}

	return $fallback;
}

/**
 * Normalize an ACF image value for templates.
 *
 * @param mixed  $value Image array, attachment ID, or URL.
 * @param string $fallback_url Bundled fallback URL.
 * @param string $fallback_alt Bundled fallback alt text.
 * @return array
 */
function vibestart_academy_image( $value, $fallback_url, $fallback_alt ) {
	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		return array(
			'url' => $value['url'],
			'alt' => ! empty( $value['alt'] ) ? $value['alt'] : $fallback_alt,
		);
	}

	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, 'full' );
		if ( $url ) {
			return array(
				'url' => $url,
				'alt' => get_post_meta( (int) $value, '_wp_attachment_image_alt', true ) ?: $fallback_alt,
			);
		}
	}

	if ( is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) ) {
		return array(
			'url' => $value,
			'alt' => $fallback_alt,
		);
	}

	return array(
		'url' => $fallback_url,
		'alt' => $fallback_alt,
	);
}
