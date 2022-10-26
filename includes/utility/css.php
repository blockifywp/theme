<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function explode;
use function is_null;
use function str_replace;

/**
 * Converts array of CSS rules to string.
 *
 * @since 0.0.22
 *
 * @param array $styles [ 'color' => 'red', 'background' => 'blue' ].
 *
 * @return string
 */
function css_array_to_string( array $styles ): string {
	$css = '';

	foreach ( $styles as $property => $value ) {
		if ( is_null( $value ) ) {
			continue;
		}

		$css .= "$property:$value;";
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

			if ( $value ) {
				$array[ $property ] = str_replace( 'xml$', 'xml;', $value );
			}
		}
	}

	return $array;
}
