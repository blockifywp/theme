<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function current_user_can;
use function get_option;
use function get_post;
use function register_rest_field;
use function update_option;
use function wp_update_post;
use WP_REST_Request;
use WP_REST_Server;

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
			'get_callback'    => static function ( array $params ): string {
				$post_id = $params['id'];
				$post    = get_post( $post_id );

				return $post->post_title;
			},
			'update_callback' => static function ( $value, $object ): void {
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
	register_rest_route(
		SLUG . '/v1',
		'/options/',
		[
			[
				'permission_callback' => static fn() => current_user_can( 'manage_options' ),
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
				'callback'            => static function ( WP_REST_Request $request ): array {
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
		]
	);
}
