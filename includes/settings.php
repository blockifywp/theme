<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_REST_Response;
use function add_action;
use function add_post_type_support;
use function add_theme_support;
use function array_keys;
use function basename;
use function current_user_can;
use function file_get_contents;
use function get_option;
use function get_post;
use function glob;
use function preg_replace;
use function register_rest_field;
use function remove_post_type_support;
use function str_replace;
use function tgmpa;
use function update_option;
use function wp_send_json_error;
use function wp_update_post;
use WP_REST_Request;
use WP_REST_Server;

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

add_action( 'after_setup_theme', NS . 'add_recommended_plugins' );
/**
 * Adds recommended plugins to TGMPA from theme config.
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

add_action( 'init', NS . 'register_page_title_rest_field' );
/**
 * Registers page title rest field.
 *
 * @since 0.0.2
 *
 * @return void
 */
function register_page_title_rest_field(): void {
	register_rest_field(
		'blockify-page-title',
		'title',
		[
			'get_callback'    => function ( array $params ): string {
				$post_id = $params['id'];
				$post    = get_post( $post_id );

				return $post->post_title;
			},
			'update_callback' => function ( $value, $object ): void {
				wp_update_post(
					[
						'ID'         => $object->ID,
						'post_title' => $value,
					]
				);
			},
		]
	);
}

add_action( 'rest_api_init', NS . 'register_options_rest_route' );
/**
 * Registers theme options endpoint.
 *
 * @since 0.2.0
 *
 * @return void
 */
function register_options_rest_route(): void {
	register_rest_route( SLUG . '/v1', '/options/', [
		[
			'permission_callback' => fn() => true,
			'methods'             => WP_REST_Server::ALLMETHODS,
			[
				'args' => [
					'name'  => [
						'required' => false,
						'type'     => 'string',
					],
					'value' => [
						'required' => false,
						'type'     => 'string',
					],
				],
			],
			'callback'            => function ( WP_REST_Request $request ): array {
				$options = get_option( SLUG ) ?? [];
				$name    = $request->get_param( 'name' ) ?? null;
				$value   = $request->get_param( 'value' ) ?? null;

				if ( $name && $value && $request->get_method() === WP_REST_Server::CREATABLE ) {
					$options[ (string) $name ] = (string) $value;

					update_option( SLUG, $options );
				}

				return $options;
			},
		],
	] );
}

add_action( 'rest_api_init', NS . 'register_icons_rest_route' );
/**
 * Registers endpoint for icon data.
 *
 * @since 0.0.1
 *
 * @return void
 */
function register_icons_rest_route(): void {
	register_rest_route( SLUG . '/v1', '/icons/', [
		'permission_callback' => fn() => current_user_can( 'edit_posts' ),
		'methods'             => WP_REST_Server::READABLE,
		[
			'args' => [
				'sets' => [
					'required' => false,
					'type'     => 'string',
				],
				'set'  => [
					'required' => false,
					'type'     => 'string',
				],
			],
		],
		'callback'            => function ( WP_REST_Request $request ): array {
			$icon_data = [];
			$icon_sets = get_sub_config( 'icons' );

			foreach ( $icon_sets as $icon_set => $set_dir ) {
				$icons = glob( $set_dir . '/*.svg' );

				foreach ( $icons as $icon ) {
					$name = basename( $icon, '.svg' );
					$icon = file_get_contents( $icon );

					if ( $icon_set === 'wordpress' ) {
						$icon = str_replace(
							[ 'fill="none"' ],
							[ 'fill="currentColor"' ],
							$icon
						);
					}

					// Remove comments.
					$icon = preg_replace( '/<!--(.|\s)*?-->/', '', $icon );

					$icon_data[ $icon_set ][ $name ] = trim( $icon );
				}
			}

			if ( $request->get_param( 'set' ) ) {
				$set = $request->get_param( 'set' );

				if ( $request->get_param( 'icon' ) ) {
					return $icon_data[ $set ][ $request->get_param( 'icon' ) ];
				}

				return $icon_data[ $set ];
			}

			if ( $request->get_param( 'sets' ) ) {
				return array_keys( $icon_data );
			}

			return $icon_data;
		},
	] );
}
