<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMException;
use WP_REST_Request;
use function apply_filters;
use function array_keys;
use function array_values;
use function basename;
use function explode;
use function file_get_contents;
use function glob;
use function implode;
use function in_array;
use function preg_replace;
use function str_contains;
use function str_replace;
use function trim;
use function ucwords;
use function uniqid;
use function wp_list_pluck;

/**
 * Returns array of all icon sets and their directory path.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_icon_sets(): array {
	$options = get_option( 'blockify' )['iconSets'] ?? [
		[
			'label' => 'WordPress',
			'value' => 'wordpress',
		],
		[
			'label' => 'Social',
			'value' => 'social',
		],
	];

	$icon_sets = [];

	foreach ( $options as $option ) {
		if ( ! isset( $option['value'] ) ) {
			continue;
		}

		if ( in_array( $option['value'], [ 'wordpress', 'social' ], true ) ) {
			$icon_sets[ $option['value'] ] = get_dir() . 'assets/svg/' . $option['value'];
		}
	}

	return apply_filters( 'blockify_icon_sets', $icon_sets );
}

/**
 * Returns icon data for rest endpoint
 *
 * @param WP_REST_Request $request Request object.
 *
 * @since 0.4.8
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
 * @param string   $set  Icon set.
 * @param string   $name Icon name.
 * @param int|null $size Icon size.
 *
 * @throws DOMException If DOM can't create element.
 * @since 0.9.10
 *
 * @return string
 */
function get_icon( string $set, string $name, int $size = null ): string {
	$icon = get_icons()[ $set ][ $name ] ?? '';
	$dom  = dom( $icon );
	$svg  = get_dom_element( 'svg', $dom );

	if ( ! $svg ) {
		return '';
	}

	$unique_id = 'icon-' . uniqid();

	$svg->setAttribute( 'role', 'img' );
	$svg->setAttribute( 'aria-labelledby', $unique_id );
	$svg->setAttribute( 'data-icon', $set . '-' . $name );

	$label = ucwords( str_replace( '-', ' ', $name ) ) . __( ' Icon', 'blockify' );
	$title = $dom->createElement( 'title' );

	$title->appendChild( $dom->createTextNode( $label ) );
	$title->setAttribute( 'id', $unique_id );

	$svg->insertBefore( $title, $svg->firstChild );

	if ( $size ) {
		$svg->setAttribute( 'width', (string) $size );
		$svg->setAttribute( 'height', (string) $size );
	}

	return $dom->saveHTML();
}

/**
 * Renders image icon styles on front end.
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @since 0.2.0
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

	$link      = get_dom_element( 'a', $figure );
	$span      = change_tag_name( $img, 'span' );
	$icon_name = $block['attrs']['iconName'] ?? 'star-empty';

	$span_classes = [
		'wp-block-image__icon',
	];

	$figure_classes = explode( ' ', $figure->getAttribute( 'class' ) );

	$block_extras        = get_block_extra_options();
	$block_extra_classes = [];

	foreach ( $block_extras as $name => $args ) {
		$block_extra_classes[] = 'has-' . $args['value'];

		if ( ! isset( $args['options'] ) ) {
			continue;
		}

		foreach ( $args['options'] as $option ) {
			$block_extra_classes[] = 'has-' . $args['value'] . '-' . $option['value'];
		}
	}

	foreach ( $figure_classes as $index => $class ) {
		if ( ! str_contains( $class, 'has-' ) ) {
			continue;
		}

		if ( in_array( $class, $block_extra_classes, true ) ) {
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

	$block_extra_values = wp_list_pluck(
		array_values( $block_extras ),
		'value'
	);

	$block_extra_custom_props = \array_map( fn( $prop ) => '--' . $prop, $block_extra_values );

	foreach ( $figure_styles as $key => $value ) {

		if ( in_array( $key, $block_extra_values, true ) ) {
			continue;
		}

		if ( in_array( $key, $block_extra_custom_props, true ) ) {
			continue;
		}

		$span_styles[ $key ] = $value;
		unset( $figure_styles[ $key ] );
	}

	$figure->setAttribute( 'style', css_array_to_string( $figure_styles ) );
	$span->setAttribute( 'style', css_array_to_string( $span_styles ) );

	if ( $link ) {
		$link->appendChild( $span );
	} else {
		$figure->appendChild( $span );
	}

	return $dom->saveHTML();
}
