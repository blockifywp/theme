<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_REST_Request;
use function apply_filters;
use function array_keys;
use function basename;
use function file_exists;
use function file_get_contents;
use function get_stylesheet_directory;
use function glob;
use function is_array;
use function is_string;
use function preg_replace;
use function str_replace;
use function strtolower;
use function trim;
use function uniqid;
use const GLOB_ONLYDIR;

/**
 * Returns array of all icon sets and their directory path.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_icon_sets(): array {
	$theme = [
		[
			'label' => 'WordPress',
			'value' => 'wordpress',
		],
		[
			'label' => 'Social',
			'value' => 'social',
		],
	];

	$child_theme = glob( get_stylesheet_directory() . '/assets/svg/*', GLOB_ONLYDIR );

	foreach ( $child_theme as $dir ) {
		$slug = basename( $dir );

		$theme[] = [
			'label' => to_title_case( $slug ),
			'value' => $slug,
		];
	}

	$options   = get_option( 'blockify' )['iconSets'] ?? $theme;
	$icon_sets = [];

	foreach ( $options as $option ) {
		if ( ! isset( $option['value'] ) ) {
			continue;
		}

		$parent = get_dir() . 'assets/svg/' . $option['value'];
		$child  = get_stylesheet_directory() . '/assets/svg/' . $option['value'];

		if ( file_exists( $parent ) ) {
			$icon_sets[ $option['value'] ] = $parent;
		}

		if ( file_exists( $child ) ) {
			$icon_sets[ $option['value'] ] = $child;
		}
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

			// Remove new lines.
			$icon = preg_replace( '/\s+/', ' ', $icon );

			// Remove tabs.
			$icon = preg_replace( '/\t+/', '', $icon );

			// Remove spaces between tags.
			$icon = preg_replace( '/>\s+</', '><', $icon );

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
 * @param string $set Icon set.
 *
 * @return array
 */
function get_icons( string $set = '' ): array {
	$icons     = [];
	$icon_sets = get_icon_sets();

	foreach ( $icon_sets as $icon_set => $dir ) {
		if ( ! is_string( $dir ) ) {
			continue;
		}

		$icons[ $icon_set ] = [];

		$files = glob( $dir . '/*.svg' );

		if ( ! is_array( $files ) ) {
			continue;
		}

		foreach ( $files as $file ) {
			$icons[ $icon_set ][ basename( $file, '.svg' ) ] = trim( file_get_contents( $file ) );
		}
	}

	return $set ? ( $icons[ $set ] ?? [] ) : $icons;
}

/**
 * Returns svg string for given icon.
 *
 * @since 0.9.10
 *
 * @param string          $set  Icon set.
 * @param string          $name Icon name.
 * @param string|int|null $size Icon size.
 *
 * @return string
 */
function get_icon( string $set, string $name, $size = null ): string {
	$set  = strtolower( $set );
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

	$label = to_title_case( $name ) . __( ' Icon', 'blockify' );
	$title = create_element( 'title', $dom );

	$title->appendChild( $dom->createTextNode( $label ) );
	$title->setAttribute( 'id', $unique_id );

	$svg->insertBefore( $title, $svg->firstChild );

	if ( $size ) {
		$has_unit = str_contains_any( (string) $size, 'px', 'em', 'rem', '%', 'vh', 'vw' );

		if ( $has_unit ) {
			$styles = css_string_to_array( $svg->getAttribute( 'style' ) );

			$styles['min-width'] = $size;
			$styles['height']    = $size;

			$svg->setAttribute( 'style', css_array_to_string( $styles ) );
		} else {
			$svg->setAttribute( 'width', (string) $size );
			$svg->setAttribute( 'height', (string) $size );
		}
	}

	$fill = $svg->getAttribute( 'fill' );

	if ( ! $fill ) {
		$svg->setAttribute( 'fill', 'currentColor' );
	}

	return trim( $dom->saveHTML() );
}
