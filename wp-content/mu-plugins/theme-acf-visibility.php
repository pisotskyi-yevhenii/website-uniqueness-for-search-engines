<?php
/**
 * Plugin Name: Theme ACF Visibility
 * Description: Hides child-theme ACF groups while the GeneratePress parent theme is active.
 * Version: 1.0.0
 * Author: Project Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'acf/load_field_groups', 'theme_acf_visibility_filter_field_groups', 40 );
add_action( 'pre_get_posts', 'theme_acf_visibility_filter_admin_list' );
add_action( 'load-post.php', 'theme_acf_visibility_block_direct_edit' );

/**
 * Check whether the GeneratePress parent theme is active directly.
 *
 * @return bool
 */
function theme_acf_visibility_is_parent_active() {
	return 'generatepress' === get_stylesheet();
}

/**
 * Resolve the owner stored by a child theme.
 *
 * @param array|WP_Post $field_group Field group data.
 * @return string
 */
function theme_acf_visibility_get_owner( $field_group ) {
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
 * Determine whether a field group belongs to a child theme.
 *
 * @param array|WP_Post $field_group Field group data.
 * @return bool
 */
function theme_acf_visibility_is_child_group( $field_group ) {
	return 'global' !== theme_acf_visibility_get_owner( $field_group );
}

/**
 * Hide child-theme groups from ACF runtime screens in GeneratePress.
 *
 * @param array[] $field_groups Loaded ACF field groups.
 * @return array[]
 */
function theme_acf_visibility_filter_field_groups( $field_groups ) {
	if ( ! theme_acf_visibility_is_parent_active() ) {
		return $field_groups;
	}

	return array_values(
		array_filter(
			$field_groups,
			static function ( $field_group ) {
				return ! theme_acf_visibility_is_child_group( $field_group );
			}
		)
	);
}

/**
 * Hide child-theme groups from the ACF administration list.
 *
 * @param WP_Query $query Current WordPress query.
 */
function theme_acf_visibility_filter_admin_list( $query ) {
	if ( ! theme_acf_visibility_is_parent_active() || ! is_admin() || ! $query->is_main_query() || 'acf-field-group' !== $query->get( 'post_type' ) ) {
		return;
	}

	$field_groups = get_posts(
		array(
			'post_type'      => 'acf-field-group',
			'post_status'    => array( 'publish', 'acf-disabled', 'trash' ),
			'posts_per_page' => -1,
		)
	);

	$child_group_ids = array();
	foreach ( $field_groups as $field_group ) {
		if ( theme_acf_visibility_is_child_group( $field_group ) ) {
			$child_group_ids[] = (int) $field_group->ID;
		}
	}

	$excluded = array_filter( array_map( 'absint', (array) $query->get( 'post__not_in' ) ) );
	$query->set( 'post__not_in', array_unique( array_merge( $excluded, $child_group_ids ) ) );
}

/**
 * Prevent direct editing of a hidden child-theme group.
 */
function theme_acf_visibility_block_direct_edit() {
	if ( ! theme_acf_visibility_is_parent_active() ) {
		return;
	}

	$post_id = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT );
	$post    = $post_id ? get_post( $post_id ) : null;

	if ( $post instanceof WP_Post && 'acf-field-group' === $post->post_type && theme_acf_visibility_is_child_group( $post ) ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=acf-field-group' ) );
		exit;
	}
}
