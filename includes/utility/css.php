<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function array_key_last;
use function array_merge;
use function count;
use function explode;
use function file_exists;
use function get_template_directory;
use function is_null;
use function rtrim;
use function str_contains;
use function str_replace;
use function wp_json_file_decode;

/**
 * Converts array of CSS rules to string.
 *
 * @since 0.0.22
 *
 * @param array $styles [ 'color' => 'red', 'background' => 'blue' ].
 * @param bool  $trim   Whether to trim the trailing semicolon.
 *
 * @return string
 */
function css_array_to_string( array $styles, bool $trim = false ): string {
	$css = '';

	foreach ( $styles as $property => $value ) {
		if ( is_null( $value ) ) {
			continue;
		}

		$value     = format_custom_property( (string) $value );
		$semicolon = $trim && $property === array_key_last( $styles ) ? '' : ';';
		$css       .= $property . ':' . $value . $semicolon;
	}

	return rtrim( $css, ';' );
}

/**
 * Converts string of CSS rules to an array.
 *
 * @since 0.0.2
 *
 * @param string $css 'color:red;background:blue'.
 *
 * @return array
 */
function css_string_to_array( string $css ): array {
	$array = [];

	// Prevent svg url strings from being split.
	$css = str_replace( 'xml;', 'xml$', $css );

	$elements = explode( ';', $css );

	foreach ( $elements as $element ) {
		$parts = explode( ':', $element, 2 );

		if ( isset( $parts[1] ) ) {
			$property = $parts[0];
			$value    = $parts[1];

			if ( $value !== '' && $value !== 'null' ) {
				$value = str_replace( 'xml$', 'xml;', $value );
				$value = format_custom_property( (string) $value );

				if ( $value ) {
					$array[ $property ] = $value;
				}
			}
		}
	}

	return $array;
}

/**
 * Formats custom properties for unsupported blocks.
 *
 * @since 0.9.10
 *
 * @param string $custom_property Custom property value to format.
 *
 * @return string
 */
function format_custom_property( string $custom_property ): string {
	if ( ! str_contains( $custom_property, 'var:' ) ) {
		$global_settings = function_exists( 'wp_get_global_settings' ) ? wp_get_global_settings() : [];
		$theme_json_file = get_template_directory() . '/theme.json';
		$theme_json      = [];

		if ( file_exists( $theme_json_file ) ) {
			$theme_json = wp_json_file_decode( $theme_json_file );
		}

		if ( ! isset( $global_settings['color']['palette']['theme'] ) && ! isset( $theme_json->settings->color->palette ) ) {
			return $custom_property;
		}

		$colors = array_merge(
			(array) $global_settings['color']['palette']['theme'],
			(array) $theme_json->settings->color->palette
		);

		$color_slugs = wp_list_pluck( $colors, 'slug' );

		if ( in_array( $custom_property, $color_slugs, true ) ) {
			return "var(--wp--preset--color--{$custom_property})";
		}

		return $custom_property;
	}

	return str_replace(
		[
			'var:',
			'|',
		],
		[
			'var(--wp--',
			'--',
		],
		$custom_property . ')'
	);
}

/**
 * Adds shorthand CSS properties.
 *
 * @param array  $styles   Existing CSS array.
 * @param string $property CSS property to add. E.g. 'margin'.
 * @param array  $values   CSS values to add.
 *
 * @return array
 */
function add_shorthand_property( array $styles, string $property, array $values ): array {
	if ( empty( $values ) || isset( $styles[ $property ] ) ) {
		return $styles;
	}

	if ( count( $values ) === 1 ) {
		return $styles;
	}

	$has_top    = isset( $values['top'] );
	$has_right  = isset( $values['right'] );
	$has_bottom = isset( $values['bottom'] );
	$has_left   = isset( $values['left'] );
	$has_all    = $has_top && $has_right && $has_bottom && $has_left;

	if ( ! $has_top && ! $has_right && ! $has_bottom && ! $has_left ) {
		return $styles;
	}

	$top    = format_custom_property( $values['top'] ?? '0' );
	$right  = format_custom_property( $values['right'] ?? '0' );
	$bottom = format_custom_property( $values['bottom'] ?? '0' );
	$left   = format_custom_property( $values['left'] ?? '0' );

	unset( $styles[ $property . '-top' ] );
	unset( $styles[ $property . '-right' ] );
	unset( $styles[ $property . '-bottom' ] );
	unset( $styles[ $property . '-left' ] );

	if ( $top === $right && $right === $bottom && $bottom === $left ) {
		$styles[ $property ] = format_custom_property( $top );
	} else if ( $top === $bottom && $left === $right ) {
		$styles[ $property ] = "$top $right";
	} else {
		$styles[ $property ] = "$top $right $bottom $left";
	}

	return $styles;
}
