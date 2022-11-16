<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function array_keys;
use function is_array;
use function is_string;
use function str_replace;

add_filter( 'render_block_core/navigation', NS . 'render_navigation_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_navigation_block( string $content, array $block ): string {

	// Replace invalid root relative URLs.
	$content = str_replace( 'http://./', './', $content );

	$spacing = $block['attrs']['style']['spacing'] ?? null;

	if ( ! $spacing ) {
		return $content;
	}

	$dom = dom( $content );
	$nav = get_dom_element( 'nav', $dom );

	if ( ! $nav ) {
		return $content;
	}

	$styles = css_string_to_array( $nav->getAttribute( 'style' ) );

	foreach ( array_keys( $spacing ) as $attribute ) {
		$prop = $attribute === 'blockGap' ? 'gap' : $attribute;

		if ( is_string( $spacing[ $attribute ] ) ) {
			$styles[ $prop ] = format_custom_property( $spacing[ $attribute ] );
		}

		if ( is_array( $spacing[ $attribute ] ) ) {
			foreach ( array_keys( $spacing[ $attribute ] ) as $side ) {
				$styles[ "$prop-$side" ] = format_custom_property( $spacing[ $attribute ][ $side ] );
			}
		}
	}

	if ( $styles ) {
		$nav->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	return $dom->saveHTML();
}
