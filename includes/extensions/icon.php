<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
use function array_keys;
use function basename;
use function current_user_can;
use function file_get_contents;
use function glob;
use function preg_replace;
use function str_replace;
use function trim;
use WP_REST_Request;
use WP_REST_Server;

add_action( 'rest_api_init', NS . 'register_icons_rest_route' );
/**
 * Registers endpoint for icon data.
 *
 * @since 0.0.1
 *
 * @return void
 */
function register_icons_rest_route(): void {
	register_rest_route(
		SLUG . '/v1',
		'/icons/',
		[
			'permission_callback' => static fn() => current_user_can( 'edit_posts' ),
			'callback'            => static fn( WP_REST_Request $request ): array => get_icon_data( $request ),
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
		]
	);
}

/**
 * Returns array of all icon sets and their directory path.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_icon_sets(): array {
	return apply_filters(
		'blockify_icon_sets',
		[
			'social'    => DIR . 'assets/svg/social',
			'wordpress' => DIR . 'assets/svg/wordpress',
		]
	);
}

/**
 * Returns icon data for rest endpoint
 *
 * @since 0.4.8
 *
 * @param WP_REST_Request $request Request object.
 *
 * @return mixed array|string
 */
function get_icon_data( WP_REST_Request $request ) {
	$icon_data = [];
	$icon_sets = get_icon_sets();

	foreach ( $icon_sets as $icon_set => $set_dir ) {
		$icons = glob( $set_dir . '/*.svg' );

		foreach ( $icons as $icon ) {
			$name = basename( $icon, '.svg' );
			$icon = file_get_contents( $icon );

			if ( $icon_set === 'WordPress' ) {
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

			// TODO: Is string being used anywhere?
			return $icon_data[ $set ][ $request->get_param( 'icon' ) ];
		}

		return $icon_data[ $set ];
	}

	if ( $request->get_param( 'sets' ) ) {
		return array_keys( $icon_data );
	}

	return $icon_data;
}

/**
 * Returns array of all registered icons.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_icons(): array {
	$icons     = [];
	$icon_sets = get_icon_sets();

	foreach ( $icon_sets as $icon_set => $dir ) {
		$icons[ $icon_set ] = [];

		foreach ( glob( $dir . '/*.svg' ) as $file ) {
			$icons[ $icon_set ][ basename( $file, '.svg' ) ] = trim( file_get_contents( $file ) );
		}
	}

	return $icons;
}

/**
 * Returns svg string for given icon.
 *
 * @since 0.9.10
 *
 * @param string $set  Icon set.
 * @param string $name Icon name.
 *
 * @return string
 */
function get_icon( string $set, string $name ): string {
	return get_icons()[ $set ][ $name ] ?? '';
}
