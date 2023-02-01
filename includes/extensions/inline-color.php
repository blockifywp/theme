<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function explode;
use function in_array;
use function str_contains;

add_filter( 'render_block', NS . 'render_inline_color' );
/**
 * Renders custom properties for inline colors.
 *
 * @since 0.9.25
 *
 * @param string $html Block HTML content.
 *
 * @return string
 */
function render_inline_color( string $html ): string {
	if ( ! str_contains( $html, 'has-inline-color' ) ) {
		return $html;
	}

	$dom = dom( $html );

	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$global_settings = wp_get_global_settings();
	$color_palette   = $global_settings['color']['palette']['theme'] ?? [];

	foreach ( $first->childNodes as $child ) {
		if ( ! $child instanceof DOMElement ) {
			continue;
		}

		$classes = explode( ' ', $child->getAttribute( 'class' ) );

		if ( ! in_array( 'has-inline-color', $classes, true ) ) {
			continue;
		}

		$styles = css_string_to_array( $child->getAttribute( 'style' ) );

		foreach ( $color_palette as $color ) {
			$hex_value   = $styles['color'] ?? '';
			$color_value = $color['color'] ?? '';

			if ( ! $hex_value || ! $color_value ) {
				continue;
			}

			if ( $hex_value === $color_value ) {
				$styles['color'] = "var(--wp--preset--color--{$color['slug']})";
				$child->setAttribute( 'style', css_array_to_string( $styles ) );

				break;
			}
		}

		$html = $dom->saveHTML();
	}

	return $html;
}
