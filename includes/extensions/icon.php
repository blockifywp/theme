<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function current_user_can;
use WP_REST_Request;
use WP_REST_Server;

add_action( 'rest_api_init', NS . 'register_icons_rest_route' );
/**
 * Registers icon REST endpoint.
 *
 * @since 0.0.1
 *
 * @return void
 */
function register_icons_rest_route(): void {
	register_rest_route(
		'blockify/v1',
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
