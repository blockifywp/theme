<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function apply_filters;
use function array_keys;
use function array_values;
use function basename;
use function file_get_contents;
use function glob;
use function implode;
use function in_array;
use function preg_replace;
use function str_replace;
use function trim;
use WP_REST_Request;

/**
 * Returns array of all icon sets and their directory path.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_icon_sets(): array {

	$options = get_option( SLUG )['iconSets'] ?? [
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

		$icon_sets[ $option['value'] ] = DIR . 'assets/svg/' . $option['value'];
	}

	return apply_filters( 'blockify_icon_sets', $icon_sets );
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

	$link    = get_dom_element( 'a', $figure );
	$span    = change_tag_name( $img, 'span' );
	$classes = explode( ' ', $figure->getAttribute( 'class' ) );
	$styles  = css_string_to_array( $figure->getAttribute( 'style' ) );
	$classes = array_diff( $classes, [ 'wp-block-image', 'is-style-icon' ] );

	// phpcs:ignore WordPress.WP.CapitalPDangit.Misspelled
	$icon_set  = $block['attrs']['iconSet'] ?? 'wordpress';
	$icon_name = $block['attrs']['iconName'] ?? 'star-empty';

	$classes = [
		'wp-block-image__icon',
		'blockify-icon',
		'blockify-icon-' . $icon_set . '-' . $icon_name,
		...$classes,
	];

	$color = $styles['----wp--custom--icon--color'] ?? 'currentColor';

	if ( $color && $color !== 'currentColor' ) {
		$styles['--wp--custom--icon--color'] = $color;
	}

	$aria_label = $img->getAttribute( 'alt' ) ? $img->getAttribute( 'alt' ) : str_replace( '-', ' ', $icon_name ) . __( ' icon', 'blockify' );

	$span->setAttribute( 'class', implode( ' ', $classes ) );

	if ( $styles ) {
		$span->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$span->setAttribute( 'title', $block['attrs']['title'] ?? $aria_label );

	if ( ! ( $block['attrs']['title'] ?? null ) || ! $aria_label ) {
		$span->setAttribute( 'aria-label', $aria_label );
		$span->setAttribute( 'role', 'presentation' );
	}

	$span->removeAttribute( 'src' );
	$span->removeAttribute( 'alt' );

	$figure_classes = [
		'wp-block-image',
		'is-style-icon',
	];

	$figure_styles = [];

	$position = $block['attrs']['style']['position'] ?? [];

	if ( $position && in_array( 'absolute', array_values( $position ), true ) ) {
		$figure_styles['display'] = 'contents';
	}

	$figure->setAttribute( 'class', implode( ' ', $figure_classes ) );

	$figure->setAttribute( 'style', css_array_to_string( $figure_styles, true ) );

	if ( ! $figure->getAttribute( 'style' ) ) {
		$figure->removeAttribute( 'style' );
	}

	if ( $link ) {
		$link->appendChild( $span );
	} else {
		$figure->appendChild( $span );
	}

	return $dom->saveHTML();
}
