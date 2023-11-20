<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_REST_Request;
use WP_REST_Server;
use function add_action;
use function current_user_can;
use function do_blocks;
use function str_contains;
use function str_replace;

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
	$classes  = $block['attrs']['className'] ?? '';
	$icon_set = $block['attrs']['iconSet'] ?? strtolower( 'WordPress' );

	if ( str_contains( $classes, 'all-icons' ) ) {
		return render_all_icons( $icon_set );
	}

	$content = ! $content ? '<figure class="wp-block-image is-style-icon"><img src="" alt=""/></figure>' : $content;
	$dom     = dom( $content );
	$figure  = get_dom_element( 'figure', $dom );
	$img     = get_dom_element( 'img', $figure );

	if ( ! $figure || ! $img ) {
		return $content;
	}

	$span         = change_tag_name( 'span', $img );
	$icon_name    = $block['attrs']['iconName'] ?? 'star-empty';
	$has_gradient = $block['attrs']['gradient'] ?? null;
	$span_classes = [ 'wp-block-image__icon' ];

	if ( $has_gradient ) {
		$span_classes[] = 'has-gradient';
	}

	$figure_classes      = explode( ' ', $figure->getAttribute( 'class' ) );
	$block_extras        = get_block_extension_options();
	$block_extra_classes = [];

	foreach ( $block_extras as $name => $args ) {
		$block_extra_classes[] = 'has-' . $args['property'];

		if ( ! isset( $args['options'] ) ) {
			continue;
		}

		foreach ( $args['options'] as $option ) {
			$block_extra_classes[] = 'has-' . $args['property'] . '-' . $option['value'];
		}
	}

	foreach ( $figure_classes as $index => $class ) {
		if ( ! str_contains( $class, 'has-' ) ) {
			continue;
		}

		if ( in_array( $class, $block_extra_classes, true ) ) {
			continue;
		}

		if ( str_contains( $class, 'has-display' ) ) {
			continue;
		}

		if ( str_contains( $class, 'has-' ) && str_contains( $class, '-background' ) ) {
			unset( $figure_classes[ $index ] );
			continue;
		}

		$span_classes[] = $class;
		unset( $figure_classes[ $index ] );
	}

	$figure->setAttribute( 'class', implode( ' ', $figure_classes ) );
	$span->setAttribute( 'class', implode( ' ', $span_classes ) );

	$aria_label = $img->getAttribute( 'alt' ) ? $img->getAttribute( 'alt' ) : str_replace( '-', ' ', $icon_name ) . __( ' icon', 'blockify' );

	$span->setAttribute( 'title', $block['attrs']['title'] ?? $aria_label );

	if ( ! ( $block['attrs']['title'] ?? null ) || ! $aria_label ) {
		$span->setAttribute( 'role', 'img' );
	}

	$span->removeAttribute( 'src' );
	$span->removeAttribute( 'alt' );

	$figure_styles = css_string_to_array( $figure->getAttribute( 'style' ) );
	$span_styles   = css_string_to_array( $span->getAttribute( 'style' ) );
	$properties    = wp_list_pluck( array_values( $block_extras ), 'property' );

	$custom_properties = array_map(
		static fn( string $property ): string => '--' . $property,
		$properties
	);

	foreach ( $figure_styles as $key => $value ) {
		if ( in_array( $key, $properties, true ) ) {
			continue;
		}

		if ( in_array( $key, $custom_properties, true ) ) {
			continue;
		}

		if ( str_contains( $key, 'margin' ) ) {
			continue;
		}

		$span_styles[ $key ] = $value;
		unset( $figure_styles[ $key ] );
	}

	$svg_string = $block['attrs']['iconSvgString'] ?? get_icon( $icon_set, $icon_name );

	if ( $has_gradient && $svg_string ) {
		$span_styles['--wp--custom--icon--url'] = 'url(\'data:image/svg+xml;utf8,' . $svg_string . '\')';
	} else {
		unset( $span_styles['--wp--custom--icon--url'] );
	}

	$size = $block['attrs']['iconSize'] ?? null;

	if ( $has_gradient && $size ) {
		$span_styles['--wp--custom--icon--size'] = $size;
	} else {
		unset( $span_styles['--wp--custom--icon--size'] );
	}

	$figure->setAttribute( 'style', css_array_to_string( $figure_styles ) );
	$span->setAttribute( 'style', css_array_to_string( $span_styles ) );

	$link = get_dom_element( 'a', $figure );

	if ( $link ) {
		$link->appendChild( $span );
	} else {
		$figure->appendChild( $span );
	}

	if ( ! $has_gradient ) {
		$icon = get_icon( $icon_set, $icon_name, $size );

		if ( $icon ) {
			$icon_dom      = dom( $icon );
			$imported_icon = $dom->importNode( $icon_dom->firstChild, true );

			$span->appendChild( $imported_icon );
		}
	}

	return $dom->saveHTML();
}

/**
 * Displays grid of all icons in a set.
 *
 * @since 1.3.0
 *
 * @param string $set Icon set name.
 *
 * @return string
 */
function render_all_icons( string $set = 'wordpress' ): string {
	$icons        = get_icons( $set );
	$inner_blocks = [];
	$limit        = 40;

	foreach ( $icons as $icon => $svg ) {
		if ( $limit-- <= 0 ) {
			break;
		}

		$inner_blocks[] = [
			'blockName' => 'core/image',
			'attrs'     => [
				'className'     => 'is-style-icon',
				'iconSet'       => $set,
				'iconName'      => $icon,
				'iconSvgString' => $svg,
				'iconSize'      => '1em',
			],
		];
	}

	$inner_blocks[] = [
		'blockName' => 'core/paragraph',
		'attrs'     => [
			'content' => __( 'This is a preview of all icons in the set.', 'blockify' ),
		],
	];

	$inner_blocks[] = [
		'blockName' => 'core/image',
		'attrs'     => [
			'className'     => 'is-style-icon',
			'iconSet'       => 'social',
			'iconName'      => 'blockify',
			'iconSvgString' => get_icons()['social']['blockify'],
			'iconSize'      => '1em',
		],
	];

	$block = [
		'blockName'   => 'core/group',
		'attrs'       => [
			'style'     => [
				'spacing' => [
					'blockGap' => 'var(--wp--preset--spacing--sm)',
				],
			],
			'fontSize'  => '24',
			'textColor' => 'heading',
			'layout'    => [
				'type'     => 'flex',
				'flexWrap' => 'wrap',
			],
		],
		'innerBlocks' => $inner_blocks,
	];

	return do_blocks( get_block_html( $block ) );
}
