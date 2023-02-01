<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function is_null;

add_filter( 'render_block_core/cover', NS . 'render_cover_block', 10, 2 );
/**
 * Renders the cover block.
 *
 * @since 0.0.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_cover_block( string $html, array $block ): string {
	$padding = $block['attrs']['style']['spacing']['padding'] ?? null;
	$zIndex  = $block['attrs']['style']['zIndex']['all'] ?? null;

	if ( is_null( $padding ) && is_null( $zIndex ) ) {
		return $html;
	}

	$dom = dom( $html );
	$div = get_dom_element( 'div', $dom );

	if ( $div ) {
		$styles = css_string_to_array( $div->getAttribute( 'style' ) );

		if ( isset( $padding['top'] ) ) {
			$styles['padding-top'] = format_custom_property( $padding['top'] );
		}

		if ( isset( $padding['right'] ) ) {
			$styles['padding-right'] = format_custom_property( $padding['right'] );
		}

		if ( isset( $padding['bottom'] ) ) {
			$styles['padding-bottom'] = format_custom_property( $padding['bottom'] );
		}

		if ( isset( $padding['left'] ) ) {
			$styles['padding-left'] = format_custom_property( $padding['left'] );
		}

		if ( ! is_null( $zIndex ) ) {
			$styles['--z-index'] = format_custom_property( $zIndex );
		}

		$div->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	return $dom->saveHTML();
}

