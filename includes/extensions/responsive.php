<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_keys;
use function array_map;
use function explode;
use function implode;
use function str_replace;

add_filter( 'render_block', NS . 'render_responsive_block_css', 10, 2 );
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
function render_responsive_block_css( string $content, array $block ): string {
	$properties  = [];
	$camel_cases = array_keys( get_responsive_settings() );

	foreach ( $camel_cases as $camel_case ) {
		$split        = preg_split( '/(?=[A-Z])/', $camel_case );
		$properties[] = implode( '-', array_map( 'strtolower', $split ) );
	}

	$style             = $block['attrs']['style'] ?? [];
	$custom_properties = [];
	$classes           = [];

	foreach ( $properties as $property ) {
		$value = $style[ "--$property" ] ?? null;

		if ( $value ?? '' ) {
			$custom_properties[ "--$property" ] = $value;

			if ( ! in_array( "has-$property", $classes, true ) ) {
				$classes[] = "has-$property";
			}
		}

		if ( $style[ "--$property-desktop" ] ?? '' ) {
			$custom_properties[ "--$property-desktop" ] = $style[ "--$property-desktop" ];

			if ( ! in_array( "has-$property", $classes, true ) ) {
				$classes[] = "has-$property";
			}
		}
	}

	if ( ! empty( $classes ) ) {
		$dom   = dom( $content );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			return $content;
		}

		$class = implode(
			' ',
			[
				...explode(
					' ',
					$first->getAttribute( 'class' )
				),
				...$classes,
			]
		);

		$class = str_replace(
			[ 'undefined', '  ' ],
			[ '', ' ' ],
			$class
		);

		$first->setAttribute( 'class', $class );

		$first->setAttribute(
			'style',
			css_array_to_string(
				array_merge(
					css_string_to_array(
						$first->getAttribute( 'style' )
					),
					$custom_properties
				)
			)
		);

		$content = $dom->saveHTML();

	}

	return $content;

}
