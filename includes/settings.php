<?php

declare( strict_types=1 );

namespace Blockify;

use function add_action;
use function add_post_type_support;
use function add_theme_support;
use function get_post;
use function register_post_meta;
use function register_rest_field;
use function remove_post_type_support;
use function wp_update_post;

add_action( 'after_setup_theme', NS . 'add_recommended_plugins' );
/**
 * Adds recommended plugins.
 *
 * @since 0.0.15
 *
 * @return void
 */
function add_recommended_plugins(): void {
	tgmpa( get_sub_config( 'recommendedPlugins' ) ?? [], [
		'is_automatic' => true,
	] );
}

add_action( 'after_setup_theme', NS . 'theme_supports' );
/**
 * Handles theme supports.
 *
 * @since 0.0.2
 *
 * @return void
 */
function theme_supports(): void {
	$theme_supports = get_sub_config( 'themeSupports' );
	$add            = $theme_supports['add'] ?? [];
	$remove         = $theme_supports['remove'] ?? [];

	foreach ( $add as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

	foreach ( $remove as $feature ) {
		remove_theme_support( $feature );
	}
}

add_action( 'after_setup_theme', NS . 'add_post_type_supports' );
/**
 * Handles post type supports.
 *
 * @since 0.0.2
 *
 * @return void
 */
function add_post_type_supports(): void {
	$post_supports = get_sub_config( 'postTypeSupports' );
	$add           = $post_supports['add'] ?? [];
	$remove        = $post_supports['remove'] ?? [];

	foreach ( $add as $post_type => $features ) {
		foreach ( $features as $feature ) {
			add_post_type_support( $post_type, $feature );
		}
	}

	foreach ( $remove as $post_type => $features ) {
		foreach ( $features as $feature ) {
			remove_post_type_support( $post_type, $feature );
		}
	}
}

add_action( 'init', NS . 'rest_fields' );
/**
 * Registers rest fields.
 *
 * @since 0.0.2
 *
 * @return void
 */
function rest_fields(): void {
	register_rest_field(
		'blockify-page-title',
		'title',
		[
			'get_callback'    => function ( $params ) {
				$post_id = $params['id'];
				$post    = get_post( $post_id );

				return $post->post_title;
			},
			'update_callback' => function ( $value, $object ) {
				wp_update_post(
					[
						'ID'         => $object->ID,
						'post_title' => $value,
					]
				);
			},
		]
	);

	register_post_meta( 'page', 'template_part_header', [
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'boolean',
	] );

	register_post_meta( 'page', 'template_part_footer', [
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
	] );
}
