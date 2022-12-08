<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function array_key_last;
use function explode;
use function is_null;
use function str_contains;
use function str_replace;

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

		$semicolon = $trim && $property === array_key_last( $styles ) ? '' : ';';
		$css      .= $property . ':' . $value . $semicolon;
	}

	return $css;
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
				$array[ $property ] = str_replace( 'xml$', 'xml;', $value );
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
