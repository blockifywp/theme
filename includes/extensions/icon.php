<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function array_diff;
use function array_keys;
use function basename;
use function current_user_can;
use function explode;
use function file_get_contents;
use function glob;
use function implode;
use function preg_replace;
use function str_replace;
use function strtolower;
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
	$icon_sets = [
		'social'    => DIR . 'assets/svg/social',
		'wordpress' => DIR . 'assets/svg/wordpress',
	];

	foreach ( $icon_sets as $icon_set => $set_dir ) {
		$icons = glob( $set_dir . '/*.svg' );

		foreach ( $icons as $icon ) {
			$name = basename( $icon, '.svg' );
			$icon = file_get_contents( $icon );

			if ( $icon_set === strtolower( 'WordPress' ) ) {
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
 * Renders image icon styles on front end.
 *
 * @since 0.2.0
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function get_icon_html( string $content, array $block ): string {
	$content = ! $content ? '<figure class="wp-block-image is-style-icon"><img src="" alt=""/></figure>' : $content;
	$dom     = dom( $content );
	$figure  = get_dom_element( 'figure', $dom );
	$img     = get_dom_element( 'img', $figure );

	if ( ! $figure || ! $img ) {
		return $content;
	}

	$span    = change_tag_name( $img, 'span' );
	$classes = explode( ' ', $figure->getAttribute( 'class' ) );
	$styles  = css_string_to_array( $figure->getAttribute( 'style' ) );
	$classes = array_diff( $classes, [ 'wp-block-image', 'is-style-icon' ] );
	$classes = [
		'wp-block-image__icon',
		'blockify-icon',
		...$classes,
	];

	$styles['--wp--custom--icon--color'] = $styles['--wp--custom--icon--color'] ?? 'currentColor';

	$aria_label = $img->getAttribute( 'alt' ) ? $img->getAttribute( 'alt' ) : $block['attrs']['icon'] ?? __( 'SVG Icon', 'blockify' );

	$span->setAttribute( 'class', implode( ' ', $classes ) );
	$span->setAttribute( 'style', css_array_to_string( $styles ) );
	$span->setAttribute( 'aria-label', $aria_label );

	$span->removeAttribute( 'src' );
	$span->removeAttribute( 'alt' );

	$figure->setAttribute( 'class', 'wp-block-image is-style-icon' );
	$figure->setAttribute( 'style', '' );

	$figure->appendChild( $span );

	return $dom->saveHTML();
}
