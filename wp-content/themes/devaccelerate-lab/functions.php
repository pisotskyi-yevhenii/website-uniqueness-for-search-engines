<?php
/**
 * DevAccelerate Lab runtime.
 *
 * @package DevAccelerateLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class DevAccelerate_Theme {
	const VERSION = '2.0.0';
	const CONTENT_VERSION = '2.0.0';

	/**
	 * Attach theme behavior.
	 */
	public static function boot() {
		add_action( 'after_setup_theme', array( __CLASS__, 'configure' ), 25 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 110 );
		add_filter( 'body_class', array( __CLASS__, 'body_identity' ), 110 );
		add_filter( 'the_generator', '__return_empty_string' );
		add_filter( 'nav_menu_link_attributes', array( __CLASS__, 'disable_placeholder_links' ), 10, 4 );
		add_filter( 'acf/load_field_groups', array( __CLASS__, 'filter_field_groups' ), 30 );
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_field_group_admin_list' ) );
		add_action( 'load-post.php', array( __CLASS__, 'block_foreign_field_group_edit' ) );
		add_action( 'after_switch_theme', array( __CLASS__, 'provision_demo' ) );
		add_action( 'acf/init', array( __CLASS__, 'seed_panel' ), 30 );
	}

	/**
	 * Register product capabilities and detach the parent presentation layer.
	 */
	public static function configure() {
		load_child_theme_textdomain( 'devaccelerate-lab', get_stylesheet_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'style', 'script', 'navigation-widgets' ) );

		register_nav_menus(
			array(
				'devaccelerate-console'   => __( 'DevAccelerate Console Navigation', 'devaccelerate-lab' ),
				'devaccelerate-resources' => __( 'DevAccelerate Resource Links', 'devaccelerate-lab' ),
				'devaccelerate-network'   => __( 'DevAccelerate Network Links', 'devaccelerate-lab' ),
				'devaccelerate-policy'    => __( 'DevAccelerate Policy Links', 'devaccelerate-lab' ),
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
	 * Replace parent assets with a separate engineering interface bundle.
	 */
	public static function enqueue() {
		foreach ( array( 'generate-style', 'generate-child', 'generate-comments', 'generate-widget-areas', 'generate-fonts', 'generate-google-fonts', 'generate-font-icons', 'font-awesome', 'generate-rtl' ) as $handle ) {
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}

		foreach ( array( 'generate-menu', 'generate-dropdown-click', 'generate-modal', 'generate-navigation-search', 'generate-back-to-top' ) as $handle ) {
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}

		$font_path = get_stylesheet_directory() . '/assets/css/type-system.css';
		$ui_path   = get_stylesheet_directory() . '/assets/css/lab-console.css';
		$js_path   = get_stylesheet_directory() . '/assets/js/lab-console.js';

		wp_enqueue_style(
			'devaccelerate-type-system',
			get_stylesheet_directory_uri() . '/assets/css/type-system.css',
			array(),
			file_exists( $font_path ) ? (string) filemtime( $font_path ) : self::VERSION
		);

		wp_enqueue_style(
			'devaccelerate-console-ui',
			get_stylesheet_directory_uri() . '/assets/css/lab-console.css',
			array( 'devaccelerate-type-system' ),
			file_exists( $ui_path ) ? (string) filemtime( $ui_path ) : self::VERSION
		);

		wp_enqueue_script(
			'devaccelerate-console-controller',
			get_stylesheet_directory_uri() . '/assets/js/lab-console.js',
			array(),
			file_exists( $js_path ) ? (string) filemtime( $js_path ) : self::VERSION,
			true
		);
	}

	/**
	 * Assign a product-specific body signature.
	 *
	 * @param string[] $classes Existing body classes.
	 * @return string[]
	 */
	public static function body_identity( $classes ) {
		$classes = array_values(
			array_filter(
				$classes,
				static function ( $class_name ) {
					return ! in_array( $class_name, array( 'wp-theme-generatepress', 'wp-child-theme-devaccelerate-lab' ), true );
				}
			)
		);
		$classes[] = 'devaccelerate-engineering-console';

		return array_unique( $classes );
	}

	/**
	 * Keep the other product field group out of ACF runtime screens.
	 *
	 * @param array[] $field_groups Loaded ACF field groups.
	 * @return array[]
	 */
	public static function filter_field_groups( $field_groups ) {
		return array_values(
			array_filter(
				$field_groups,
				static function ( $field_group ) {
					return 'group_vibestart_home_showcase' !== ( $field_group['key'] ?? '' );
				}
			)
		);
	}

	/**
	 * Hide the other product field group from the ACF admin list.
	 *
	 * @param WP_Query $query Current WordPress query.
	 */
	public static function filter_field_group_admin_list( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || 'acf-field-group' !== $query->get( 'post_type' ) ) {
			return;
		}

		$foreign_group_ids = get_posts(
			array(
				'post_type'      => 'acf-field-group',
				'post_status'    => array( 'publish', 'acf-disabled', 'trash' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'name'           => 'group_vibestart_home_showcase',
			)
		);
		if ( ! $foreign_group_ids ) {
			return;
		}

		$excluded = array_filter( array_map( 'absint', (array) $query->get( 'post__not_in' ) ) );
		$query->set( 'post__not_in', array_unique( array_merge( $excluded, array_map( 'absint', $foreign_group_ids ) ) ) );
	}

	/**
	 * Prevent direct editing of the other product field group.
	 */
	public static function block_foreign_field_group_edit() {
		$post_id = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT );
		$post    = $post_id ? get_post( $post_id ) : null;

		if ( $post instanceof WP_Post && 'acf-field-group' === $post->post_type && 'group_vibestart_home_showcase' === $post->post_name ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=acf-field-group' ) );
			exit;
		}
	}

	/**
	 * Disable the demonstration links while retaining semantic navigation.
	 *
	 * @param array    $attributes Link attributes.
	 * @param WP_Post  $item Menu item.
	 * @param stdClass $arguments Menu arguments.
	 * @param int      $level Menu level.
	 * @return array
	 */
	public static function disable_placeholder_links( $attributes, $item, $arguments, $level ) {
		unset( $item, $level );

		if ( isset( $arguments->theme_location ) && 0 === strpos( $arguments->theme_location, 'devaccelerate-' ) ) {
			$attributes['href']          = '#';
			$attributes['aria-disabled'] = 'true';
			$attributes['tabindex']      = '-1';
			$attributes['data-console-placeholder'] = 'true';
		}

		return $attributes;
	}

	/**
	 * Prepare the product-specific site state.
	 */
	public static function provision_demo() {
		$home_id = self::home_page_id();

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
		update_option( 'page_for_posts', 0 );
		update_option( 'blogname', 'DevAccelerate Lab' );
		update_option( 'blogdescription', 'Practical AI systems for software teams' );

		$privacy = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
		if ( $privacy instanceof WP_Post && (int) $privacy->ID !== $home_id ) {
			wp_delete_post( $privacy->ID, true );
		}

		$location_map = get_theme_mod( 'nav_menu_locations', array() );
		$location_map['devaccelerate-console'] = self::replace_menu(
			'DevAccelerate Console Menu',
			array( 'Home', 'About Us', 'Services', 'Our Team', 'Contact Us' )
		);
		$location_map['devaccelerate-resources'] = self::replace_menu(
			'DevAccelerate Resource Menu',
			array( 'Workflow Map', 'Tool Selection', 'Review Standards' )
		);
		$location_map['devaccelerate-network'] = self::replace_menu(
			'DevAccelerate Network Menu',
			array( 'LinkedIn', 'X', 'Facebook' )
		);
		$location_map['devaccelerate-policy'] = self::replace_menu(
			'DevAccelerate Policy Menu',
			array( 'Privacy Policy', 'Terms and Conditions' )
		);

		set_theme_mod( 'nav_menu_locations', $location_map );
	}

	/**
	 * Resolve or create the single home page.
	 *
	 * @return int
	 */
	private static function home_page_id() {
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
	 * Rebuild one theme-owned menu.
	 *
	 * @param string   $name Menu name.
	 * @param string[] $entries Menu labels.
	 * @return int
	 */
	private static function replace_menu( $name, $entries ) {
		$menu = wp_get_nav_menu_object( $name );

		if ( $menu ) {
			return (int) $menu->term_id;
		}

		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}

		foreach ( $entries as $offset => $entry ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'    => $entry,
					'menu-item-url'      => '#',
					'menu-item-status'   => 'publish',
					'menu-item-position' => $offset + 1,
					'menu-item-type'     => 'custom',
				)
			);
		}

		return $menu_id;
	}

	/**
	 * Populate the engineering panel after Local JSON is available.
	 */
	public static function seed_panel() {
		if ( ! function_exists( 'update_field' ) || self::CONTENT_VERSION === get_option( 'devaccelerate_panel_seeded' ) ) {
			return;
		}

		$home_id = (int) get_option( 'page_on_front' );
		if ( ! $home_id ) {
			return;
		}

		$header_logo_id = self::seed_image(
			'devaccelerate-header-logo',
			get_stylesheet_directory() . '/assets/images/devaccelerate-symbol.png',
			'DevAccelerate Lab'
		);
		$footer_logo_id = self::seed_image(
			'devaccelerate-footer-logo',
			get_stylesheet_directory() . '/assets/images/devaccelerate-symbol-light.png',
			'DevAccelerate Lab'
		);

		update_field(
			'field_devaccelerate_site_chrome',
			array(
				'devaccelerate_header_logo'        => $header_logo_id,
				'devaccelerate_header_tagline'     => 'AI SYSTEMS FOR SOFTWARE TEAMS',
				'devaccelerate_skip_label'         => 'Skip to engineering workflow',
				'devaccelerate_mobile_menu_symbol' => '[+]',
				'devaccelerate_mobile_menu_label'  => 'Open console navigation',
				'devaccelerate_mobile_menu_close_label' => 'Close console navigation',
				'devaccelerate_system_state'       => 'SYSTEM: READY',
				'devaccelerate_footer_logo'        => $footer_logo_id,
				'devaccelerate_copyright'          => '© 2026 DevAccelerate Engineering. Human-reviewed systems only.',
			),
			$home_id
		);

		update_field( 'field_devaccelerate_panel_kicker', 'ENGINEERING ENABLEMENT / COURSE 02', $home_id );
		update_field( 'field_devaccelerate_panel_title', 'Integrate AI into Real Development Workflows', $home_id );
		update_field(
			'field_devaccelerate_panel_description',
			'Learn how to select coding agents, define review boundaries, and introduce AI without weakening engineering ownership.',
			$home_id
		);
		update_field(
			'field_devaccelerate_primary_resource',
			array(
				'title'  => 'Inspect the workflow',
				'url'    => '#',
				'target' => '',
			),
			$home_id
		);
		update_field( 'field_devaccelerate_panel_cursor', '_', $home_id );
		update_field( 'field_devaccelerate_command_symbol', '>', $home_id );
		update_field(
			'field_devaccelerate_runtime',
			array(
				'devaccelerate_runtime_filename'   => 'workflow.config',
				'devaccelerate_runtime_status'     => 'ACTIVE',
				'devaccelerate_runtime_key_one'    => 'agent.role',
				'devaccelerate_runtime_value_one'  => 'implementation_assistant',
				'devaccelerate_runtime_key_two'    => 'human.role',
				'devaccelerate_runtime_value_two'  => 'decision_owner',
				'devaccelerate_runtime_key_three'  => 'review.mode',
				'devaccelerate_runtime_value_three' => 'required',
				'devaccelerate_runtime_key_four'   => 'output.state',
				'devaccelerate_runtime_value_four' => 'verified',
			),
			$home_id
		);
		update_field(
			'field_devaccelerate_metric_one',
			array(
				'devaccelerate_metric_one_value' => '01',
				'devaccelerate_metric_one_label' => 'Tool selection framework',
			),
			$home_id
		);
		update_field(
			'field_devaccelerate_metric_two',
			array(
				'devaccelerate_metric_two_value' => '02',
				'devaccelerate_metric_two_label' => 'Review and control loop',
			),
			$home_id
		);
		update_field( 'field_devaccelerate_show_matrix', 1, $home_id );
		update_field( 'field_devaccelerate_accent', '#b7ff3c', $home_id );
		update_field(
			'field_devaccelerate_matrix',
			array(
				'devaccelerate_matrix_eyebrow'          => 'IMPLEMENTATION MATRIX',
				'devaccelerate_matrix_title'            => 'A controlled path from tool trial to team workflow',
				'devaccelerate_matrix_item_one_label'   => 'SELECT',
				'devaccelerate_matrix_item_one_text'    => 'Match the tool to repository access, task risk, and review requirements.',
				'devaccelerate_matrix_item_two_label'   => 'CONSTRAIN',
				'devaccelerate_matrix_item_two_text'    => 'Define context, acceptance criteria, and the decisions that remain human-owned.',
				'devaccelerate_matrix_item_three_label' => 'VERIFY',
				'devaccelerate_matrix_item_three_text'  => 'Use tests, diffs, and manual review before generated work reaches the codebase.',
			),
			$home_id
		);
		update_field(
			'field_devaccelerate_footer_prompt',
			array(
				'devaccelerate_footer_eyebrow'   => 'NEXT SYSTEM UPGRADE',
				'devaccelerate_footer_title'     => 'Make AI part of the engineering process, not a shortcut around it.',
				'devaccelerate_footer_link_label' => 'Open implementation notes',
			),
			$home_id
		);

		update_option( 'devaccelerate_panel_seeded', self::CONTENT_VERSION );
	}

	/**
	 * Register a bundled PNG in the Media Library once.
	 *
	 * @param string $asset_key Stable asset key.
	 * @param string $source_path Theme image path.
	 * @param string $alt_text Initial alternative text.
	 * @return int
	 */
	private static function seed_image( $asset_key, $source_path, $alt_text ) {
		$existing = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_devaccelerate_seed_asset',
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
		update_post_meta( $attachment_id, '_devaccelerate_seed_asset', $asset_key );

		return (int) $attachment_id;
	}

	/**
	 * Read an ACF value without making the template depend on ACF availability.
	 *
	 * @param string $field Field name.
	 * @param mixed  $fallback Default value.
	 * @return mixed
	 */
	public static function field( $field, $fallback ) {
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field );
			if ( false !== $value && null !== $value && '' !== $value ) {
				return $value;
			}
		}

		return $fallback;
	}

	/**
	 * Normalize an ACF image value.
	 *
	 * @param mixed  $value ACF image value.
	 * @param string $fallback_url Bundled fallback.
	 * @param string $fallback_alt Bundled alt text.
	 * @return array
	 */
	public static function image( $value, $fallback_url, $fallback_alt ) {
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
}

DevAccelerate_Theme::boot();
